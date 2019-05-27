<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($medico, $formData) !!}	
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

	<div class="form-group col-6 col-md-6">
		{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}<div class="" style="display: inline-block;color: red;">*</div>
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}<div class="" style="display: inline-block;color: red;">*</div>
		{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
	</div>

	<div class="form-group col-6 col-md-6" >
		{!! Form::label('codigo', 'Código:', array('class' => ' control-label')) !!}<div class="" style="display: inline-block;color: red;">*</div>
		{{-- {!! Form::text('codigo', $medico != null?($cliente->dni == null? $cliente->ruc:$cliente->dni): '', array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese código de medico')) !!} --}}
		{!! Form::text('codigo', null, array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese código de medico')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('dni', 'DNI:', array('class' => ' control-label')) !!}
		{!! Form::text('dni', null, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI')) !!}
	</div>

	<div class="form-group col-6 col-md-6" >
		{!! Form::label('telefono', 'Teléfono:', array('class' => 'control-label')) !!}
			{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese numero telefono')) !!}
	</div>

	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('direccion', 'Direccion:', array('class' => ' control-label')) !!}
			{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion')) !!}
	</div>

	
<div class="form-group ">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarMedico', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelarMedico'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('650');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$("#modal"+(contadorModal - 1)).on('hidden.bs.modal', function () {
		$('.modal' + (contadorModal-2)).css('pointer-events','auto'); 
	});
}); 
</script>