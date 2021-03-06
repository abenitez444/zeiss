<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Factura;
use App\Provider;
use App\Client;
use App\Complement;
use App\Order;

class GetFacturasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-facturas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los archivos de las Facturas del FTP';

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
        $total = $this->getFacturas();
        Log::info('success in Commands@GetFacturasCommand - Total de archivos procesados: '.$total, []);
    }

    public function getFacturas(){
        $total = 0;
        $totalErrores = 0;
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
                                        $factura['numero_factura'] = $xml->getAttribute("Folio");
                                        $factura['total_cost'] = $xml->getAttribute("Total");
                                        $factura['fecha'] = $xml->getAttribute("Fecha");
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
                        $errormsg_file = $file." - ". $e->getMessage();
                        Log::error('error in Commands@GetFacturasCommand-Try: '.$errormsg_file, []);
                        $totalErrores ++;
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file = $file." - Error leyendo el xml o error en la estructura del xml";
                        Log::warning('error in Commands@GetFacturasCommand: '.$errormsg_file, []);
                        $totalErrores ++;
                    }
                    elseif (!$usr_exist){
                        $errormsg_file = $file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        Log::warning('error in Commands@GetFacturasCommand: '.$errormsg_file, []);
                        $totalErrores ++;
                    }
                    else {
                        $errormsg_file = $file." - Cargado correctamente";
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

        Log::info('error in Commands@GetFacturasCommand - Total de archivos fallados: '.$totalErrores, []);
        return $total;
    }
}
