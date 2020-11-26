<?php

namespace App\Http\Controllers;

use App\Client;
use App\Complement;
use App\Factura;
use App\Payment;
use App\Provider;
use App\User;

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
        else
            $facturas = DB::select('select * from facturas left join _users_facturas on factura_id = facturas.id where user_id = '.$user->id.' order by facturas.id desc');

        return view('admin.facturas.index', ['facturas'=>$facturas,'load_invoice'=>true]);

    }

    public function getInvoicesClients()
    {
        $facturas = DB::select('select * from facturas left join _users_facturas on factura_id = facturas.id left join users on users.id = _users_facturas.user_id left join users_roles on users_roles.user_id = users.id where users_roles.role_id = 3 order by facturas.id desc');

        return view('admin.facturas.index', ['facturas'=>$facturas,'load_invoice'=>true]);
    }

    public function getInvoicesProviders()
    {
        $facturas = DB::select('select * from facturas left join _users_facturas on factura_id = facturas.id left join users on users.id = _users_facturas.user_id left join users_roles on users_roles.user_id = users.id where users_roles.role_id = 2 order by facturas.id desc');

        return view('admin.facturas.index', ['facturas'=>$facturas,'load_invoice'=>false]);
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

        $path = public_path($this->folder);
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
                    if(date('D', strtotime($factura['payment_promise_date'])) != 'Mon')
                        $factura['payment_promise_date'] = date("Y-m-d", strtotime('next Monday '.$factura['payment_promise_date']));
                }
                else
                    $user_id = $request->user_id;

                if(in_array($extension_file,['zip'])){
                    $xml_type = false;
                    $xml_body = false;
                    $usr_exist = true;
                    $provider_not = false;
                    $file->move('carpetafacturas', $name_file);

                    $unzipper  = new Unzip();
                    $filenames = $unzipper->extract(public_path('carpetafacturas/').$name_file, public_path('carpetafacturas/'));

                    foreach ($filenames as $filename) {

                        if(strpos($filename, "__MACOSX/") === false){
                            if (strpos($filename, "xml")){
                                $xml_type = true;

                                $xmlFile = public_path()."/carpetafacturas/".$filename;

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

                    $file->move('carpetafacturas', $name_file);

                    $xmlFile = public_path()."/carpetafacturas/".$name_file;

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
                        unlink(public_path('carpetafacturas/').$name_file);
                    }
                    elseif (!$usr_exist){
                        $errormsg_file[] = $name_file." - El cliente o proveedor asociado no se encuentra en el sistema";
                        unlink(public_path('carpetafacturas/').$name_file);
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
                    $factura['nombre_factura'] = $name_file;
                    $factura['estado'] = 2;

                    $file->move('carpetafacturas', $name_file);

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

        return redirect()->route('facturas.index');
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

        $path = public_path($this->folder);
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
                    $file->move('carpetafacturas', $name_file);

                    $complement['name'] = $name_file;
                    $complement['factura_id'] = $facturaId;

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    Complement::create($complement);
                }
                elseif ($extension_file == 'xml'){
                    $file->move('carpetafacturas', $name_file);

                    $complement['name'] = $name_file;
                    $complement['factura_id'] = $facturaId;

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                    Complement::create($complement);
                }
                elseif ($extension_file == 'pdf'){
                    $file->move('carpetafacturas', $name_file);

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
        $factura = Factura::find($facturaId);
        $path = "";

        if ($factura){
            $file_name = $factura->nombre_factura;
            $name_file = pathinfo($file_name, PATHINFO_FILENAME);

            if (empty($file_name)){
                return redirect(URL::previous())->with('error', "No se encontró un archivo para este tipo o factura.");
            }

            if(file_exists(public_path('carpetafacturas/').$name_file.'.'.$ext))
                $path = public_path('carpetafacturas/').$name_file.'.'.$ext;
            elseif (file_exists(public_path('carpetafacturas/').$name_file)) {
                $path = public_path('carpetafacturas/').$name_file.'/'.$name_file.'.'.$ext;
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
        $complement = Complement::find($complementId);

        if ($complement){
            $file_name = $complement->name;

            if (empty($file_name)){
                return redirect(URL::previous())->with('error', "No se encontró un archivo para este complemento.");
            }

            $path = public_path('carpetafacturas/').$file_name;

            if(is_file($path)){
                return response()->download($path);
            }
            else {
                return redirect(URL::previous())->with('error', "No se encontró un archivo para este complemento.");
            }
        }
        else{
            return redirect(response::back());
        }
    }

    public function deleteComplement($id)
    {
        $complement = Complement::findOrFail($id);

        if (file_exists(public_path('carpetafacturas/').$complement->name))
            unlink(public_path('carpetafacturas/').$complement->name);

        $complement->delete();

        return redirect()->route('facturas.index');
    }

    public function deleteDocument($path, $file_name){
        if(file_exists(public_path('carpetafacturas/').$file_name.'.xml'))
            unlink(public_path('carpetafacturas/').$file_name.'.xml');
        elseif (file_exists(public_path('carpetafacturas/').$file_name)) {
            unlink(public_path('carpetafacturas/').$file_name.'/'.$file_name.'.xml');
        }
        if(file_exists(public_path('carpetafacturas/').$file_name.'.pdf'))
            unlink(public_path('carpetafacturas/').$file_name.'.pdf');
        elseif (file_exists(public_path('carpetafacturas/').$file_name)) {
            unlink(public_path('carpetafacturas/').$file_name.'/'.$file_name.'.pdf');
        }
        if(file_exists(public_path('carpetafacturas/').$file_name.'.zip'))
            unlink(public_path('carpetafacturas/').$file_name.'.zip');
        elseif (file_exists(public_path('carpetafacturas/').$file_name)) {
            unlink(public_path('carpetafacturas/').$file_name.'/'.$file_name.'.zip');
        }

        if (file_exists(public_path('carpetafacturas/').$file_name))
            rmdir(public_path('carpetafacturas/').$file_name);
    }

    public function setPaymentInvoice(Request $request)
    {
        $amount = 0;
        $referencia = "";
        $signature = "";

        foreach ($request->ids as $id){
            $invoice = Factura::findOrFail($id);

            $amount += number_format($invoice->total_cost,2,".",",");
            $referencia .= "F".$id;
        }

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

                    $factura->estado = 1;

                    $factura->save();
                }
                else {
                    $pago = false;
                }
            }
        }

        if($pago)
            return redirect('/login')->with('error', "El pago se realizo correctamente, numero de autorizacion: ". $request->autorizacion);
        else
            return redirect('/login')->with('error', "Tuvimos un problema con su pago");
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

        $path = public_path($this->folder);
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
                    $file->move('carpetafacturas', $name_file);

                    $unzipper  = new Unzip();
                    $filenames = $unzipper->extract(public_path('carpetafacturas/').$name_file, public_path('carpetafacturas/'));

                    foreach ($filenames as $filename) {
                        if(strpos($filename, "__MACOSX/") === false){
                            if (strpos($filename, "xml")){
                                $xml_type = true;

                                $xmlFile = public_path()."/carpetafacturas/".$filename;

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
                                                        unlink(public_path('carpetafacturas/').$name_file);
                                                    }
                                                }
                                                else {
                                                    $xml_body = false;

                                                    $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                                                    unlink(public_path('carpetafacturas/').$name_file);
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

                    $file->move('carpetafacturas', $name_file);

                    $xmlFile = public_path()."/carpetafacturas/".$name_file;

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

                                            if(file_exists(public_path('carpetafacturas/').$file_name.'.pdf')){
                                                $complemento['name'] = $file_name.'.pdf';
                                                $complemento['factura_id'] = $factura->id;

                                                Complement::create($complemento);

                                                $errormsg_file[] = $file_name.".pdf - Cargado correctamente para la factura #". $factura->numero_factura;
                                            }
                                        }
                                        else{
                                            $invoice_exist = false;

                                            $errormsg_file[] = $name_file." - La factura asociada #". $xml->getAttribute("IdDocumento")." no se encuentra en el sistema";
                                            unlink(public_path('carpetafacturas/').$name_file);
                                        }
                                    }
                                    else {
                                        $xml_body = false;

                                        $errormsg_file[] = $name_file." - Error leyendo el xml o error en la estructura del xml";
                                        unlink(public_path('carpetafacturas/').$name_file);
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
                    $file->move('carpetafacturas', $name_file);
                }
                else {
                    $errormsg_file[] = $name_file." - El archivo debe ser de formato: pdf, xml  o zip";
                }
            }
        }

        return back()->with('info',$errormsg_file);
    }
}
