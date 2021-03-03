@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas</h2>
        </div>

        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('proveedor') || Auth::user()->hasRole('manager')  )
            @if ($load_invoice)
                <div class="col-md-6">
                    <div class="row offset-lg-3">
                        <div class="col-md-6">
                            <a href="{{ route('facturas.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas</a>
                        </div>
                        <div class="col-md-6">
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
                    <div class="col-md-5">
                        <p><b> Cantidad de Facturas a descargar: </b><span id="count-facturas">0</span></p>
                    </div>
                    <div class="col-md-7">
                        <form action="{{ route('facturas.download') }}" method="post" id="form2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                            <button class="btn btn-primary btn-md float-md-right" id="download_invoice">Descargar Facturas</button>
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
                    <th>Archivo factura</th>
                    <th>Importe</th>
                    <th>Fecha de vencimiento</th>
                    <th>Estado portal BBVA</th>
                    <th>Descargar</th>
                    @endcan
                    @can('isProveedor')
                    <th>Id</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    <th>Fecha promesa de pago</th>
                    <th>Fecha limite para enviar el complemento de pago</th>
                    @endcan
                    @canany(['isAdmin','isManager'])
                    <th>Id</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    <th>Usuario Asociado</th>
                    @endcanany
                    @if (!$load_invoice)
                        @canany(['isAdmin','isManager'])
                        <th>Fecha promesa de pago</th>
                        <th>Fecha limite para enviar el complemento de pago</th>
                        @endcanany
                    @endif
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
                    <th>Archivo factura</th>
                    <th>Importe</th>
                    <th>Fecha de vencimiento</th>
                    <th>Estado portal BBVA</th>
                    <th>Descargar</th>
                    @endcan
                    @can('isProveedor')
                    <th>ID Factura</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    <th>Fecha promesa de pago</th>
                    <th>Fecha limite para enviar el complemento de pago</th>
                    @endcan
                    @canany(['isAdmin','isManager'])
                    <th>ID Factura</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    <th>Usuario Asociado</th>
                    @endcanany
                    @if (!$load_invoice)
                        @canany(['isAdmin','isManager'])
                        <th>Fecha promesa de pago</th>
                        <th>Fecha limite para enviar el complemento de pago</th>
                        @endcanany
                    @endif
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        @foreach($facturas as $key => $cat)
                        <tr>
                            @can('isCliente')
                            <td><input type="checkbox" id="{{ $key }}" class="pay"></td>
                            <td>{{ $cat->factura_id }}</td>
                            <td>{{ $cat->cod_cliente }}</td>
                            <td>{{ $cat->numero_factura }}</td>
                            <td>{{ $cat->nombre_factura }}</td>
                            <td>{{ $cat->total_cost }}</td>
                            <td>{{ (!empty($cat->payment_promise_date)) ? date('d/m/Y', strtotime($cat->payment_promise_date)) : "No definido" }}</td>
                            <td>{{ $cat->estado }}</td>
                            <th><input type="checkbox" id="{{ $cat->factura_id }}" class="download"></th>
                            @endcan
                            @can('isProveedor')
                            <td>{{ $cat->factura_id }}</td>
                            <td>{{ $cat->numero_factura }}</td>
                            <td>{{ $cat->nombre_factura }}</td>
                            <td>{{ $cat->total_cost }}</td>
                            <td>{{ $cat->estado }}</td>
                            <td>{{ (!empty($cat->payment_promise_date)) ? date('d/m/Y', strtotime($cat->payment_promise_date)) : "No definido" }}</td>
                            <td>{{ (!empty($cat->deadline_for_complement)) ? date('d/m/Y', strtotime($cat->deadline_for_complement)) : "No definido" }}</td>
                            @endcan
                            @canany(['isAdmin','isManager'])
                            <td>{{ $cat->factura_id }}</td>
                            <td>{{ $cat->numero_factura }}</td>
                            <td>{{ $cat->nombre_factura }}</td>
                            <td>{{ $cat->total_cost }}</td>
                            <td>{{ $cat->estado }}</td>
                            <td>{{ $cat->name }}</td>
                            @endcanany
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                <td>{{ (!empty($cat->payment_promise_date)) ? date('d/m/Y', strtotime($cat->payment_promise_date)) : "No definido" }}</td>
                                <td>{{ (!empty($cat->deadline_for_complement)) ? date('d/m/Y', strtotime($cat->deadline_for_complement)) : "No definido" }}</td>
                                @endcanany
                            @endif
                            <td>

{{--                                <a href="{{ route('facturas.show', $cat->factura_id) }}" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a>--}}
                                @canany(['isAdmin','isManager'])
{{--                                    <a href="{{ route('facturas.edit', $cat->factura_id) }}" title="Editar" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>--}}
                                    @if ($load_invoice)
                                        <a href="{{ url('/admin/facturas/complemento-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>
                                    @endif
                                    <a href="" data-target="#modal-change-{{$cat->factura_id}}" title="Cambiar Estatus" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-cogs"></i></a>
                                    <a href="" data-target="#modal-delete-{{$cat->factura_id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>
                                    <a href="{{ url('/admin/facturas/complementos-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                @endcanany
                                @can('isCliente')
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>
                                    <a href="{{ url('/admin/facturas/complementos-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                @endcan
                                @can('isProveedor')
                                    {{-- <a href="{{ route('facturas.edit', $cat->id) }}" title="Editar" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>--}}
                                    <a href="{{ url('/admin/facturas/complemento-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>
                                    <a href="" data-target="#modal-change-{{$cat->factura_id}}" title="Cancelar" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-cogs"></i></a>
                                    <a href="" data-target="#modal-delete-{{$cat->factura_id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>
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

            $('.pay').on('change', function() {
                var count = 0;
                var amount = 0.00;

                $(".pay:checked").each(function() {
                    count ++;
                    var data = table.row($(this).attr('id')).data();

                    amount += parseFloat(data[5]);
                });

                $("#count").html(count);
                $("#amount").html(amount.toFixed(2));
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
