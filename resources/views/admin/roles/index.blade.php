@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

<div class="row py-lg-4">
    <div class="col-md-6">
        <h2>Listado de roles</h2>
    </div>
    <!--div class="col-md-6">
        <a href="/roles/create" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear nuevo rol</a>
    </div-->
</div>

<!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
        Tabla roles</div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="listRole" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Id</th>
                <th>Rol</th>
                <th>Enlace</th>
                <!--th>Permisos</th-->
                <th>Herramientas</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Id</th>
                <th>Rol</th>
                <th>Enlace</th>
                <!--th>Permisos</th-->
                <th>Herramientas</th>
            </tr>
            </tfoot>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role['id'] }}</td>
                        <td>{{ $role['name'] }}</td>
                        <td>{{ $role['slug'] }}</td>
                        <!--td>
                            @if ($role->permissions != null)

                                @foreach ($role->permissions as $permission)
                                <span class="badge badge-secondary">
                                    {{ $permission->name }}
                                </span>
                                @endforeach

                            @endif
                        </td-->
                        <td>
                            <a href="{{ route('roles.show', $role['id']) }}" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a>
                            <!--a href="/roles/{{ $role['id'] }}/edit" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="#" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" data-target="#deleteModal" data-roleid="{{$role['id']}}"><i class="fas fa-trash-alt"></i></a-->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro de que quieres eliminar esto?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        </div>
        <div class="modal-body">Seleccione "eliminar" si realmente desea eliminar esta función.</div>
        <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <form method="POST" action="">
            @method('DELETE')
            @csrf
            {{-- <input type="hidden" id="role_id" name="role_id" value=""> --}}
            <a class="btn btn-primary" style="color: #ffffff;" onclick="$(this).closest('form').submit();">Eliminar</a>
        </form>
        </div>
    </div>
    </div>
</div>

</div>

@section('js_role_page')

<script>
    $(document).ready( function () {
        $('#listRole').DataTable({
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            responsive: true
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var role_id = button.data('roleid')

        var modal = $(this)
        // modal.find('.modal-footer #role_id').val(role_id)
        modal.find('form').attr('action','/roles/' + role_id);
    })
</script>

@endsection


@endsection
