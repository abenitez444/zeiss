<?php

namespace App\Http\Controllers;

use App\Order;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrdersController extends Controller
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

    public function index(Request $request)
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') )
            return view('admin.orders.index');
        else{
            $orders = DB::select('select orders.* from orders left join clients on client = clients.cod_cliente where clients.user_id = '.Auth::user()->id.' order by orders.id desc');

            $order_new = array();
            foreach ($orders as $order){
                //$clave = array_search($order->name_file, $posiciones);
                $clave = $this->getPosicion($order->name_file);
                $order_new[$order->order][$clave] = $this->array_map_key($order->order, $order->reference, $order->status, $order->EstadoOrden, $order->client, $order->dateTime, $order->code, $order->coating, $order->color, $order->montage);
            }

            return view('admin.orders.index2', ['orders'=>$order_new]);
        }
    }

    public function getOrdersAjax(Request $request)
    {
        if ($request->ajax()) {
            if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') )
                $orders = Order::orderBy('id', 'desc');
            else
                $orders = DB::select('select orders.* from orders left join clients on client = clients.cod_cliente where clients.user_id = '.Auth::user()->id.' order by orders.id desc');

            return DataTables::of($orders)
            ->addColumn('reference', function($row){
                return $row->reference;
            })
            ->addColumn('order', function($row){
                return $row->order;
            })
            ->addColumn('status', function($row){
                return $row->status;
            })
            ->addColumn('EstadoOrden', function($row){
                return $row->status;
            })
            ->addColumn('client', function($row){
                return $row->client;
            })
            ->addColumn('dateTime', function($row){
                return $row->dateTime;
            })
            ->addColumn('code', function($row){
                return $row->code;
            })
            ->addColumn('coating', function($row){
                return $row->coating;
            })
            ->addColumn('color', function($row){
                return $row->color;
            })
            ->addColumn('montage', function($row){
                return $row->montage;
            })
            ->make(true);

        }

        
    }

    function getPosicion($name_file){
        $posiciones = array('DE', 'TJ', 'AL', 'MATCH1', 'MATCH2', 'BF', 'FAC', 'GUIA', 'REDO', 'SCRAP');

        foreach ($posiciones as $key => $posicion){
            if( (strpos($name_file, $posicion)))
                return $key + 1;
        }

        return 0;
    }

    function array_map_key($order ,$reference, $status, $EstadoOrden, $client, $dateTime, $code, $coating, $color, $montage){
        $tmp_array['order']  = $order;
        $tmp_array['reference']  = $reference;
        $tmp_array['status']  = $status;
        $tmp_array['EstadoOrden'] = $EstadoOrden;
        $tmp_array['client']     = $client;
        $tmp_array['dateTime']  = $dateTime;
        $tmp_array['code']  = $code;
        $tmp_array['coating']  = $coating;
        $tmp_array['color']  = $color;
        $tmp_array['montage']  = $montage;
        return $tmp_array;
     }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function getFilesS3(){

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

                    $xmlFile = storage_path('app')."\\".$file;

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
