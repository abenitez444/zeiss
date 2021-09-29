<?php $__env->startSection('content'); ?>

<div class="container">       
    <div class="card">
        <div class="card-header">
            <h3>Nombre: <?php echo e($user->name); ?></h3>  
            <h4>Correo: <?php echo e($user->email); ?></h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Rol</h5>
            <p class="card-text">
                <?php if($user->roles->isNotEmpty()): ?>
                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge badge-primary">
                            <?php echo e($role->name); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </p>
            <!--h5 class="card-title">Permisos</h5>
            <p class="card-text">
                <?php if($user->permissions->isNotEmpty()): ?>                                        
                    <?php $__currentLoopData = $user->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge badge-success">
                            <?php echo e($permission->name); ?>                                    
                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>            
                <?php endif; ?>
            </p-->

        </div>
        <div class="card-footer">
            <!--a href="<?php echo e(url()->previous()); ?>" class="btn btn-primary">Volver</a-->
            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-primary" >Volver</a>
        </div>
    </div>
</div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/users/show.blade.php ENDPATH**/ ?>