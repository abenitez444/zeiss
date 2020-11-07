@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">


    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Gestión de productos</h2>
        </div>
        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  )
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <a href="{{ route('productos.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear Producto</a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('productos.csv') }}"" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Cargar CSV</a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('productos.images') }}"" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Cargar Imagenes </a>
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

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de productos</div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="listProduct" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Puntos</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Puntos</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                <tbody>
                    @foreach($productos as $art)
                            <tr>
                                <td>{{ $art->id }}</td>
                                <td>{{ $art->nombre }}</td>
                                <td>{{ $art->codigo }}</td>
                                <td>{{ $art->stock }}</td>
                                <td>{{ $art->categorias }}</td>
                                <td>{{ $art->puntos }}</td>
                                <td>{{ $art->descripcion }}</td>
                                <td>{{ $art->estado }}</td>
                                <td>
                                    <!--a href="{{ route('productos.show', $art->id) }}" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a-->
                                    <a href="{{ route('productos.edit', $art->id) }}" class="btn btn-warning btn-circle btn-sm" title="Editar"><i class="fa fa-edit"></i>
                                        <!--button class="btn btn-info">Editar</button-->
                                    </a>
                                    @can('isAdmin')
                                    <a href="" data-target="#modal-delete-{{$art->id}}" data-toggle="modal" title="Eliminar" class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i>
                                        <!--button class="btn btn-danger">Eliminar</button-->
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @include('admin.productos.modal')
                        @endforeach
                </tbody>
            </table>
            </div>
            {{ $productos->render() }}
        </div>
    </div>

</div>

@section('js_user_page')

    <script>
        $(document).ready( function () {
            $('#listProduct').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

@endsection

@endsection
