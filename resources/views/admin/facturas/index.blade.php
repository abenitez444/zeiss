@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas</h2>
        </div>

        @if( Auth::user()->hasRole('proveedor')  )
            @if ($load_invoice)
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ url('/admin/facturas/create/xml') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas XML</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('/admin/facturas/create/pdf') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas PDF</a>
                        </div>
                        {{-- <div class="col-md-4">
                            <a href="{{ url('/admin/facturas/create/zip') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas ZIP</a>
                        </div> --}}
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <a href="{{ route('complementos.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar Complementos</a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        @if( Auth::user()->hasRole('cliente')  )
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <p><b> Cantidad de Facturas: </b><span id="count">0</span></p>
                        <p><b> Monto a Pagar: </b><span id="amount">0.00</span></p>
                    </div>
                    <div class="col-md-7">
                        <form action="{{ route('facturas.payment') }}" method="post" id="form">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                            <button class="btn btn-primary btn-md float-md-right" id="pay_invoice">Pagar en linea</button>
                        </form>
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-md-7 offset-lg-5">
                <div class="row">
                    <div class="col-md-4">
                        <p><b> Cantidad de Facturas a descargar: </b><span id="count-facturas">0</span></p>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('facturas.download', 'pdf') }}" method="post" id="form2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                            <button class="btn btn-primary btn-md float-md-right" id="download_invoice">Descargar Formato PDF</button>
                        </form>
                    </div>                        
                    <div class="col-md-4">
                        <form action="{{ route('facturas.download', 'xml') }}" method="post" id="form3">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                            <button class="btn btn-primary btn-md float-md-right" id="download_invoice2">Descargar Formato XML</button>
                        </form>
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-md-7 offset-lg-5">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary btn-md float-md-right text-white" href="{{ url('/admin/facturas/status/'.Auth::user()->id) }}">Estado de Cuenta</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (session('error'))
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                <li><strong>{{ session('error') }}</strong></li>
            </ul>
        </div>
    @endif

    @if ($message = Session::get('info'))
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                @foreach ($message as $msg)
                    <li><strong>{{ $msg }}</strong></li>
                @endforeach
            </ul>
        </div>
    @endif

    <div   class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de facturas</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listInvoice" width="100%" cellspacing="0">
                <thead>
                <tr>
                    @can('isCliente')
                    <th>Seleccionar para pagar</th>
                    <th>ID Factura</th>
                    <th>Codigo Cliente</th>
                    <th># Factura</th>
                    <th>En el portal desde</th>
                    <th>Fecha emision factura</th>
                    <th>Archivo factura</th>
                    <th style="text-align: right">Importe</th>
                    <th>Fecha de vencimiento</th>
                    <th>Estado portal BBVA</th>
                    <th>Pago otro Banco</th>
                    <th>Descargar</th>
                    @endcan
                    @can('isProveedor')
                    <th>Id</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Fecha Factura</th>
                    <th>En el portal desde</th>
                    <th style="text-align: right">Costo Total</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Fecha promesa de pago</th>
                    <th>Fecha de pago</th>
                    <th>Fecha limite para enviar el complemento de pago</th>
                    @endcan
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    @can('isCliente')
                    <th>Seleccionar para pagar</th>
                    <th>ID Factura</th>
                    <th>Codigo Cliente</th>
                    <th># Factura</th>
                    <th>En el portal desde</th>
                    <th>Fecha emision factura</th>
                    <th>Archivo factura</th>
                    <th style="text-align: right">Importe</th>
                    <th>Fecha de vencimiento</th>
                    <th>Estado portal BBVA</th>
                    <th>Pago otro Banco</th>
                    <th>Descargar</th>
                    @endcan
                    @can('isProveedor')
                    <th>ID Factura</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Fecha Factura</th>
                    <th>En el portal desde</th>
                    <th style="text-align: right">Costo Total</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Fecha promesa de pago</th>
                    <th>Fecha de pago</th>
                    <th>Fecha limite para enviar el complemento de pago</th>
                    @endcan
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        @foreach($facturas as $key => $cat)
                        <tr>
                            @can('isCliente')
                            @if (in_array($cat->estadoOtro, ['Pagado', 'pagado', 'Validado', 'validado']) || in_array($cat->estado, ['Pagado', 'pagado', 'Validado', 'validado']))
                                <td><i class="fas fa-check"></i></td>
                            @else
                                <td><input type="checkbox" id="{{ $key }}" class="pay"></td>
                            @endif
                            <td>{{ $cat->factura_id }}</td>
                            <td>{{ $cat->cod_cliente }}</td>
                            <td>{{ $cat->numero_factura }}</td>
                            <td>{{ date('d/m/Y', strtotime($cat->fecha_sistema)) }}</td>
                            <td>{{ (!empty($cat->fecha)) ? date('d/m/Y', strtotime($cat->fecha)) : "No definido" }}</td>
                            <td>{{ $cat->nombre_factura }}</td>
                            <td>@convert($cat->total_cost)</td>
                            <td>{{ (!empty($cat->fecha)) ? date('d/m/Y', strtotime($cat->fecha."+ ".$cat->credit_days." days")) : "No definido" }}</td>
                            <td>{{ (!empty($cat->estadoOtro)) ? '' : $cat->estado }}</td>
                            <td>{{ (!empty($cat->estadoOtro)) ? $cat->estadoOtro : '' }}</td>
                            <th><input type="checkbox" id="{{ $cat->factura_id }}" class="download"></th>
                            @endcan
                            @can('isProveedor')
                            <td>{{ $cat->factura_id }}</td>
                            <td>{{ $cat->numero_factura }}</td>
                            <td>{{ $cat->nombre_factura }}</td>
                            <td>{{ $cat->fecha_factura }}</td>
                            <td>{{ date('d/m/Y', strtotime($cat->fecha_sistema)) }}</td>
                            <td>@convert($cat->total_cost)</td>
                            <td>{{ $cat->moneda }}</td>
                            <td>{{ (!empty($cat->estadoOtro)) ? $cat->estadoOtro : $cat->estado }}</td>
                            <td>{{ (!empty($cat->payment_promise_date)) ? date('d/m/Y', strtotime($cat->payment_promise_date)) : "No definido" }}</td>
                            <td>{{ (!empty($cat->fecha_pago)) ? date('d/m/Y', strtotime($cat->fecha_pago)) : "" }}</td>
                            <td>{{ (!empty($cat->deadline_for_complement)) ? date('d/m/Y', strtotime($cat->deadline_for_complement)) : "" }}</td>
                            @endcan
                            <td>
                                @can('isCliente')
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    {{-- <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a> --}}
                                    <a href="{{ url('/admin/facturas/complementos-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                @endcan
                                @can('isProveedor')
                                    {{-- <a href="{{ route('facturas.edit', $cat->id) }}" title="Editar" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>--}}
                                    <a href="{{ url('/admin/facturas/complemento-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>
                                  {{--<a href="" data-target="#modal-change-{{$cat->factura_id}}" title="Cancelar" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-cogs"></i></a>--}}
                                    <a href="" data-target="#modal-delete-{{$cat->factura_id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    {{-- <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a> --}}
                                    <a href="{{ url('/admin/facturas/complementos-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                @endcan
                            </td>
                        </tr>
                        @include('admin.facturas.modal')
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

</div>

@section('js_user_page')
    <script>
       $(document).ready( function () {
            var table = $('#listInvoice').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[1, 'desc']]
            });

            function number_format (number, decimals, dec_point, thousands_sep) {
                // Strip all characters but numerical ones.
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }

            $('#pay_invoice').on('click', function (event) {
                event.preventDefault();
                var count = 0;

                $(".pay:checked").each(function() {
                    count ++;

                    var data = table.row($(this).attr('id')).data();

                    $('<input>', {
                        type: 'hidden',
                        value: data[1],
                        name: 'ids[]'
                    }).appendTo('#form');
                });

                if(count == 0)
                    alert("No ha seleccionado ninguna factura");
                else
                    $("#form").submit();
            });

            $('#download_invoice').on('click', function (event) {
                event.preventDefault();
                var count = 0;

                $(".download:checked").each(function() {
                    count ++;

                    $('<input>', {
                        type: 'hidden',
                        value: $(this).attr('id'),
                        name: 'ids[]'
                    }).appendTo('#form2');
                });

                if(count == 0)
                    alert("No ha seleccionado ninguna factura");
                else
                    $("#form2").submit();
            });

            $('#download_invoice2').on('click', function (event) {
                event.preventDefault();
                var count = 0;

                $(".download:checked").each(function() {
                    count ++;

                    $('<input>', {
                        type: 'hidden',
                        value: $(this).attr('id'),
                        name: 'ids[]'
                    }).appendTo('#form3');
                });

                if(count == 0)
                    alert("No ha seleccionado ninguna factura");
                else
                    $("#form3").submit();
            });

            $('.pay').on('change', function() {
                var count = 0;
                var amount = 0.00;

                $(".pay:checked").each(function() {
                    count ++;
                    var data = table.row($(this).attr('id')).data();

                    amount += parseFloat(data[7].replace(",", ""));
                });

                $("#count").html(count);
                $("#amount").html(number_format(amount, 2, '.', ','));
            });

            $('.download').on('change', function() {
                var count = 0;

                $(".download:checked").each(function() {
                    count ++;
                });

                $("#count-facturas").html(count);
            });
        });
    </script>

@endsection


@endsection
