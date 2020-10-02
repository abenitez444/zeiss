<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;
use DB;


class CategoriasController extends Controller
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
        //

            $categorias = DB::select('select * from categorias order by id desc');

            //$categorias = Categoria::where('condicion','=','1')
              //  ->orderBy('id', 'DESC')
                //->paginate(5);

            return view('admin.categorias.index', [
                'categorias'=>$categorias,
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
        return view('admin.categorias.create');
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
        //
        $entrada = $request->all();
        $entrada['estado'] = 1;

        Categoria::create($entrada);

        return redirect()->route('categorias.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        return view('admin.categorias.show',
            ['categorias'=>Categoria::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', ['categoria'=>$categoria]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //
        $articulo = Categoria::findOrFail($id);
        $entrada = $request->all();


        $entrada['estado'] = 'activo';

        $articulo->fill($entrada)->save();

        return redirect()->route('categorias.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $articulo = Categoria::findOrFail($id);
        $articulo->estado = 'inactivo';
        $articulo->update();
        return redirect()->route('categorias.index');
    }
}
