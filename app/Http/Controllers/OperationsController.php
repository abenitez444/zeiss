<?php

namespace App\Http\Controllers;

use App\Operation;
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

    public function getProducts(){
        return view('admin.operations.products.index');
    }
}
