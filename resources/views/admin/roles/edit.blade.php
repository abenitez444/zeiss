@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

<h1>Actualizar el Rol</h1>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li> 
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/roles/{{$role->id}}">
    @csrf
    @method('PATCH')
    
    <div class="form-group">
        <label for="role_name">Nombre del rol</label>
        <input type="text" name="role_name" class="form-control" id="role_name" placeholder="Nombre del rol..." value="{{$role->name}}" required>
    </div>
    <div class="form-group">
        <label for="role_slug">Enlace del rol</label>
        <input type="text" name="role_slug" tag="role_slug" class="form-control" id="role_slug" placeholder="Enlace del rol..." value="{{$role->slug}}" required>
    </div>
    <div class="form-group" >
        <label for="roles_permissions">Añadir permisos</label><br>
        <!--input type="text" data-role="tagsinput" name="roles_permissions" class="form-control" id="roles_permissions" value="@foreach ($role->permissions as $permission)
            {{$permission->name. ","}}
        @endforeach"-->
        @foreach ($role->permissions as $permission)
            <label class="alert alert-success"><strong>{{$permission->name}}</strong></label>
        @endforeach
        <br>
        <label><input type="checkbox" name="roles_permissions[]" value="view"> Ver</label>
        <label><input type="checkbox" name="roles_permissions[]" value="create"> Crear</label>
        <label><input type="checkbox" name="roles_permissions[]" value="edit"> Editar</label>
        <label><input type="checkbox" name="roles_permissions[]" value="delete"> Eliminar</label>
    </div>     

    <div class="form-group pt-2">
        <input class="btn btn-primary" type="submit" value="Actualizar">
        <a href="{{ route('roles.index') }}" class="btn btn-danger" >Cancelar</a>
    </div>
</form>

</div>

@section('css_role_page')
    <link rel="stylesheet" href="/css/admin/bootstrap-tagsinput.css">
@endsection

@section('js_role_page')
    <script src="/js/admin/bootstrap-tagsinput.js"></script>

    <script>

        $(document).ready(function(){
            $('#role_name').keyup(function(e){
                var str = $('#role_name').val();
                str = str.replace(/\W+(?!$)/g, '-').toLowerCase();//rplace stapces with dash
                $('#role_slug').val(str);
                $('#role_slug').attr('placeholder', str);
            });
        });
        
    </script>

@endsection

@endsection