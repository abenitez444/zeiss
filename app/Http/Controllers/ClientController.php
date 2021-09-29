<?php

namespace App\Http\Controllers;

use App\Client;
use App\Exports\FacturasClientesExport;
use App\Factura;
use App\Imports\ClientsImport;
use App\Role;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
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

        $clientes = DB::table('users as u')
            ->join('clients as c', 'u.id', '=', 'c.user_id')
            ->join('users_roles as ur', 'u.id', '=', 'ur.user_id')
            ->join('roles as r', 'r.id', '=', 'ur.role_id')
            ->select('u.id as idd', 'u.name as nombreuser', 'u.email as correo', 'r.name as nombrerol', 'r.slug as enlace', 'c.cod_cliente as codigo')
            ->where('r.id','=',3)
            ->orderBy('u.id', 'DESC')
            ->paginate(10000);

        //echo json_encode($clientes);
        return view('admin.clientes.index', ['clientes'=>$clientes]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->ajax()){
            $roles = Role::where('id', $request->role_id)->first();
            $permissions = $roles->permissions;

            return $permissions;
        }

        $roles = Role::all();

        return view('admin.clientes.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate the fields
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|unique:users|email|max:191',
            'rfc' => 'required|max:191',
            'phone' => 'required',
            'credit_days' => 'required',
            'payment_method' => 'required',
            'way_to_pay' => 'required',
            'password' => 'required|between:8,191|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if($request->role != null){
            $user->roles()->attach($request->role);
            $user->save();
        }

        $client = new Client;
        $client->rfc = $request->rfc;
        $client->phone = $request->phone;
        $client->credit_days = $request->credit_days;
        $client->payment_method = $request->payment_method;
        $client->way_to_pay = $request->way_to_pay;
        $client->cfdi = $request->cfdi;
        $client->status = $request->status;
        $client->cod_cliente = $request->cod_cliente;
        $client->participa = ($request->participa == 'on' ? 1 : 0);
        $client->user_id = $user->id;

        $client->save();

        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = Client::with('user')->where('user_id', $id)->get();

        return view('admin.clientes.show', ['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $client = Client::with('user')->where('user_id', $id)->get();

        return view('admin.clientes.edit', [
            'user'=>$client[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //validate the fields
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$id,
            'rfc' => 'required|max:191',
            'phone' => 'required',
            'credit_days' => 'required',
            'payment_method' => 'required',
            'way_to_pay' => 'required',
            'password' => 'confirmed',
        ]);

        $client = Client::with('user')->where('user_id', $id)->get();
        $client = $client[0];
        $user = $client->user;

        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password != null){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();

        if($request->role != null){
            $user->roles()->attach($request->role);
            $user->save();
        }

        Client::updateOrCreate(
                ['user_id' => $id],
                ['rfc' => $request->rfc,
                'phone' => $request->phone,
                'credit_days' => $request->credit_days,
                'payment_method' => $request->payment_method,
                'way_to_pay' => $request->way_to_pay,
                'cfdi' => $request->cfdi,
                'status' => $request->status,
                'cod_cliente' => $request->cod_cliente,
                'participa' => ($request->participa == 'on' ? 1 : 0),
                ]
        );

        return redirect()->route('clients.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->user->roles()->detach();
        $client->user->permissions()->detach();
        $client->user->delete();
        $client->delete();

        return redirect()->route('clients.index');
    }

    public function getLoad(){
        return view('admin.clientes.load');
    }

    public function setLoad(){
        try {
            (new ClientsImport)->import(request()->file('uploadfile'));

            return redirect()->route('clients.index')->with('info', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')->with('info', 'Ha ocurrido un error importando. Error: '.$e->getMessage());
        }
    }

    public function exportFactura(Request $request){

        $sql = "SELECT factura_id, cod_cliente, numero_factura, nombre_factura, total_cost, estado, name
                FROM facturas left join _users_facturas on factura_id = facturas.id
                left join users on users.id = _users_facturas.user_id
                left join clients on clients.user_id = users.id
                left join users_roles on users_roles.user_id = users.id
                where users_roles.role_id = 3";

        if(!empty($request->estado))
            $sql .= " AND estado = '".$request->estado."'";

        if(!empty($request->client))
            $sql .= " AND cod_cliente like '%".$request->client."%'";

        if(!empty($request->start) && !empty($request->end))
            $sql .= " AND facturas.created_at BETWEEN '".$request->start."' AND '".$request->end."'";
        elseif(!empty($request->start) && empty($request->end))
            $sql .= " AND MONTH(facturas.created_at) = MONTH('".$request->start."') AND YEAR(facturas.created_at) = YEAR('".$request->start."')";
        elseif(empty($request->start) && !empty($request->end))
            $sql .= " AND MONTH(facturas.created_at) = MONTH('".$request->end."') AND YEAR(facturas.created_at) = YEAR('".$request->end."')";

        $sql .= " order by facturas.id desc";

        $facturas_array = DB::select($sql);

        $facturas = new FacturasClientesExport($facturas_array);
        //dd($facturas);
        return Excel::download($facturas, 'facturas_clientes.xlsx');
    }
}
