<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($persona, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('comision_acum', 'Comisión acumulada:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('comision_acum', null, array('class' => 'form-control input-xs', 'id' => 'comision_acum', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-6 col-md- col-sm-6" style ="padding-top: 15px">
		{!! Form::label('montopagar', 'Monto a pagar:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('montopagar', null, array('class' => 'form-control input-xs', 'id' => 'montopagar', 'placeholder' => '0.00')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="glyphicon glyphicon-usd"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('400');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$("#montopagar").blur(function() {
		if($('#montopagar').val() != ""){
			var montopagar = parseFloat($('#montopagar').val());
		}else{
			var montopagar = 0.00;
		}
		var comision_acum = parseFloat($("#comision_acum").val());
		evaluarcomisionpagar(montopagar, comision_acum);
	});

}); 
</script>