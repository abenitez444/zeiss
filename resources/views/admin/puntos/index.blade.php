@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">



    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Gestión de puntos</h2>
        </div>

        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  )
            <div class="col-md-6">
                <a href="{{ route('puntos.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                    Crear cantidad de puntos
                </a>
            </div>
        @endif


    </div>

    <div   class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de puntos</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listPoint" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        @foreach($puntos as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td>{{ $cat->puntos }}</td>
                            <td>{{ $cat->estado }}</td>
                            <td>
                                @can('isAdmin')
                                <a href="{{ route('puntos.edit', $cat->id) }}" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i>
                                </a>
                                <a href="" data-target="#modal-delete-{{$cat->id}}" title="Eliminar" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>

                        @include('admin.puntos.modal')

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
            $('#listPoint').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

@endsection

@endsection
