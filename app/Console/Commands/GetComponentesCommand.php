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

class GetComponentesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-componentes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los archivos de los Complementos de Pago del FTP';

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
        $total = $this->getComponentes();
        Log::info('success in Commands@GetComponentesCommand - Total de archivos procesados: '.$total, []);
    }

    public function getComponentes(){
        $total = 0;
        $files_s3 = Storage::disk('sftp-complementos')->allFiles();

        foreach ($files_s3 as $file){
            if(strpos($file,"xml")){
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

                                            $errormsg_file = $file." - Cargado correctamente para la factura #". $factura->numero_factura;
                                            Log::notice('error in Commands@GetComponentesCommand: '.$errormsg_file, []);

                                            Complement::create($complemento);

                                            $total ++;

                                            $factura->NumParcialidad = $xml->getAttribute("NumParcialidad");
                                            $factura->save();

                                            if(Storage::disk('sftp-complementos')->exists($file_name.'.pdf')){
                                                $complemento['name'] = $file_name.'.pdf';
                                                $complemento['factura_id'] = $factura->id;

                                                Complement::create($complemento);

                                                $errormsg_file = $file_name.".pdf - Cargado correctamente para la factura #". $factura->numero_factura;
                                                Log::notice('error in Commands@GetComponentesCommand: '.$errormsg_file, []);

                                                $total ++;
                                            }
                                        }
                                        else{
                                            $invoice_exist = false;

                                            $errormsg_file = $file." - La factura asociada #". $xml->getAttribute("IdDocumento")." no se encuentra en el sistema";
                                            Log::warning('error in Commands@GetComponentesCommand: '.$errormsg_file, []);
                                        }
                                    }
                                    else {
                                        $xml_body = false;

                                        $errormsg_file = $file." - Error leyendo el xml o error en la estructura del xml";
                                        Log::warning('error in Commands@GetComponentesCommand: '.$errormsg_file, []);
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $xml_body = false;
                        $errormsg_file = $file." - ". $e->getMessage();
                        Log::error('error in Commands@GetComponentesCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    unlink($xmlFile);
                }
            }
        }

        return $total;
    }
}
