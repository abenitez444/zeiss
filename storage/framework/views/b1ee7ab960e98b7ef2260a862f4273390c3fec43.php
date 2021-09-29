<?php $__env->startSection('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Productos Canjeados</h2>
        </div>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                <li><strong><?php echo e(session('error')); ?></strong></li>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Productos Canjeados</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listOperations" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Puntos</th>
                            <th>Cantidad</th>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                            <th>Cliente</th>
                            <?php endif; ?>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Puntos</th>
                            <th>Cantidad</th>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                            <th>Cliente</th>
                            <?php endif; ?>
                            <th>Fecha</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($operation->id); ?></td>
                                <td><?php echo e($operation->nombre); ?></td>
                                <td><?php echo e($operation->puntos); ?></td>
                                <td><?php echo e($operation->cantidad); ?></td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <td><?php echo e($operation->name); ?></td>
                                <?php endif; ?>
                                <td><?php echo e((!empty($operation->created_at)) ? date('d/m/Y', strtotime($operation->created_at) ) : "No definido"); ?></td>
                            </tr>
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
            var table = $('#listOperations').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[0, 'desc']]
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/operations/index.blade.php ENDPATH**/ ?>