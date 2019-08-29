<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::open(['route' => $ruta["buscardpresentacion"], 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusquedaProductoPresentacion']) !!}
	{!! Form::hidden('idpresentacion', $id, array('id' => 'idpresentacion')) !!}
	{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo',array('class'=>'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'id'=>'btnNuevo','onclick'=>'modal(\''.URL::route($ruta["nuevapresentacion"], array('listar'=>'SI', 'idproducto'=>$id)).'\',\''.$titulo_registrarpresentacion.'\',this);')) !!}
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
