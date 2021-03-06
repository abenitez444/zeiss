<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<div class="row py-lg-4">
    <div class="col-md-6">
        <h2>Listado de roles</h2>
    </div>
    <!--div class="col-md-6">
        <a href="/roles/create" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear nuevo rol</a>
    </div-->
</div>

<!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
        Tabla roles</div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="listRole" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Id</th>
                <th>Rol</th>
                <th>Enlace</th>
                <!--th>Permisos</th-->
                <th>Herramientas</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Id</th>
                <th>Rol</th>
                <th>Enlace</th>
                <!--th>Permisos</th-->
                <th>Herramientas</th>
            </tr>
            </tfoot>
            <tbody>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($role['id']); ?></td>
                        <td><?php echo e($role['name']); ?></td>
                        <td><?php echo e($role['slug']); ?></td>
                        <!--td>
                            <?php if($role->permissions != null): ?>

                                <?php $__currentLoopData = $role->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge badge-secondary">
                                    <?php echo e($permission->name); ?>

                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php endif; ?>
                        </td-->
                        <td>
                            <a href="<?php echo e(route('roles.show', $role['id'])); ?>" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a>
                            <!--a href="/roles/<?php echo e($role['id']); ?>/edit" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="#" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" data-target="#deleteModal" data-roleid="<?php echo e($role['id']); ?>"><i class="fas fa-trash-alt"></i></a-->
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">??Est??s seguro de que quieres eliminar esto?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">??</span>
        </button>
        </div>
        <div class="modal-body">Seleccione "eliminar" si realmente desea eliminar esta funci??n.</div>
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

<?php $__env->startSection('js_role_page'); ?>

<script>
    $(document).ready( function () {
        $('#listRole').DataTable({
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            responsive: true
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var role_id = button.data('roleid')

        var modal = $(this)
        // modal.find('.modal-footer #role_id').val(role_id)
        modal.find('form').attr('action','/roles/' + role_id);
    })
</script>

<?php $__env->stopSection(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>