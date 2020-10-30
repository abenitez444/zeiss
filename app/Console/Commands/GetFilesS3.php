<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Factura;
use App\Provider;
use App\Client;
use App\Order;

class GetFilesS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-files-s3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los archivos de las Facturas, Complementos de Pago y Ordenes del bucket S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getFacturas();
        $this->getOrdenes();
    }

    public function getFacturas(){
        $files_s3 = Storage::disk('sftp-facturas')->allFiles();

        foreach ($files_s3 as $file){
            if(strpos($file,"xml")){
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
                                        $factura['nombre_factura'] = $file;
                                        $factura['estado'] = 1;
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
                        $errormsg_file = $file." - ". $e->getMessage();
                        Log::error('error in Commands@CurrentBalance-LastBalance: '.$errormsg_file);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file = $file." - Error leyendo el xml o error en la estructura del xml";
                        Log::error('error in Commands@CurrentBalance-LastBalance: '.$errormsg_file);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        Log::error('error in Commands@CurrentBalance-LastBalance: '.$errormsg_file);
                    }
                    else {
                        $errormsg_file = $file." - Cargado correctamente";
                        Log::error('success in Commands@CurrentBalance-LastBalance: '.$errormsg_file);

                        $idFactura = Factura::create($factura);
                        DB::table('_users_facturas')->insert(
                            ['user_id' => $user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                        );
                    }

                    unlink($xmlFile);
                }
            }
        }
    }

    public function getOrdenes(){

        $files_s3 = Storage::disk('sftp-ordenes')->allFiles();

        foreach ($files_s3 as $file){

            if(strpos($file,"xml")){

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
                                if($element['tag'] == 'OrderNo')
                                    $order['order'] = $element['value'];
                                elseif($element['tag'] == 'referenceNo')
                                    $order['reference'] = $element['value'];
                                elseif($element['tag'] == 'dateTime')
                                    $order['dateTime'] = $element['value'];
                                elseif($element['tag'] == 'Status')
                                    $order['status'] = $element['value'];
                                elseif($element['tag'] == 'Client')
                                    $order['client'] = $element['value'];
                                else
                                    $xml_body = false;
                            }
                        }
                        else
                            $xml_body = false;
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file = $file." - ". $e->getMessage();
                        Log::error('error in Commands@GetOrders: '.$errormsg_file);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file = $file." - Error leyendo el xml o error en la estructura del xml";
                        Log::error('error in Commands@GetOrders: '.$errormsg_file);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        Log::error('error in Commands@GetOrders: '.$errormsg_file);
                    }
                    else {
                        $errormsg_file = $file." - Cargado correctamente";
                        Log::error('success in Commands@GetOrders: '.$errormsg_file);

                        $order['name_file'] = $file;

                        Order::create($order);
                     }

                    unlink($xmlFile);
                }
            }
        }
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
