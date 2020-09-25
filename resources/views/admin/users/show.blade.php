@extends('admin.layouts.dashboard')

@section('content')

<div class="container">       
    <div class="card">
        <div class="card-header">
            <h3>Nombre: {{$user->name}}</h3>  
            <h4>Correo: {{$user->email}}</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Rol</h5>
            <p class="card-text">
                @if ($user->roles->isNotEmpty())
                    @foreach ($user->roles as $role)
                        <span class="badge badge-primary">
                            {{ $role->name }}
                        </span>
                    @endforeach
                @endif
            </p>
            <!--h5 class="card-title">Permisos</h5>
            <p class="card-text">
                @if ($user->permissions->isNotEmpty())                                        
                    @foreach ($user->permissions as $permission)
                        <span class="badge badge-success">
                            {{ $permission->name }}                                    
                        </span>
                    @endforeach            
                @endif
            </p-->

        </div>
        <div class="card-footer">
            <!--a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a-->
            <a href="{{ route('users.index') }}" class="btn btn-primary" >Volver</a>
        </div>
    </div>
</div>
    
@endsection