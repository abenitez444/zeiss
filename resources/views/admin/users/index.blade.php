@extends('admin.layouts.dashboard')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row py-lg-4">
            <div class="col-md-6">
                <h2>Gestión de usuarios</h2>
            </div>
            <div class="col-md-6">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear nuevo usuario</a>
            </div>
        </div>


        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-table"></i>
                Tabla usuarios</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="listUser" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <!--th>Permisos</th-->
                            <th>Herramientas</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <!--th>Permisos</th-->
                            <th>Herramientas</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach ($users as $user)
                            @if(!\Auth::user()->hasRole('admin') && $user->hasRole('admin')) @continue; @endif
                            <tr {{ Auth::user()->id == $user->idd ? 'bgcolor=#ddd' : '' }}>
                                <td>{{$user->idd}}</td>
                                <td>{{$user->nombreuser}}</td>
                                <td>{{$user->correo}}</td>
                                <td>{{$user->nombrerol}}</td>
                                {{--                    <td>--}}
                                {{--                        @if ($user->roles->isNotEmpty())--}}
                                {{--                            @foreach ($user->roles as $role)--}}
                                {{--                                <span class="badge badge-secondary">--}}
                                {{--                                    {{ $role->name }}--}}
                                {{--                                </span>--}}
                                {{--                            @endforeach--}}
                                {{--                        @endif--}}

                                {{--                    </td>--}}
                                {{--                    <td>--}}
                                {{--                        @if ($user->permissions->isNotEmpty())--}}

                                {{--                            @foreach ($user->permissions as $permission)--}}
                                {{--                                <span class="badge badge-secondary">--}}
                                {{--                                    {{ $permission->name }}--}}
                                {{--                                </span>--}}
                                {{--                            @endforeach--}}

                                {{--                        @endif--}}
                                {{--                    </td>--}}
                                <td>
                                    <a href="{{ route('users.show', $user->idd) }}" class="btn btn-info btn-circle btn-sm" ><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('users.edit', $user->idd) }}" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                    @can('isAdmin')
                                        <a href="#" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" data-target="#deleteModal" data-userid="{{$user->idd}}"><i class="fas fa-trash-alt"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- delete Modal-->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro de que quieres eliminar el usuario?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Seleccione "eliminar" si realmente desea eliminar este usuario.</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                <form method="POST" action="">
                                    @method('DELETE')
                                    @csrf
                                    {{-- <input type="hidden" id="user_id" name="user_id" value=""> --}}
                                    <a class="btn btn-primary" style="color: #ffffff;" onclick="$(this).closest('form').submit();">Eliminar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div-->
        </div>

    </div>

@section('js_user_page')

    <script src="/vendor/chart.js/Chart.min.js"></script>
    <script src="/js/admin/demo/chart-area-demo.js"></script>

    <script>
        $(document).ready( function () {
            $('#listUser').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });

        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var user_id = button.data('userid')

            var modal = $(this)
            // modal.find('.modal-footer #user_id').val(user_id)
            modal.find('form').attr('action','/admin/users/' + user_id);
        })
    </script>

@endsection

@endsection
