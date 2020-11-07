@extends('admin.layouts.dashboard')


@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">


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


			<form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

				<div class="col-md-6">
					<div class="form-group">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" class="form-control" placeholder="Nombre" value="{{ old('nombre') }}" required>
					</div>
					<div class="form-group">
						<label for="codigo">Código</label>
						<input type="text" name="codigo" class="form-control" placeholder="Código" value="{{ old('codigo') }}" required>
					</div>
					<div class="form-group">
						<label for="stock">Stock</label>
						<input type="number" min="0" name="stock" class="form-control" placeholder="Stock" value="{{ old('stock') }}" required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="categorias_id">Categoría</label>
						<select name="categorias_id" class="form-control">
							@foreach($categorias as $cat)
								<option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label for="puntos">Puntos</label>
						<input type="number" min="0" name="puntos" class="form-control" placeholder="Puntos" value="{{ old('puntos') }}" required>
					</div>

					<div class="form-group">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" class="form-control" placeholder="Descripción" value="{{ old('descripcion') }}" required>
					</div>

					<div class="form-group">
						<label for="estado">Estado</label>
						<select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
						</select>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<button class="btn btn-primary" type="submit">
							Guardar
						</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-danger" >Cancelar</a>
					</div>
				</div>
			</form>

		</div>
	</div>


</div>


@endsection
