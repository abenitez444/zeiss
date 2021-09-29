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

class GetOrdenesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-ordenes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los archivos de las Ordenes del FTP';

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
        $total = $this->getOrdenes();
        Log::info('success in Commands@GetOrdenesCommand - Total de archivos procesados: '.$total, []);
    }

    public function getOrdenes(){
        $total = 0;
        $files_s3 = Storage::disk('sftp-ordenes')->allFiles();

        foreach ($files_s3 as $file){

            if(strpos($file,"xml") || strpos($file,"XML")){

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
                                elseif($element['tag'] == 'Status')
                                    $order['status'] = $element['value'];
                                elseif($element['tag'] == 'EstadoOrden')
                                    $order['EstadoOrden'] = ($element['value'] != 'Cerrada') ? 'Abierta' : 'Cerrada';
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
                        $errormsg_file = $file." - ". $e->getMessage();
                        Log::error('error in Commands@GetOrdenesCommand: '.$errormsg_file, []);
                    }

                    $xml->close();

                    if (!$xml_body){
                        $errormsg_file = $file." - Error leyendo el xml o error en la estructura del xml";
                        Log::warning('error in Commands@GetOrdenesCommand: '.$errormsg_file, []);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file = $file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        Log::warning('error in Commands@GetOrdenesCommand: '.$errormsg_file, []);
                    }
                    else {
                        $errormsg_file = $file." - Cargado correctamente";
                        Log::notice('success in Commands@GetOrdenesCommand: '.$errormsg_file, []);

                        $order['name_file'] = $file;

                        Order::create($order);

                        $total ++;
                     }

                    unlink($xmlFile);

                }
            }
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
