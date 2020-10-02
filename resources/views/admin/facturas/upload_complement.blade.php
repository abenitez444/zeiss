@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">


	<h1>
		Cargar Complementos de Pago
	</h1>


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

			<form action="{{ url('/admin/facturas/complemento-pago/'.$factura->id) }}" method="POST"  enctype="multipart/form-data">

				<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

				<div class="form-group">
					<label for="imagen">Archivos de complemento</label>
                    <input type="file" name="uploadfile[]" multiple required>
				</div>
				<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" >
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="{{ route('facturas.index') }}" class="btn btn-danger" >Cancelar</a>
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
