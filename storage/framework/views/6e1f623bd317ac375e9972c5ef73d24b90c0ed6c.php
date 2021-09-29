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
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php for($i = 0; $i < 9; $i++): ?>
                                <?php if(isset($order[$i])): ?>
                                    <tr>
                                        <td><?php echo e($order[$i]['reference']); ?></td>
                                        <td><?php echo e($order[$i]['order']); ?></td>
                                        <td><?php echo e($order[$i]['status']); ?></td>
                                        <td><?php echo e($order[$i]['EstadoOrden']); ?></td>
                                        <td><?php echo e($order[$i]['client']); ?></td>
                                        <td><?php echo e($order[$i]['dateTime']); ?></td>
                                        <td><?php echo e($order[$i]['code']); ?></td>
                                        <td><?php echo e($order[$i]['coating']); ?></td>
                                        <td><?php echo e($order[$i]['color']); ?></td>
                                        <td><?php echo e($order[$i]['montage']); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endfor; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            var table = $('#listOrder').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                "bSort" : false
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/orders/index2.blade.php ENDPATH**/ ?>