<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($servicio, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
	<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('descripcion', 'Descripción:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'name', 'placeholder' => 'Ingrese descripción')) !!}
		</div>
	</div>
	<div class="form-group">
	<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('precio', 'Precio:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('precio', null, array('class' => 'form-control input-xs', 'id' => 'name', 'placeholder' => 'Ingrese precio')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('tipocomision', 'Tipo de Comisión:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			@if($servicio == null)
			<select id="tipocomision" name="tipocomision" class="form-control input-xs">
				<option disabled selected>SELECCIONE TIPO DE COMISIÓN</option>
				<option value="0">PORCENTAJE ( % )</option>
				<option value="1">MONTO (S/.)</option>
			</select>
			@else
				@if($servicio->tipo_comision == 0)
				<select id="tipocomision" name="tipocomision" class="form-control input-xs">
					<option disabled >SELECCIONE TIPO DE COMISIÓN</option>
					<option selected value="0">PORCENTAJE ( % )</option>
					<option value="1">MONTO (S/.)</option>
				</select>
				@elseif($servicio->tipo_comision == 1)
				<select id="tipocomision" name="tipocomision" class="form-control input-xs">
					<option disabled >SELECCIONE TIPO DE COMISIÓN</option>
					<option value="0">PORCENTAJE ( % )</option>
					<option selected value="1">MONTO (S/.)</option>
				</select>
				@endif
			@endif
		</div>
	</div>
	<div class="form-group">
	<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('comision', 'Comisión:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('comision', null, array('class' => 'form-control input-xs', 'id' => 'name', 'placeholder' => 'Ingrese comisión')) !!}
		</div>
	</div>


	<div class="form-group">
		{!! Form::label('frecuente', 'Frecuente:', array('class' => 'col-sm-4 col-xs-12 control-label')) !!}
		<div class="col-sm-8 col-xs-12">
		
		@if($servicio == null)
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="frecuente" id="frecuentesi" value="1">
				<label class="form-check-label" for="frecuentesi">SI</label>
			</div>
			<div class="form-check form-check-inline">
				<input checked class="form-check-input" type="radio" name="frecuente" id="frecuenteno" value="0">
				<label class="form-check-label" for="frecuenteno">NO</label>
			</div>
		@else
			@if($servicio->frecuente == 1)
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="frecuente" id="frecuentesi" value="1">
					<label class="form-check-label" for="frecuentesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="frecuente" id="frecuenteno" value="0">
					<label class="form-check-label" for="frecuenteno">NO</label>
				</div>
			@elseif($servicio->frecuente == 0)
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="frecuente" id="frecuentesi" value="1">
					<label class="form-check-label" for="frecuentesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="frecuente" id="frecuenteno" value="0">
					<label class="form-check-label" for="frecuenteno">NO</label>
				</div>
			@endif
		@endif
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('editable', 'Precio editable:', array('class' => 'col-sm-4 col-xs-12 control-label')) !!}
		<div class="col-sm-8 col-xs-12">
		
		@if($servicio == null)
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="editable" id="editablesi" value="1">
				<label class="form-check-label" for="editablesi">SI</label>
			</div>
			<div class="form-check form-check-inline">
				<input checked class="form-check-input" type="radio" name="editable" id="editableno" value="0">
				<label class="form-check-label" for="editableno">NO</label>
			</div>
		@else
			@if($servicio->editable == 1)
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="editable" id="editablesi" value="1">
					<label class="form-check-label" for="editablesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="editable" id="editableno" value="0">
					<label class="form-check-label" for="editableno">NO</label>
				</div>
			@elseif($servicio->editable == 0)
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="editable" id="editablesi" value="1">
					<label class="form-check-label" for="editablesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="editable" id="editableno" value="0">
					<label class="form-check-label" for="editableno">NO</label>
				</div>
			@endif
		@endif
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
	configurarAnchoModal('500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>