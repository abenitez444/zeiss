<div class="modal fade" id="modal-delete-<?php echo e($cat->id); ?>">
	<form action="<?php echo e(url('/admin/complements/delete/'.$cat->id)); ?> }}" method="POST">
		<?php echo e(csrf_field()); ?>

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal">
						<span aria-hidden>x</span>
					</button-->
					<h4 class="modal-title">Eliminar Complemento</h4>
				</div>
				<div class="modal-body">
					<p>
						Confirme si desea eliminar: <strong><?php echo e($cat->name); ?></strong>
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
<?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/facturas/complement-modal.blade.php ENDPATH**/ ?>