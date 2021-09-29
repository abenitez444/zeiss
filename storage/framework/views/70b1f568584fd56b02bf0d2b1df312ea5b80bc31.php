<div class="modal fade" id="modal-change-<?php echo e($art->id); ?>">
    <form action="<?php echo e(url('/admin/productos/change/'.$art->id)); ?>" method="POST">
		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
                    <h4 class="modal-title">Cambiar Estado de Producto</h4>
				</div>
				<div class="modal-body">
                    <select class="form-control" id="estado" name="estado">
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                    </select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cerrar
					</button>
					<button type="submit" class="btn btn-primary">Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modal-delete-<?php echo e($art->id); ?>">
	<form action="<?php echo e(route('productos.destroy', $art->id)); ?>" method="POST">
		<?php echo e(method_field('DELETE')); ?>

		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Eliminar Producto</h4>
				</div>
				<div class="modal-body">
					<p>
						Confirme si desea eliminar: <strong><?php echo e($art->nombre); ?></strong>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cerrar
					</button>
					<button type="submit" class="btn btn-primary">Confirmar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/productos/modal.blade.php ENDPATH**/ ?>