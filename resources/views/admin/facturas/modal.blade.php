<div class="modal fade" id="modal-change-{{$cat->factura_id}}">
    <form action="{{ url('/admin/facturas/cancel/'.$cat->factura_id) }}" method="POST">
		{{ csrf_field() }}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal">
						<span aria-hidden>x</span>
					</button-->
                    {{--  <h4 class="modal-title">Desactivar Factura</h4>  --}}
                    <h4 class="modal-title">Cambiar Estado de Factura</h4>
				</div>
				<div class="modal-body">
                    <select class="form-control" id="estado" name="estado">
                        <option value="1">Pagado</option>
                        <option value="2">Pendiente</option>
                        <option value="3">Cancelado</option>
                    </select>
					{{--  <p>
						Confirme si desea desactivar: <strong>{{ $cat->nombre_factura }}</strong>
					</p>  --}}
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

<div class="modal fade" id="modal-delete-{{$cat->factura_id}}">
	<form action="{{ route('facturas.destroy', $cat->factura_id) }}" method="POST">
		{{ method_field('DELETE') }}
		{{ csrf_field() }}
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
						Confirme si desea eliminar: <strong>{{ $cat->nombre_factura }}</strong>
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
