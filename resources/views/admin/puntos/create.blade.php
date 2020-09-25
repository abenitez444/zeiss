@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">


	<h2>
		Nueva cantidad de puntos
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
			
			<form action="{{ route('puntos.store') }}" method="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
				<div class="form-group">
					<label for="nombre">Puntos</label>
					<input type="text" name="puntos" class="form-control" placeholder="Puntos" required="">
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="{{ route('puntos.index') }}" class="btn btn-danger" >Cancelar</a>
				</div>
			</form>

		</div>
	</div>
</div>

@stop