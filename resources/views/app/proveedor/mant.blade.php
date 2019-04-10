
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

		$('#distrito_id').select2({
			dropdownParent: $("#modal"+(contadorModal-1)),
			
			minimumInputLenght: 2,
			ajax: {
				url: "{{ URL::route($ruta['listdistritos'], array()) }}",
				dataType: 'json',
				delay: 250,
				data: function(params){
					return{
						q: $.trim(params.term)
					};
				},
				processResults: function(data){
					return{
						results: data
					};
				}
				
			}
		});
	}); 
 
</script>