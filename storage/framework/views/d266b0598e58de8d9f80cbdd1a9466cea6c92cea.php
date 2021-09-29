<?php $__env->startSection('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Follow the lens</h2>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Follow the lens</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listOrder" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Orden de Venta</th>
                            <th>Estatus</th>
                            <th>Estado Orden</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Codigo Producto</th>
                            <th>Revestimiento</th>
                            <th>Color</th>
                            <th>Montura</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Referencia</th>
                            <th>Orden de Venta</th>
                            <th>Estatus</th>
                            <th>Estado Orden</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Codigo Producto</th>
                            <th>Revestimiento</th>
                            <th>Color</th>
                            <th>Montura</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js_user_page'); ?>
    <script>
        $(document).ready( function () {
            route = "<?php echo e(route('orders.ajax')); ?>";

            var table = $('#listOrder').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': route,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                columns: [
                    {data: 'reference', name: 'reference'},
                    {data: 'order', name: 'order'},
                    {data: 'status', name: 'status'},
                    {data: 'EstadoOrden', name: 'EstadoOrden'},
                    {data: 'client', name: 'client'},
                    {data: 'dateTime', name: 'dateTime'},
                    {data: 'code', name: 'code'},
                    {data: 'coating', name: 'coating'},
                    {data: 'color', name: 'color'},
                    {data: 'montage', name: 'montage'},
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                "bSort" : false
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>