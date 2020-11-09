@extends('admin.layouts.dashboard')

@section('css_role_page')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

	<h2>
		Editar los puntos: {{ $punto->puntos }}
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

			<form action="{{ route('puntos.update', $punto->id) }} " method="POST">
				<!-- <input name="_method" type="hidden" value="PUT"> -->
				{{ method_field('PUT') }}
				{{ csrf_field() }}

				<div class="form-group">
					<label for="nombre">Puntos</label>
					<input type="text" name="puntos" class="form-control"
						value="{{ $punto->puntos }}"
						placeholder="{{ $punto->puntos }}">
                </div>
                <div class="form-group">
					<label for="factura_id">Facturas</label>
					<select class='form-control mi-selector' name='factura_id' required>
                        <option value=''>Seleccione una factura</option>
                        @foreach ($facturas as $factura)
                            <option value='{{ $factura->id }}' {{ ($factura->id == $punto->factura[0]->id) ? 'selected' : '' }}>{{ $factura->numero_factura.' - '.$factura->name }}</option>
                        @endforeach
                    </select>
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

@section('js_user_page')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.mi-selector').select2();
    });
</script>
@endsection

@stop
