<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row py-lg-4">
            <div class="col-md-6">
                <h2>Gestión de usuarios</h2>
            </div>
            <div class="col-md-6">
                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear nuevo usuario</a>
            </div>
        </div>


        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-table"></i>
                Tabla usuarios</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="listUser" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <!--th>Permisos</th-->
                            <th>Herramientas</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <!--th>Permisos</th-->
                            <th>Herramientas</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!\Auth::user()->hasRole('admin') && $user->hasRole('admin')): ?> <?php continue; ?>; <?php endif; ?>
                            <tr <?php echo e(Auth::user()->id == $user->idd ? 'bgcolor=#ddd' : ''); ?>>
                                <td><?php echo e($user->idd); ?></td>
                                <td><?php echo e($user->nombreuser); ?></td>
                                <td><?php echo e($user->correo); ?></td>
                                <td><?php echo e($user->nombrerol); ?></td>
                                
                                
                                
                                
                                
                                
                                
                                

                                
                                
                                

                                
                                
                                
                                
                                

                                
                                
                                <td>
                                    <a href="<?php echo e(route('users.show', $user->idd)); ?>" class="btn btn-info btn-circle btn-sm" ><i class="fa fa-eye"></i></a>
                                    <a href="<?php echo e(route('users.edit', $user->idd)); ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isAdmin')): ?>
                                        <a href="#" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" data-target="#deleteModal" data-userid="<?php echo e($user->idd); ?>"><i class="fas fa-trash-alt"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <!-- delete Modal-->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro de que quieres eliminar el usuario?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Seleccione "eliminar" si realmente desea eliminar este usuario.</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                <form method="POST" action="">
                                    <?php echo method_field('DELETE'); ?>
                                    <?php echo csrf_field(); ?>
                                    
                                    <a class="btn btn-primary" style="color: #ffffff;" onclick="$(this).closest('form').submit();">Eliminar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div-->
        </div>

    </div>

<?php $__env->startSection('js_user_page'); ?>

    <script src="/vendor/chart.js/Chart.min.js"></script>
    <script src="/js/admin/demo/chart-area-demo.js"></script>

    <script>
        $(document).ready( function () {
            $('#listUser').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });

        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var user_id = button.data('userid')

            var modal = $(this)
            // modal.find('.modal-footer #user_id').val(user_id)
            modal.find('form').attr('action','/admin/users/' + user_id);
        })
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/users/index.blade.php ENDPATH**/ ?>