<?php $__env->startSection('css_role_page'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

	<h2>
		Editar los puntos: <?php echo e($punto->puntos); ?>

	</h2>

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

			<form action="<?php echo e(route('puntos.update', $punto->id)); ?> " method="POST">
				<!-- <input name="_method" type="hidden" value="PUT"> -->
				<?php echo e(method_field('PUT')); ?>

				<?php echo e(csrf_field()); ?>


				<div class="form-group">
					<label for="nombre">Puntos</label>
					<input type="text" name="puntos" class="form-control"
						value="<?php echo e($punto->puntos); ?>"
						placeholder="<?php echo e($punto->puntos); ?>">
                </div>
                <div class="form-group">
					<label for="factura_id">Facturas</label>
					<select class='form-control mi-selector' name='factura_id' required>
                        <option value=''>Seleccione una factura</option>
                        <?php $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value='<?php echo e($factura->id); ?>' <?php echo e(($factura->id == $punto->factura[0]->id) ? 'selected' : ''); ?>><?php echo e($factura->numero_factura.' - '.$factura->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">
						Guardar
					</button>
					<a href="<?php echo e(route('puntos.index')); ?>" class="btn btn-danger" >Cancelar</a>
				</div>
			</form>

		</div>
	</div>


</div>

<?php $__env->startSection('js_user_page'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.mi-selector').select2();
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/puntos/edit.blade.php ENDPATH**/ ?>