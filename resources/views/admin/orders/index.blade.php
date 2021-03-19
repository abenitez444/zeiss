@extends('admin.layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Follow the lens</h2>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Follow the lens</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listOrder" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Orden de Venta</th>
                            <th>Estatus</th>
                            <th>Estado Orden</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Codigo Producto</th>
                            <th>Revestimiento</th>
                            <th>Color</th>
                            <th>Montura</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Referencia</th>
                            <th>Orden de Venta</th>
                            <th>Estatus</th>
                            <th>Estado Orden</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Codigo Producto</th>
                            <th>Revestimiento</th>
                            <th>Color</th>
                            <th>Montura</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($orders as $key => $order)
                            <tr>
                                <td>{{ $order->reference }}</td>
                                <td>{{ $order->order }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->EstadoOrden }}</td>
                                <td>{{ $order->client }}</td>
                                <td>{{ $order->dateTime }}</td>
                                <td>{{ $order->code }}</td>
                                <td>{{ $order->coating }}</td>
                                <td>{{ $order->color }}</td>
                                <td>{{ $order->montage }}</td>
                            </tr>
                        @endforeach
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
            var table = $('#listOrder').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[0, 'desc']]
            });
        });
    </script>
@endsection

