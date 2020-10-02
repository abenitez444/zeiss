@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">



	<div class="row py-lg-2">
		<div class="col-md-6">
            <h2>Listado de facturas</h2>
        </div>

        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('proveedor') || Auth::user()->hasRole('cliente') || Auth::user()->hasRole('manager')  )
		    <div class="col-md-6">
                <a href="{{ route('facturas.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
					Cargar facturas</a>
            </div>
		@endif



	</div>

	<div   class="card shadow mb-4">
		<div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de facturas</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
				<tr>
                    <th>Id</th>
					<th># Factura</th>
					<th>Nombre factura</th>
                    <th>Costo Total</th>
					<th>Estado</th>
					<th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
					<th># Factura</th>
					<th>Nombre factura</th>
                    <th>Costo Total</th>
					<th>Estado</th>
					<th>Herramientas</th>
                </tr>
                </tfoot>
					<tbody>
						@foreach($facturas as $cat)
						<tr>
							<td>{{ $cat->id }}</td>
							<td>{{ $cat->numero_factura }}</td>
							<td>{{ $cat->nombre_factura }}</td>
							<td>{{ $cat->total_cost }}</td>
							<td>{{ $cat->estado }}</td>
							<td>

								<!--a href="{{ route('facturas.show', $cat->id) }}" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a-->
								@can('isAdmin')
{{--									<a href="{{ route('facturas.edit', $cat->id) }}" title="Editar" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>--}}
                                        <a href="{{ url('/admin/facturas/complemento-pago/'.$cat->id) }}" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>
                                	    <a href="" data-target="#modal-delete-{{$cat->id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                @endcan

							</td>
						</tr>
						@include('admin.facturas.modal')
						@endforeach
					</tbody>
				</table>

			</div>

		</div>
	</div>

</div>

@endsection
