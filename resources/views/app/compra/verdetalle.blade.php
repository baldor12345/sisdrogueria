<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::open(['route' => $ruta["buscard"], 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusquedaCompra1']) !!}
	{!! Form::hidden('idc', $id, array('id' => 'idc')) !!}
	{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
	<div class="row">	
		<div class=" card-box">  
			<table>
				<tr>
					<td width="20%"><strong> DOC:</strong></td>
					<td width="30%">{!! Form::select('tipo', $cboDocumento, $compra->documento, array('class' => ' input-sm', 'id' => 'tipo', 'disabled')) !!}</td>
					<td width="20%"><strong> N° DOC.:</strong></td>
					<td width="30%">{{ $compra->numero_documento }}</td>
				</tr>
				<tr>
					<td width="20%"><strong> CRÉDITO:</strong></td>
					<td width="30%">{!! Form::select('credito', $cboCredito, $compra->credito, array('class' => 'input-sm', 'id' => 'credito')) !!}</td>
					<td width="20%"><strong> N° DIAS:</strong></td>
					<td width="30%">{{ $proveedor->numero_dias or '0'}}</td>
				</tr>

				<tr>
					<td width="20%"><strong> FECHA:</strong></td>
					<td width="30%">{{ $compra->fecha }}</td>
					<td width="20%"><strong> TOTAL:</strong></td>
					<td width="30%">{{ $compra->total.'       '}} <strong> IGV: </strong>{{ $compra->igv }}</td>
				</tr>
				<tr>
					<td width="20%" colspan="1"><strong> PROVEEDOR:</strong></td>
					<td width="80%" colspan="3">{{ $proveedor->nombre }}</td>
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
