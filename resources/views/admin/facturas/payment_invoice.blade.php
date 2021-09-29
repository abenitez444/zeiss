@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1>
		Pagar Facturas
    </h1>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4>Total: @convert($amount)</h4>
                    <h4>Identificador de tu pago: {{$referencia}}</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title">BBVA Multipagos Express</h5>
                    <form action="https://www.adquiramexico.com.mx:443/mExpress/pago/avanzado" method="post"/>
                        <input type="hidden" name="importe" value="{{$amount}}"/>
                        <input type="hidden" name="referencia" value="{{$referencia}}"/>
                        <input type="hidden" name="urlretorno" value="http://portalmx.zeiss.com/admin/facturas/comprobante-pago"/>
                        <input type="hidden" name="idexpress" value="1809"/>
                        <input type="hidden" name="financiamiento" value="0"/>
                        <input type="hidden" name="plazos" value=""/>
                        <input type="hidden" name="mediospago" value="110000"/>
                        <input type="hidden" name="signature" value="{{$signature}}"/>
                        <input type="image" src="https://dicff9jl33o1o.cloudfront.net/verticales/bexpress/resources/img/icon/paybutton_4.png" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
