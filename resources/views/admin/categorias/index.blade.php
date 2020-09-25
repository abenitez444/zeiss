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
                <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
					Crear Categoría
				</a>
            </div>
		@endif
	</div>

	<div   class="card shadow mb-4">
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

@endsection
