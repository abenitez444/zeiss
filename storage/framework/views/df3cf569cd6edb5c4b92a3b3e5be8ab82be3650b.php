<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <h1>Crear nuevo proveedor</h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('providers.store')); ?>" enctype="multipart/form-data">
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
                        <label for="contact">Contacto</label>
                        <input type="text" name="contact" class="form-control" id="contact" placeholder="Contacto..." required value="<?php echo e(old('contact')); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cfdi">Uso de CFDi</label>
                        <select class="form-control" name="cfdi" id="cfdi">
                            <option value="Gastos general">Gastos general</option>
                            <option value="Compra materias primas">Compra materias primas</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="credit_terms">Términos de crédito</label>
                        <select class="form-control" name="credit_terms" id="credit_terms">
                            <option value="0">0 dias</option>
                            <option value="5">5 dias</option>
                            <option value="10">10 dias</option>
                            <option value="15">15 dias</option>
                            <option value="30">30 dias</option>
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

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cod_proveedor">Numero de Proveedor</label>
                        <input type="text" name="cod_proveedor" autocomplete="off" class="form-control" id="cod_proveedor" placeholder="Numero de Proveedor..." value="<?php echo e(old('cod_proveedor')); ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group pt-2">
                <input class="btn btn-primary" type="submit" value="Guardar">
                <a href="<?php echo e(route('providers.index')); ?>" class="btn btn-danger" >Cancelar</a>
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

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/proveedores/create.blade.php ENDPATH**/ ?>