@extends('admin.layouts.dashboard')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <h1>Crear nuevo proveedor</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('providers.store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nombre o Razon social</label>
                        <input type="text" name="name" autocomplete="off" class="form-control" id="name" placeholder="Nombre..." value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" name="email" autocomplete="off" class="form-control" id="email" placeholder="Correo..." value="{{ old('email') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" name="rfc" autocomplete="off" class="form-control" id="rfc" placeholder="RFC..." value="{{ old('rfc') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" name="phone" autocomplete="off" class="form-control" id="phone" placeholder="(00) 0000-0000" value="{{ old('phone') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="credit_days">Dias de Credito</label>
                        <input type="number" name="credit_days" class="form-control" id="credit_days" placeholder="Dias de Credito..." required min="0" value="{{ old('credit_days') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method">Metodo de Pago</label>
                        <input type="text" name="payment_method" class="form-control" placeholder="Metodo de Pago..." id="payment_method" required value="{{ old('payment_method') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="way_to_pay">Forma de Pago</label>
                        <input type="text" name="way_to_pay" class="form-control" id="way_to_pay" placeholder="Forma de Pago..." required value="{{ old('way_to_pay') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cfdi">Uso de CFDi</label>
                        <select class="form-control" name="cfdi" id="cfdi">
                            <option value="Gastos general">Gastos general</option>
                            <option value="Compra materias primas">Compra materias primas</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="1">Activo</option>
                            <option value="0">Detenido por credito</option>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña..." required minlength="8">
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
                <input class="btn btn-primary" type="submit" value="Guardar">
                <a href="{{ route('providers.index') }}" class="btn btn-danger" >Cancelar</a>
            </div>
        </form>

        @section('js_user_page')

            <script>

                $(document).ready(function(){
                    var permissions_box = $('#permissions_box');
                    var permissions_ckeckbox_list = $('#permissions_ckeckbox_list');

                    permissions_box.hide(); // hide all boxes


                    $('#role').on('change', function() {
                        var role = $(this).find(':selected');
                        var role_id = role.data('role-id');
                        var role_slug = role.data('role-slug');

                        permissions_ckeckbox_list.empty();

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
