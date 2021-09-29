<div class="modal fade" id="modal-change">
    <form id="change-status-form" action="" method="POST">
		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
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

<div class="modal fade" id="modal-delete">
	<form id="delete-status-form" action="" method="POST">
		<?php echo e(method_field('DELETE')); ?>

		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Eliminar Factura</h4>
				</div>
				<div class="modal-body">
					<p>
						<strong> ¿Desea eliminar la factura?</strong>
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
<?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/modal2.blade.php ENDPATH**/ ?>