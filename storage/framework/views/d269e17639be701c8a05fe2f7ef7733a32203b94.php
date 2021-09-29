<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1>
		Pagar Facturas
    </h1>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4>Total: <?php echo number_format($amount, 2, '.', ','); ?></h4>
                    <h4>Identificador de tu pago: <?php echo e($referencia); ?></h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title">BBVA Multipagos Express</h5>
                    <form action="https://www.adquiramexico.com.mx:443/mExpress/pago/avanzado" method="post"/>
                        <input type="hidden" name="importe" value="<?php echo e($amount); ?>"/>
                        <input type="hidden" name="referencia" value="<?php echo e($referencia); ?>"/>
                        <input type="hidden" name="urlretorno" value="http://portalmx.zeiss.com/admin/facturas/comprobante-pago"/>
                        <input type="hidden" name="idexpress" value="1809"/>
                        <input type="hidden" name="financiamiento" value="0"/>
                        <input type="hidden" name="plazos" value=""/>
                        <input type="hidden" name="mediospago" value="110000"/>
                        <input type="hidden" name="signature" value="<?php echo e($signature); ?>"/>
                        <input type="image" src="https://dicff9jl33o1o.cloudfront.net/verticales/bexpress/resources/img/icon/paybutton_4.png" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/payment_invoice.blade.php ENDPATH**/ ?>