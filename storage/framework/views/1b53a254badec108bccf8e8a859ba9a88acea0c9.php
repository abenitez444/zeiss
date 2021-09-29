<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">


	<h1>
		Cargar Complementos de Pago
	</h1>


	<div class="row">
		<div class="col-md-6 col-xs-12">
			<?php if(count($errors)>0): ?>
			<div class="alert alert-danger">
				<ul>
					<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			</div>
			<?php endif; ?>

			<form action="<?php echo e(url('/admin/facturas/complemento-pago/'.$factura->id)); ?>" method="POST"  enctype="multipart/form-data">

				<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">

				<div class="form-group">
					<label for="imagen">Archivos de complemento</label>
                    <input type="file" name="uploadfile[]" multiple required>
				</div>
				<input type="hidden" name="user_id" value="<?php echo e(Auth::user()->id); ?>" >
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="<?php echo e(route('facturas.index')); ?>" class="btn btn-danger" >Cancelar</a>
				</div>
			</form>

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

		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/upload_complement.blade.php ENDPATH**/ ?>