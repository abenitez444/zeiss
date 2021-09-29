<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Gestión de puntos</h2>
        </div>

        <?php if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  ): ?>
            <div class="col-md-6">
                <div class="row offset-lg-3">
                    <div class="col-md-6">
                        <a href="<?php echo e(route('puntos.csv')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Cargar puntos</a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo e(route('puntos.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Crear cantidad de puntos
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if( Auth::user()->hasRole('cliente')  ): ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="offset-lg-6 col-md-6">
                        <p><b> Puntos Disponibles: </b><span><?php echo e($puntos_cant); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if(session('info')): ?>
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                <li><strong><?php echo e(session('info')); ?></strong></li>
            </ul>
        </div>
    <?php endif; ?>

    <div   class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de puntos</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listPoint" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                    <th>Cliente</th>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                    <th>Vigente hasta</th>
                    <?php endif; ?>
                    <th>Factura</th>
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                    <th>Cliente</th>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                    <th>Vigente hasta</th>
                    <?php endif; ?>
                    <th>Factura</th>
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        <?php $__currentLoopData = $puntos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cat->id); ?></td>
                            <td><?php echo e($cat->puntos); ?></td>
                            <td><?php echo e($cat->estado); ?></td>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                            <td><?php echo e((isset($cat->user[0])) ? $cat->user[0]->name : 'Error, no tiene usuario'); ?></td>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                            <td><?php echo e((!empty($cat->created_at)) ? date('d/m/Y', strtotime($cat->created_at."+ 1 year") ) : "No definido"); ?></td>
                            <?php endif; ?>
                            <td><?php echo e((isset($cat->user[0])) ? $cat->factura[0]->numero_factura : 'Error, no tiene factura'); ?></td>
                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isAdmin')): ?>
                                <a href="<?php echo e(route('puntos.edit', $cat->id)); ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i>
                                </a>
                                <a href="" data-target="#modal-delete-<?php echo e($cat->id); ?>" title="Eliminar" data-toggle="modal" class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php echo $__env->make('admin.puntos.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php $__env->startSection('js_user_page'); ?>

    <script>
        $(document).ready( function () {
            $('#listPoint').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/puntos/index.blade.php ENDPATH**/ ?>