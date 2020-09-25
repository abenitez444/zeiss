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
                <a href="{{ route('productos.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear nuevo producto</a>
            </div>
        @endif
    </div>


    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de productos</div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
					<th>Nombre</th>
					<th>Código</th>
					<th>Stock</th>
                    <th>Categoría</th>
                    <th>Punto</th>
                    <th>Descripción</th>
					<th>Imágen</th>
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
                    <th>Punto</th>
                    <th>Descripción</th>
					<th>Imágen</th>
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
								<td>
									<img src="{{ asset('imagenes/'.$art->imagen) }}" alt="{{ $art->nombre }}" height="50" width="50" class="img-thumbnail">
								</td>
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



@endsection
