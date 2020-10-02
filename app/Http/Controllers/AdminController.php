<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AdminController extends Controller
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

    	$facturas = DB::select('select count(*) id FROM facturas');
    	$productos = DB::select('select count(*) id FROM productos');
        $users = DB::select('select count(*) id FROM users');
            //$categorias = Categoria::where('condicion','=','1')
              //  ->orderBy('id', 'DESC')
                //->paginate(5);

            return view('admin.index', [
                'facturas'=>$facturas,
                'productos'=>$productos,
                'users'=>$users,
            ]);

        //return view('admin.index');
        /*if(!\Auth::user()->hasRole('administrador') && !\Auth::user()->hasRole('manager') ){
            return redirect('/users');
        }else{
            return redirect('/facturas');
        }*/

    }
}
