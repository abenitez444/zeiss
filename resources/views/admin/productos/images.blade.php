@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

	<h1>
		Cargar Imagenes de Productos
	</h1>

	<div class="row">
		<div class="col-md-6 col-xs-12">
			<div class="alert alert-success">
                Puede seleccionar varias imagenes al mismo tiempo, formato jpeg, png o gif
			</div>

			<form action="{{ route('productos.images.update') }}" method="POST"  enctype="multipart/form-data">

				<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

				<div class="form-group">
					<label for="imagen">Imagenes de productos</label>
                    <input type="file" name="uploadfile[]" multiple required accept="image/*">
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="{{ route('productos.index') }}" class="btn btn-danger" >Cancelar</a>
				</div>
			</form>

                @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul>
                            @foreach ($message as $msg)
                                <li><strong>{{ $msg }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

		</div>
	</div>
</div>

@stop
