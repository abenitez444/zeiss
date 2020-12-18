<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Operation;
use App\Producto;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationsController extends Controller
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
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') ){
            $operations = Operation::all();

            return view('admin.operations.index', ['operations'=>$operations]);
        }
        else {
            $operations = DB::select('select operations.*, users.name, productos.nombre from operations left join users on user_id = users.id left join productos on productos.id = operations.producto_id where operations.user_id = '.Auth::user()->id.' order by operations.created_at desc');

            return view('admin.operations.index', ['operations'=>$operations]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function getProducts($idCategoria = null){
        $imagenes = array();
        $puntos_total = DB::select('select ( ifnull(SUM(puntos.puntos), 0 )) as cant from puntos left join _users_puntos on punto_id = puntos.id where puntos.estado = 1 and _users_puntos.user_id = '.Auth::user()->id);
        $puntos_operations = DB::select('select ( ifnull(SUM(operations.puntos), 0 )) as cant from operations where operations.user_id = '.Auth::user()->id);
        $puntos_cant = $puntos_total[0]->cant - $puntos_operations[0]->cant;
        $puntos_cant = ($puntos_cant < 0 ) ? 0 : $puntos_cant;
        $categorias = Categoria::all();
        if($idCategoria != 0){
            $productos = Producto::where('categorias_id', $idCategoria)->get();
        }
        else
            $productos = Producto::all();

        foreach ($productos as $product){
            $imagen = DB::select('select name from productos_images where name like "'.$product->codigo.'%" order by name desc limit 1');

            if(!empty($imagen))
                $imagenes[$product->id] = $imagen[0]->name;
        }

        return view('admin.operations.products.index',
                    ['puntos_cant'=> $puntos_cant,
                    'categorias' => $categorias,
                    'productos' => $productos,
                    'imagenes' => $imagenes]);
    }

    public function setPayment(Request $request)
    {
        foreach ($request->ids as $key => $id){
            $product = Producto::findOrFail($id);
            $cantidad = $request->stocks[$key];

            $operation = new Operation();
            $operation->puntos = ($product->puntos * $cantidad);
            $operation->cantidad = $cantidad;
            $operation->producto_id = $id;
            $operation->user_id = Auth::user()->id;
            $operation->save();
        }

        return redirect()->route('operations.index')->with('error', "Los puntos fueron canjeados correctamente");
    }
}
