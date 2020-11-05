<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class PagosController extends Controller
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
        $user = Auth::user();

        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') )
            $pagos = DB::select('select payments.*, users.name from payments left join payments_facturas on payment_id = payments.id left join _users_facturas on _users_facturas.factura_id = payments_facturas.factura_id left join users on users.id = _users_facturas.user_id order by payments.created_at desc');
        else
            $pagos = DB::select('select payments.* from payments left join payments_facturas on payment_id = payments.id left join _users_facturas on _users_facturas.factura_id = payments_facturas.factura_id where user_id = '.$user->id.' order by payments.created_at desc');

        return view('admin.pagos.index', ['pagos'=>$pagos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('admin.facturas.create');
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
        $pago = Payment::findOrFail($id);

        $pago->delete();

        return redirect()->route('pagos.index');
    }

    public function viewInvoice($pagoId)
    {
        $pago = Payment::findOrFail($pagoId);

        $facturas = DB::select('select facturas.numero_factura, facturas.total_cost from facturas left join payments_facturas on payments_facturas.factura_id = facturas.id where payments_facturas.payment_id = '.$pagoId.' order by facturas.numero_factura asc');

        return view('admin.pagos.view-invoice', ['facturas'=>$facturas, 'pago'=>$pago]);

    }

    public function validationPayment($id)
    {
        $facturas = DB::select('select facturas.id from facturas left join payments_facturas on payments_facturas.factura_id = facturas.id where payments_facturas.payment_id = '.$id);

        foreach ($facturas as $factura){

            $factura = Factura::findOrFail($factura->id);

            $factura->estado = 4;

            $factura->save();
        }

        return redirect()->route('facturas.clientes')->with('error', "Las facturas fueron actualizadas");
    }
}
