<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Nombre o Razon social: <?php echo e($user[0]->user->name); ?></h3>
            <h4>Correo: <?php echo e($user[0]->user->email); ?></h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Rol</h5>
            <p class="card-text">
              Cliente
            </p>
        </div>
        <div class="card-body">
            <h5 class="card-title">Datos</h5>
            <div class="row">
                <div class="col-md-4">
                    <p class="card-text">
                        RFC - <?php echo e($user[0]->rfc); ?>

                    </p>
                    <p class="card-text">
                        Metodo de Pago - <?php echo e($user[0]->payment_method); ?>

                    </p>
                    <p class="card-text">
                        Status - <?php echo e($user[0]->status ? "Activo" : "Detenido por credito"); ?>

                    </p>
                </div>
                <div class="col-md-4">
                    <p class="card-text">
                        Teléfono - <?php echo e($user[0]->phone); ?>

                    </p>
                    <p class="card-text">
                        Forma de Pago - <?php echo e($user[0]->way_to_pay); ?>

                    </p>
                    <p class="card-text">
                        Codigo Cliente - <?php echo e($user[0]->cod_cliente); ?>

                    </p>
                </div>
                <div class="col-md-4">
                    <p class="card-text">
                        Dias de Credito - <?php echo e($user[0]->credit_days); ?>

                    </p>
                    <p class="card-text">
                        Uso de CFDi - <?php echo e($user[0]->cfdi); ?>

                    </p>
                </div>
        </div>
        <div class="card-footer">
            <!--a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Volver</a-->
            <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-primary" >Volver</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/abenitez444/localhost/CarlSeizz/resources/views/admin/clientes/show.blade.php ENDPATH**/ ?>