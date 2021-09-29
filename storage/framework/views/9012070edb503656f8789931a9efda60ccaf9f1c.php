<?php $__env->startSection('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Facturas de Pago #<?php echo e($pago->referencia); ?></h2>
        </div>

        <?php if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')): ?>
            <div class="col-md-6">
                <a href="<?php echo e(route('pagos.validation', $pago->id)); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                    Validar Pago</a>
            </div>
        <?php endif; ?>
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
                        <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($factura->numero_factura); ?></td>
                                <td><?php echo e($factura->total_cost); ?></td>
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
            var table = $('#listPaymentInvoice').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/pagos/view-invoice.blade.php ENDPATH**/ ?>