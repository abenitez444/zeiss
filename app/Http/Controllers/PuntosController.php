<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Punto;
use DB;
use Illuminate\Http\Request;

class PuntosController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $puntos = Punto::with('user')->with('factura')->get();

        return view('admin.puntos.index', [
            'puntos'=>$puntos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facturas = DB::select('select facturas.id, facturas.numero_factura, users.name from facturas left join _users_facturas on factura_id = facturas.id left join users on users.id = _users_facturas.user_id left join users_roles on users_roles.user_id = users.id where users_roles.role_id = 3 order by facturas.id desc');

        return view('admin.puntos.create', ['facturas'=>$facturas,'load_invoice'=>true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $entrada = $request->all();

        $entrada['estado'] = 1;

        $factura = Factura::with('user')->findOrFail($request->factura_id);

        $punto = Punto::create($entrada);

        DB::table('_users_puntos')->insert(
            ['user_id' => $factura->user[0]->id, 'punto_id' => $punto->id,'factura_id' => $request->factura_id, 'created_at' => NOW(), 'updated_at' => NOW()]
        );

        return redirect()->route('puntos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('admin.puntos.show',
            ['puntos'=>Punto::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $facturas = DB::select('select facturas.id, facturas.numero_factura, users.name from facturas left join _users_facturas on factura_id = facturas.id left join users on users.id = _users_facturas.user_id left join users_roles on users_roles.user_id = users.id where users_roles.role_id = 3 order by facturas.id desc');

        $punto = Punto::with('user')->with('factura')->findOrFail($id);
        //$punto = Punto::findOrFail($id);

        return view('admin.puntos.edit', ['punto'=>$punto,'facturas'=>$facturas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $punto = Punto::findOrFail($id);
        $entrada = $request->all();

        $entrada['estado'] = 'activo';

        $factura = Factura::with('user')->findOrFail($request->factura_id);

        $punto->fill($entrada)->save();

        DB::table('_users_puntos')
            ->where('punto_id',$punto->id)
            ->update(['user_id' => $factura->user[0]->id, 'factura_id' => $request->factura_id, 'updated_at' => NOW()]);

        return redirect()->route('puntos.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Punto  $punto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $punto = Punto::findOrFail($id);
        $punto->estado = 'inactivo';
        $punto->update();
        return redirect()->route('puntos.index');

    }

    public function getCsv(){
        return view('admin.puntos.csv');
    }

    public function setCsv(){
        try {
            (new CategoriasImport)->import(request()->file('uploadfile'));

            return redirect()->route('categorias.index')->with('info', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')->with('info', 'Ha ocurrido un error importando, revise que existan todos los datos para cada uno y que los estados sean activo o inactivo');
        }
    }
}
