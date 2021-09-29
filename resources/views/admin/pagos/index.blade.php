@extends('admin.layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de pagos</h2>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de pagos</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listPayment" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @canany(['isAdmin','isManager'])
                            <th>ID Factura</th>
                            <th>Cliente</th>
                            <th>Archivo factura</th>
                            <th>Archivo pago</th>
                            <th>Fecha de pago</th>
                            <th>Estado portal BBVA</th>
                            <th>Pago otro banco</th>
                            <th>Importe</th>
                            <th>RefQAD</th>
                            @endcanany
                            @canany(['isCliente','isProveedor'])
                            <th>Fecha</th>
                            <th>Metodo de Pago</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                            <th>Mensaje</th>
                            @endcanany
                            <th>Herramientas</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @canany(['isAdmin','isManager'])
                            <th>ID Factura</th>
                            <th>Cliente</th>
                            <th>Archivo factura</th>
                            <th>Archivo pago</th>
                            <th>Fecha de pago</th>
                            <th>Estado portal BBVA</th>
                            <th>Pago otro banco</th>
                            <th>Importe</th>
                            <th>RefQAD</th>
                            @endcanany
                            @canany(['isCliente','isProveedor'])
                            <th>Fecha</th>
                            <th>Metodo de Pago</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                            <th>Mensaje</th>
                            @endcanany
                            <th>Herramientas</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_user_page')
    <script>
        $(document).ready( function () {
            @if($load_invoice)
                route = "{{ route('pagos.admin') }}";
            @else
                route = "{{ route('pagos.other') }}";
            @endif

            var table = $('#listPayment').DataTable({
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
                    @if ($load_invoice)
                        {data: 'facturas_id', name: 'facturas_id'},
                        {data: 'cod_cliente', name: 'cod_cliente'},
                        {data: 'nombre_factura', name: 'nombre_factura'},
                        {data: 'name_file', name: 'name_file'},
                        {data: 'Fpago', name: 'Fpago'},
                        {data: 'estado', name: 'estado'},
                        {data: 'estadoOtro', name: 'estadoOtro'},
                        {
                            data: 'importe',
                            name: 'importe',
                            className: 'dt-body-right'
                        },
                        {data: 'RefQAD', name: 'RefQAD'},
                    @else
                        {data: 'Fpago', name: 'Fpago'},
                        {data: 'mediopago', name: 'mediopago'},
                        {data: 'referencia', name: 'referencia'},
                        {
                            data: 'importe',
                            name: 'importe',
                            className: 'dt-body-right'
                        },
                        {data: 'mensaje', name: 'mensaje'},
                    @endif
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[0, 'desc']]
            });
        });
    </script>
@endsection
