
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="row">
	<div class="col-md-12">
		<fieldset >    	
			<div class="panel panel-default">
				<div class="form-group"  >
					{!! Form::label('codigo', 'Codigo*:', array('class' => 'col-sm-3 col-sm-12 control-label')) !!}
					<div class="col-sm-4 col-xs-12" >
						{!! Form::text('codigo', null, array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese codigo')) !!}
					</div>
					<div class="col-sm-5 col-xs-12" >
						{!! Form::text('codigo_barra', null, array('class' => 'form-control input-xs', 'id' => 'codigo_barra', 'placeholder' => 'codigo barra')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('descripcion', 'Descripcion*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-9 col-xs-12" >
						{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion', 'style'=>'height: 25px')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('sustancia_activa', 'Principio Activo*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-9 col-xs-12" >
						{!! Form::text('sustancia_activa', null, array('class' => 'form-control input-xs', 'id' => 'sustancia_activa', 'placeholder' => 'Ingrese sustancia activa', 'style'=>'height: 25px')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('uso_terapeutico', 'Uso Terapeutico*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-9 col-xs-12" >
						{!! Form::textarea('uso_terapeutico', null, array('class' => 'form-control input-xs', 'cols'=>'8', 'rows'=>'rows', 'id' => 'uso_terapeutico', 'placeholder' => 'Ingrese uso terapeutico')) !!}
					</div>
				</div>
			</div>
		</fieldset>	

		<fieldset>   
			<div class="panel panel-default">
				<div class="form-group" >
					{!! Form::label('tipo', 'Tipo*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-4 col-xs-12">
						{!! Form::select('tipo', $cboTipo, null, array('class' => 'form-control input-xs', 'id' => 'tipo')) !!}
					</div>
					{!! Form::label('afecto', 'Afecto*:', array('class' => 'col-sm-2 col-xs-12 control-label')) !!}
					<div class="col-sm-3 col-xs-12">
						{!! Form::select('afecto', $cboAfecto, null, array('class' => 'form-control input-xs', 'id' => 'afecto')) !!}
					</div>
				</div>
		
				<div class="form-group " >
					{!! Form::label('marca_id', 'Marc/Lab:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-9 col-xs-12" >
						{!! Form::select('marca_id', $cboMarca, null, array('class' => 'form-control input-xs', 'id' => 'marca_id', 'style'=>'height: 25px')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('procedencia', 'Procedencia:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-9 col-xs-12" >
						{!! Form::text('procedencia', 'sin especificar', array('class' => 'form-control input-xs', 'id' => 'procedencia', 'placeholder' => 'Ingrese procedencia')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('stock_minimo', 'Stock Min*:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-2 col-xs-12" >
						{!! Form::text('stock_minimo', '5', array('class' => 'form-control input-xs input-number', 'id' => 'stock_minimo', 'placeholder' => '', 'style'=>'height: 25px')) !!}
					</div>
					{!! Form::label('ubicacion', 'Ubicacion:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-4 col-xs-12" >
						{!! Form::text('ubicacion', 'stand 01', array('class' => 'form-control input-xs', 'id' => 'ubicacion', 'placeholder' => 'Ingrese ubicacion', 'style'=>'height: 25px')) !!}
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
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarProductEdit', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
</div>	
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('800');
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