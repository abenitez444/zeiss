<?php

namespace App\Http\Controllers;

use App\Client;
use App\Role;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $clientes = DB::table('users as a')
            ->join('users_roles as d', 'a.id', '=', 'd.user_id')
            ->join('roles as c', 'c.id', '=', 'd.role_id')
            ->select('a.id as idd', 'a.name as nombreuser', 'a.email as correo', 'c.name as nombrerol', 'c.slug as enlace')
            ->where('c.id','=',3)
            ->orderBy('a.id', 'DESC')
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
            'email' => 'required|email|max:191',
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
}
