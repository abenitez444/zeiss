@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

	<h2>
		Editar Factura: {{ $factura->nombre_factura }}
	</h2>

	<div class="row">
		<div class="col-md-6 col-xs-12">
			@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			
			<form action="{{ route('facturas.update', $factura->id) }} " method="POST">
				<!-- <input name="_method" type="hidden" value="PUT"> -->
				{{ method_field('PUT') }}
				{{ csrf_field() }}

				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre_factura" class="form-control" 
						value="{{ $factura->nombre_factura }}" 
						placeholder="{{ $factura->nombre_factura }}" required>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="{{ route('facturas.index') }}" class="btn btn-danger" >Cancelar</a>
				</div>
			</form>

		</div>
	</div>


</div>



@stop