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
        $client->user_id = $user->id;

        $client->save();

        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = Client::with('user')->where('user_id', $id)->get();

        return view('admin.clientes.show', ['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        echo $user;
        $roles = Role::get();
        $userRole = $user->roles->first();
        /*if($userRole != null){
            $rolePermissions = $userRole->allRolePermissions;
        }else{
            $rolePermissions = null;
        }*/
        //$userPermissions = $user->permissions;

        // dd($rolePermission);

        return view('admin.clientes.edit', [
            'user'=>$user,
            'roles'=>$roles,
            'userRole'=>$userRole,
            //'rolePermissions'=>$rolePermissions,
            //'userPermissions'=>$userPermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        //validate the fields
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password != null){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        //$user->permissions()->detach();

        if($request->role != null){
            $user->roles()->attach($request->role);
            $user->save();
        }

        /*if($request->permissions != null){
            foreach ($request->permissions as $permission) {
                $user->permissions()->attach($permission);
                $user->save();
            }
        }*/

        return redirect('/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();

        return redirect('/users');
    }
}
