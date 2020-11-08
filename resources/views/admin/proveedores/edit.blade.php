@extends('admin.layouts.dashboard')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <h2>Actualizar datos del proveedor</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('providers.update', $user->user->id) }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf()

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nombre o Razon social</label>
                        <input type="text" name="name" autocomplete="off" class="form-control" id="name" placeholder="Nombre..." value="{{ $user->user->name }}" required @can('isProveedor') readonly @endcan>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" name="email" autocomplete="off" class="form-control" id="email" placeholder="Correo..." value="{{ $user->user->email }}" required >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" name="rfc" autocomplete="off" class="form-control" id="rfc" placeholder="RFC..." value="{{ $user->rfc }}" required @can('isProveedor') readonly @endcan>
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
                        <label for="contact">Contacto</label>
                        <input type="text" name="contact" class="form-control" id="contact" placeholder="Contacto..." required value="{{ $user->contact }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cfdi">Uso de CFDi</label>
                        @can('isProveedor')
                            <input type="text" name="cfdi" class="form-control" id="cfdi" required value="{{ $user->cfdi }}" readonly>
                        @else
                            <select class="form-control" name="cfdi" id="cfdi">
                                <option value="Gastos general" {{ $user->cfdi == "Gastos general" ? "selected" : ""}}>Gastos general</option>
                                <option value="Compra materias primas" {{ $user->cfdi == "Compra materias primas" ? "selected" : ""}}>Compra materias primas</option>
                            </select>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="credit_terms">Términos de crédito</label>
                        <select class="form-control" name="credit_terms" id="credit_terms">
                            <option value="0" {{ $user->credit_terms == 0 ? "selected" : ""}}>0 dias</option>
                            <option value="5" {{ $user->credit_terms == 5 ? "selected" : ""}}>5 dias</option>
                            <option value="10" {{ $user->credit_terms == 10 ? "selected" : ""}}>10 dias</option>
                            <option value="15" {{ $user->credit_terms == 15 ? "selected" : ""}}>15 dias</option>
                            <option value="30" {{ $user->credit_terms == 30 ? "selected" : ""}}>30 dias</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role">Rol</label>
                        <select class="role form-control" name="role" id="role">
                            <option data-role-id="2" data-role-slug="proveedor" value="2">Proveedor</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                {{--  <div class="col-md-6">
                    <div class="form-group">
                        <label for="deadline_for_complement">Fecha limite para enviar el complemento de pago</label>
                        <input type="date" name="deadline_for_complement" class="form-control" placeholder="AAAA-MM-DD" id="deadline_for_complement" required value="{{ $user->deadline_for_complement }}" @can('isProveedor') readonly @endcan>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_promise_date">Fecha promesa de pago</label>
                        <input type="date" name="payment_promise_date" class="form-control" placeholder="AAAA-MM-DD" id="payment_promise_date" required value="{{ $user->payment_promise_date }}" @can('isProveedor') readonly @endcan>
                    </div>
                </div>  --}}
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
                <a href="{{ route('providers.index') }}" class="btn btn-danger" >Cancelar</a>
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
                            url: "/providers/create",
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
