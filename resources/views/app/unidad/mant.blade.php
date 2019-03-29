<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($unidad, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('name', 'Nombre:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('name', null, array('class' => 'form-control input-xs', 'id' => 'name', 'placeholder' => 'Ingrese nombre')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('450');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>