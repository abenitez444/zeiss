@extends('admin.layouts.dashboard')

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas</h2>
        </div>

        @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('proveedor') || Auth::user()->hasRole('manager')  )
            <div class="col-md-6">
                <a href="{{ route('facturas.create') }}" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                    Cargar facturas</a>
            </div>
        @endif
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
            Data de facturas</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listInvoice" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    @canany(['isAdmin','isManager'])
                    <th>Usuario Asociado</th>
                    @endif
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    @canany(['isAdmin','isManager'])
                    <th>Usuario Asociado</th>
                    @endcanany
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        @foreach($facturas as $cat)
                        <tr>
                            <td>{{ $cat->factura_id }}</td>
                            <td>{{ $cat->numero_factura }}</td>
                            <td>{{ $cat->nombre_factura }}</td>
                            <td>{{ $cat->total_cost }}</td>
                            <td>{{ $cat->estado }}</td>
                            @canany(['isAdmin','isManager'])
                            <td>{{ $cat->name }}</td>
                            @endcanany
                            <td>

{{--                                <a href="{{ route('facturas.show', $cat->factura_id) }}" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a>--}}
                                @canany(['isAdmin','isManager'])
{{--                                    <a href="{{ route('facturas.edit', $cat->id) }}" title="Editar" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>--}}
                                        <a href="{{ url('/admin/facturas/complemento-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>
                                        <a href="" data-target="#modal-change-{{$cat->factura_id}}" title="Cancelar" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-cogs"></i></a>
                                        <a href="" data-target="#modal-delete-{{$cat->factura_id}}" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                        <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                        <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                        <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>
                                        <a href="{{ url('/admin/facturas/complementos-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                @endcanany
                                @can('isCliente')
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf') }}" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml') }}" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    <a href="{{ url('/admin/facturas/imprimir/'.$cat->factura_id.'/zip') }}" class="btn btn-success btn-circle btn-sm" title="Descargar ZIP"><i class="fas fa-file-contract"></i> </a>
                                    <a href="{{ url('/admin/facturas/complementos-pago/'.$cat->factura_id) }}" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                @endcan
                            </td>
                        </tr>
                        @include('admin.facturas.modal')
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
            $('#listInvoice').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

@endsection


@endsection
