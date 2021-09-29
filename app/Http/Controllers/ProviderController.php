<?php

namespace App\Http\Controllers;

use App\Client;
use App\Exports\FacturasProveedoresExport;
use App\Imports\ProvidersImport;
use App\Provider;
use App\Role;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ProviderController extends Controller
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

        $providers = DB::table('users as u')
            ->join('providers as p', 'u.id', '=', 'p.user_id')
            ->join('users_roles as ur', 'u.id', '=', 'ur.user_id')
            ->join('roles as r', 'r.id', '=', 'ur.role_id')
            ->select('u.id as idd', 'u.name as nombreuser', 'u.email as correo', 'r.name as nombrerol', 'r.slug as enlace', 'p.cod_proveedor as cod_proveedor')
            ->where('r.id','=',2)
            ->orderBy('u.id', 'DESC')
            ->paginate(10000);

        //echo json_encode($clientes);
        return view('admin.proveedores.index', ['providers'=>$providers]);

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

        return view('admin.proveedores.create', ['roles' => $roles]);
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
            'contact' => 'required|string|max:191',
            'credit_terms' => 'required',
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

        $provider = new Provider();
        $provider->rfc = $request->rfc;
        $provider->phone = $request->phone;
        $provider->contact = $request->contact;
        $provider->credit_terms = $request->credit_terms;
        $provider->cfdi = $request->cfdi;
        $provider->user_id = $user->id;
        $provider->cod_proveedor = $request->cod_proveedor;

        $provider->save();

        return redirect()->route('providers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Provider::with('user')->where('user_id', $id)->get();

        return view('admin.proveedores.show', ['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $provider = Provider::with('user')->where('user_id', $id)->get();

        return view('admin.proveedores.edit', [
            'user'=>$provider[0]
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
            'contact' => 'required|string|max:191',
            'credit_terms' => 'required',
            'password' => 'confirmed',
        ]);

        $provider = Provider::with('user')->where('user_id', $id)->get();
        $provider = $provider[0];
        $user = $provider->user;

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

        Provider::updateOrCreate(
            ['user_id' => $id],
            ['rfc' => $request->rfc,
                'phone' => $request->phone,
                'contact' => $request->contact,
                'credit_terms' => $request->credit_terms,
                'cfdi' => $request->cfdi,
                'cod_proveedor' => $request->cod_proveedor,
            ]
        );

        if(Auth::user()->hasRole('proveedor'))
            return redirect()->route('home');
        else
            return redirect()->route('providers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        $provider->user->roles()->detach();
        $provider->user->permissions()->detach();
        $provider->user->delete();
        $provider->delete();

        return redirect()->route('providers.index');
    }

    public function getLoad(){
        return view('admin.proveedores.load');
    }

    public function setLoad(){
        try {
            (new ProvidersImport)->import(request()->file('uploadfile'));

            return redirect()->route('providers.index')->with('info', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('providers.index')->with('info', 'Ha ocurrido un error importando. Error: '.$e->getMessage());
        }
    }

    public function exportFactura(Request $request){

        $sql = "SELECT factura_id, cod_proveedor, numero_factura, nombre_factura, total_cost, estado, name
                FROM facturas left join _users_facturas on factura_id = facturas.id
                left join users on users.id = _users_facturas.user_id
                left join providers on providers.user_id = users.id
                left join users_roles on users_roles.user_id = users.id
                where users_roles.role_id = 2";

        if(!empty($request->estado))
            $sql .= " AND estado = '".$request->estado."'";

        if(!empty($request->provider))
            $sql .= " AND cod_proveedor like '%".$request->provider."%'";

        if(!empty($request->start) && !empty($request->end))
            $sql .= " AND facturas.created_at BETWEEN '".$request->start."' AND '".$request->end."'";
        elseif(!empty($request->start) && empty($request->end))
            $sql .= " AND MONTH(facturas.created_at) = MONTH('".$request->start."') AND YEAR(facturas.created_at) = YEAR('".$request->start."')";
        elseif(empty($request->start) && !empty($request->end))
            $sql .= " AND MONTH(facturas.created_at) = MONTH('".$request->end."') AND YEAR(facturas.created_at) = YEAR('".$request->end."')";

        $sql .= " order by facturas.id desc";

        $facturas_array = DB::select($sql);

        $facturas = new FacturasProveedoresExport($facturas_array);
        //dd($facturas);
        return Excel::download($facturas, 'facturas_proveedores.xlsx');
    }
}
