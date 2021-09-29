<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
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
        // $user = Auth::user();

        // if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') )
        //     $pagos = DB::select('select payments.*, users.name from payments left join payments_facturas on payment_id = payments.id left join _users_facturas on _users_facturas.factura_id = payments_facturas.factura_id left join users on users.id = _users_facturas.user_id order by payments.created_at desc');
        // else
        //     $pagos = DB::select('select payments.* from payments left join payments_facturas on payment_id = payments.id left join _users_facturas on _users_facturas.factura_id = payments_facturas.factura_id where user_id = '.$user->id.' order by payments.created_at desc');

        // return view('admin.pagos.index', ['pagos'=>$pagos]);
        
        //return view('admin.pagos.index');
    }

    public function getPagosAdmin(Request $request) {

        if ($request->ajax()) {

            //$pagos = DB::select('select payments.*, clients.cod_cliente, facturas.id as facturas_id, facturas.nombre_factura, facturas.estado, facturas.estadoOtro from payments left join payments_facturas on payment_id = payments.id left join facturas on facturas.id = payments_facturas.factura_id left join _users_facturas on _users_facturas.factura_id = payments_facturas.factura_id left join clients on clients.user_id = _users_facturas.user_id order by payments.created_at desc');

            $pagos = Payment::with('factura');
            
            return DataTables::of($pagos)
            ->addColumn('action', function ($row) {

                $btn = '<a href="'.url('/admin/pagos/facturas/'.$row->id).'" class="btn btn-warning btn-circle btn-sm" title="Ver Facturas"><i class="fas fa-eye"></i> </a>';
                    
                $btn .= '<a href="javascript: void(0);" onclick="openModalChange('.$row->id.');" title="Validar Pago" class="btn btn-primary btn-circle btn-sm modal-open" ><i class="fas fa-check-square"></i></a>';

                $btn .= '<a href="javascript: void(0);" onclick="openModalDelete('.$row->id.');" title="Eliminar" class="btn btn-danger btn-circle btn-sm modal-open" ><i class="fas fa-trash-alt"></i></a>';

                return $btn;
            })
            ->addColumn('created_at', function($row){
                return $row->created_at;
            })
            ->addColumn('nombre_factura', function (Payment $payment) {
                return $payment->factura[0]->nombre_factura;
            })
            ->addColumn('name_file', function($row){
                return $row->name_file;
            })
            ->addColumn('importe', function($row){
                return number_format($row->importe, 2, '.', ',');
            })
            ->addColumn('estado', function (Payment $payment) {
                return (!empty($payment->factura[0]->name_file)) ? '' : 'Pagado';
            })
            ->addColumn('estadoOtro', function (Payment $payment) {
                return (!empty($payment->factura[0]->name_file)) ? 'Pagado' : '';
            })
            // ->addColumn('cod_cliente', function($row){
            //     return $row->cod_cliente;
            // })
            ->addColumn('cod_cliente', function (Payment $payment) {
                //return $payment->factura[0];
                return "-";
            })
            ->addColumn('RefQAD', function($row){
                return $row->RefQAD;
            })
            // ->addColumn('facturas_id', function($row){
            //     return $row->facturas_id;
            // })
            ->addColumn('facturas_id', function (Payment $payment) {
                return $payment->factura[0]->id;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.pagos.index', ['load_invoice'=>true]);

    }

    public function getPagosOther(Request $request) {

        if ($request->ajax()) {
            $user = Auth::user();

            $pagos = DB::select('select payments.* from payments left join payments_facturas on payment_id = payments.id left join _users_facturas on _users_facturas.factura_id = payments_facturas.factura_id where user_id = '.$user->id.' order by payments.created_at desc');

            return DataTables::of($pagos)
            ->addColumn('action', function ($row) {
                    
                $btn = '<a href="'.url('/admin/pagos/facturas/'.$row->id).'" class="btn btn-warning btn-circle btn-sm" title="Ver Facturas"><i class="fas fa-eye"></i> </a>';
            
                return $btn;
            })
            ->addColumn('created_at', function($row){
                return $row->created_at;
            })
            ->addColumn('mediopago', function($row){
                return $row->mediopago;
            })
            ->addColumn('referencia', function($row){
                return $row->referencia;
            })
            ->addColumn('importe', function($row){
                return number_format($row->importe, 2, '.', ',');
            })
            ->addColumn('mensaje', function($row){
                return $row->mensaje;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.pagos.index', ['load_invoice'=>false]);
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
