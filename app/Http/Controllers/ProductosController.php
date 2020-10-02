<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;

class ProductosController extends Controller
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

        $productos = DB::table('productos as a')
            ->join('categorias as c', 'a.categorias_id', '=', 'c.id')
            ->join('puntos as d', 'a.puntos_id', '=', 'd.id')
            ->select('a.id', 'a.nombre', 'a.codigo', 'a.stock', 'a.descripcion', 'a.imagen', 'a.estado', 'c.nombre as categorias', 'd.puntos as puntos')
            ->orderBy('a.id', 'DESC')
            ->paginate(10000);

    		return view('admin.productos.index', [
				'productos'=>$productos,
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
        $categorias = DB::table('categorias')->where('estado', '=', 'activo')->get();
        $puntos = DB::table('puntos')->where('estado', '=', 'activo')->get();

        return view('admin.productos.create',['categorias' => $categorias, 'puntos' => $puntos]);
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
        if($request->hasFile('uploadfile')){
            $archivo = $request->file('uploadfile');
            $photo = $archivo->getClientOriginalName();
            $archivo->move('imagenes', $photo);
            $entrada['imagen'] = $photo;
        }

        $entrada['estado'] = 1;

        Producto::create($entrada);

        return redirect()->route('productos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
        return view('admin.productos.show',
            ['productos'=>Producto::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $articulo = Producto::findOrFail($id);

        $categorias = DB::table('categorias')->where('estado', '=', 'activo')->get();
        $puntos = DB::table('puntos')->where('estado', '=', 'activo')->get();

        return view('admin.productos.edit',[
            'articulo' => $articulo,
            'categorias' => $categorias,
            'puntos' => $puntos
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $articulo = Producto::findOrFail($id);
        $entrada = $request->all();

        if($request->hasFile('uploadfile')){
            $archivo = $request->file('uploadfile');
            $photo = $archivo->getClientOriginalName();
            $archivo->move('imagenes', $photo);
            $entrada['imagen'] = $photo;
        }


        $entrada['estado'] = 'activo';

        $articulo->fill($entrada)->save();

        return redirect()->route('productos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $articulo = Producto::findOrFail($id);
        $articulo->estado = 'inactivo';
        $articulo->update();
        return redirect()->route('productos.index');
    }
}
