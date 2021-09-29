<?php

namespace App\Http\Controllers;

use App\Client;
use DB;
use App\Role;
use App\User;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
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

        $sellers = DB::table('users as u')
            ->join('sellers as s', 'u.id', '=', 's.user_id')
            ->join('users_roles as ur', 'u.id', '=', 'ur.user_id')
            ->join('roles as r', 'r.id', '=', 'ur.role_id')
            ->select('u.id as idd', 'u.name as nombreuser', 'u.email as correo', 's.points as puntos', 's.clave as clave')
            ->where('r.id','=',5)
            ->orderBy('u.id', 'DESC')
            ->paginate(10000);

        return view('admin.sellers.index', ['sellers'=>$sellers]);

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

        $clients = Client::with('user')->where('participa', 1)->get();

        return view('admin.sellers.create', ['roles' => $roles, 'clients' => $clients]);
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
            'clave' => 'required',
            'address' => 'required',
            'client_id' => 'required',
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

        $seller = new Seller();
        $seller->rfc = $request->rfc;
        $seller->phone = $request->phone;
        $seller->clave = $request->clave;
        $seller->points = ($request->points) ? : 0;
        $seller->address = $request->address;
        $seller->user_id = $user->id;
        $seller->client_id = $request->client_id;

        $seller->save();

        return redirect()->route('sellers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = Seller::with('user')->where('user_id', $id)->get();

        return view('admin.sellers.show', ['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $seller = Seller::with('user')->where('user_id', $id)->get();

        $clients = Client::with('user')->where('participa', 1)->get();

        return view('admin.sellers.edit', [
            'user'=>$seller[0],
            'clients' => $clients
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
            'clave' => 'required',
            'address' => 'required',
            'client_id' => 'required',
            'password' => 'confirmed',
        ]);

        $seller = Seller::with('user')->where('user_id', $id)->get();
        $seller = $seller[0];
        $user = $seller->user;

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

        Seller::updateOrCreate(
                ['user_id' => $id],
                ['rfc' => $request->rfc,
                'phone' => $request->phone,
                'clave' => $request->clave,
                'points' => ($request->points) ? : 0,
                'address' => $request->address,
                'client_id' => $request->client_id,
                ]
        );

        return redirect()->route('sellers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller)
    {
        $seller->user->roles()->detach();
        $seller->user->permissions()->detach();
        $seller->user->delete();
        $seller->delete();

        return redirect()->route('sellers.index');
    }

    // public function getLoad(){
    //     return view('admin.clientes.load');
    // }

    // public function setLoad(){
    //     try {
    //         (new ClientsImport)->import(request()->file('uploadfile'));

    //         return redirect()->route('clients.index')->with('info', 'Archivo importado correctamente');
    //     } catch (\Exception $e) {
    //         return redirect()->route('clients.index')->with('info', 'Ha ocurrido un error importando. Error: '.$e->getMessage());
    //     }
    // }
}
