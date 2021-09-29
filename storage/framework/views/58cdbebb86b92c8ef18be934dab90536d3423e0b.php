<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas de <?php echo e(($load_invoice) ? 'Clientes' : 'Proveedores'); ?> </h2>
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

    <hr>

    <?php if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')  ): ?>
        <?php if($load_invoice): ?>
            <form action="<?php echo e(route('facturas.excel.client')); ?>" method="POST">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">
                <div class="row py-lg-2">
                    <div class="col-md-2">
                        <label for="start">Fecha Desde:</label>
                        <input type="date" id="start" name="start">
                    </div>
                    <div class="col-md-2">
                        <label for="end">Fecha Hasta:</label>
                        <input type="date" id="end" name="end">
                    </div>
                    <div class="col-md-3">Numero cliente:</label>
                        <input type="text" id="client" name="client">
                    </div>
                    <div class="col-md-2 mt-4" style="margin-left: -5rem!important;">
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Estados facturas</option>
                            <option value="pagado">Pagado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="validado">Validado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-success btn-md float-md-right">Exportar Facturas en Excel</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
        <form action="<?php echo e(route('facturas.excel.provider')); ?>" method="POST">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">
            <div class="row py-lg-2">
                <div class="col-md-2">
                    <label for="start">Fecha Desde:</label>
                    <input type="date" id="start" name="start">
                </div>
                <div class="col-md-2">
                    <label for="end">Fecha Hasta:</label>
                    <input type="date" id="end" name="end">
                </div>
                <div class="col-md-3">Numero Proveedor:</label>
                    <input type="text" id="provider" name="provider">
                </div>
                <div class="col-md-2 mt-4" style="margin-left: -5rem!important;">
                    <select class="form-control" id="estado" name="estado">
                        <option value="">Estados facturas</option>
                        <option value="pagado">Pagado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="validado">Validado</option>
                    </select>
                </div>
                <div class="col-md-3 mt-4">
                    <button type="submit" class="btn btn-success btn-md float-md-right">Exportar Facturas en Excel</button>
                </div>
            </div>
        </form>  
        <hr> 
        <div class="col-md-7 offset-lg-5">
            <div class="row">
                <div class="col-md-4">
                    <p><b> Cantidad de Facturas a descargar: </b><span id="count-facturas">0</span></p>
                </div>
                <div class="col-md-4">
                    <form action="<?php echo e(route('facturas.download', 'pdf')); ?>" method="post" id="form2">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">
                        <button class="btn btn-primary btn-md float-md-right" id="download_invoice">Descargar Formato PDF</button>
                    </form>
                </div>                        
                <div class="col-md-4">
                    <form action="<?php echo e(route('facturas.download', 'xml')); ?>" method="post" id="form3">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">
                        <button class="btn btn-primary btn-md float-md-right" id="download_invoice2">Descargar Formato XML</button>
                    </form>
                </div>
            </div>
            <hr>
        </div>
        <?php endif; ?>
    <?php endif; ?>

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
                            <?php if($load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Cliente</th>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Proveedor</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th># Factura</th>
                            <?php if(!$load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                    <th>Fecha Factura</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th>Nombre factura</th>
                            <th>Costo Total</th>
                            <?php if(!$load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                    <th>Moneda</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th>Estado</th>
                            <th>Usuario Asociado</th>
                            <?php endif; ?>
                            <?php if(!$load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Fecha promesa de pago</th>
                                <th>Fecha limite para enviar el complemento de pago</th>
                                <th>Fecha de pago</th>
                                <th>En el portal desde</th>
                                <th>Descargar</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th>Herramientas</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                            <th>ID</th>
                            <?php if($load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Cliente</th>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Proveedor</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th># Factura</th>
                            <?php if(!$load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                    <th>Fecha Factura</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th>Nombre factura</th>
                            <th>Costo Total</th>
                            <?php if(!$load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Moneda</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th>Estado</th>
                            <th>Usuario Asociado</th>
                            <?php endif; ?>
                            <?php if(!$load_invoice): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
                                <th>Fecha promesa de pago</th>
                                <th>Fecha limite para enviar el complemento de pago</th>
                                <th>Fecha de pago</th>
                                <th>En el portal desde</th>
                                <th>Descargar</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th>Herramientas</th>
                        </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
                <?php echo $__env->make('admin.facturas.modal2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('js_user_page'); ?>
    <script>
       $(document).ready( function () {
            <?php if($load_invoice): ?>
                route = "<?php echo e(route('facturas.clientes')); ?>";
            <?php else: ?>
                route = "<?php echo e(route('facturas.proveedores')); ?>";
            <?php endif; ?>

            var table = $('#listInvoice').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': route,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                columns: [
                    {data: 'factura_id', name: 'factura_id'},
                    <?php if($load_invoice): ?>
                        {data: 'cod_cliente', name: 'cod_cliente'},
                    <?php else: ?>
                        {data: 'cod_proveedor', name: 'cod_proveedor'},
                    <?php endif; ?>
                    {data: 'numero_factura', name: 'numero_factura'},
                    <?php if(!$load_invoice): ?>
                        {data: 'fecha', name: 'fecha'},
                    <?php endif; ?>
                    {data: 'nombre_factura', name: 'nombre_factura'},
                    {
                        data: 'total_cost',
                        name: 'total_cost',
                        className: 'dt-body-right'
                    },
                    <?php if(!$load_invoice): ?>
                        {data: 'moneda', name: 'moneda'},
                    <?php endif; ?>
                    {data: 'estado', name: 'estado'},
                    {data: 'name', name: 'name'},
                    <?php if(!$load_invoice): ?>
                        {data: 'payment_promise_date', name: 'payment_promise_date'},
                        {data: 'deadline_for_complement', name: 'deadline_for_complement'},
                        {data: 'FechaPago', name: 'FechaPago'},
                        {data: 'fecha_sistema', name: 'fecha_sistema'},
                        {data: 'check', name: 'check'},
                    <?php endif; ?>
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[1, 'desc']]
            });
        });

        function openModalChange(id){
            $('#change-status-form').attr('action', "/admin/facturas/cancel/"+id);  

            $("#modal-change").modal("show");
        }

        function openModalDelete(id){
            $('#delete-status-form').attr('action', "/admin/facturas/"+id);  

            $("#modal-delete").modal("show");
        }

        $('#download_invoice').on('click', function (event) {
            event.preventDefault();
            var count = 0;

            $(".download:checked").each(function() {
                count ++;

                 $('<input>', {
                    type: 'hidden',
                    value: $(this).attr('id'),
                    name: 'ids[]'
                }).appendTo('#form2');
            });

            if(count == 0)
                 alert("No ha seleccionado ninguna factura");
            else
                $("#form2").submit();
        });

        $('#download_invoice2').on('click', function (event) {
            event.preventDefault();
            var count = 0;

            $(".download:checked").each(function() {
                count ++;

                $('<input>', {
                    type: 'hidden',
                    value: $(this).attr('id'),
                    name: 'ids[]'
                }).appendTo('#form3');
            });

            if(count == 0)
                alert("No ha seleccionado ninguna factura");
            else
                $("#form3").submit();
        });

        var count = 0;
        function descargar(checkeds){
            if(checkeds.checked)
                count ++;
            else
                count --;

            $("#count-facturas").html(count);
        }
        
    </script>

<?php $__env->stopSection(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/index2.blade.php ENDPATH**/ ?>