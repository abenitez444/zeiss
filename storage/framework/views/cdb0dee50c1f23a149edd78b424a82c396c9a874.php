<div class="modal fade" id="modal-change-<?php echo e($cat->factura_id); ?>">
    <form action="<?php echo e(url('/admin/facturas/cancel/'.$cat->factura_id)); ?>" method="POST">
		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal">
						<span aria-hidden>x</span>
					</button-->
                    
                    <h4 class="modal-title">Cambiar Estado de Factura</h4>
				</div>
				<div class="modal-body">
                    <select class="form-control" id="estado" name="estado">
                        <option value="1">Pagado</option>
                        <option value="2">Pendiente</option>
                        <option value="3">Cancelado</option>
                        <option value="4">Validado</option>
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

<div class="modal fade" id="modal-delete-<?php echo e($cat->factura_id); ?>">
	<form action="<?php echo e(route('facturas.destroy', $cat->factura_id)); ?>" method="POST">
		<?php echo e(method_field('DELETE')); ?>

		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal">
						<span aria-hidden>x</span>
					</button-->
					<h4 class="modal-title">Eliminar Factura</h4>
				</div>
				<div class="modal-body">
					<p>
						Confirme si desea eliminar: <strong><?php echo e($cat->nombre_factura); ?></strong>
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
<?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/modal.blade.php ENDPATH**/ ?>