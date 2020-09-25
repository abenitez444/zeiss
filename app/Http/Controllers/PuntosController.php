<?php

namespace App\Http\Controllers;

use App\Punto;
use DB;
use Illuminate\Http\Request;

class PuntosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $puntos = DB::select('select * from puntos order by id desc');

            //$categorias = Categoria::where('condicion','=','1')
              //  ->orderBy('id', 'DESC')
                //->paginate(5);

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
        //
        return view('admin.puntos.create');
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

        $entrada['estado'] = 1;

        Punto::create($entrada);

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
        //
        $punto = Punto::findOrFail($id);
        return view('admin.puntos.edit', ['punto'=>$punto]);
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

        $punto->fill($entrada)->save();

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
}
