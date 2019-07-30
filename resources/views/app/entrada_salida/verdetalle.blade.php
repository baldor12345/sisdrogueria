<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::open(['route' => $ruta["buscardes"], 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusquedaEntradaSalida1']) !!}
	{!! Form::hidden('ides', $id, array('id' => 'ides')) !!}
	{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}

	<div class="row">	
		<div class="card-box"> 
			<table>
				<tr>
					<td width="20%"><strong>TIPO:</strong></td>
					<td width="30%">{!! Form::select('tipo', $cboTipo, $entrada_salida->tipo, array('class' => ' input-sm', 'id' => 'tipo', 'disabled')) !!}</td>
					<td width="20%"><strong>NUMERO DOC.:</strong></td>
					<td width="30%">{{ $entrada_salida->num_documento }}</td>
				</tr>
				<tr>
					<td width="20%"><strong>FECHA:</strong></td>
					<td width="30%">{{ $fecha }}</td>
					<td width="20%"><strong>COMENTARIO:</strong></td>
					<td width="30%">{{ $entrada_salida->comentario }}</td>
				</tr>
			</table>  	
		</div>				
	</div>
{!! Form::close() !!}

<div id="listado{{ $entidad }}"></div>

<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1000');
	buscar("{{ $entidad }}");
	init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
}); 
</script>
