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
                    Cliente
                </p>
            </div>
            <div class="card-body">
                <h5 class="card-title">Datos</h5>
                <div class="row">
                    <div class="col-md-4">
                        <p class="card-text">
                            RFC - {{ $user[0]->rfc }}
                        </p>
                        <p class="card-text">
                            Metodo de Pago - {{ $user[0]->payment_method }}
                        </p>
                        <p class="card-text">
                            Status - {{ $user[0]->status ? "Activo" : "Detenido por credito" }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="card-text">
                            Teléfono - {{ $user[0]->phone }}
                        </p>
                        <p class="card-text">
                            Forma de Pago - {{ $user[0]->way_to_pay }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="card-text">
                            Dias de Credito - {{ $user[0]->credit_days }}
                        </p>
                        <p class="card-text">
                            Uso de CFDi - {{ $user[0]->cfdi }}
                        </p>
                    </div>
                </div>
                <div class="card-footer">
                <!--a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a-->
                    <a href="{{ route('providers.index') }}" class="btn btn-primary" >Volver</a>
                </div>
            </div>
        </div>

@endsection
