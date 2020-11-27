@extends('admin.layouts.dashboard')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>{{$error}}</h3>
        </div>
        <div class="card-body">
            <h5 class="card-title">Datos</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="card-text">
                        Codigo - {{ $payment['codigo'] }}
                    </p>
                    <p class="card-text">
                        Autorizacion - {{ $payment['autorizacion'] }}
                    </p>
                    <p class="card-text">
                        Importe - {{ $payment['importe'] }}
                    </p>
                    <p class="card-text">
                        Financiado - {{ $payment['financiado'] }}
                    </p>
                    <p class="card-text">
                        s_transm - {{ $payment['s_transm'] }}
                    </p>
                    <p class="card-text">
                        cve Tipo Pago - {{ $payment['cveTipoPago'] }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="card-text">
                        Mensaje - {{ $payment['mensaje'] }}
                    </p>
                    <p class="card-text">
                        Referencia - {{ $payment['referencia'] }}
                    </p>
                    <p class="card-text">
                        Medio de pago - {{ $payment['mediopago'] }}
                    </p>
                    <p class="card-text">
                        Plazos - {{ $payment['plazos'] }}
                    </p>
                    <p class="card-text">
                        Tarjeta habiente - {{ $payment['tarjetahabiente'] }}
                    </p>
                    <p class="card-text">
                        Hash - {{ $payment['hash'] }}
                    </p>
                </div>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a>
        </div>
    </div>
</div>

@endsection
