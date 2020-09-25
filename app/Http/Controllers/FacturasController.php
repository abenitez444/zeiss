<?php

namespace App\Http\Controllers;

use App\Factura;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use DB;

class FacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
            //$facturas = DB::select('select * from facturas order by id desc');

            //$categorias = Categoria::where('condicion','=','1')
              //  ->orderBy('id', 'DESC')
                //->paginate(5);

        $facturas = DB::select('select * from facturas order by id desc');


        return view('admin.facturas.index', ['facturas'=>$facturas]);


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
        //


        $entrada = $request->all();
        $entrada['numero_factura'] = 12;

        if($request->hasFile('uploadfile')){
            $archivo = $request->file('uploadfile');
            $photo = $archivo->getClientOriginalName();
            $archivo->move('carpetafacturas', $photo);
            $entrada['nombre_factura'] = $photo;
        }

        $entrada['total_cost'] = 2000;

        $entrada['estado'] = 1;

        $idFactura = Factura::create($entrada);

        //$last_valor = Factura::latest('id')->first();
        //DB::query('insert into _users_facturas (user_id, factura_id) values ($request->user_id, $last_valor)')->get();

        DB::table('_users_facturas')->insert(
            ['user_id' => $request->user_id, 'factura_id' => $idFactura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
        );

        return redirect()->route('facturas.index');
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
        //
        $factura = Factura::findOrFail($id);
        $factura->estado = 'cancelado';
        $factura->update();
        return redirect()->route('facturas.index');
    }
}
