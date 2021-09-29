@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas de {{ ($load_invoice) ? 'Clientes' : 'Proveedores' }} </h2>
        </div>

        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  )
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
    </div>

    <hr>

    @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  )
        @if ($load_invoice)
            <form action="{{ route('facturas.excel.client') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <div class="row py-lg-2">
                    <div class="col-md-2">
                        <label for="start">Fecha Desde:</label>
                        <input type="date" id="start" name="start">
                    </div>
                    <div class="col-md-2">
                        <label for="end">Fecha Hasta:</label>
                        <input type="date" id="end" name="end">
                    </div>
                    <div class="col-md-3">Numero cliente:</label>
                        <input type="text" id="client" name="client">
                    </div>
                    <div class="col-md-2 mt-4" style="margin-left: -5rem!important;">
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Estados facturas</option>
                            <option value="pagado">Pagado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="validado">Validado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-success btn-md float-md-right">Exportar Facturas en Excel</button>
                    </div>
                </div>
            </form>
        @else
        <form action="{{ route('facturas.excel.provider') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
            <div class="row py-lg-2">
                <div class="col-md-2">
                    <label for="start">Fecha Desde:</label>
                    <input type="date" id="start" name="start">
                </div>
                <div class="col-md-2">
                    <label for="end">Fecha Hasta:</label>
                    <input type="date" id="end" name="end">
                </div>
                <div class="col-md-3">Numero Proveedor:</label>
                    <input type="text" id="provider" name="provider">
                </div>
                <div class="col-md-2 mt-4" style="margin-left: -5rem!important;">
                    <select class="form-control" id="estado" name="estado">
                        <option value="">Estados facturas</option>
                        <option value="pagado">Pagado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="validado">Validado</option>
                    </select>
                </div>
                <div class="col-md-3 mt-4">
                    <button type="submit" class="btn btn-success btn-md float-md-right">Exportar Facturas en Excel</button>
                </div>
            </div>
        </form>  
        <hr> 
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
        @endif
    @endif

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
                            @canany(['isAdmin','isManager'])
                            <th>ID</th>
                            @if ($load_invoice)
                                @canany(['isAdmin','isManager'])
                                <th>Cliente</th>
                                @endcanany
                            @else
                                @canany(['isAdmin','isManager'])
                                <th>Proveedor</th>
                                @endcanany
                            @endif
                            <th># Factura</th>
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                    <th>Fecha Factura</th>
                                @endcanany
                            @endif
                            <th>Nombre factura</th>
                            <th>Costo Total</th>
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                    <th>Moneda</th>
                                @endcanany
                            @endif
                            <th>Estado</th>
                            <th>Usuario Asociado</th>
                            @endcanany
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                <th>Fecha promesa de pago</th>
                                <th>Fecha limite para enviar el complemento de pago</th>
                                <th>Fecha de pago</th>
                                <th>En el portal desde</th>
                                <th>Descargar</th>
                                @endcanany
                            @endif
                            <th>Herramientas</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @canany(['isAdmin','isManager'])
                            <th>ID</th>
                            @if ($load_invoice)
                                @canany(['isAdmin','isManager'])
                                <th>Cliente</th>
                                @endcanany
                            @else
                                @canany(['isAdmin','isManager'])
                                <th>Proveedor</th>
                                @endcanany
                            @endif
                            <th># Factura</th>
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                    <th>Fecha Factura</th>
                                @endcanany
                            @endif
                            <th>Nombre factura</th>
                            <th>Costo Total</th>
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                <th>Moneda</th>
                                @endcanany
                            @endif
                            <th>Estado</th>
                            <th>Usuario Asociado</th>
                            @endcanany
                            @if (!$load_invoice)
                                @canany(['isAdmin','isManager'])
                                <th>Fecha promesa de pago</th>
                                <th>Fecha limite para enviar el complemento de pago</th>
                                <th>Fecha de pago</th>
                                <th>En el portal desde</th>
                                <th>Descargar</th>
                                @endcanany
                            @endif
                            <th>Herramientas</th>
                        </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
                @include('admin.facturas.modal2')
            </div>
        </div>
    </div>
</div>

@section('js_user_page')
    <script>
       $(document).ready( function () {
            @if($load_invoice)
                route = "{{ route('facturas.clientes') }}";
            @else
                route = "{{ route('facturas.proveedores') }}";
            @endif

            var table = $('#listInvoice').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': route,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'factura_id', name: 'factura_id'},
                    @if ($load_invoice)
                        {data: 'cod_cliente', name: 'cod_cliente'},
                    @else
                        {data: 'cod_proveedor', name: 'cod_proveedor'},
                    @endif
                    {data: 'numero_factura', name: 'numero_factura'},
                    @if(!$load_invoice)
                        {data: 'fecha', name: 'fecha'},
                    @endif
                    {data: 'nombre_factura', name: 'nombre_factura'},
                    {
                        data: 'total_cost',
                        name: 'total_cost',
                        className: 'dt-body-right'
                    },
                    @if(!$load_invoice)
                        {data: 'moneda', name: 'moneda'},
                    @endif
                    {data: 'estado', name: 'estado'},
                    {data: 'name', name: 'name'},
                    @if(!$load_invoice)
                        {data: 'payment_promise_date', name: 'payment_promise_date'},
                        {data: 'deadline_for_complement', name: 'deadline_for_complement'},
                        {data: 'FechaPago', name: 'FechaPago'},
                        {data: 'fecha_sistema', name: 'fecha_sistema'},
                        {data: 'check', name: 'check'},
                    @endif
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[1, 'desc']]
            });
        });

        function openModalChange(id){
            $('#change-status-form').attr('action', "/admin/facturas/cancel/"+id);  

            $("#modal-change").modal("show");
        }

        function openModalDelete(id){
            $('#delete-status-form').attr('action', "/admin/facturas/"+id);  

            $("#modal-delete").modal("show");
        }

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

        var count = 0;
        function descargar(checkeds){
            if(checkeds.checked)
                count ++;
            else
                count --;

            $("#count-facturas").html(count);
        }
        
    </script>

@endsection


@endsection
