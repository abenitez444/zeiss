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
                            <th>Fecha</th>
                            <th>Metodo de Pago</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                            <th>Mensaje</th>
                            @canany(['isAdmin','isManager'])
                            <th>Cliente</th>
                            @endcanany
                            <th>Herramientas</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Fecha</th>
                            <th>Metodo de Pago</th>
                            <th>Referencia</th>
                            <th>Monto</th>
                            <th>Mensaje</th>
                            @canany(['isAdmin','isManager'])
                            <th>Cliente</th>
                            @endcanany
                            <th>Herramientas</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($pagos as $key => $pago)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($pago->created_at)) }}</td>
                                <td>{{ $pago->mediopago }}</td>
                                <td>{{ $pago->referencia }}</td>
                                <td>{{ $pago->importe }}</td>
                                <td>{{ $pago->mensaje }}</td>
                                @canany(['isAdmin','isManager'])
                                <td>{{ $pago->name }}</td>
                                @endcanany
                                <td>
                                    @canany(['isAdmin','isManager'])
                                        <a href="{{ url('/admin/pagos/facturas/'.$pago->payment_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Facturas"><i class="fas fa-eye"></i> </a>
                                        <a href="" data-target="#modal-change-{{$pago->payment_id}}" title="Validar Pago" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-check-square"></i></a>
                                        <a href="" data-target="#modal-delete-{{$pago->payment_id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    @endcanany
                                    @can('isCliente')
                                        <a href="{{ url('/admin/pagos/facturas/'.$pago->payment_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Facturas"><i class="fas fa-eye"></i> </a>
                                    @endcan
                                </td>
                            </tr>
                            @include('admin.pagos.modal')
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
            var table = $('#listPayment').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[0, 'desc']]
            });
        });
    </script>
@endsection
