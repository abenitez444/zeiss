    @extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

<h2>Actualizar datos del cliente</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('clients.update', $user->user->id) }}" enctype="multipart/form-data">
    @method('PATCH')
    @csrf()

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nombre o Razon social</label>
                <input type="text" name="name" autocomplete="off" class="form-control" id="name" placeholder="Nombre..." value="{{ $user->user->name }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Correo</label>
                <input type="email" name="email" autocomplete="off" class="form-control" id="email" placeholder="Correo..." value="{{ $user->user->email }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" autocomplete="off" class="form-control" id="rfc" placeholder="RFC..." value="{{ $user->rfc }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" autocomplete="off" class="form-control" id="phone" placeholder="(00) 0000-0000" value="{{ $user->phone }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="credit_days">Dias de Credito</label>
                <input type="number" name="credit_days" class="form-control" id="credit_days" placeholder="Dias de Credito..." required min="0" value="{{ $user->credit_days }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="payment_method">Metodo de Pago</label>
                <input type="text" name="payment_method" class="form-control" placeholder="Metodo de Pago..." id="payment_method" required value="{{ $user->payment_method }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="way_to_pay">Forma de Pago</label>
                <input type="text" name="way_to_pay" class="form-control" id="way_to_pay" placeholder="Forma de Pago..." required value="{{ $user->way_to_pay }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="cfdi">Uso de CFDi</label>
                <select class="form-control" name="cfdi" id="cfdi">
                    <option value="Gastos general" {{ $user->cfdi == "Gastos general" ? "selected" : ""}}>Gastos general</option>
                    <option value="Compra materias primas" {{ $user->cfdi == "Compra materias primas" ? "selected" : ""}}>Compra materias primas</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="status">
                    <option value="1" {{ $user->status ? "selected" : ""}}>Activo</option>
                    <option value="0" {{ !$user->status ? "selected" : ""}}>Detenido por credito</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Rol</label>
                <select class="role form-control" name="role" id="role">
                    <option data-role-id="3" data-role-slug="cliente" value="3">Cliente</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="cod_cliente">Codigo Cliente</label>
                <input type="text" name="cod_cliente" class="form-control" id="cod_cliente" placeholder="Codigo Cliente..." value="{{ $user->cod_cliente }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="participa">Participa en puntos</label>
                <input type="checkbox" name="participa" class="form-control" id="participa" {{($user->participa) ? "checked" : "" }}>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña..." minlength="8">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Contraseña..." id="password_confirmation">
            </div>
        </div>
    </div>

    <div class="form-group pt-2">
        <input class="btn btn-primary" type="submit" value="Actualizar">
        <a href="{{ route('clients.index') }}" class="btn btn-danger" >Cancelar</a>
    </div>

</form>

@section('js_user_page')

    <script>

        $(document).ready(function(){
            var permissions_box = $('#permissions_box');
            var permissions_ckeckbox_list = $('#permissions_ckeckbox_list');
            var user_permissions_box = $('#user_permissions_box');
            var user_permissions_ckeckbox_list = $('#user_permissions_ckeckbox_list');

            permissions_box.hide(); // hide all boxes


            $('#role').on('change', function() {
                var role = $(this).find(':selected');
                var role_id = role.data('role-id');
                var role_slug = role.data('role-slug');

                permissions_ckeckbox_list.empty();
                user_permissions_box.empty();

                $.ajax({
                    url: "/clients/create",
                    method: 'get',
                    dataType: 'json',
                    data: {
                        role_id: role_id,
                        role_slug: role_slug,
                    }
                }).done(function(data) {

                    console.log(data);

                    permissions_box.show();
                    // permissions_ckeckbox_list.empty();

                    $.each(data, function(index, element){
                        $(permissions_ckeckbox_list).append(
                            '<div class="custom-control custom-checkbox">'+
                                '<input class="custom-control-input" type="checkbox" name="permissions[]" id="'+ element.slug +'" value="'+ element.id +'">' +
                                '<label class="custom-control-label" for="'+ element.slug +'">'+ element.name +'</label>'+
                            '</div>'
                        );

                    });
                });
            });

            $('#phone').mask('(00) 0000-0000');
        });

    </script>

@endsection
</div>

@endsection
