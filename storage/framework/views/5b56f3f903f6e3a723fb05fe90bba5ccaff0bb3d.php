<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row py-lg-2">
            <div class="col-md-6">
                <h2>Listado de complementos</h2>
            </div>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-info alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul>
                    <li><strong><?php echo e(session('error')); ?></strong></li>
                </ul>
            </div>
        <?php endif; ?>

        <div   class="card shadow mb-4">
            <div class="card-header py-3">
                <i class="fas fa-table"></i>
                Data de complementos</div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="listComplement" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Id Complemento</th>
                            <th># Factura</th>
                            <th>Nombre Complemento</th>
                            <th>No. de Parcialidad</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Herramientas</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Id Complemento</th>
                            <th># Factura</th>
                            <th>Nombre Complemento</th>
                            <th>No. de Parcialidad</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Herramientas</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($cat->id); ?></td>
                                <td><?php echo e($cat->numero_factura); ?></td>
                                <td><?php echo e($cat->name); ?></td>
                                <td><?php echo e($cat->NumParcialidad); ?></td>
                                <td><?php echo e($cat->total_cost); ?></td>
                                <td><?php echo e($cat->estado); ?></td>
                                <td>

                                <!--a href="<?php echo e(route('facturas.show', $cat->id)); ?>" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a-->
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                        <a href="<?php echo e(url('/admin/complements/imprimir/'.$cat->id)); ?>" class="btn btn-info btn-circle btn-sm" title="Descargar Complemento"><i class="fas fa-file-pdf"></i> </a>
                                        <a href="" data-target="#modal-delete-<?php echo e($cat->id); ?>" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                                        <a href="<?php echo e(url('/admin/complements/imprimir/'.$cat->id)); ?>" class="btn btn-info btn-circle btn-sm" title="Descargar Complemento"><i class="fas fa-file-pdf"></i> </a>
                                        
                                        
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?>
                                        <a href="<?php echo e(url('/admin/complements/imprimir/'.$cat->id)); ?>" class="btn btn-info btn-circle btn-sm" title="Descargar Complemento"><i class="fas fa-file-pdf"></i> </a>
                                        <a href="" data-target="#modal-delete-<?php echo e($cat->id); ?>" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php echo $__env->make('admin.facturas.complement-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            $('#listComplement').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/complements.blade.php ENDPATH**/ ?>