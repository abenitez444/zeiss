<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<div class="row py-lg-4">
    <div class="col-md-8">
        <h2>Gestión de clientes</h2>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-6">
                <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear nuevo cliente</a>
            </div>
            <div class="col-md-6">
                <a href="<?php echo e(route('clients.load')); ?>"" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                    Cargar Archivo</a>
            </div>
        </div>
    </div>
</div>

<?php if(session('info')): ?>
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <ul>
            <li><strong><?php echo e(session('info')); ?></strong></li>
        </ul>
    </div>
<?php endif; ?>


<!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
        Tabla clientes</div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="listClient" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Id</th>
                <th>Cliente</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Herramientas</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Id</th>
                <th>Cliente</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Herramientas</th>
            </tr>
            </tfoot>
            <tbody>
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->idd); ?></td>
                    <td><?php echo e($user->codigo); ?></td>
                    <td><?php echo e($user->nombreuser); ?></td>
                    <td><?php echo e($user->correo); ?></td>
                    <td><?php echo e($user->nombrerol); ?></td>
                    <td>
                        <a href="<?php echo e(route('clients.show', $user->idd)); ?>" class="btn btn-info btn-circle btn-sm" ><i class="fa fa-eye"></i></a>
                        <a href="<?php echo e(route('clients.edit', $user->idd)); ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
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
                <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro de que quieres eliminar el cliente?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">Seleccione "eliminar" si realmente desea eliminar este cliente.</div>
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
            $('#listClient').DataTable({
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
            modal.find('form').attr('action','/admin/clients/' + user_id);
        })
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/abenitez444/localhost/CarlSeizz/resources/views/admin/clientes/index.blade.php ENDPATH**/ ?>