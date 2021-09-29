<?php

namespace App\Console\Commands;

use App\Factura;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Payment;

class GetPagosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-pagos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los archivos de los pagos del FTP';

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
        $total = $this->getPagos();
        Log::info('success in Commands@GetPagosCommand - Total de archivos procesados: '.$total, []);
    }

    public function getPagos() {
        $total = 0;

        $disk = Storage::disk('sftp-pagos');

        $files_s3 = array_filter($disk->files(),
            function ($item) {return strpos($item, '.xml');}
        );

        foreach ($files_s3 as $file){
            $today = date('d/m/Y');
            $modified = date('d/m/Y', $disk->lastModified($file));
            
            //if($this->compararFechas ($today,$modified) == 0){
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
                        $errormsg_file = $file." - ". $e->getMessage();
                        Log::error('error in Commands@GetPagosCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file = $file." - Error leyendo el xml o error en la estructura del xml";
                        Log::warning('error in Commands@GetPagosCommand: '.$errormsg_file, []);
                    }
                    elseif (!$invoice_exist){
                        $errormsg_file = $file." - La factura asociada no se encuentra en el sistema";
                        Log::warning('error in Commands@GetPagosCommand: '.$errormsg_file, []);
                    }
                    else {
                        $errormsg_file = $file." - Cargado correctamente";
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

        return $total;
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
