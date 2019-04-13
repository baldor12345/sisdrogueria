
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}
<div class="row">
	<fieldset class="col-md-6">    	
		<div class="panel panel-default">
			<div class="form-group">
				{!! Form::label('codigo', 'Codigo*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-4 col-xs-12">
					{!! Form::text('codigo', null, array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese codigo')) !!}
				</div>
				<div class="col-sm-5 col-xs-12">
					{!! Form::text('codigo_barra', null, array('class' => 'form-control input-xs', 'id' => 'codigo_barra', 'placeholder' => 'codigo barra Opcional')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('descripcion', 'Descripcion*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion')) !!}
				</div>
			</div>
			<div class="form-group ">
				{!! Form::label('sustancia_activa', 'Principio Activo*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::text('sustancia_activa', null, array('class' => 'form-control input-xs', 'id' => 'sustancia_activa', 'placeholder' => 'Ingrese sustancia activa')) !!}
				</div>
			</div>
			<div class="form-group ">
				{!! Form::label('uso_terapeutico', 'Uso Terapeutico*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::text('uso_terapeutico', null, array('class' => 'form-control input-xs', 'id' => 'uso_terapeutico', 'placeholder' => 'Ingrese uso terapeutico')) !!}
				</div>
			</div>
		</div>
	</fieldset>	
	
	<fieldset class="col-md-6">   
		<div class="panel panel-default">
			<div class="form-group">
				{!! Form::label('tipo', 'Tipo*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::select('tipo', $cboTipo, null, array('class' => 'form-control input-xs', 'id' => 'tipo')) !!}
				</div>
			</div>

			<div class="form-group ">
				{!! Form::label('proveedor_id', 'Proveedor:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::select('proveedor_id', $cboProveedor, null, array('class' => 'form-control input-xs', 'id' => 'proveedor_id')) !!}
				</div>
			</div>
	
			<div class="form-group ">
				{!! Form::label('marca_id', 'Marc/Lab:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::select('marca_id', $cboMarca, null, array('class' => 'form-control input-xs', 'id' => 'marca_id')) !!}
				</div>
			</div>
			<div class="form-group ">
				{!! Form::label('stock_minimo', 'Stock Min*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-2 col-xs-12">
					{!! Form::text('stock_minimo', null, array('class' => 'form-control input-xs input-number', 'id' => 'stock_minimo', 'placeholder' => '')) !!}
				</div>
				{!! Form::label('ubicacion', 'Ubicacion:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-4 col-xs-12">
					{!! Form::text('ubicacion', null, array('class' => 'form-control input-xs', 'id' => 'ubicacion', 'placeholder' => 'Ingrese ubicacion')) !!}
				</div>
			</div>
		</div>
	</fieldset>
</div>

<div class="row">
	<fieldset class="col-md-6">    	
		<div class="panel panel-default">
			<div class="form-group ">
				{!! Form::label('unidad_id', 'Unidad*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::select('unidad_id', $cboUnidad, null, array('class' => 'form-control input-xs', 'id' => 'unidad_id')) !!}
				</div>
			</div>

			<div class="form-group ">
				{!! Form::label('categoria_id', 'Categoria:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::select('categoria_id', $cboCategoria, null, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
				</div>
			</div>

		</div>
	</fieldset>	
	
	<fieldset > 
		<div class="panel panel-default">
			<div class="form-group">
				{!! Form::label('costo', ' Compra*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::text('costo', null, array('class' => 'form-control input-xs', 'id' => 'costo', 'placeholder' => 'Ingrese precio de compra', 'onkeypress'=>'return filterFloat(event,this);')) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('precio_publico', 'Venta*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
				<div class="col-sm-9 col-xs-12">
					{!! Form::text('precio_publico', null, array('class' => 'form-control input-xs', 'id' => 'precio_publico', 'placeholder' => 'Ingrese precio de venta', 'onkeypress'=>'return filterFloat(event,this);')) !!}
				</div>
			</div>
		</div>
	</fieldset>
</div>
	
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarProducto', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('950');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
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

function filterFloat(evt,input){
	// Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
	var key = window.Event ? evt.which : evt.keyCode;    
	var chark = String.fromCharCode(key);
	var tempValue = input.value+chark;
	if(key >= 48 && key <= 57){
		if(filter(tempValue)=== false){
			return false;
		}else{       
			return true;
		}
	}else{
		if(key == 8 || key == 13 || key == 0) {     
			return true;              
		}else if(key == 46){
				if(filter(tempValue)=== false){
					return false;
				}else{       
					return true;
				}
		}else{
			return false;
		}
	}
}
function filter(__val__){
	var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
	if(preg.test(__val__) === true){
		return true;
	}else{
	return false;
	}
	
}

</script>