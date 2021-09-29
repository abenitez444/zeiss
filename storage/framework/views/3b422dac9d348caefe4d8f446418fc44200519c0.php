<?php $__env->startSection('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">


	<div class="row">
		<div class="col-md-12 col-xs-12">
			<?php if(count($errors)>0): ?>
			<div class="alert alert-danger">
				<ul>
					<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			</div>
            <?php endif; ?>


			<form action="<?php echo e(route('productos.store')); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" id="token">

				<div class="col-md-6">
					<div class="form-group">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo e(old('nombre')); ?>" required>
					</div>
					<div class="form-group">
						<label for="codigo">Código</label>
						<input type="text" name="codigo" class="form-control" placeholder="Código" value="<?php echo e(old('codigo')); ?>" required>
					</div>
					<div class="form-group">
						<label for="stock">Stock</label>
						<input type="number" min="0" name="stock" class="form-control" placeholder="Stock" value="<?php echo e(old('stock')); ?>" required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="categorias_id">Categoría</label>
						<select name="categorias_id" class="form-control">
							<?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($cat->id); ?>"><?php echo e($cat->nombre); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>

					<div class="form-group">
						<label for="puntos">Puntos</label>
						<input type="number" min="0" name="puntos" class="form-control" placeholder="Puntos" value="<?php echo e(old('puntos')); ?>" required>
					</div>

					<div class="form-group">
						<label for="descripcion">Descripción</label>
						<input type="text" name="descripcion" class="form-control" placeholder="Descripción" value="<?php echo e(old('descripcion')); ?>" required>
					</div>

					<div class="form-group">
						<label for="estado">Estado</label>
						<select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
						</select>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<button class="btn btn-primary" type="submit">
							Guardar
						</button>
                        <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-danger" >Cancelar</a>
					</div>
				</div>
			</form>

		</div>
	</div>


</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/productos/create.blade.php ENDPATH**/ ?>