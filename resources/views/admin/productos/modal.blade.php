<div class="modal fade" id="modal-change-{{$art->id}}">
    <form action="{{ url('/admin/productos/change/'.$art->id) }}" method="POST">
		{{ csrf_field() }}
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

<div class="modal fade" id="modal-delete-{{$art->id}}">
	<form action="{{ route('productos.destroy', $art->id) }}" method="POST">
		{{ method_field('DELETE') }}
		{{ csrf_field() }}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Eliminar Producto</h4>
				</div>
				<div class="modal-body">
					<p>
						Confirme si desea eliminar: <strong>{{ $art->nombre }}</strong>
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

