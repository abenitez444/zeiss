@extends('admin.layouts.dashboard')

@section('content')

<div class="container">       
    <div class="card">
        <div class="card-header">
            <h3>Nombre: {{$role['name']}}</h3>  
            <h4>Enlace: {{$role['slug']}}</h4>
        </div>
        <!--div class="card-body">
            <h5 class="card-title">Permisos</h5>
            <p class="card-text">
                ...........
            </p>
        </div-->
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a>
            <a href="{{ route('roles.index') }}" class="btn btn-danger" >Cancelar</a>
        </div>
    </div>
</div>

@endsection
