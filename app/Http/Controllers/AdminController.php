<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Factura;
use App\Provider;
use App\Client;

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

    	$facturas = DB::select('select count(*) id FROM facturas');
    	$productos = DB::select('select count(*) id FROM productos');
        $users = DB::select('select count(*) id FROM users');
            //$categorias = Categoria::where('condicion','=','1')
              //  ->orderBy('id', 'DESC')
                //->paginate(5);

            return view('admin.index', [
                'facturas'=>$facturas,
                'productos'=>$productos,
                'users'=>$users,
            ]);

        //return view('admin.index');
        /*if(!\Auth::user()->hasRole('administrador') && !\Auth::user()->hasRole('manager') ){
            return redirect('/users');
        }else{
            return redirect('/facturas');
        }*/

    }

    public function getFilesS3(){

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

                    $xmlFile = storage_path('app')."\\".$file;

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
}
