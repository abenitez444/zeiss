<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><?php echo e($error); ?></h3>
        </div>
        <div class="card-body">
            <h5 class="card-title">Datos</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="card-text">
                        Codigo - <?php echo e($payment['codigo']); ?>

                    </p>
                    <p class="card-text">
                        Autorizacion - <?php echo e($payment['autorizacion']); ?>

                    </p>
                    <p class="card-text">
                        Importe - <?php echo e($payment['importe']); ?>

                    </p>
                    <p class="card-text">
                        Financiado - <?php echo e($payment['financiado']); ?>

                    </p>
                    <p class="card-text">
                        s_transm - <?php echo e($payment['s_transm']); ?>

                    </p>
                    <p class="card-text">
                        cve Tipo Pago - <?php echo e($payment['cveTipoPago']); ?>

                    </p>
                </div>
                <div class="col-md-6">
                    <p class="card-text">
                        Mensaje - <?php echo e($payment['mensaje']); ?>

                    </p>
                    <p class="card-text">
                        Referencia - <?php echo e($payment['referencia']); ?>

                    </p>
                    <p class="card-text">
                        Medio de pago - <?php echo e($payment['mediopago']); ?>

                    </p>
                    <p class="card-text">
                        Plazos - <?php echo e($payment['plazos']); ?>

                    </p>
                    <p class="card-text">
                        Tarjeta habiente - <?php echo e($payment['tarjetahabiente']); ?>

                    </p>
                    <p class="card-text">
                        Hash - <?php echo e($payment['hash']); ?>

                    </p>
                </div>
        </div>
        <div class="card-footer">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Volver</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/comprobante-pago.blade.php ENDPATH**/ ?>