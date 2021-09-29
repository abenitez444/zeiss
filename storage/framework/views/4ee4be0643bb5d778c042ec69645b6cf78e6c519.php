

<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas de Clientes </h2>
        </div>

        <?php if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  ): ?>
            <?php if($load_invoice): ?>
                <div class="col-md-6">
                    <div class="row offset-lg-3">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('facturas.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas</a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('complementos.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar Complementos</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                <li><strong><?php echo e(session('error')); ?></strong></li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if($message = Session::get('info')): ?>
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                <?php $__currentLoopData = $message; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><strong><?php echo e($msg); ?></strong></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div   class="card shadow mb-4">
        <div class="card-header py-3">
            <i class="fas fa-table"></i>
            Data de facturas</div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="listInvoice" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th># Factura</th>
                            <th>Nombre factura</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Usuario Asociado</th>
                            <?php endif; ?>
                            <th>Herramientas</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th># Factura</th>
                            <th>Nombre factura</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Usuario Asociado</th>
                            <?php endif; ?>
                            <th>Herramientas</th>
                        </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('js_user_page'); ?>
    <script>
       $(document).ready( function () {

            var table = $('#listInvoice').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "<?php echo e(route('facturas.clientes')); ?>",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                columns: [
                    {data: 'factura_id', name: 'factura_id'},
                    {data: 'cod_cliente', name: 'cod_cliente'},
                    {data: 'numero_factura', name: 'numero_factura'},
                    {data: 'nombre_factura', name: 'nombre_factura'},
                    {data: 'total_cost', name: 'total_cost'},
                    {data: 'estado', name: 'estado'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[1, 'desc']]
            });
        });
    </script>

<?php $__env->stopSection(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/index_clientes.blade.php ENDPATH**/ ?>