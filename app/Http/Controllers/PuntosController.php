<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Imports\PuntosImport;
use App\Punto;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') ){
            $puntos = Punto::with('user')->with('factura')->get();

            return view('admin.puntos.index', [
                'puntos'=>$puntos,
            ]);
        }else{
            $puntos_total = DB::select('select ( ifnull(SUM(puntos.puntos), 0 )) as cant from puntos left join _users_puntos on punto_id = puntos.id where puntos.estado = 1 and _users_puntos.user_id = '.Auth::user()->id);
            $puntos_operations = DB::select('select ( ifnull(SUM(operations.puntos), 0 )) as cant from operations where operations.user_id = '.Auth::user()->id);
            $puntos_cant = $puntos_total[0]->cant - $puntos_operations[0]->cant;
            $puntos_cant = ($puntos_cant < 0 ) ? 0 : $puntos_cant;

            $value = Auth::user()->id;
            $puntos = Punto::with(['user', 'factura'])
                    ->whereHas('user', function($q) use($value) {
                    $q->where('users.id', $value);
            })->get();

            return view('admin.puntos.index', [
                'puntos_cant'=> $puntos_cant,
                'puntos'=> $puntos
            ]);
        }
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
            (new PuntosImport)->import(request()->file('uploadfile'));

            return redirect()->route('puntos.index')->with('info', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('puntos.index')->with('info', 'Ha ocurrido un error importando '.$e->getMessage());
        }
    }
}
