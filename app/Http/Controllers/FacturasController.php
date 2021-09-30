<?php

namespace App\Http\Controllers;

use App\Client;
use App\Complement;
use App\Factura;
use App\Payment;
use App\Provider;
use App\User;
use ZipArchive;

use File;
use Croppa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\URL;
use VIPSoft\Unzip\Unzip;
use Yajra\DataTables\DataTables;


class FacturasController extends Controller
{
    public $folder = '\carpetafacturas';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => 'getPaymentInvoice']);
    }

    public function index()
    {
        $user = Auth::user();

        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') )
            $facturas = DB::select('select * from facturas left join _users_facturas on factura_id = facturas.id left join users on users.id = _users_facturas.user_id order by facturas.id desc');
        elseif(Auth::user()->hasRole('cliente') )
            $facturas = DB::select('select *, facturas.created_at as fecha_sistema from facturas left join _users_facturas on factura_id = facturas.id left join clients on clients.user_id = _users_facturas.user_id where _users_facturas.user_id = '.$user->id.' order by facturas.id desc');
        else
            $facturas = DB::select('select *, facturas.created_at as fecha_sistema, facturas.fecha as fecha_factura, payments_providers.FechaPago as fecha_pago 
            from facturas left join payments_providers on payments_providers.Factura = facturas.id 
            left join _users_facturas on factura_id = facturas.id where user_id = '.$user->id.' order by facturas.id desc');

        return view('admin.facturas.index', ['facturas'=>$facturas,'load_invoice'=>true]);

    }

    public function getInvoicesClients(Request $request)
    {
        if ($request->ajax()) {
            $facturas = DB::select('select * from facturas left join _users_facturas on factura_id = facturas.id left join users on users.id = _users_facturas.user_id left join users_roles on users_roles.user_id = users.id left join clients on clients.user_id = users.id where users_roles.role_id = 3 order by facturas.id desc');

            return DataTables::of($facturas)
            ->addColumn('action', function ($row) {

                $btn = '<a href="'.url('/admin/facturas/complemento-pago/'.$row->factura_id).'" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>';

                $btn .= '<a href="javascript: void(0);" onclick="openModalChange('.$row->factura_id.');" title="Cambiar Estatus" class="btn btn-primary btn-circle btn-sm modal-open" ><i class="fas fa-cogs"></i></a>';

                $btn .= '<a href="javascript: void(0);" onclick="openModalDelete('.$row->factura_id.');" title="Eliminar" class="btn btn-danger btn-circle btn-sm modal-open" ><i class="fas fa-trash-alt"></i></a>';

                $btn .= '<a href="'.url('/admin/facturas/imprimir/'.$row->factura_id.'/pdf').'" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>';

                $btn .= '<a href="'.url('/admin/facturas/imprimir/'.$row->factura_id.'/xml').'" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>';

                // $btn .= '<a href="'.url('/admin/facturas/imprimir/'.$row->factura_id.'/zip').'" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>';

                $btn .= '<a href="'.url('/admin/facturas/complementos-pago/'.$row->factura_id).'" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>';

                return $btn;
            })
            ->addColumn('factura_id', function($row){
                return $row->factura_id;
            })
            ->addColumn('cod_cliente', function($row){
                return $row->cod_cliente;
            })
            ->addColumn('numero_factura', function($row){
                return $row->numero_factura;
            })
            ->addColumn('nombre_factura', function($row){
                return $row->nombre_factura;
            })
            ->addColumn('total_cost', function($row){
                return number_format($row->total_cost, 2, '.', ',');
            })
            ->addColumn('estado', function($row){
                return (!empty($row->estadoOtro)) ? $row->estadoOtro : $row->estado;
            })
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.facturas.index2', ['load_invoice'=>true]);
    }

    public function getInvoicesProviders(Request $request)
    {
        if ($request->ajax()) {
            $facturas = DB::select('select *, facturas.created_at as fecha_sistema, payments_providers.FechaPago from facturas 
            left join payments_providers on payments_providers.Factura = facturas.id
            left join _users_facturas on factura_id = facturas.id 
            left join users on users.id = _users_facturas.user_id 
            left join users_roles on users_roles.user_id = users.id 
            left join providers on providers.user_id = users.id 
            where users_roles.role_id = 2 order by facturas.id desc');

            return DataTables::of($facturas)
            ->addColumn('action', function ($row) {

                $btn = '<a href="'.url('/admin/facturas/complemento-pago/'.$row->factura_id).'" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>';

                $btn .= '<a href="javascript: void(0);" onclick="openModalChange('.$row->factura_id.');" title="Cambiar Estatus" class="btn btn-primary btn-circle btn-sm modal-open" ><i class="fas fa-cogs"></i></a>';

                $btn .= '<a href="javascript: void(0);" onclick="openModalDelete('.$row->factura_id.');" title="Eliminar" class="btn btn-danger btn-circle btn-sm modal-open" ><i class="fas fa-trash-alt"></i></a>';

                $btn .= '<a href="'.url('/admin/facturas/imprimir/'.$row->factura_id.'/pdf').'" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>';

                $btn .= '<a href="'.url('/admin/facturas/imprimir/'.$row->factura_id.'/xml').'" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>';

                // $btn .= '<a href="'.url('/admin/facturas/imprimir/'.$row->factura_id.'/zip').'" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>';

                $btn .= '<a href="'.url('/admin/facturas/complementos-pago/'.$row->factura_id).'" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>';

                return $btn;
            })
            ->addColumn('fecha_sistema', function($row){
                return $row->fecha_sistema;
            })
            ->addColumn('fecha', function($row){
                return $row->fecha;
            })
            ->addColumn('factura_id', function($row){
                return $row->factura_id;
            })
            ->addColumn('cod_proveedor', function($row){
                return $row->cod_proveedor;
            })
            ->addColumn('numero_factura', function($row){
                return $row->numero_factura;
            })
            ->addColumn('nombre_factura', function($row){
                return $row->nombre_factura;
            })
            ->addColumn('total_cost', function($row){
                return number_format($row->total_cost, 2, '.', ',');
            })
            ->addColumn('moneda', function($row){
                return $row->moneda;
            })
            ->addColumn('estado', function($row){
                if($row->estadoOtro){
                    return $row->estadoOtro;
                }else{
                    if($row->estado == 'validado')
                    {
                      return 'programado';
                    }elseif($row->estado == 'pendiente')
                    {
                      return 'pendiente';
                    }else{
                      return 'rechazado';
                    }
                }
            })
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->addColumn('payment_promise_date', function($row){
                return (!empty($row->payment_promise_date)) ? date('d/m/Y', strtotime($row->payment_promise_date)) : "No definido";
            })
            ->addColumn('deadline_for_complement', function($row){
                return (!empty($row->deadline_for_complement)) ? date('d/m/Y', strtotime($row->deadline_for_complement)) : "";
            })
            ->addColumn('FechaPago', function($row){
                return (!empty($row->FechaPago)) ? date('d/m/Y', strtotime($row->FechaPago)) : "";
            })
            ->addColumn('check', function($row){
                return '<input type="checkbox" id="'.$row->factura_id.'" class="download" onchange="descargar(this)">';
            })
            ->rawColumns(['action','check'])
            ->make(true);
        }

        return view('admin.facturas.index2', ['load_invoice'=>false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.facturas.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createInvoiceProvider($ext)
    {
        return view('admin.facturas.create_provider', ['ext' => $ext]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'uploadfile' => 'required'
        ]);

        $path = storage_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        $errormsg_file = array();

        if($request->hasfile('uploadfile')) {
            foreach($request->file('uploadfile') as $file)
            {
                $name_file = $file->getClientOriginalName();
                $file_name = pathinfo($name_file, PATHINFO_FILENAME);
                $extension_file = $file->getClientOriginalExtension();
                $factura = $request->all();
                if(Auth::user()->hasRole('proveedor')){
                    $user_id = Auth::user()->id;
                    $provider = Provider::with('user')->where('user_id', $user_id)->first();
                    $factura['payment_promise_date'] = date("Y-m-d", strtotime(date('Y-m-d')."+ ".$provider->credit_terms." days"));
                    // if(date('D', strtotime($factura['payment_promise_date'])) != 'Mon')
                    //     $factura['payment_promise_date'] = date("Y-m-d", strtotime('next Monday '.$factura['payment_promise_date']));
                }
                else
                    $user_id = $request->user_id;

                if(in_array($extension_file,['zip'])){
                    $xml_type = false;
                    $xml_body = false;
                    $usr_exist = true;
                    $provider_not = false;
                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $unzipper  = new Unzip();
                    $filenames = $unzipper->extract(storage_path('carpetafacturas/').$name_file, storage_path('carpetafacturas/'));

                    foreach ($filenames as $filename) {

                        if(strpos($filename, "__MACOSX/") === false){
                            if (strpos($filename, "xml")){
                                $xml_type = true;

                                $xmlFile = storage_path()."/carpetafacturas/".$filename;

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
                                                    $factura['fecha'] = $xml->getAttribute("Fecha");
                                                    $factura['nombre_factura'] = $name_file;
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
                                            elseif (Auth::user()->hasRole('proveedor') && $xml->name == 'cfdi:Emisor'){
                                                if($xml->hasAttributes) {
                                                    $user = Provider::with('user')->where('rfc', $xml->getAttribute( "Rfc"))->first();
                                                    if(!empty($user)){
                                                        if($xml->getAttribute( "Rfc") != $user->rfc){
                                                            $provider_not = true;
                                                        }
                                                    }
                                                    else
                                                        $usr_exist = false;
                                                }
                                                else {
                                                    $xml_body = false;
                                                }
                                            }
                                            elseif (($xml->name == 'cfdi:Emisor' || $xml->name == 'cfdi:Receptor') && !Auth::user()->hasRole('proveedor') ){
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
                                    $errormsg_file[] = $name_file." - ". $e->getMessage();
                                }

                                $xml->close();
                            }
                        }
                    }

                    if(!$xml_type){
                        $errormsg_file[] = $name_file." - El comprimido debe contener el archivo xml";
                        $this->deleteDocument($path, $file_name);
                    }
                    elseif (!$xml_body){
                        $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                        $this->deleteDocument($path, $file_name);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $name_file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        $this->deleteDocument($path, $file_name);
                    }
                    elseif ($provider_not){
                        $errormsg_file[] = $name_file." - El proveedor asociado a la factura no es el mismo que carga";
                        $this->deleteDocument($path, $file_name);
                    }
                    else {
                        $errormsg_file[] = $name_file." - Cargado correctamente";

                        $idFactura = Factura::create($factura);
                        DB::table('_users_facturas')->insert(
                            ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );
                    }
                }
                elseif ($extension_file == 'xml'){
                    $xml_body = false;
                    $usr_exist = true;
                    $provider_not = false;

                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $xmlFile = storage_path()."/carpetafacturas/".$name_file;

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
                                        $factura['fecha'] = $xml->getAttribute("Fecha");
                                        $factura['nombre_factura'] = $name_file;
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
                                elseif (Auth::user()->hasRole('proveedor') && $xml->name == 'cfdi:Emisor'){
                                    if($xml->hasAttributes) {
                                        $user = Provider::with('user')->where('rfc', $xml->getAttribute( "Rfc"))->first();
                                        if(!empty($user)){
                                            if($xml->getAttribute( "Rfc") != $user->rfc){
                                                $provider_not = true;
                                            }
                                        }
                                        else
                                            $usr_exist = false;
                                    }
                                    else {
                                        $xml_body = false;
                                    }
                                }
                                elseif (($xml->name == 'cfdi:Emisor' || $xml->name == 'cfdi:Receptor') && !Auth::user()->hasRole('proveedor')){
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
                        $errormsg_file[] = $name_file." - ". $e->getMessage();
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                        unlink(storage_path('carpetafacturas/').$name_file);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $name_file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        unlink(storage_path('carpetafacturas/').$name_file);
                    }
                    elseif ($provider_not){
                        $errormsg_file[] = $name_file." - El proveedor asociado a la factura no es el mismo que carga";
                        $this->deleteDocument($path, $file_name);
                    }
                    else {
                        $errormsg_file[] = $name_file." - Cargado correctamente";

                        $idFactura = Factura::create($factura);
                        DB::table('_users_facturas')->insert(
                            ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );
                    }
                }
                elseif ($extension_file == 'pdf'){
                    $factura['numero_factura'] = 12;
                    $factura['total_cost'] = 2000;
                    $factura['fecha'] = date("Y-m-d");
                    $factura['nombre_factura'] = $name_file;
                    $factura['estado'] = 2;

                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    $idFactura = Factura::create($factura);
                    DB::table('_users_facturas')->insert(
                        ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                    );
                }
                else {
                    $errormsg_file[] = $name_file." - El archivo debe ser de formato: pdf, xml  o zip";
                }
            }
        }

        return back()->with('info',$errormsg_file);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeInvoiceProvider(Request $request)
    {
        request()->validate([
            'uploadfile' => 'required'
        ]);

        $path = storage_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        $errormsg_file = array();

        if($request->hasfile('uploadfile')) {
            foreach($request->file('uploadfile') as $file)
            {
                $name_file = $file->getClientOriginalName();
                $file_name = pathinfo($name_file, PATHINFO_FILENAME);
                $extension_file = $file->getClientOriginalExtension();
                $factura = $request->all();

                unset($factura['ext']);

                if(Auth::user()->hasRole('proveedor')){
                    $user_id = Auth::user()->id;
                    $provider = Provider::with('user')->where('user_id', $user_id)->first();
                    $factura['payment_promise_date'] = date("Y-m-d", strtotime(date('Y-m-d')."+ ".$provider->credit_terms." days"));
                    // if(date('D', strtotime($factura['payment_promise_date'])) != 'Mon')
                    //     $factura['payment_promise_date'] = date("Y-m-d", strtotime('next Monday '.$factura['payment_promise_date']));
                }

                if(in_array($extension_file,['zip']) && $request->ext == 'zip'){
                    $xml_type = false;
                    $xml_body = false;
                    $usr_exist = true;
                    $provider_not = false;
                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $unzipper  = new Unzip();
                    $filenames = $unzipper->extract(storage_path('carpetafacturas/').$name_file, storage_path('carpetafacturas/'));

                    foreach ($filenames as $filename) {

                        if(strpos($filename, "__MACOSX/") === false){
                            if (strpos($filename, "xml")){
                                $xml_type = true;

                                $xmlFile = storage_path()."/carpetafacturas/".$filename;

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
                                                    $factura['moneda'] = $xml->getAttribute("Moneda");
                                                    $factura['MetodoPago'] = $xml->getAttribute("MetodoPago");
                                                    $factura['fecha'] = $xml->getAttribute("Fecha");
                                                    $factura['nombre_factura'] = $name_file;
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
                                            elseif (Auth::user()->hasRole('proveedor') && $xml->name == 'cfdi:Emisor'){
                                                if($xml->hasAttributes) {
                                                    $user = Provider::with('user')->where('rfc', $xml->getAttribute( "Rfc"))->first();
                                                    if(!empty($user)){
                                                        if($xml->getAttribute( "Rfc") != $user->rfc){
                                                            $provider_not = true;
                                                        }
                                                    }
                                                    else
                                                        $usr_exist = false;
                                                }
                                                else {
                                                    $xml_body = false;
                                                }
                                            }
                                            elseif (($xml->name == 'cfdi:Emisor' || $xml->name == 'cfdi:Receptor') && !Auth::user()->hasRole('proveedor') ){
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
                                    $errormsg_file[] = $name_file." - ". $e->getMessage();
                                }

                                $xml->close();
                            }
                        }
                    }

                    if(!$xml_type){
                        $errormsg_file[] = $name_file." - El comprimido debe contener el archivo xml";
                        $this->deleteDocument($path, $file_name);
                    }
                    elseif (!$xml_body){
                        $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                        $this->deleteDocument($path, $file_name);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $name_file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        $this->deleteDocument($path, $file_name);
                    }
                    elseif ($provider_not){
                        $errormsg_file[] = $name_file." - El proveedor asociado a la factura no es el mismo que carga";
                        $this->deleteDocument($path, $file_name);
                    }
                    else {
                        $errormsg_file[] = $name_file." - Cargado correctamente";

                        $idFactura = Factura::create($factura);
                        DB::table('_users_facturas')->insert(
                            ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );
                    }
                }
                elseif ($extension_file == 'xml' && $request->ext == 'xml'){
                    $xml_body = false;
                    $usr_exist = true;
                    $provider_not = false;

                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $xmlFile = storage_path()."/carpetafacturas/".$name_file;

                    $xml = new \XMLReader();
                    $xml->open($xmlFile);

                    try {
                        $xml_body = true;
                        while ($xml->read()) {
                            if ($xml->nodeType == \XMLReader::ELEMENT) {
                                if ($xml->name == 'cfdi:Comprobante') {
                                    if($xml->hasAttributes) {
                                        $factura['numero_factura'] = $xml->getAttribute("Folio");
                                        $factura['total_cost'] = $xml->getAttribute("Total");
                                        $factura['moneda'] = $xml->getAttribute("Moneda");
                                        $factura['MetodoPago'] = $xml->getAttribute("MetodoPago");
                                        $factura['fecha'] = $xml->getAttribute("Fecha");
                                        $factura['nombre_factura'] = $name_file;
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
                                elseif (Auth::user()->hasRole('proveedor') && $xml->name == 'cfdi:Emisor'){
                                    if($xml->hasAttributes) {
                                        $user = Provider::with('user')->where('rfc', $xml->getAttribute( "Rfc"))->first();
                                        if(!empty($user)){
                                            if($xml->getAttribute( "Rfc") != $user->rfc){
                                                $provider_not = true;
                                            }
                                        }
                                        else
                                            $usr_exist = false;
                                    }
                                    else {
                                        $xml_body = false;
                                    }
                                }
                                elseif (($xml->name == 'cfdi:Emisor' || $xml->name == 'cfdi:Receptor') && !Auth::user()->hasRole('proveedor')){
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
                        $errormsg_file[] = $name_file." - ". $e->getMessage();
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                        unlink(storage_path('carpetafacturas/').$name_file);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $name_file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        unlink(storage_path('carpetafacturas/').$name_file);
                    }
                    elseif ($provider_not){
                        $errormsg_file[] = $name_file." - El proveedor asociado a la factura no es el mismo que carga";
                        $this->deleteDocument($path, $file_name);
                    }
                    else {
                        $errormsg_file[] = $name_file." - Cargado correctamente";

                        $idFactura = Factura::create($factura);
                        DB::table('_users_facturas')->insert(
                            ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );
                    }
                }
                elseif ($extension_file == 'pdf' && $request->ext == 'pdf'){
                    // $factura['numero_factura'] = 12;
                    // $factura['total_cost'] = 2000;
                    // $factura['nombre_factura'] = $name_file;
                    // $factura['estado'] = 2;

                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    // $idFactura = Factura::create($factura);
                    // DB::table('_users_facturas')->insert(
                    //     ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                    // );
                }
                else {
                    $errormsg_file[] = $name_file." - El archivo debe ser de formato: ".$request->ext;
                }
            }
        }

        return back()->with('info',$errormsg_file);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('admin.facturas.show',
            ['facturas'=>Factura::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $factura = Factura::findOrFail($id);
        return view('admin.facturas.edit', ['factura'=>$factura]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $factura = Factura::findOrFail($id);
        $entrada = $request->all();


        $entrada['estado'] = 'pendiente';

        $factura->fill($entrada)->save();

        return redirect()->route('facturas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);

        $factura->delete();

        return redirect(URL::previous());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $data = $request->all();

        if($data['estado'] == 1){
            $data['deadline_for_complement'] = date("Y-m-d", strtotime("first day of next month"));
            $data['deadline_for_complement'] = date("Y-m-d", strtotime($data['deadline_for_complement']."+ 9 days"));
        }
        elseif (in_array($data['estado'], [2,3]))
            $data['deadline_for_complement'] = null;

        $factura->fill($data)->save();

        return redirect(URL::previous());
    }

    public function receiveComplement($facturaId)
    {
        $factura = Factura::find($facturaId);
        $toDownload = false;

        if ($factura) {
            return view('admin.facturas.upload_complement')
                ->with('factura', $factura);
        }else{
            return redirect(URL::previous())->with('error', "Esta Factura no existe.");
        }
    }

    public function postReceiveComplement(Request $request, $facturaId){
        request()->validate([
            'uploadfile' => 'required'
        ]);

        $path = storage_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        $errormsg_file = array();

        if($request->hasfile('uploadfile')) {
            foreach($request->file('uploadfile') as $file)
            {
                $name_file = $file->getClientOriginalName();
                $extension_file = $file->getClientOriginalExtension();
                $complement = $request->all();

                if(in_array($extension_file,['zip'])){
                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $complement['name'] = $name_file;
                    $complement['factura_id'] = $facturaId;

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    Complement::create($complement);
                }
                elseif ($extension_file == 'xml'){
                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $complement['name'] = $name_file;
                    $complement['factura_id'] = $facturaId;

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    Complement::create($complement);
                }
                elseif ($extension_file == 'pdf'){
                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $complement['name'] = $name_file;
                    $complement['factura_id'] = $facturaId;

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    Complement::create($complement);
                }
                else {
                    $errormsg_file[] = $name_file." - El archivo debe ser de formato: pdf, xml  o zip";
                }
            }
        }

        return back()->with('info',$errormsg_file);
    }

    public function downloadDocument($facturaId, $ext){
        try {
            $factura = Factura::find($facturaId);
            $path = "";

            if ($factura){
                $file_name = $factura->nombre_factura;
                $name_file = pathinfo($file_name, PATHINFO_FILENAME);

                if (empty($file_name)){
                    return redirect(URL::previous())->with('error', "No se encontró un archivo para este tipo o factura.");
                }

                if(file_exists(storage_path('carpetafacturas/').$name_file.'.'.$ext))
                    $path = storage_path('carpetafacturas/').$name_file.'.'.$ext;
                elseif (file_exists(storage_path('carpetafacturas/').$name_file)) {
                    $path = storage_path('carpetafacturas/').$name_file.'/'.$name_file.'.'.$ext;
                }
                elseif(Storage::disk('sftp-facturas')->exists($name_file.'.'.$ext)){
                    return Storage::disk('sftp-facturas')->download($name_file.'.'.$ext);
                }

                if(is_file($path)){
                    return response()->download($path);
                }
                else {
                    return redirect(URL::previous())->with('error', "No se encontró un archivo para este tipo o factura.");
                }
            }
            else{
                return redirect(response::back());
            }
        } catch (\Throwable $th) {
            return redirect(URL::previous())->with('error', "Error conectandose al servidor FTP, contacte al administrador!");
        }
    }

    public function viewComplement($facturaId)
    {
        $user = Auth::user();

        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') ){
            $facturas = DB::select('select complements.id, facturas.numero_factura, complements.name, facturas.total_cost, facturas.estado, facturas.NumParcialidad from facturas left join complements on complements.factura_id = facturas.id where complements.factura_id = '.$facturaId.' order by facturas.id desc');
        }
        else {
            $facturas = DB::select('select complements.id, facturas.numero_factura, complements.name, facturas.total_cost, facturas.estado, facturas.NumParcialidad from facturas left join _users_facturas on _users_facturas.factura_id = facturas.id left join complements on complements.factura_id = facturas.id where user_id = '.$user->id.' and complements.factura_id = '.$facturaId.' order by facturas.id desc');
        }

        return view('admin.facturas.complements', ['facturas'=>$facturas]);

    }

    public function downloadComplement($complementId){
        try {
            $complement = Complement::find($complementId);
            $path = "";

            if ($complement){
                $file_name = $complement->name;

                if (empty($file_name)){
                    return redirect(URL::previous())->with('error', "No se encontró un archivo para este complemento.");
                }

                $path = storage_path('carpetafacturas/').$file_name;

                if(is_file($path)){
                    return response()->download($path);
                }
                elseif(Storage::disk('sftp-complementos')->exists($file_name)){
                    return Storage::disk('sftp-complementos')->download($file_name);
                }
                else {
                    return redirect(URL::previous())->with('error', "No se encontró un archivo para este complemento.");
                }
            }
            else{
                return redirect(response::back());
            }
        } catch (\Throwable $th) {
            return redirect(URL::previous())->with('error', "Error conectandose al servidor FTP, contacte al administrador!");
        }
    }

    public function deleteComplement($id)
    {
        $complement = Complement::findOrFail($id);

        if (file_exists(storage_path('carpetafacturas/').$complement->name))
            unlink(storage_path('carpetafacturas/').$complement->name);

        $complement->delete();

        return redirect()->route('facturas.index');
    }

    public function deleteDocument($path, $file_name){
        if(file_exists(storage_path('carpetafacturas/').$file_name.'.xml'))
            unlink(storage_path('carpetafacturas/').$file_name.'.xml');
        elseif (file_exists(storage_path('carpetafacturas/').$file_name)) {
            unlink(storage_path('carpetafacturas/').$file_name.'/'.$file_name.'.xml');
        }
        if(file_exists(storage_path('carpetafacturas/').$file_name.'.pdf'))
            unlink(storage_path('carpetafacturas/').$file_name.'.pdf');
        elseif (file_exists(storage_path('carpetafacturas/').$file_name)) {
            unlink(storage_path('carpetafacturas/').$file_name.'/'.$file_name.'.pdf');
        }
        if(file_exists(storage_path('carpetafacturas/').$file_name.'.zip'))
            unlink(storage_path('carpetafacturas/').$file_name.'.zip');
        elseif (file_exists(storage_path('carpetafacturas/').$file_name)) {
            unlink(storage_path('carpetafacturas/').$file_name.'/'.$file_name.'.zip');
        }

        if (file_exists(storage_path('carpetafacturas/').$file_name))
            rmdir(storage_path('carpetafacturas/').$file_name);
    }

    public function setPaymentInvoice(Request $request)
    {
        $amount = 0;
        $referencia = "";
        $signature = "";

        foreach ($request->ids as $id){
            $invoice = Factura::findOrFail($id);

            $amount += $invoice->total_cost;
            $referencia .= "F".$id;
        }

        $amount = number_format($amount,2,".","");

        $string = $referencia.$amount."1809";

        $signature = hash_hmac('sha256', $string, "Sc4On=U=A6Xbg0aZz5Yd");

        return view('admin.facturas.payment_invoice', ['amount'=>$amount, 'referencia'=>$referencia, 'signature'=>$signature]);
    }

    public function getPaymentInvoice(Request $request)
    {
        $payment['codigo'] = $request->codigo;
        $payment['mensaje'] = $request->mensaje;
        $payment['autorizacion'] = $request->autorizacion;
        $payment['referencia'] = $request->referencia;
        $payment['importe'] = $request->importe;
        $payment['mediopago'] = $request->mediopago;
        $payment['financiado'] = $request->financiado;
        $payment['plazos'] = $request->plazos;
        $payment['s_transm'] = $request->s_transm;
        $payment['hash'] = $request->hash;
        $payment['tarjetahabiente'] = $request->tarjetahabiente;
        $payment['cveTipoPago'] = $request->cveTipoPago;
        $payment['signature'] = $request->signature;

        $idPayment = Payment::create($payment);

        $facturas = explode('F', $request->referencia);

        $pago = false;

        foreach ($facturas as $idFactura){
            if(!empty($idFactura)){
                DB::table('payments_facturas')->insert(
                    ['payment_id' => $idPayment->id, 'factura_id' => $idFactura, 'created_at' => NOW(), 'updated_at' => NOW()]
                );

                if($request->codigo == "0"){
                    $pago = true;

                    $factura = Factura::findOrFail($idFactura);

                    $factura->estado = 'pagado';

                    $factura->save();
                }
                else {
                    $pago = false;
                }
            }
        }

        if($pago)
            return view('admin.facturas.comprobante-pago', ['error'=>"El pago se realizo correctamente, numero de autorizacion: ". $request->autorizacion, 'payment'=>$payment]);
        else
            return view('admin.facturas.comprobante-pago', ['error'=>"Tuvimos un problema con su pago", 'payment'=>$payment]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createComplement()
    {
        //
        return view('admin.facturas.complement-create');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComplement(Request $request)
    {
        request()->validate([
            'uploadfile' => 'required'
        ]);

        $path = storage_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        $errormsg_file = array();

        if($request->hasfile('uploadfile')) {
            foreach($request->file('uploadfile') as $file)
            {
                $name_file = $file->getClientOriginalName();
                $file_name = pathinfo($name_file, PATHINFO_FILENAME);
                $extension_file = $file->getClientOriginalExtension();
                $complemento = array();
                $factura = array();

                if(in_array($extension_file,['zip'])){
                    $xml_type = false;
                    $xml_body = false;
                    $invoice_exist = true;
                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $unzipper  = new Unzip();
                    $filenames = $unzipper->extract(storage_path('carpetafacturas/').$name_file, storage_path('carpetafacturas/'));

                    foreach ($filenames as $filename) {
                        if(strpos($filename, "__MACOSX/") === false){
                            if (strpos($filename, "xml")){
                                $xml_type = true;

                                $xmlFile = storage_path()."/carpetafacturas/".$filename;

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
                                                        $complemento['name'] = $name_file;
                                                        $complemento['factura_id'] = $factura->id;

                                                        $errormsg_file[] = $name_file." - Cargado correctamente para la factura #". $factura->numero_factura;

                                                        Complement::create($complemento);

                                                        $factura->NumParcialidad = $xml->getAttribute("NumParcialidad");
                                                        $factura->save();
                                                    }
                                                    else{
                                                        $invoice_exist = false;

                                                        $errormsg_file[] = $name_file." - La factura asociada #". $xml->getAttribute("IdDocumento")." no se encuentra en el sistema";
                                                        unlink(storage_path('carpetafacturas/').$name_file);
                                                    }
                                                }
                                                else {
                                                    $xml_body = false;

                                                    $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                                                    unlink(storage_path('carpetafacturas/').$name_file);
                                                }
                                            }
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $xml_body = false;
                                    $errormsg_file[] = $name_file." - ". $e->getMessage();
                                }

                                $xml->close();
                            }
                        }
                    }

                    if(!$xml_type){
                        $errormsg_file[] = $name_file." - El comprimido debe contener el archivo xml";
                        $this->deleteDocument($path, $file_name);
                    }
                }
                elseif ($extension_file == 'xml'){
                    $xml_body = false;
                    $invoice_exist = true;

                    $file->move(storage_path('carpetafacturas'), $name_file);

                    $xmlFile = storage_path()."/carpetafacturas/".$name_file;

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
                                            $complemento['name'] = $name_file;
                                            $complemento['factura_id'] = $factura->id;

                                            $errormsg_file[] = $name_file." - Cargado correctamente para la factura #". $factura->numero_factura;

                                            Complement::create($complemento);

                                            $factura->NumParcialidad = $xml->getAttribute("NumParcialidad");
                                            $factura->save();

                                            if(file_exists(storage_path('carpetafacturas/').$file_name.'.pdf')){
                                                $complemento['name'] = $file_name.'.pdf';
                                                $complemento['factura_id'] = $factura->id;

                                                Complement::create($complemento);

                                                $errormsg_file[] = $file_name.".pdf - Cargado correctamente para la factura #". $factura->numero_factura;
                                            }
                                        }
                                        else{
                                            $invoice_exist = false;

                                            $errormsg_file[] = $name_file." - La factura asociada #". $xml->getAttribute("IdDocumento")." no se encuentra en el sistema";
                                            unlink(storage_path('carpetafacturas/').$name_file);
                                        }
                                    }
                                    else {
                                        $xml_body = false;

                                        $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                                        unlink(storage_path('carpetafacturas/').$name_file);
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file[] = $name_file." - ". $e->getMessage();
                    }

                    $xml->close();
                }
                elseif ($extension_file == 'pdf'){
                    $file->move(storage_path('carpetafacturas'), $name_file);
                }
                else {
                    $errormsg_file[] = $name_file." - El archivo debe ser de formato: pdf, xml  o zip";
                }
            }
        }

        return back()->with('info',$errormsg_file);
    }

    public function downloadInvoiceAll(Request $request, $ext)
    {
        try {
            if (count($request->ids) > 1) {
                $public_dir = storage_path();
                $zipFileName = 'facturas-usuario#'.Auth::user()->id.'.zip';
                if(is_file($zipFileName))
                    unlink($zipFileName);
                $this->createZipFile($request->ids, $ext, $public_dir, $zipFileName);
                return response()->download(storage_path($zipFileName));
            } else {
                foreach ($request->ids as $id){
                    $factura = Factura::find($id);
        
                    $path = "";
        
                    if ($factura){
                        $file_name = $factura->nombre_factura;
                        $name_file = pathinfo($file_name, PATHINFO_FILENAME);
        
                        if (empty($file_name)){
                            $msg[] = "La factura ".$name_file." no tiene nombre de archivo";
                        }
        
                        if(file_exists(storage_path('carpetafacturas/').$name_file.'.'.$ext))
                            $path = storage_path('carpetafacturas/').$name_file.'.'.$ext;
                        elseif (file_exists(storage_path('carpetafacturas/').$name_file)) {
                            $path = storage_path('carpetafacturas/').$name_file.'/'.$name_file.'.'.$ext;
                        }
        
                        if(is_file($path)){
                            return response()->download($path);
                        }
                        elseif(Storage::disk('sftp-facturas')->exists($name_file.'.'.$ext)){
                            return Storage::disk('sftp-facturas')->download($path);
                        }
                        else {
                            $msg[] = "No se encontró un archivo ".$ext." para factura ".$name_file;
                        }
                    }
                    else{
                        $msg[] = "No se encontró la factura con id ".$id;
                    }
                }
        
                //die();
                return redirect(URL::previous())->with('info', $msg);
            }
        } catch (\Throwable $th) {
            return redirect(URL::previous())->with('error', "Error descargando las facturas!!");
        }
    }

    public function createZipFile($files = null, $ext = null, $public_dir = null, $zipFileName = null) {
        $msg = array();
        $zip = new ZipArchive;

        if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $id){
                $factura = Factura::find($id);

                $path = ""; $path2 = ""; $name = "";
 
                if ($factura){
                    $file_name = $factura->nombre_factura;
                    $name_file = pathinfo($file_name, PATHINFO_FILENAME);

                    if (empty($file_name)){
                        $msg[] = "La factura ".$name_file." no tiene nombre de archivo";
                    }

                    if(file_exists(storage_path('carpetafacturas\\').$name_file.'.'.$ext)){
                        $path = storage_path('carpetafacturas\\').$name_file.'.'.$ext;
                    }
                    elseif (file_exists(storage_path('carpetafacturas\\').$name_file)) {
                        $path = storage_path('carpetafacturas\\').$name_file.'\\'.$name_file.'.'.$ext;
                    }

                    if(is_file($path)){
                        $zip->addFromString(basename($path),  file_get_contents($path));
                    }
                    elseif(Storage::disk('sftp-facturas')->exists($name_file.'.'.$ext)){
                        $file = $name_file.'.'.$ext;
                        $string = Storage::disk('sftp-facturas')->get($file);
                        Storage::disk('local')->put($file,$string);
                        $path = storage_path('app')."/".$file;
                        $zip->addFromString(basename($path),  file_get_contents($path));
                    }
                    else {
                        $msg[] = "No se encontró un archivo ".$ext." para factura ".$name_file;
                    }
                }
                else{
                    $msg[] = "No se encontró la factura con id ".$id;
                }
            }
            $zip->close();
            if(is_dir(storage_path('app')))
                $this->rmDir_rf(storage_path('app'));
        }
    }

    function rmDir_rf($carpeta)
    {
        foreach(glob($carpeta . "/*") as $archivos_carpeta){             
            if (is_dir($archivos_carpeta)){
                $this->rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }

    public function downloadStatus($clientId){

        try {
            $msg = array();
            $client = Client::with('user')->where('user_id', $clientId)->first();

            if ($client){
                $rfc = $client->rfc;
                $cod_cliente = $client->cod_cliente;

                if(Storage::disk('sftp-estados-de-cuentas')->exists($rfc.'-'.$cod_cliente.'.pdf')){
                    return Storage::disk('sftp-estados-de-cuentas')->download($rfc.'-'.$cod_cliente.'.pdf');
                }
                else {
                    $msg = "No se encontró un archivo para este estado.";
                }
            }
            else{
                $msg = "No se encontró el cliente.";
            }

            return redirect(URL::previous())->with('error', $msg);

        } catch (\Throwable $th) {
            return redirect(URL::previous())->with('error', "Error descargando el estado, no se puede conectar a FTP o no existe su estado, contacte al administrador!!");
        }

    }
}
