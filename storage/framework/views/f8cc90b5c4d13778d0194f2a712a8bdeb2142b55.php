<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<h1>Crear nuevo usuario</h1>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('users.store')); ?>" enctype="multipart/form-data">
    <?php echo e(csrf_field()); ?>


    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="Nombre..." value="<?php echo e(old('name')); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Correo</label>
        <input type="email" name="email" class="form-control" id="email" placeholder="Correo..." value="<?php echo e(old('email')); ?>">
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña..." required minlength="8">
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Contraseña..." id="password_confirmation">
    </div>
    <div class="form-group">
        <label for="role">Seleccionar Rol</label>

        <select class="role form-control" name="role" id="role" required>
            <option value="">Seleccionar Rol...</option>
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option data-role-id="<?php echo e($role->id); ?>" data-role-slug="<?php echo e($role->slug); ?>" value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <div id="permissions_box" >
            <label for="roles">Seleccionar Permisos</label>
            <div id="permissions_ckeckbox_list">
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="permissions[]" id="<?php echo e($permission->slug); ?>" value="<?php echo e($permission->id); ?>">
                        <label class="custom-control-label" for="<?php echo e($permission->slug); ?>"><?php echo e($permission->name); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="form-group pt-2">
        <input class="btn btn-primary" type="submit" value="Guardar">
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-danger" >Cancelar</a>
    </div>
</form>

<?php $__env->startSection('js_user_page'); ?>

    <script>

        // $(document).ready(function(){
        //     var permissions_box = $('#permissions_box');
        //     var permissions_ckeckbox_list = $('#permissions_ckeckbox_list');

        //     permissions_box.hide(); // hide all boxes


        //     $('#role').on('change', function() {
        //         var role = $(this).find(':selected');
        //         var role_id = role.data('role-id');
        //         var role_slug = role.data('role-slug');

        //         permissions_ckeckbox_list.empty();

        //         $.ajax({
        //             url: "/users/create",
        //             method: 'get',
        //             dataType: 'json',
        //             data: {
        //                 role_id: role_id,
        //                 role_slug: role_slug,
        //             }
        //         }).done(function(data) {

        //             console.log(data);

        //             permissions_box.show();
        //             // permissions_ckeckbox_list.empty();

        //             $.each(data, function(index, element){
        //                 $(permissions_ckeckbox_list).append(
        //                     '<div class="custom-control custom-checkbox">'+
        //                         '<input class="custom-control-input" type="checkbox" name="permissions[]" id="'+ element.slug +'" value="'+ element.id +'">' +
        //                         '<label class="custom-control-label" for="'+ element.slug +'">'+ element.name +'</label>'+
        //                     '</div>'
        //                 );

        //             });
        //         });
        //     });
        // });

    </script>



<?php $__env->stopSection(); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/users/create.blade.php ENDPATH**/ ?>