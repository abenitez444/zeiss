@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Gestión de categorias</h2>
        </div>
        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  )
            <div class="col-md-6">
                <div class="row offset-lg-3">
                    <div class="col-md-8">
                        <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Crear Categoría
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('categorias.csv') }}"" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Cargar CSV</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (session('info'))
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                <li><strong>{{ session('info') }}</strong></li>
            </ul>
        </div>
    @endif

    <div   class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de productos</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listCategory" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        @foreach($categorias as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td>{{ $cat->nombre }}</td>
                            <td>{{ $cat->descripcion }}</td>
                            <td>{{ $cat->estado }}</td>
                            <td>
                                <a href="{{ route('categorias.edit', $cat->id) }}" class="btn btn-warning btn-circle btn-sm" title="Editar"><i class="fa fa-edit"></i>
                                </a>
                                @can('isAdmin')
                                <a href="" data-target="#modal-delete-{{$cat->id}}" title="Eliminar" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>

                        @include('admin.categorias.modal')

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
            $('#listCategory').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

@endsection

@endsection
