
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}
	<div class="row">
		<div class="form-group">
			<div class="col-3 col-lg-3 col-md-3 col-sm-12 col-xs-12" >
				{!! Form::label('codigo', 'Codigo:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::text('codigo', null , array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese Codigo', 'maxlength' => '22')) !!}
			</div>
			<div class="col-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
				{!! Form::label('nombre', 'Nombre:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::text('nombre', null, array('class' => 'form-control input-xs input-number', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre...')) !!}
			</div>
			<div class="col-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
				{!! Form::label('cantidad', 'Cantidad:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::number('cantidad', null, array('class' => 'form-control input-xs input-number', 'id' => 'cantidad', 'placeholder' => 'Ingrese cantidad...')) !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
				{!! Form::label('precio_venta', 'Precio de Venta:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::number('precio_venta', null, array('class' => 'form-control input-xs input-number', 'id' => 'precio_venta', 'placeholder' => 'Ingrese precio v.')) !!}
			</div>
			<div class="col-3 col-lg-3 col-md-3 col-sm-12 col-xs-12" >
				{!! Form::label('fecha_llegada', 'Fecha de LLegada:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::date('fecha_llegada', null , array('class' => 'form-control input-xs', 'id' => 'fecha_llegada', 'placeholder' => 'Ingrese Codigo', 'maxlength' => '22')) !!}
			</div>
			<div class="col-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
				{!! Form::label('fecha_caducidad', 'Fecha de Caducidad:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::date('fecha_caducidad', null, array('class' => 'form-control input-xs input-number', 'id' => 'fecha_caducidad', 'placeholder' => 'Ingrese nombre...')) !!}
			</div>
			<div class="col-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
				{!! Form::label('sitio', 'Sitio:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::text('sitio', null, array('class' => 'form-control input-xs input-number', 'id' => 'sitio', 'placeholder' => 'sitio...')) !!}
			</div>
		</div>
		<?php
			if($producto != null){
				echo "<input type='hidden' id='fechaTempLleg' value='".Date::parse($producto->fecha_llegada )->format('d/m/Y')."'>";
				echo "<input type='hidden' id='fechaTempCad' value='".Date::parse($producto->fecha_caducidad)->format('d/m/Y')."'>";
			}else{
				echo "<input type='hidden' id='fechaTempLleg' value=''>";
				echo "<input type='hidden' id='fechaTempCad' value=''>";
			}
		?>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
				{!! Form::label('marca_id', 'Marca/Laboratorio:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::select('marca_id', $cboMarca, null, array('class' => 'form-control input-xs', 'id' => 'marca_id')) !!}
			</div>
			<div class="col-4 col-lg-4 col-md-4 col-sm-12 col-xs-12" >
				{!! Form::label('unidad_id', 'Unidad:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::select('unidad_id', $cboUnidad, null, array('class' => 'form-control input-xs', 'id' => 'unidad_id')) !!}
			</div>
			<div class="col-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
				{!! Form::label('categoria_id', 'Categoria:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::select('categoria_id', $cboCategoria, null, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
				{!! Form::label('proveedor_id', 'Proveedor:') !!}<div class="" style="display: inline-block;color: red;">*</div>
				{!! Form::select('proveedor_id', $cboMarca, null, array('class' => 'form-control input-xs', 'id' => 'proveedor_id')) !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
				{!! Form::label('descripcion', 'Descripcion:') !!}
				{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion')) !!}
			</div>
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
	configurarAnchoModal('750');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	var fechaActual = new Date();
	var day = ("0" + fechaActual.getDate()).slice(-2);
	var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
	var fecha = (fechaActual.getFullYear()) +"-"+month+"-"+day+"";
	$('#fecha_llegada').val(fecha);
	$('#fecha_caducidad').val(fecha);

	if($('#fechaTempCad').val() !== ""){
		// DD/MM/YYYY
		var valores_fecha_llegada = $('#fechaTempLleg').val().split('/');
		var valores_fecha_caducidad = $('#fechaTempCad').val().split('/');
		//yyy/MM/DD
		var fecha_lleg = valores_fecha_llegada[2] + "-" + valores_fecha_llegada[1] + "-" + valores_fecha_llegada[0];
		var fecha_cad = valores_fecha_caducidad[2] + "-" + valores_fecha_caducidad[1] + "-" + valores_fecha_caducidad[0];
		$('#fecha_llegada').val(fecha_lleg);
		$('#fecha_caducidad').val(fecha_cad);
	}else{
		$('#fecha_llegada').val(fecha);
		$('#fecha_caducidad').val(fecha);
	}

	$('.input-number').on('input', function () { 
    	this.value = this.value.replace(/[^0-9]/g,'');
	});

	$('#marca_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listmarcas'], array()) }}",
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

	$('#unidad_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listunidades'], array()) }}",
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

	$('#categoria_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listcategorias'], array()) }}",
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

	$('#proveedor_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listproveedores'], array()) }}",
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