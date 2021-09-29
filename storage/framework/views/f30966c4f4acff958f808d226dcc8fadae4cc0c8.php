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
                    Proveedor
                </p>
            </div>
            <div class="card-body">
                <h5 class="card-title">Datos</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-text">
                            RFC - <?php echo e($user[0]->rfc); ?>

                        </p>
                        <p class="card-text">
                            Contacto - <?php echo e($user[0]->contact); ?>

                        </p>
                        
                        <p class="card-text">
                            Términos de crédito - <?php echo e($user[0]->credit_terms); ?> dias
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="card-text">
                            Teléfono - <?php echo e($user[0]->phone); ?>

                        </p>
                        <p class="card-text">
                            Uso de CFDi - <?php echo e($user[0]->cfdi); ?>

                        </p>
                        <p class="card-text">
                            Numero de Proveedor - <?php echo e($user[0]->cod_proveedor); ?>

                        </p>
                    </div>
                </div>
                <br>
                
                
                <div class="card-footer">
                <!--a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Volver</a-->
                    <a href="<?php echo e(route('providers.index')); ?>" class="btn btn-primary" >Volver</a>
                </div>
            </div>
        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/proveedores/show.blade.php ENDPATH**/ ?>