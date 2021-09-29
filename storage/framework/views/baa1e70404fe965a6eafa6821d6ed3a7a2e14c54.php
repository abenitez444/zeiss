<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">


    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Gestión de productos</h2>
        </div>
        <?php if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  ): ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <a href="<?php echo e(route('productos.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">Crear Producto</a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo e(route('productos.csv')); ?>"" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Cargar CSV</a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('productos.images')); ?>"" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                            Cargar Imagenes </a>
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

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de productos</div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="listProduct" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Puntos</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Puntos</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                <tbody>
                    <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($art->id); ?></td>
                                <td><?php echo e($art->nombre); ?></td>
                                <td><?php echo e($art->codigo); ?></td>
                                <td><?php echo e($art->stock); ?></td>
                                <td><?php echo e($art->categorias); ?></td>
                                <td><?php echo e($art->puntos); ?></td>
                                <td><?php echo e($art->descripcion); ?></td>
                                <td><?php echo e($art->estado); ?></td>
                                <td>
                                    <!--a href="<?php echo e(route('productos.show', $art->id)); ?>" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a-->
                                    <a href="<?php echo e(route('productos.edit', $art->id)); ?>" class="btn btn-warning btn-circle btn-sm" title="Editar"><i class="fa fa-edit"></i>
                                        <!--button class="btn btn-info">Editar</button-->
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isAdmin')): ?>
                                        <a href="" data-target="#modal-change-<?php echo e($art->id); ?>" title="Cambiar Estatus" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-cogs"></i></a>
                                        <a href="" data-target="#modal-delete-<?php echo e($art->id); ?>" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                        <!--button class="btn btn-danger">Eliminar</button-->
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php echo $__env->make('admin.productos.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            </div>
            <?php echo e($productos->render()); ?>

        </div>
    </div>

</div>

<?php $__env->startSection('js_user_page'); ?>

    <script>
        $(document).ready( function () {
            $('#listProduct').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/productos/index.blade.php ENDPATH**/ ?>