<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Factura;
use App\Provider;
use App\Client;
use App\Complement;
use App\Order;
use App\Payment;
use App\PaymentsProvider;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') ){
            $facturas = DB::select('select count(*) id FROM facturas');
            $complements = DB::select('select count(*) id FROM complements');
            $orders = DB::select('select count(*) id FROM orders');
            $payments = DB::select('select count(*) id FROM payments');
            $productos = DB::select('select count(*) id FROM productos');
            $users = DB::select('select count(*) id FROM users');

            return view('admin.index', [
                'facturas'=>$facturas,
                'complements'=> $complements,
                'orders'=> $orders,
                'productos'=>$productos,
                'payments'=>$payments,
                'users'=>$users,
            ]);
        }else{
            $facturas = DB::select('select count(*) as cant from facturas left join _users_facturas on factura_id = facturas.id where user_id = '.Auth::user()->id);
            $puntos_total = DB::select('select ( ifnull(SUM(puntos.puntos), 0 )) as cant from puntos left join _users_puntos on punto_id = puntos.id where puntos.estado = 1 and _users_puntos.user_id = '.Auth::user()->id);
            $puntos_operations = DB::select('select ( ifnull(SUM(operations.puntos), 0 )) as cant from operations where operations.user_id = '.Auth::user()->id);
            $puntos = $puntos_total[0]->cant - $puntos_operations[0]->cant;
            $puntos = ($puntos < 0 ) ? 0 : $puntos;

            return view('admin.index', [
                'facturas'=> $facturas,
                'puntos'=> $puntos
            ]);
        }
    }

    function compararFechas($primera, $segunda)
    {
        $valoresPrimera = explode ("/", $primera);
        $valoresSegunda = explode ("/", $segunda);

        $diaPrimera    = $valoresPrimera[0];
        $mesPrimera  = $valoresPrimera[1];
        $anyoPrimera   = $valoresPrimera[2];

        $diaSegunda   = $valoresSegunda[0];
        $mesSegunda = $valoresSegunda[1];
        $anyoSegunda  = $valoresSegunda[2];

        $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);
        $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);

        if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
            // "La fecha ".$primera." no es v&aacute;lida";
            return 0;
        }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
            // "La fecha ".$segunda." no es v&aacute;lida";
            return 0;
        }else{
            return  $diasPrimeraJuliano - $diasSegundaJuliano;
        }
    }

    public function getFacturas(){

        $total = 0;
        $errormsg_file = "";
        $disk = Storage::disk('sftp-facturas');

        $files_s3 = array_filter($disk->files(),
            function ($item) {return strpos($item, '.xml');}
        );

        foreach ($files_s3 as $file){
            $today = date ('d/m/Y' , strtotime('-3 day', strtotime(date('Y-m-j'))));
            $modified = date('d/m/Y', $disk->lastModified($file));

            if($this->compararFechas ($today,$modified) <= 0){
                $exists = Factura::where('nombre_factura', $file)->first();

                if(empty($exists)){
                    $factura = array();
                    $user_id = 1;
                    $xml_body = false;
                    $usr_exist = true;
                    $xmlString = Storage::disk('sftp-facturas')->get($file);
                    Storage::disk('local')->put($file,$xmlString);

                    $xmlFile = storage_path('app')."/".$file;

                    $xml = new \XMLReader();
                    $xml->open($xmlFile);

                    try {
                        $xml_body = true;
                        while ($xml->read()) {
                            if ($xml->nodeType == \XMLReader::ELEMENT) {
                                if ($xml->name == 'cfdi:Comprobante') {
                                    if($xml->hasAttributes) {
                                        $factura['numero_factura'] = $xml->getAttribute( "Folio");
                                        $factura['total_cost'] = $xml->getAttribute( "Total");
                                        $factura['fecha'] = $xml->getAttribute( "Fecha");
                                        $factura['nombre_factura'] = $file;
                                        $factura['estado'] = 2;
                                    }
                                    else {
                                        $xml_body = false;
                                    }
                                }
                                elseif($xml->name == 'tfd:TimbreFiscalDigital') {
                                    if($xml->hasAttributes) {
                                        $factura['IdDocumento'] = $xml->getAttribute( "UUID");
                                    }
                                    else {
                                        $xml_body = false;
                                    }
                                }
                                elseif (($xml->name == 'cfdi:Emisor' || $xml->name == 'cfdi:Receptor')){
                                    if($xml->hasAttributes) {
                                        if($xml->getAttribute( "Rfc") != 'CZV9204036N2'){
                                            if ($xml->name == 'cfdi:Emisor'){
                                                $user = Provider::with('user')->where('rfc', $xml->getAttribute( "Rfc"))->first();
                                            }
                                            else {
                                                $user = Client::with('user')->where('rfc', $xml->getAttribute( "Rfc"))->first();
                                            }

                                            if(!empty($user)){
                                                $user_id = $user->user->id;
                                            }
                                            else
                                                $usr_exist = false;
                                        }
                                    }
                                    else {
                                        $xml_body = false;
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file .= $file." - ". $e->getMessage() ."\n";
                        Log::error('error in Commands@GetFacturasCommand-Try: '.$errormsg_file, []);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file .= $file." - Error leyendo el xml o error en la estructura del xml \n";
                        Log::warning('error in Commands@GetFacturasCommand: '.$errormsg_file, []);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file .= $file." - El cliente o proveedor asociado no se encuentra en el sistema \n";
                        Log::warning('error in Commands@GetFacturasCommand: '.$errormsg_file, []);
                    }
                    else {
                        $errormsg_file .= $file." - Cargado correctamente \n";
                        Log::notice('success in Commands@GetFacturasCommand: '.$errormsg_file, []);

                        $idFactura = Factura::create($factura);
                        DB::table('_users_facturas')->insert(
                            ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );

                        $total ++;
                    }
                    
                    unlink($xmlFile);
                }
            }
        }

        Log::info('success in Commands@GetFacturasCommand - Total de archivos procesados: '.$total, []);
        $errormsg_file .= "Total de archivos procesados: ".$total;

        return $errormsg_file;
    }

    public function getComponentes(){
        $errormsg_file = "";
        $total = 0;
        $disk = Storage::disk('sftp-complementos');

        $files_s3 = array_filter($disk->files(),
            function ($item) {return strpos($item, '.xml');}
        );

        foreach ($files_s3 as $file){
            $today = date ('d/m/Y' , strtotime('-3 day', strtotime(date('Y-m-j'))));
            $modified = date('d/m/Y', $disk->lastModified($file));

            if($this->compararFechas ($today,$modified) <= 0){
                $exists = Complement::where('name', $file)->first();

                if(empty($exists)){
                    $complemento = array();
                    $factura = array();
                    $xml_body = false;
                    $invoice_exist = true;
                    $file_name = pathinfo($file, PATHINFO_FILENAME);
                    $xmlString = Storage::disk('sftp-complementos')->get($file);
                    Storage::disk('local')->put($file,$xmlString);

                    $xmlFile = storage_path('app')."/".$file;

                    $xml = new \XMLReader();
                    $xml->open($xmlFile);

                    try {
                        $xml_body = true;
                        while ($xml->read()) {
                            if ($xml->nodeType == \XMLReader::ELEMENT) {
                                if ($xml->name == 'pago10:DoctoRelacionado') {
                                    if($xml->hasAttributes) {
                                        $factura = Factura::where('IdDocumento', $xml->getAttribute("IdDocumento"))->first();
                                        if(!empty($factura)){
                                            $complemento['name'] = $file;
                                            $complemento['factura_id'] = $factura->id;

                                            $errormsg_file .= $file." - Cargado correctamente para la factura #". $factura->numero_factura."\n";
                                            Log::notice('error in Commands@GetComponentesCommand: '.$errormsg_file, []);

                                            Complement::create($complemento);

                                            $total ++;

                                            $factura->NumParcialidad = $xml->getAttribute("NumParcialidad");
                                            $factura->save();

                                            if(Storage::disk('sftp-complementos')->exists($file_name.'.pdf')){
                                                $complemento['name'] = $file_name.'.pdf';
                                                $complemento['factura_id'] = $factura->id;

                                                Complement::create($complemento);

                                                $errormsg_file .= $file_name.".pdf - Cargado correctamente para la factura #". $factura->numero_factura."\n";
                                                Log::notice('error in Commands@GetComponentesCommand: '.$errormsg_file, []);

                                                $total ++;
                                            }
                                        }
                                        else{
                                            $invoice_exist = false;

                                            $errormsg_file .= $file." - La factura asociada #". $xml->getAttribute("IdDocumento")." no se encuentra en el sistema \n";
                                            Log::warning('error in Commands@GetComponentesCommand: '.$errormsg_file, []);
                                        }
                                    }
                                    else {
                                        $xml_body = false;

                                        $errormsg_file .= $file." - Error leyendo el xml o error en la estructura del xml \n";
                                        Log::warning('error in Commands@GetComponentesCommand: '.$errormsg_file, []);
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file .= $file." - ". $e->getMessage()."\n";
                        Log::error('error in Commands@GetComponentesCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    unlink($xmlFile);
                }
            }
        }

        Log::info('success in Commands@GetComponentesCommand - Total de archivos procesados: '.$total, []);
        $errormsg_file .= "Total de archivos procesados: ".$total;

        return $errormsg_file;
    }

    public function getOrdenes(){
        $errormsg_file = "";
        $total = 0;

        $disk = Storage::disk('sftp-ordenes');

        $files_s3 = array_filter($disk->files(),
            function ($item) {return strpos($item, '.xml');}
        );

        foreach ($files_s3 as $file){
            $today = date ('d/m/Y' , strtotime('-3 day', strtotime(date('Y-m-j'))));
            $modified = date('d/m/Y', $disk->lastModified($file));

            if($this->compararFechas ($today,$modified) <= 0){
                $exists = Order::where('name_file', $file)->first();

                if(empty($exists)){
                    $order = array();
                    $xml_body = false;
                    $usr_exist = true;
                    $xmlString = Storage::disk('sftp-ordenes')->get($file);
                    Storage::disk('local')->put($file,$xmlString);

                    $xmlFile = storage_path('app')."/".$file;

                    $xml = new \XMLReader();
                    $xml->open($xmlFile);

                    try {
                        $assoc = $this->xml2assoc($xml);
                        if(isset($assoc[0]['tag']) && $assoc[0]['tag'] == 'tracking'){
                            $xml_body = true;
                            foreach ($assoc[0]['value'] as $element){
                                if(in_array($element['tag'],['OrderNo', 'Order']))
                                    $order['order'] = $element['value'];
                                elseif(in_array($element['tag'],['referenceNo', 'reference']))
                                    $order['reference'] = $element['value'];
                                elseif($element['tag'] == 'dateTime')
                                    $order['dateTime'] = $element['value'];
                                elseif($element['tag'] == 'Status') {
                                    $order['status'] = $element['value'];
                                    if($element['value'] == 'Enviado al Cliente')
                                        $order['EstadoOrden'] = 'Cerrada';
                                    else
                                        $order['EstadoOrden'] = 'Abierta';    
                                }
                                // elseif($element['tag'] == 'EstadoOrden')
                                //     $order['EstadoOrden'] = ($element['value'] != 'Cerrada') ? 'Abierta' : 'Cerrada';
                                elseif($element['tag'] == 'Client')
                                    $order['client'] = $element['value'];
                                elseif($element['tag'] == 'Code')
                                    $order['code'] = $element['value'];
                                elseif($element['tag'] == 'Montage')
                                    $order['montage'] = $element['value'];
                                elseif($element['tag'] == 'coating')
                                    $order['coating'] = $element['value'];
                                elseif($element['tag'] == 'Color')
                                    $order['color'] = $element['value'];
                                else
                                    $xml_body = false;
                            }
                        }
                        else
                            $xml_body = false;
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file .= $file." - ". $e->getMessage()."\n";
                        Log::error('error in Commands@GetOrdenesCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file .= $file." - Error leyendo el xml o error en la estructura del xml \n";
                        Log::warning('error in Commands@GetOrdenesCommand: '.$errormsg_file, []);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file .= $file." - El cliente o proveedor asociado no se encuentra en el sistema \n";
                        Log::warning('error in Commands@GetOrdenesCommand: '.$errormsg_file, []);
                    }
                    else {
                        $errormsg_file .= $file." - Cargado correctamente \n";
                        Log::notice('success in Commands@GetOrdenesCommand: '.$errormsg_file, []);

                        $order['name_file'] = $file;

                        Order::create($order);

                        $total ++;
                     }

                    unlink($xmlFile);

                }
            }
        }

        Log::info('success in Commands@GetOrdenesCommand - Total de archivos procesados: '.$total, []);
        $errormsg_file .= "Total de archivos procesados: ".$total;

        return $errormsg_file;
    }

    public function getPagos() {
        $errormsg_file = "";
        $total = 0;

        $disk = Storage::disk('sftp-pagos');

        $files_s3 = array_filter($disk->files(),
            function ($item) {return strpos($item, '.xml');}
        );

        foreach ($files_s3 as $file){
            $today = date ('d/m/Y' , strtotime('-3 day', strtotime(date('Y-m-j'))));
            $modified = date('d/m/Y', $disk->lastModified($file));

            //if($this->compararFechas ($today,$modified) <= 0){
                $exists = Payment::where('name_file', $file)->first();

                if(empty($exists)){
                    $pago = array();
                    $factura = null;
                    $xml_body = false;
                    $invoice_exist = true;
                    $xmlString = Storage::disk('sftp-pagos')->get($file);
                    Storage::disk('local')->put($file,$xmlString);

                    $xmlFile = storage_path('app')."/".$file;

                    $xml = new \XMLReader();
                    $xml->open($xmlFile);

                    try {
                        $assoc = $this->xml2assoc($xml);
                        if(isset($assoc[0]['tag']) && $assoc[0]['tag'] == 'payment'){
                            $xml_body = true;
                            foreach ($assoc[0]['value'] as $element){
                                if(in_array($element['tag'],['Factura'])){
                                    $factura_name = substr_replace(trim($element['value']), '_', 2, 0);
                                    $factura = Factura::where('nombre_factura', 'like','%'.$factura_name.'%')->first();
                                    if(!empty($factura)){

                                        $factura->estadoOtro = 'Pagado';
                                        $factura->save();
                                     }
                                    else
                                       $invoice_exist = false;
                                }
                                elseif($element['tag'] == 'Referencia')
                                    $pago['referencia'] = trim($element['value']);
                                elseif($element['tag'] == 'Fpago'){
                                    $fecha_pago = date("m/d/Y", strtotime(trim($element['value'])));
                                    $fecha_pago = date("Y-m-d", strtotime($fecha_pago));
                                    $pago['Fpago'] = $fecha_pago;
                                }
                                elseif($element['tag'] == 'RefQAD')
                                    $pago['RefQAD'] = trim($element['value']);
                            }

                        }
                        else
                            $xml_body = false;    
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file .= $file." - ". $e->getMessage()."\n";
                        Log::error('error in Commands@GetPagosCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file .= $file." - Error leyendo el xml o error en la estructura del xml \n";
                        Log::warning('error in Commands@GetPagosCommand: '.$errormsg_file, []);
                    }
                    elseif (!$invoice_exist){
                        $errormsg_file .= $file." - La factura asociada no se encuentra en el sistema \n";
                        Log::warning('error in Commands@GetPagosCommand: '.$errormsg_file, []);
                    }
                    else {
                        $errormsg_file .= $file." - Cargado correctamente \n";
                        Log::notice('success in Commands@GetPagosCommand: '.$errormsg_file, []);

                        $pago['name_file'] = $file;

                        $pago = Payment::create($pago);

                        DB::table('payments_facturas')->insert(
                            ['payment_id' => $pago->id, 'factura_id' => $factura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );

                        $total ++;
                     }

                    unlink($xmlFile);
                }
            //}
        }

        Log::info('success in Commands@GetPagosCommand - Total de archivos procesados: '.$total, []);
        $errormsg_file .= "Total de archivos procesados: ".$total;

        return $errormsg_file;
    }

    public function getPagosProveedor() {
        $errormsg_file = "";
        $total = 0;

        $disk = Storage::disk('sftp-pagos-proveedores');

        $files_s3 = array_filter($disk->files(),
            function ($item) {return strpos($item, '.xml');}
        );

        foreach ($files_s3 as $file){
            $today = date ('d/m/Y' , strtotime('-3 day', strtotime(date('Y-m-j'))));
            $modified = date('d/m/Y', $disk->lastModified($file));

            if($this->compararFechas ($today,$modified) <= 0){
                $exists = PaymentsProvider::where('name_file', $file)->first();

                if(empty($exists)){
                    $pago = array();
                    $factura = null;
                    $xml_body = false;
                    $invoice_exist = true;
                    $provider_exist = true;
                    $xmlString = Storage::disk('sftp-pagos-proveedores')->get($file);
                    Storage::disk('local')->put($file,$xmlString);

                    $xmlFile = storage_path('app')."/".$file;

                    $xml = new \XMLReader();
                    $xml->open($xmlFile);

                    try {
                        $assoc = $this->xml2assoc($xml);
                        if(isset($assoc[0]['tag']) && $assoc[0]['tag'] == 'tracking'){
                            $xml_body = true;
                            foreach ($assoc[0]['value'] as $element){
                                if($element['tag'] == 'Proveedor'){
                                    $cod_proveedor = trim($element['value']);
                                    $proveedor = Provider::where('cod_proveedor', 'like', $cod_proveedor)->first();

                                    if(!empty($proveedor)){
                                        $pago['Proveedor'] = trim($element['value']);
                                    }
                                    else
                                        $provider_exist = false;                                    
                                }                                    
                                elseif(in_array($element['tag'],['Factura'])){
                                    $IdDocumento = trim($element['value']);
                                    $factura = Factura::where('IdDocumento', 'like','%'.$IdDocumento)->first();
                                    if(!empty($factura)){
                                        $pago['Factura'] = $factura->id;
                                        $deadline = date("d/m/Y", strtotime($pago['FechaPago']));
                                        $deadline = date("Y-m-01", strtotime($deadline."+ 1 month"));
                                        $deadline = date("Y-m-d", strtotime($deadline."+ 9 days"));

                                        $factura->estadoOtro = 'Pagado';
                                        if($factura->MetodoPago == 'PPD')
                                            $factura->deadline_for_complement = $deadline;
                                        $factura->save();
                                     }
                                    else
                                       $invoice_exist = false;
                                }
                                elseif($element['tag'] == 'ChequeQAD')
                                    $pago['ChequeQAD'] = trim($element['value']);
                                elseif($element['tag'] == 'VoucherQAD')
                                    $pago['VoucherQAD'] = trim($element['value']);
                                elseif($element['tag'] == 'FechaPago')
                                    $pago['FechaPago'] = trim($element['value']);
                            }
                        }
                        else
                            $xml_body = false;    
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file .= $file." - ". $e->getMessage()."\n";
                        Log::error('error in Commands@GetPagosProveedoresCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    //dd($pago);

                    if (!$xml_body){
                        $errormsg_file .= $file." - Error leyendo el xml o error en la estructura del xml \n";
                        Log::warning('error in Commands@GetPagosProveedoresCommand: '.$errormsg_file, []);
                    }
                    elseif (!$invoice_exist){
                        $errormsg_file .= $file." - La factura asociada no se encuentra en el sistema \n";
                        Log::warning('error in Commands@GetPagosProveedoresCommand: '.$errormsg_file, []);
                    }
                    elseif (!$provider_exist){
                        $errormsg_file .= $file." - El proveedor asociado no se encuentra en el sistema \n";
                        Log::warning('error in Commands@GetPagosProveedoresCommand: '.$errormsg_file, []);
                    }
                    else {
                        $errormsg_file .= $file." - Cargado correctamente \n";
                        Log::notice('success in Commands@GetPagosProveedoresCommand: '.$errormsg_file, []);

                        $pago['name_file'] = $file;

                        $pago = PaymentsProvider::create($pago);

                        $total ++;
                     }

                    unlink($xmlFile);
                }
            }
        }

        Log::info('success in Commands@GetPagosProveedoresCommand - Total de archivos procesados: '.$total, []);
        $errormsg_file .= "Total de archivos procesados: ".$total;

        return $errormsg_file;
    }

    function xml2assoc($xml) {
        $tree = null;
        while($xml->read())
            switch ($xml->nodeType) {
                case \XMLReader::END_ELEMENT: return $tree;
                case \XMLReader::ELEMENT:
                    $node = array('tag' => $xml->name, 'value' => $xml->isEmptyElement ? '' : $this->xml2assoc($xml));
                    if($xml->hasAttributes)
                        while($xml->moveToNextAttribute())
                            $node['attributes'][$xml->name] = $xml->value;
                    $tree[] = $node;
                break;
                case \XMLReader::TEXT:
                case \XMLReader::CDATA:
                    $tree .= $xml->value;
            }
        return $tree;
    }
}
