<div class="modal fade" id="modal-delete-{{$pago->id}}">
	<form action="{{ route('pagos.destroy', $pago->id) }}" method="POST">
		{{ method_field('DELETE') }}
		{{ csrf_field() }}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal">
						<span aria-hidden>x</span>
					</button-->
					<h4 class="modal-title">Eliminar Pago</h4>
				</div>
				<div class="modal-body">
					<p>
						Confirme si desea eliminar pago <strong>#{{ $pago->referencia }}</strong>
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

<div class="modal fade" id="modal-change-{{$pago->id}}">
	<form action="{{ route('pagos.validation', $pago->id) }}" method="GET">
		{{ csrf_field() }}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal">
						<span aria-hidden>x</span>
					</button-->
					<h4 class="modal-title">Validar Pago</h4>
				</div>
				<div class="modal-body">
					<p>
						Confirme si desea validar el pago <strong>#{{ $pago->referencia }}</strong>
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
