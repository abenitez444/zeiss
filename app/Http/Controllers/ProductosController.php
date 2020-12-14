<?php

namespace App\Http\Controllers;

use App\Imports\ProductosImport;
use App\Producto;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use File;

class ProductosController extends Controller
{
    public $folder = '\imagenes';

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
            ->select('a.id', 'a.nombre', 'a.codigo', 'a.stock', 'a.descripcion', 'a.estado', 'c.nombre as categorias', 'a.puntos as puntos')
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

        return view('admin.productos.create',['categorias' => $categorias]);
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
        $producto = Producto::findOrFail($id);

        $producto->delete();

        return redirect()->route('productos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request, $id)
    {
        //
        $producto = Producto::findOrFail($id);
        $data = $request->all();
        $producto->fill($data)->save();

        return redirect()->route('productos.index');
    }

    public function getCsv(){
        return view('admin.productos.csv');
    }

    public function setCsv(){
        try {
            (new ProductosImport)->import(request()->file('uploadfile'));

            return redirect()->route('productos.index')->with('info', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('info', 'Ha ocurrido un error importando, revise que los codigo de productos sean diferentes, existan todos los datos para cada uno o que existan todas las categorias usadas');
        }
    }

    public function getImages(){
        return view('admin.productos.images');
    }

    public function setImages(Request $request){
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
                try {
                    $name_file = $file->getClientOriginalName();

                    DB::table('productos_images')->insert(
                        ['name' => $name_file, 'created_at' => NOW(), 'updated_at' => NOW()]
                    );

                    $file->move('imagenes', $name_file);

                    $errormsg_file[] = $name_file." - Cargado correctamente";

                } catch (\Exception $e) {
                    $errormsg_file[] = $name_file." - Error al subir esta imagen, por favor verifique que su nombre no se repita";
                }
            }
        }

        return back()->with('info',$errormsg_file);
    }
}
