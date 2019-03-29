
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}

	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
			{!! Form::label('descripcion', 'Descripcion:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese Descripcion')) !!}
		</div>
	</div>

	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('precioventa', 'Precio Venta:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('precioventa', null, array('class' => 'form-control input-xs', 'id' => 'precioventa', 'placeholder' => 'Ingrese Precio de Venta')) !!}
		</div>
	</div>	
	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('marca_id', 'Marca:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::select('marca_id', $cboMarca, null, array('class' => 'form-control input-xs', 'id' => 'marca_id')) !!}
		</div>
	</div>

	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('unidad_id', 'Unidad:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::select('unidad_id', $cboUnidad, null, array('class' => 'form-control input-xs', 'id' => 'unidad_id')) !!}
		</div>
	</div>

	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('categoria_id', 'Categoria:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::select('categoria_id', $cboCategoria, null, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
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
	configurarAnchoModal('520');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>