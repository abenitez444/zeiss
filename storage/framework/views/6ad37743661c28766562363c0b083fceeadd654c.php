<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<h1>Crear nuevo vendedor</h1>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('sellers.store')); ?>" enctype="multipart/form-data">
    <?php echo e(csrf_field()); ?>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nombre o Razon social</label>
                <input type="text" name="name" autocomplete="off" class="form-control" id="name" placeholder="Nombre..." value="<?php echo e(old('name')); ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Correo</label>
                <input type="email" name="email" autocomplete="off" class="form-control" id="email" placeholder="Correo..." value="<?php echo e(old('email')); ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="rfc">RFC</label>
                <input type="text" name="rfc" autocomplete="off" class="form-control" id="rfc" placeholder="RFC..." value="<?php echo e(old('rfc')); ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" autocomplete="off" class="form-control" id="phone" placeholder="(00) 0000-0000" value="<?php echo e(old('phone')); ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="points">Puntos</label>
                <input type="number" name="points" class="form-control" id="points" placeholder="Puntos..." min="0" value="<?php echo e(old('points')); ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="clave">Codigo</label>
                <input type="text" name="clave" class="form-control" placeholder="Codigo Vendedor..." id="clave" required value="<?php echo e(old('clave')); ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Rol</label>
                <select class="role form-control" name="role" id="role">
                    <option data-role-id="5" data-role-slug="vendedor" value="5">Vendedor</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Cliente Asociado</label>
				<select class='form-control mi-selector' name='client_id' required>
                    <option value=''>Seleccione un cliente</option>
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value='<?php echo e($client->user->id); ?>'><?php echo e($client->user->name.' - '.$client->rfc); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="address">Direccion de Envio</label>
                <input type="text" name="address" class="form-control" placeholder="Direccion..." id="address" required value="<?php echo e(old('address')); ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña..." required minlength="8">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Contraseña..." id="password_confirmation">
            </div>
        </div>
    </div>

    <div class="form-group pt-2">
        <input class="btn btn-primary" type="submit" value="Guardar">
        <a href="<?php echo e(route('sellers.index')); ?>" class="btn btn-danger" >Cancelar</a>
    </div>
</form>

<?php $__env->startSection('js_user_page'); ?>

    <script>

        $(document).ready(function(){
            var permissions_box = $('#permissions_box');
            var permissions_ckeckbox_list = $('#permissions_ckeckbox_list');

            permissions_box.hide(); // hide all boxes

            $('#role').on('change', function() {
                var role = $(this).find(':selected');
                var role_id = role.data('role-id');
                var role_slug = role.data('role-slug');

                permissions_ckeckbox_list.empty();

                $.ajax({
                    url: "/sellers/create",
                    method: 'get',
                    dataType: 'json',
                    data: {
                        role_id: role_id,
                        role_slug: role_slug,
                    }
                }).done(function(data) {

                    console.log(data);

                    permissions_box.show();
                    // permissions_ckeckbox_list.empty();

                    $.each(data, function(index, element){
                        $(permissions_ckeckbox_list).append(
                            '<div class="custom-control custom-checkbox">'+
                                '<input class="custom-control-input" type="checkbox" name="permissions[]" id="'+ element.slug +'" value="'+ element.id +'">' +
                                '<label class="custom-control-label" for="'+ element.slug +'">'+ element.name +'</label>'+
                            '</div>'
                        );

                    });
                });
            });

            $('#phone').mask('(00) 0000-0000');
        });

    </script>

<?php $__env->stopSection(); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/sellers/create.blade.php ENDPATH**/ ?>