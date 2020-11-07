@extends('admin.layouts.dashboard')


@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

<h2>
    Editar Producto: {{ $articulo->nombre }}
</h2>

	<div class="row">
		<div class="col-md-12 col-xs-12">
			@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif


			<form action="{{ route('productos.update', $articulo->id) }}" method="POST" enctype="multipart/form-data">
				<!-- <input name="_method" type="hidden" value="PUT"> -->
				{{ method_field('PUT') }}
				{{ csrf_field() }}

				<div class="col-md-6">
					<div class="form-group">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" class="form-control"  value="{{ $articulo->nombre }}" required>
					</div>
					<div class="form-group">
						<label for="codigo">Código</label>
						<input type="text" name="codigo" class="form-control" value="{{ $articulo->codigo }}" required>
					</div>
					<div class="form-group">
						<label for="stock">Stock</label>
						<input type="number" min="0" name="stock" class="form-control" value="{{ $articulo->stock }}" required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="id_categoria">Categoría</label>
						<select name="categorias_id" class="form-control">
							@foreach($categorias as $cat)
								@if($cat->id == $articulo->categorias_id)
									<option value="{{ $cat->id }}" selected>
										{{ $cat->nombre }}
									</option>
								@else
									<option value="{{ $cat->id }}">
										{{ $cat->nombre }}
									</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="puntos">Puntos</label>
						<input type="number" min="0" name="puntos" class="form-control" value="{{ $articulo->puntos }}" required>
					</div>

					<div class="form-group">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" class="form-control"
						value="{{ $articulo->descripcion }}" required>
					</div>

                    <div class="form-group">
						<label for="estado">Estado</label>
						<select name="estado" class="form-control">
                            <option value="1" {{ $articulo->estado == "activo" ? "selected" : ""}}>Activo</option>
                            <option value="2" {{ $articulo->estado == "inactivo" ? "selected" : ""}}>Inactivo</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="{{ route('productos.index') }}" class="btn btn-danger" > Cancelar </a>
				</div>

			</form>

		</div>
	</div>
</div>

@stop
