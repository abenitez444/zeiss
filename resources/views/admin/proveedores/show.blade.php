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
                    Proveedor
                </p>
            </div>
            <div class="card-body">
                <h5 class="card-title">Datos</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-text">
                            RFC - {{ $user[0]->rfc }}
                        </p>
                        <p class="card-text">
                            Contacto - {{ $user[0]->contact }}
                        </p>
                        <p class="card-text">
                            Fecha promesa de pago - {{ $user[0]->payment_promise_date }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="card-text">
                            Teléfono - {{ $user[0]->phone }}
                        </p>
                        <p class="card-text">
                            Uso de CFDi - {{ $user[0]->cfdi }}
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-text">
                            Fecha limite para enviar el complemento de pago - {{ $user[0]->deadline_for_complement }}
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <p class="card-text">
                            Términos de crédito <br> {{ $user[0]->credit_terms }}
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
