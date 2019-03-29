@php 
$nombrepersona = NULL;
if (!is_null($usuario)) {
	$persona = $usuario->persona->personamaestro;
	$nombrepersona = $persona->apellidos.', '.$persona->nombres;
}
@endphp
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($usuario, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
	{!! Form::label('usertype_id', 'Tipo de usuario:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::select('usertype_id', $cboTipousuario, null, array('class' => 'form-control input-xs', 'id' => 'usertype_id')) !!}
	</div>
</div>
<div class="form-group">
<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
	{!! Form::label('nombrepersona', 'Persona:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
	{!! Form::hidden('person_id', null, array('id' => 'person_id')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		@if(!is_null($usuario))
		{!! Form::text('nombrepersona', $nombrepersona, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		@else
		{!! Form::text('nombrepersona', $nombrepersona, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		@endif
	</div>
</div>
<div class="form-group">
<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
	{!! Form::label('login', 'Login:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('login', null, array('class' => 'form-control input-xs', 'id' => 'login', 'placeholder' => 'Ingrese login')) !!}
	</div>
</div>
<div class="form-group">
<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
	{!! Form::label('password', 'Contraseña:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::password('password', array('class' => 'form-control input-xs', 'id' => 'password', 'placeholder' => 'Ingrese contraseña')) !!}
	</div>
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
		configurarAnchoModal('600');


		var empleados = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			limit: 5,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'caja/empleadoautocompletar/%QUERY',
				filter: function (empleados) {
					return $.map(empleados, function (empleado) {
						return {
							value: empleado.value,
							id: empleado.id,
						};
					});
				}
			}
		});
		empleados.initialize();

		$('#nombrepersona').typeahead(null,{
			displayKey: 'value',
			source: empleados.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#person_id').val(datum.id);
		});

		
	}); 
</script>