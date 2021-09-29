<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row py-lg-2">
        <div class="col-md-6">
            <h2>Listado de facturas</h2>
        </div>

        <?php if( Auth::user()->hasRole('proveedor')  ): ?>
            <?php if($load_invoice): ?>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="<?php echo e(url('/admin/facturas/create/xml')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas XML</a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo e(url('/admin/facturas/create/pdf')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar facturas PDF</a>
                        </div>
                        
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <a href="<?php echo e(route('complementos.create')); ?>" class="btn btn-primary btn-md float-md-right" role="button" aria-pressed="true">
                                Cargar Complementos</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if( Auth::user()->hasRole('cliente')  ): ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <p><b> Cantidad de Facturas: </b><span id="count">0</span></p>
                        <p><b> Monto a Pagar: </b><span id="amount">0.00</span></p>
                    </div>
                    <div class="col-md-7">
                        <form action="<?php echo e(route('facturas.payment')); ?>" method="post" id="form">
                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">
                            <button class="btn btn-primary btn-md float-md-right" id="pay_invoice">Pagar en linea</button>
                        </form>
                    </div>
                </div>
                <hr>
            </div>
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
            <div class="col-md-7 offset-lg-5">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary btn-md float-md-right text-white" href="<?php echo e(url('/admin/facturas/status/'.Auth::user()->id)); ?>">Estado de Cuenta</a>
                    </div>
                </div>
            </div>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                    <th>Seleccionar para pagar</th>
                    <th>ID Factura</th>
                    <th>Codigo Cliente</th>
                    <th># Factura</th>
                    <th>En el portal desde</th>
                    <th>Fecha emision factura</th>
                    <th>Archivo factura</th>
                    <th style="text-align: right">Importe</th>
                    <th>Fecha de vencimiento</th>
                    <th>Estado portal BBVA</th>
                    <th>Pago otro Banco</th>
                    <th>Descargar</th>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?>
                    <th>Id</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Fecha Factura</th>
                    <th>En el portal desde</th>
                    <th style="text-align: right">Costo Total</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Fecha promesa de pago</th>
                    <th>Fecha de pago</th>
                    <th>Fecha limite para enviar el complemento de pago</th>
                    <?php endif; ?>
                    <th>Herramientas</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                    <th>Seleccionar para pagar</th>
                    <th>ID Factura</th>
                    <th>Codigo Cliente</th>
                    <th># Factura</th>
                    <th>En el portal desde</th>
                    <th>Fecha emision factura</th>
                    <th>Archivo factura</th>
                    <th style="text-align: right">Importe</th>
                    <th>Fecha de vencimiento</th>
                    <th>Estado portal BBVA</th>
                    <th>Pago otro Banco</th>
                    <th>Descargar</th>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?>
                    <th>ID Factura</th>
                    <th># Factura</th>
                    <th>Nombre factura</th>
                    <th>Fecha Factura</th>
                    <th>En el portal desde</th>
                    <th style="text-align: right">Costo Total</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Fecha promesa de pago</th>
                    <th>Fecha de pago</th>
                    <th>Fecha limite para enviar el complemento de pago</th>
                    <?php endif; ?>
                    <th>Herramientas</th>
                </tr>
                </tfoot>
                    <tbody>
                        <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                            <?php if(in_array($cat->estadoOtro, ['Pagado', 'pagado', 'Validado', 'validado']) || in_array($cat->estado, ['Pagado', 'pagado', 'Validado', 'validado'])): ?>
                                <td><i class="fas fa-check"></i></td>
                            <?php else: ?>
                                <td><input type="checkbox" id="<?php echo e($key); ?>" class="pay"></td>
                            <?php endif; ?>
                            <td><?php echo e($cat->factura_id); ?></td>
                            <td><?php echo e($cat->cod_cliente); ?></td>
                            <td><?php echo e($cat->numero_factura); ?></td>
                            <td><?php echo e(date('d/m/Y', strtotime($cat->fecha_sistema))); ?></td>
                            <td><?php echo e((!empty($cat->fecha)) ? date('d/m/Y', strtotime($cat->fecha)) : "No definido"); ?></td>
                            <td><?php echo e($cat->nombre_factura); ?></td>
                            <td><?php echo number_format($cat->total_cost, 2, '.', ','); ?></td>
                            <td><?php echo e((!empty($cat->fecha)) ? date('d/m/Y', strtotime($cat->fecha."+ ".$cat->credit_days." days")) : "No definido"); ?></td>
                            <td><?php echo e((!empty($cat->estadoOtro)) ? '' : $cat->estado); ?></td>
                            <td><?php echo e((!empty($cat->estadoOtro)) ? $cat->estadoOtro : ''); ?></td>
                            <th><input type="checkbox" id="<?php echo e($cat->factura_id); ?>" class="download"></th>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?>
                            <td><?php echo e($cat->factura_id); ?></td>
                            <td><?php echo e($cat->numero_factura); ?></td>
                            <td><?php echo e($cat->nombre_factura); ?></td>
                            <td><?php echo e($cat->fecha_factura); ?></td>
                            <td><?php echo e(date('d/m/Y', strtotime($cat->fecha_sistema))); ?></td>
                            <td><?php echo number_format($cat->total_cost, 2, '.', ','); ?></td>
                            <td><?php echo e($cat->moneda); ?></td>
                            <td><?php echo e((!empty($cat->estadoOtro)) ? $cat->estadoOtro : $cat->estado); ?></td>
                            <td><?php echo e((!empty($cat->payment_promise_date)) ? date('d/m/Y', strtotime($cat->payment_promise_date)) : "No definido"); ?></td>
                            <td><?php echo e((!empty($cat->fecha_pago)) ? date('d/m/Y', strtotime($cat->fecha_pago)) : ""); ?></td>
                            <td><?php echo e((!empty($cat->deadline_for_complement)) ? date('d/m/Y', strtotime($cat->deadline_for_complement)) : ""); ?></td>
                            <?php endif; ?>
                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isCliente')): ?>
                                    <a href="<?php echo e(url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf')); ?>" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="<?php echo e(url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml')); ?>" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    
                                    <a href="<?php echo e(url('/admin/facturas/complementos-pago/'.$cat->factura_id)); ?>" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isProveedor')): ?>
                                    
                                    <a href="<?php echo e(url('/admin/facturas/complemento-pago/'.$cat->factura_id)); ?>" class="btn btn-warning btn-circle btn-sm" title="Subir Complemento de Pago"><i class="fas fa-upload"></i> </a>
                                    <a href="" data-target="#modal-change-<?php echo e($cat->factura_id); ?>" title="Cancelar" data-toggle="modal"  class="btn btn-primary btn-circle btn-sm" ><i class="fas fa-cogs"></i></a>
                                    <a href="" data-target="#modal-delete-<?php echo e($cat->factura_id); ?>" title="Eliminar" data-toggle="modal"  class="btn btn-danger btn-circle btn-sm" ><i class="fas fa-trash-alt"></i></a>
                                    <a href="<?php echo e(url('/admin/facturas/imprimir/'.$cat->factura_id.'/pdf')); ?>" class="btn btn-info btn-circle btn-sm" title="Descargar Factura PDF"><i class="fas fa-file-pdf"></i> </a>
                                    <a href="<?php echo e(url('/admin/facturas/imprimir/'.$cat->factura_id.'/xml')); ?>" class="btn btn-primary btn-circle btn-sm" title="Descargar factura XML"><i class="fas fa-file-code"></i> </a>
                                    
                                    <a href="<?php echo e(url('/admin/facturas/complementos-pago/'.$cat->factura_id)); ?>" class="btn btn-warning btn-circle btn-sm" title="Ver Complementos de Pago"><i class="fas fa-download"></i> </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php echo $__env->make('admin.facturas.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            var table = $('#listInvoice').DataTable({
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                },
                responsive: true,
                'order': [[1, 'desc']]
            });

            function number_format (number, decimals, dec_point, thousands_sep) {
                // Strip all characters but numerical ones.
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }

            $('#pay_invoice').on('click', function (event) {
                event.preventDefault();
                var count = 0;

                $(".pay:checked").each(function() {
                    count ++;

                    var data = table.row($(this).attr('id')).data();

                    $('<input>', {
                        type: 'hidden',
                        value: data[1],
                        name: 'ids[]'
                    }).appendTo('#form');
                });

                if(count == 0)
                    alert("No ha seleccionado ninguna factura");
                else
                    $("#form").submit();
            });

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

            $('.pay').on('change', function() {
                var count = 0;
                var amount = 0.00;

                $(".pay:checked").each(function() {
                    count ++;
                    var data = table.row($(this).attr('id')).data();

                    amount += parseFloat(data[7].replace(",", ""));
                });

                $("#count").html(count);
                $("#amount").html(number_format(amount, 2, '.', ','));
            });

            $('.download').on('change', function() {
                var count = 0;

                $(".download:checked").each(function() {
                    count ++;
                });

                $("#count-facturas").html(count);
            });
        });
    </script>

<?php $__env->stopSection(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/index.blade.php ENDPATH**/ ?>