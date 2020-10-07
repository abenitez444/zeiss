@extends('admin.layouts.dashboard')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row py-lg-2">
            <div class="col-md-6">
                <h2>Listado de complementos</h2>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-info alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul>
                    <li><strong>{{ session('error') }}</strong></li>
                </ul>
            </div>
        @endif

        <div   class="card shadow mb-4">
            <div class="card-header py-3">
                <i class="fas fa-table"></i>
                Data de complementos</div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="listComplement" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Id Complemento</th>
                            <th># Factura</th>
                            <th>Nombre Complemento</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Herramientas</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Id Complemento</th>
                            <th># Factura</th>
                            <th>Nombre Complemento</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Herramientas</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($facturas as $cat)
                            <tr>
                                <td>{{ $cat->id }}</td>
                                <td>{{ $cat->numero_factura }}</td>
                                <td>{{ $cat->name }}</td>
                                <td>{{ $cat->total_cost }}</td>
                                <td>{{ $cat->estado }}</td>
                                <td>

                                <!--a href="{{ route('facturas.show', $cat->id) }}" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a-->
                                    @canany(['isAdmin','isManager'])
                                        <a href="{{ url('/admin/complements/imprimir/'.$cat->id) }}" class="btn btn-info btn-circle btn-sm" title="Descargar Complemento"><i class="fas fa-file-pdf"></i> </a>
                                        <a href="" data-target="#modal-delete-{{$cat->id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    @endcanany
                                    @can('isCliente')
                                        <a href="{{ url('/admin/complements/imprimir/'.$cat->id) }}" class="btn btn-info btn-circle btn-sm" title="Descargar Complemento"><i class="fas fa-file-pdf"></i> </a>
                                        {{--                                    <a href="{{ url('/admin/complements/imprimir/'.$cat->id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar Complemento XML"><i class="fas fa-file-code"></i> </a>--}}
                                        {{--                                    <a href="{{ url('/admin/complements/imprimir/'.$cat->id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar Complemento ZIP"><i class="fas fa-file-contract"></i> </a>--}}
                                    @endcan
                                </td>
                            </tr>
                            @include('admin.facturas.complement-modal')
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

    </div>

@section('js_user_page')
    <script>
        $(document).ready( function () {
            $('#listComplement').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

@endsection

@endsection
