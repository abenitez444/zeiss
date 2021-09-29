<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <h2>Actualizar datos del proveedor</h2>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('providers.update', $user->user->id)); ?>" enctype="multipart/form-data">
            <?php echo method_field('PATCH'); ?>
            <?php echo csrf_field(); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nombre o Razon social</label>
                        <input type="text" name="name" autocomplete="off" class="form-control" id="name" placeholder="Nombre..." value="<?php echo e($user->user->name); ?>" required <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?> readonly <?php endif; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" name="email" autocomplete="off" class="form-control" id="email" placeholder="Correo..." value="<?php echo e($user->user->email); ?>" required >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" name="rfc" autocomplete="off" class="form-control" id="rfc" placeholder="RFC..." value="<?php echo e($user->rfc); ?>" required <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?> readonly <?php endif; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" name="phone" autocomplete="off" class="form-control" id="phone" placeholder="(00) 0000-0000" value="<?php echo e($user->phone); ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact">Contacto</label>
                        <input type="text" name="contact" class="form-control" id="contact" placeholder="Contacto..." required value="<?php echo e($user->contact); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cfdi">Uso de CFDi</label>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?>
                            <input type="text" name="cfdi" class="form-control" id="cfdi" required value="<?php echo e($user->cfdi); ?>" readonly>
                        <?php else: ?>
                            <select class="form-control" name="cfdi" id="cfdi">
                                <option value="Gastos general" <?php echo e($user->cfdi == "Gastos general" ? "selected" : ""); ?>>Gastos general</option>
                                <option value="Compra materias primas" <?php echo e($user->cfdi == "Compra materias primas" ? "selected" : ""); ?>>Compra materias primas</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="credit_terms">Términos de crédito</label>
                        <select class="form-control" name="credit_terms" id="credit_terms">
                            <option value="0" <?php echo e($user->credit_terms == 0 ? "selected" : ""); ?>>0 dias</option>
                            <option value="5" <?php echo e($user->credit_terms == 5 ? "selected" : ""); ?>>5 dias</option>
                            <option value="10" <?php echo e($user->credit_terms == 10 ? "selected" : ""); ?>>10 dias</option>
                            <option value="15" <?php echo e($user->credit_terms == 15 ? "selected" : ""); ?>>15 dias</option>
                            <option value="30" <?php echo e($user->credit_terms == 30 ? "selected" : ""); ?>>30 dias</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role">Rol</label>
                        <select class="role form-control" name="role" id="role">
                            <option data-role-id="2" data-role-slug="proveedor" value="2">Proveedor</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña..." minlength="8">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Contraseña..." id="password_confirmation">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cod_proveedor">Numero de Proveedor</label>
                        <input type="text" name="cod_proveedor" autocomplete="off" class="form-control" id="cod_proveedor" placeholder="Numero de Proveedor..." value="<?php echo e($user->cod_proveedor); ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group pt-2">
                <input class="btn btn-primary" type="submit" value="Actualizar">
                <a href="<?php echo e(route('providers.index')); ?>" class="btn btn-danger" >Cancelar</a>
            </div>

        </form>

        <?php $__env->startSection('js_user_page'); ?>

            <script>

                $(document).ready(function(){
                    var permissions_box = $('#permissions_box');
                    var permissions_ckeckbox_list = $('#permissions_ckeckbox_list');
                    var user_permissions_box = $('#user_permissions_box');
                    var user_permissions_ckeckbox_list = $('#user_permissions_ckeckbox_list');

                    permissions_box.hide(); // hide all boxes


                    $('#role').on('change', function() {
                        var role = $(this).find(':selected');
                        var role_id = role.data('role-id');
                        var role_slug = role.data('role-slug');

                        permissions_ckeckbox_list.empty();
                        user_permissions_box.empty();

                        $.ajax({
                            url: "/providers/create",
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

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/proveedores/edit.blade.php ENDPATH**/ ?>