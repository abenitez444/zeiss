@extends('admin.layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Productos Canjeados</h2>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Productos Canjeados</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listOperations" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Puntos</th>
                            @canany(['isAdmin','isManager'])
                            <th>Cliente</th>
                            @endcanany
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Puntos</th>
                            @canany(['isAdmin','isManager'])
                            <th>Cliente</th>
                            @endcanany
                            <th>Fecha</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($operations as $key => $operation)
                            <tr>
                                <td>{{ $operation->id }}</td>
                                <td>{{ $operation->nombre }}</td>
                                <td>{{ $operation->puntos }}</td>
                                @canany(['isAdmin','isManager'])
                                <td>{{ $operation->name }}</td>
                                @endcanany
                                <td>{{ (!empty($operation->created_at)) ? date('d/m/Y', strtotime($operation->created_at) ) : "No definido" }}</td>
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
            var table = $('#listOperations').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[0, 'desc']]
            });
        });
    </script>
@endsection

