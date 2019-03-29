<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($modelo, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}



@if($modelo != null)
	@if($modelo->razonsocial !=null)
		<p><strong>Razón Social:</strong><p>{{ $modelo->razonsocial }}</p>
	@else
		<p><strong>Nombres y Apellidos:</strong> {{ $modelo->nombres . ' ' . $modelo->apellidos }}</p>
	@endif
	@if($modelo->dni != null )
		<p><strong>Documento:</strong> {{ $modelo->dni }}</p>
	@else
		<p><strong>Documento:</strong> {{ $modelo->ruc }}</p>
	@endif
	@if($modelo->direccion != null)
		<p><strong>Dirección:</strong> {{ $modelo->direccion }}</p>
	@endif
	@if($modelo->telefono != null)
		<p><strong>Teléfono:</strong> {{ $modelo->telefono }}</p>
	@endif
	@if($modelo->celular != null)
		<p><strong>Celular:</strong> {{ $modelo->celular }}</p>
	@endif
	@if($modelo->email != null)
		<p><strong>e-mail:</strong> {{ $modelo->email }}</p>
	@endif
	@if($modelo->fechanacimiento != null)
		<p><strong>Fecha de Nacimiento:</strong> {{ date("d/m/Y", strtotime( $modelo->fechanacimiento))  }}</p>
	@endif
	@if($modelo->distrito_id != null )
		<p><strong>Ubicación:</strong> {{ $departamento->nombre . ' - ' . $provincia->nombre . ' - ' . $distrito->nombre  }}</p>
	@endif 
@endif


@if($entidad =="Persona")
<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
	{!! Form::label('roles', 'Roles:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-4 col-xs-12">
		<input type="checkbox" id="cliente" name="cliente" value="C"><label for="cliente"> Cliente</label><br>
		<input type="checkbox" id="proveedor" name="proveedor" value="P"><label for="proveedor"> Proveedor</label><br>
	</div>
	<div id="comisionhtml" class="col-sm-4 col-xs-12">
	
	</div>
</div>
@endif


{!! $mensaje or '<blockquote><p class="text-dark">Tenemos un registro con este documento, ¿Desea registrarlo?</p></blockquote>' !!}
<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> Registrar', array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardarrepetido()')) !!}
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal((contadorModal - 1));')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		configurarAnchoModal('400');
	}); 

	function guardarrepetido(){

		var respuesta ="";

		var id = '{{$modelo->id}}' ;

		var entidad = '{{$entidad}}';

		@if($entidad == "cliente")

			var ajax = $.ajax({
				"method": "POST",
				"url": "{{ url('/cliente/guardarrepetido') }}",
				"data": {
					"persona_id" : id, 
					"_token": "{{ csrf_token() }}",
					}
			}).done(function(info){
				respuesta = info;
			}).always(function(){
				cerrarModal();
				repetido(entidad);
			});
		
		@elseif($entidad == "Proveedor")

			var ajax = $.ajax({
				"method": "POST",
				"url": "{{ url('/proveedor/guardarrepetido') }}",
				"data": {
					"persona_id" : id, 
					"_token": "{{ csrf_token() }}",
					}
			}).done(function(info){
				respuesta = info;
			}).always(function(){
				cerrarModal();
				repetido(entidad);
			});

		@elseif($entidad == "Trabajador")

			var ajax = $.ajax({
				"method": "POST",
				"url": "{{ url('/trabajador/guardarrepetido') }}",
				"data": {
					"persona_id" : id, 
					"_token": "{{ csrf_token() }}",
					}
			}).done(function(info){
				respuesta = info;
			}).always(function(){
				cerrarModal();
				repetido(entidad);
			});

		@elseif($entidad == "Persona")
			var type = null;
			var secondtype = null;
			if( $("#cliente").is(':checked')){
				type = "C";
			}
			if( $("#proveedor").is(':checked')){
				secondtype = "P";
			}
			var ajax = $.ajax({
				"method": "POST",
				"url": "{{ url('/caja/guardarrepetido') }}",
				"data": {
					"persona_id" : id, 
					"_token": "{{ csrf_token() }}",
					'type': type,
					'secondtype': secondtype,
					}
			}).done(function(info){
				respuesta = info;
			}).always(function(){
				cerrarModal();
				repetido(entidad);
			});
		@endif


	}

</script>