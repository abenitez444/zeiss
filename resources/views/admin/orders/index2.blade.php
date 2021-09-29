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
                            @for ($i = 0; $i < 9; $i++)
                                @if (isset($order[$i]))
                                    <tr>
                                        <td>{{ $order[$i]['reference'] }}</td>
                                        <td>{{ $order[$i]['order'] }}</td>
                                        <td>{{ $order[$i]['status'] }}</td>
                                        <td>{{ $order[$i]['EstadoOrden'] }}</td>
                                        <td>{{ $order[$i]['client'] }}</td>
                                        <td>{{ $order[$i]['dateTime'] }}</td>
                                        <td>{{ $order[$i]['code'] }}</td>
                                        <td>{{ $order[$i]['coating'] }}</td>
                                        <td>{{ $order[$i]['color'] }}</td>
                                        <td>{{ $order[$i]['montage'] }}</td>
                                    </tr>
                                @endif
                            @endfor
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
                "bSort" : false
            });
        });
    </script>
@endsection

