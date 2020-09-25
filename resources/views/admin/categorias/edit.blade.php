@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

	<h2>
		Editar Categoría: {{ $categoria->nombre }}
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
			
			<form action="{{ route('categorias.update', $categoria->id) }} " method="POST">
				<!-- <input name="_method" type="hidden" value="PUT"> -->
				{{ method_field('PUT') }}
				{{ csrf_field() }}

				<div class="form-group">
					<label for="nombre">Nombre</label>
					<input type="text" name="nombre" class="form-control" 
						value="{{ $categoria->nombre }}" 
						placeholder="{{ $categoria->nombre }}">
				</div>
				<div class="form-group">
					<label for="descripcion">Descripción</label>
					<input type="text" name="descripcion" class="form-control"
						value="{{ $categoria->descripcion }}"
						placeholder="{{ $categoria->descripcion }}">
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="{{ route('categorias.index') }}" class="btn btn-danger" >Cancelar</a>
				</div>
			</form>

		</div>
	</div>


</div>



@stop