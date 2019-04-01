<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($distrito, $formData) !!}	
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group col-12 col-md-12">
			{!! Form::label('cboDepartamento', 'Deaprtamento: ', array('class' => 'aval')) !!}
			{!! Form::select('cboDepartamento', $cboDepartamentos, $distrito != null? $distrito->provincia->departamento_id: 0, array('class' => 'form-control input-sm', 'id' => 'cboDepartamento')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
			{!! Form::label('cboProvincia', 'Provincia: ', array('class' => 'aval')) !!}
			{!! Form::select('cboProvincia', $cboProvincias, $distrito != null? $distrito->provincia_id: 0, array('class' => 'form-control input-sm', 'id' => 'cboProvincia')) !!}
	</div>
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
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