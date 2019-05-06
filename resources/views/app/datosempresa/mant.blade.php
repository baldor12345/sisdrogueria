
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($empresa, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

	<div class="form-group">
			{!! Form::label('ruc', 'RUC:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
		<div class="col-sm-9 col-xs-12">
			{!! Form::text('ruc', $empresa->ruc, array('class' => 'form-control input-sm', 'id' => 'ruc', 'placeholder' => '')) !!}
		</div>
	</div>
	<div class="form-group">
			{!! Form::label('razon_social', 'Razon Social:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
		<div class="col-sm-9 col-xs-12">
			{!! Form::text('razon_social', $empresa->razon_social, array('class' => 'form-control input-sm', 'id' => 'razon_social', 'placeholder' => '')) !!}
		</div>
	</div>
	<div class="form-group">
			{!! Form::label('direccion', 'Dirección:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
		<div class="col-sm-9 col-xs-12">
			{!! Form::text('direccion', $empresa->direccion, array('class' => 'form-control input-sm', 'id' => 'direccion', 'placeholder' => '')) !!}
		</div>
	</div>

	<div class="form-group">
			{!! Form::label('telefono', 'Telefono:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
		<div class="col-sm-9 col-xs-12">
			{!! Form::text('telefono', $empresa->telefono, array('class' => 'form-control input-sm', 'id' => 'telefono', 'placeholder' => '')) !!}
		</div>
	</div>
	<div class="form-group">
			{!! Form::label('email', 'Email:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
		<div class="col-sm-9 col-xs-12">
			{!! Form::text('email', $empresa->email, array('class' => 'form-control input-sm', 'id' => 'email', 'placeholder' => '')) !!}
		</div>
	</div>

	<div class="form-group col-12 col-md-12">
			{!! Form::label('departamento_id', 'Departamento: ', array('class' => '')) !!}
			{!! Form::select('departamento_id', $cboDepartamentos, array('class' => 'form-control input-sm', 'id' => 'departamento_id')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('provincia_id', 'Provincia: ', array('class' => '')) !!}
		{!! Form::select('provincia_id', $cboProvincias, array('class' => 'form-control input-sm', 'id' => 'provincia_id')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('distrito_id', 'Distrito: ', array('class' => '')) !!}
		{!! Form::select('distrito_id', $cboDistritos, array('class' => 'form-control input-sm', 'id' => 'distrito_id')) !!}
	</div> 


<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="usertype_id"]').focus();
		configurarAnchoModal('450');

	// $('#departamento_id').change(function(){
	// 	$.get("provincia/"+$(this).val()+"",function(response, facultad){//
	// 		$('#provincia_id').empty();
	// 		var cantidad = response[0];
	// 		var listProvincias = response[1];
	// 		var cboProvincias = '<option value="0">Seleccione</option>';
	// 		for(var i=0; i<cantidad; i++){
	// 			cboProvincias +='<option value="'+listProvincias[i].id+'">'+listProvincias[i].nombre+'</option>'
	// 		}
	// 		$('#provincia_id').append(cboProvincias);
	// 	});
	// });

	// $('#provincia_id').change(function(){
	// 	$.get("distrito/"+$(this).val()+"",function(response, facultad){//
	// 		$('#distrito_id').empty();
	// 		var cantidad = response[0];
	// 		var listDistritos = response[1];
	// 		var cboDistritos = '<option value="0">Seleccione</option>';
	// 		for(var i=0; i<cantidad; i++){
	// 			cboDistritos +='<option value="'+listDistritos[i].id+'">'+listDistritos[i].nombre+'</option>'
	// 		}
	// 		$('#distrito_id').append(cboDistritos);
	// 	});
	// });

	}); 

	
</script>