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
              Vendedor
            </p>
        </div>
        <div class="card-body">
            <h5 class="card-title">Datos</h5>
            <div class="row">
                <div class="col-md-3">
                    <p class="card-text">
                        RFC - <?php echo e($user[0]->rfc); ?>

                    </p>
                </div>
                <div class="col-md-3">
                    <p class="card-text">
                        Teléfono - <?php echo e($user[0]->phone); ?>

                    </p>
                </div>
                <div class="col-md-3">
                    <p class="card-text">
                        Puntos - <?php echo e($user[0]->points); ?>

                    </p>
                </div>
                <div class="col-md-3">
                    <p class="card-text">
                        Codigo Vendedor - <?php echo e($user[0]->clave); ?>

                    </p>
                </div>
                <hr>
                <div class="col-md-12">
                    <p class="card-text">
                        Direccion de envio - <?php echo e($user[0]->address); ?>

                    </p>
                </div>
        </div>
        <div class="card-footer">
            <!--a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Volver</a-->
            <a href="<?php echo e(route('sellers.index')); ?>" class="btn btn-primary" >Volver</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/sellers/show.blade.php ENDPATH**/ ?>