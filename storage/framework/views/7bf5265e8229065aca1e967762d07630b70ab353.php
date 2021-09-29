<?php $__env->startSection('content'); ?>

<div class="container">       
    <div class="card">
        <div class="card-header">
            <h3>Nombre: <?php echo e($role['name']); ?></h3>  
            <h4>Enlace: <?php echo e($role['slug']); ?></h4>
        </div>
        <!--div class="card-body">
            <h5 class="card-title">Permisos</h5>
            <p class="card-text">
                ...........
            </p>
        </div-->
        <div class="card-footer">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Volver</a>
            <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-danger" >Cancelar</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/roles/show.blade.php ENDPATH**/ ?>