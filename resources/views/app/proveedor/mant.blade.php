<script>
	function cargarselect2(entidad){
		padre = 'provincia';
		if(entidad == 'provincia'){
			padre = 'departamento';
		}

		var select = $('#' + padre + '_id').val();

		if(select == '' && entidad == 'provincia'){
			$('#provincia_id').html('<option value="" selected="selected">Seleccione</option>');
			$('#distrito_id').html('<option value="" selected="selected">Seleccione</option>');
    	}

		route = 'proveedor/cargarselect/' + select + '?entidad=' + entidad + '&t=si';

		$.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			beforeSend: function() {
				$('#' + entidad + '_id').html('<option value="" selected="selected">Seleccione</option>');
	        	if(padre == 'departamento'){
					$('#distrito_id').html('<option value="" selected="selected">Seleccione</option>');
	        	}
			},
	        success: function(res){
	        	$('#' + entidad + '_id').html(res);
	        	if(padre == 'departamento'){
					$('#distrito_id').html('<option value="" selected="selected">Seleccione</option>');
	        	}
	        }
		});
	}
</script>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($proveedor, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('nombre', 'Nombre:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese Nombre o Razon Social')) !!}
	</div>
</div>

<div class="form-group col-sm-12">
	{!! Form::label('ruc', 'Ruc:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese Direccion')) !!}
	</div>
</div>
<div class="form-group col-sm-12">
	{!! Form::label('direccion', 'Direccion:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese Direccion')) !!}
	</div>
</div>


<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('persona_contacto', 'Persona de Contacto:') !!}
	</div>
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('persona_contacto', null, array('class' => 'form-control input-xs', 'id' => 'persona_contacto', 'placeholder' => 'Ingrese Persona de Contacto')) !!}
	</div>
</div>

<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('celular', 'Telf/Cel:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-5 col-md-5 col-xs-12">
		{!! Form::text('telefono', null, array('class' => 'form-control input-xs input-number', 'id' => 'telefono', 'placeholder' => 'Ingrese Telefono', 'maxlength' => '15')) !!}
	</div>

	<div class="col-sm-4 col-md-4 col-xs-12">
		{!! Form::text('celular', null, array('class' => 'form-control input-xs input-number', 'id' => 'celular', 'placeholder' => 'Ingrese Celular', 'maxlength' => '9')) !!}
	</div>
</div>

<div class="form-group col-sm-12">
	{!! Form::label('estado', 'Estado:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::select('estado', $cboEstado, null, array('class' => 'form-control input-xs', 'id' => 'estado')) !!}
	</div>
</div>

<div class="form-group col-sm-12">
	{!! Form::label('departamento_id', 'Departamento:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::select('departamento_id', $cboDepartamento, null, array('class' => 'form-control input-xs', 'id' => 'departamento_id', 'onchange' => 'cargarselect2("provincia")')) !!}
	</div>
</div>

<div class="form-group col-sm-12">
	{!! Form::label('provincia_id', 'Provincia:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::select('provincia_id', $cboProvincia, null, array('class' => 'form-control input-xs', 'id' => 'provincia_id', 'onchange' => 'cargarselect2("distrito")')) !!}
	</div>
</div>

<div class="form-group col-sm-12">
	{!! Form::label('distrito_id', 'Distrito:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::select('distrito_id', $cboDistrito, null, array('class' => 'form-control input-xs', 'id' => 'distrito_id')) !!}
	</div>
</div>


<div class="form-group">
	<div class="col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarProveedor', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>

{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nombre"]').focus();
		configurarAnchoModal('500');

		$('.input-number').on('input', function () { 
				this.value = this.value.replace(/[^0-9]/g,'');
		});

	}); 
 
</script>