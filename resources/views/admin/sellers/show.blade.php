@extends('admin.layouts.dashboard')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Nombre o Razon social: {{$user[0]->user->name}}</h3>
            <h4>Correo: {{$user[0]->user->email}}</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Rol</h5>
            <p class="card-text">
              Vendedor
            </p>
        </div>
        <div class="card-body">
            <h5 class="card-title">Datos</h5>
            <div class="row">
                <div class="col-md-3">
                    <p class="card-text">
                        RFC - {{ $user[0]->rfc }}
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="card-text">
                        Teléfono - {{ $user[0]->phone }}
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="card-text">
                        Puntos - {{ $user[0]->points }}
                    </p>
                </div>
                <div class="col-md-3">
                    <p class="card-text">
                        Codigo Vendedor - {{ $user[0]->clave }}
                    </p>
                </div>
                <hr>
                <div class="col-md-12">
                    <p class="card-text">
                        Direccion de envio - {{ $user[0]->address }}
                    </p>
                </div>
        </div>
        <div class="card-footer">
            <!--a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a-->
            <a href="{{ route('sellers.index') }}" class="btn btn-primary" >Volver</a>
        </div>
    </div>
</div>

@endsection
