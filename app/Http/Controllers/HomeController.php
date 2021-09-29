<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Order;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::where('published', 1)->orderBy('id', 'desc')->simplePaginate(3);

        return view('welcome', ['posts' => $posts]);
    }

    /**
     * Show the post identified by the $id
     */
    public function show($id)
    {
        //Find the post with the id = $id
        $post = Post::find($id);

        return view('/show', ['post' => $post]);
    }

    public function updateFacturasFechasXml($from, $to)
    {
        $facturas = Factura::skip($from)->take($to)->get();

        foreach ($facturas as $factura) {
            $file_name = $factura->nombre_factura;
            $name_file = pathinfo($file_name, PATHINFO_FILENAME);
            $path = '';

            if (empty($file_name)){
                echo "Factura #".$factura->numero_factura." no tiene nombre asociado";
                echo "<br>";
            }

            if(file_exists(storage_path('carpetafacturas/').$name_file.'.xml'))
                $path = storage_path('carpetafacturas/').$name_file.'.xml';
            elseif (file_exists(storage_path('carpetafacturas/').$name_file)) {
                $path = storage_path('carpetafacturas/').$name_file.'/'.$name_file.'.xml';
            }
            elseif(Storage::disk('sftp-facturas')->exists($name_file.'.xml')){
                $xmlString = Storage::disk('sftp-facturas')->get($name_file.'.xml');
                Storage::disk('local')->put($name_file.'.xml',$xmlString);

                $path = storage_path('app')."/".$name_file.'.xml';
            }

            if(!empty($path)){
                $xml = new \XMLReader();
                $xml->open($path);

                try {
                    while ($xml->read()) {
                        if ($xml->nodeType == \XMLReader::ELEMENT) {
                            if ($xml->name == 'cfdi:Comprobante') {
                                if($xml->hasAttributes) {
                                    $factura->fecha = $xml->getAttribute( "Fecha");
                                }
                                else {
                                    echo "Factura #".$factura->numero_factura." No tiene fecha";
                                    echo "<br>";
                                }
                            }
                        }
                    }

                    $factura->save();

                    echo "Factura #".$factura->numero_factura." Fecha actualizada";
                    echo "<br>";
                } catch (\Exception $e) {
                    echo "Factura #".$factura->numero_factura." e:".$e->getMessage();
                    echo "<br>";
                }
            }
            else {
                echo "Factura #".$factura->numero_factura." No tiene archivo valido";
                echo "<br>";
            }
        }
    }

    public function updateOrdenesStatus($from, $to)
    {
        $ordenes = Order::where('status', 'Enviado al Cliente')->skip($from)->take($to)->get();
        $total = 0;

        foreach ($ordenes as $orden) {
            $orden->EstadoOrden = 'Cerrada';
            $orden->save();
            $total++;
        }

        echo "Ordenes actualizadas: ".$total;
    }
}
