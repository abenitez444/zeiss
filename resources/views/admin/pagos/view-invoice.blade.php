@extends('admin.layouts.dashboard')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Facturas de Pago #{{ $pago->referencia }}</h2>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Resumen</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listPaymentInvoice" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Numero Factura</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Numero Factura</th>
                            <th>Monto</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($facturas as $key => $factura)
                            <tr>
                                <td>{{ $factura->numero_factura }}</td>
                                <td>{{ $factura->total_cost }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_user_page')
    <script>
        $(document).ready( function () {
            var table = $('#listPaymentInvoice').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>
@endsection
