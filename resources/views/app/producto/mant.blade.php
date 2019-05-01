
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="row">
	<div class="col-md-6">
		<fieldset >    	
			<div class="panel panel-default">
				<div class="form-group"  >
					{!! Form::label('codigo', 'Codigo*:', array('class' => 'col-sm-3 col-sm-12 control-label')) !!}
					<div class="col-sm-4 col-xs-12" >
						{!! Form::text('codigo', $codigo, array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese codigo')) !!}
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
				<br>
			</div>
		</fieldset>
	</div>
	<div class="col-md-6">
		<fieldset>    	
			<div class="panel panel-default">
				<div class="form-group ">
					{!! Form::label('categoria_id', 'Categoria:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
					<div class="col-sm-9 col-xs-12">
						{!! Form::select('categoria_id', $cboCategoria, null, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
					</div>
				</div>
			</div>
		</fieldset>	
		<fieldset> 
			<div class="panel panel-default">
				<div class="form-inline " style="height: 25px">
					{!! Form::label('marca_id', 'Ingrese Presentaciones', array('class' => 'col-sm-12 col-xs-12 control-label', 'style'=>'text-align:center;')) !!}
				</div>
				<div class="form-inline">
					<table>
						<tr style="height: 10px;">
							<td>&nbsp;</td>
							<td class=" input-sm"><b>Presentacion</b></td>
							<td>{!! Form::select('present_id', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'present_id','style'=>'text-align: right;')) !!}</td>
							<td class=" input-sm"><b>P.Compra:</b></td>
							<td><input class="form-control input-sm" style="width:60px" onkeypress="return filterFloat(event,this);" id="preciocompra" size="3" name="preciocompra" type="text" style="text-align: right;"></td>
							<td class=" input-sm"><b>Cant.Uds:</b></td>
							<td><input class="form-control input-sm input-number" id="unidad_x_presentacion" size="3" name="unidad_x_presentacion" type="text"></td>
							<td class=" input-sm"><b>P.Venta:</b></td>
							<td><input class="form-control input-sm" style="width:60px" id="precioventaunitario" onkeypress="return filterFloat(event,this);"  size="3" name="precioventaunitario" type="text" style="text-align: right;"></td>
							<td><button id="btnAgregar" name="btnAgregar" class="btn btn-info btn-xs" onclick="agregar();" title="" type="button"><i class="glyphicon glyphicon-plus"></i></button></td>
						</tr>
					</table>
				</div>
				<br>
				<div class="form-inline">
					<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
						<thead>
							<tr>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="40%">Presentacion</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="20%">P. Compra</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Cant. Uds</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">P. Venta</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Elim</th>                            
							</tr>
						</thead>
						@if($listdet_=='')
						@else
						<tbody>
							@foreach($listdet_ as $key => $value)
								<tr class='datos-presentacion' id_producto_presentacion='{{$value->propresent_id}}' id_present='{{ $value->presentacion_id }}'  preciocomp='{{ $value->precio_compra}}'  unidad_x_present='{{ $value->cant_unidad_x_presentacion}}' precioventaunit='{{ $value->precio_venta_unitario }}'>
									<td>{{ $value->presentacion_nombre }} </td>
									<td>{{ $value->precio_compra }} </td>
									<td>{{ $value->cant_unidad_x_presentacion }} </td>
									<td>{{ $value->precio_venta_unitario }}</td>
									<td><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this);" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>
								</tr>
							@endforeach
						</tbody>
						@endif
					</table>
				</div>
			</div>
		</fieldset>
		
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarProducto', 'onclick' => 'guardar_producto(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
</div>	
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1200');
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

function agregar(){
	//datos de la presentacion
	var presentacion_dat ="";
	var select_p = "";
	var select_p = document.getElementById('present_id');
	presentacion_dat = select_p.options[select_p.selectedIndex].innerText;

	var preciocompra 				= $('#preciocompra').val();
	var present_id 					= $('#present_id').val();
	var unidad_x_presentacion 		= $('#unidad_x_presentacion').val();
	var precioventaunitario 		= $('#precioventaunitario').val();
	if(parseInt($('#present_id').val()) != 0){
		if(unidad_x_presentacion != ''){
			var d = '<tr class="datos-presentacion" id_present="'+$('#present_id').val()+'"  preciocomp="'+preciocompra+'"  unidad_x_present="'+unidad_x_presentacion+'" precioventaunit="'+precioventaunitario+'">'+
				'<td class="input-sm" width="40%" align="center">'+presentacion_dat+'</td>'+
				'<td class="input-sm" width="20%" align="center">'+preciocompra+'</td>'+
				'<td class="input-sm" width="15%" align="center">'+unidad_x_presentacion+'</td>'+
				'<td class="input-sm" width="15%" align="center">'+precioventaunitario+'</td>'+
				'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this);" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
				'</tr>';
			$("#tabla").append(d);
			$('#present_id').val(0);
			$('#preciocompra').val('');
			$('#unidad_x_presentacion').val('');
			$('#precioventaunitario').val('');
			
		}else{
			window.alert("Ingrese Cantidad por presentacion!");
			$('#unidad_x_presentacion').focus();
		}
		
	}else{
		window.alert("Seleccione presentacion!");
		$('#presentacion_id').focus();
	}
}
function quitar(t){
	var mensaje;
    var opcion = confirm("Desea ELiminar el producto registrado?");
    if (opcion == true) {
        var td = t.parentNode;
		var tr = td.parentNode;
		var table = tr.parentNode;
		table.removeChild(tr);
	}

}

function guardar_producto(entidad, idboton) {
	var idformulario = IDFORMMANTENIMIENTO + entidad;
	var data         = submitForm_control(idformulario);
	var respuesta    = '';
	var listar       = 'NO';
	if ($(idformulario + ' :input[id = "listar"]').length) {
		var listar = $(idformulario + ' :input[id = "listar"]').val()
	};
	$(idboton).button('loading');
	data.done(function(msg) {
		respuesta = msg;
		
	}).fail(function(xhr, textStatus, errorThrown) {
		respuesta = 'ERROR';
		$(idboton).removeClass('disabled');
		$(idboton).removeAttr('disabled');
		$(idboton).html('<i class="fa fa-check fa-lg"></i>Guardar');
	}).always(function() {
		if(respuesta === 'ERROR'){
		}else{
			if (respuesta === 'OK') {
				cerrarModal();
				if (listar === 'SI') {
					
					buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
					
				}        
			} else {
				mostrarErrores(respuesta, idformulario, entidad);
				$(idboton).removeClass('disabled');
				$(idboton).removeAttr('disabled');
				$(idboton).html('<i class="fa fa-check fa-lg"></i>Guardar');
			}
		}
	});
}
function submitForm_control(idformulario) {
	var i=0;
	var datos="";
	$('.datos-presentacion').each(function() {
		datos += 	"&id_present"		+i+"="+$(this).attr("id_present")+
					"&propresent_id"	+i+"="+$(this).attr("id_producto_presentacion")+
					"&preciocomp"	+i+"="+$(this).attr("preciocomp")+
					"&unidad_x_present"	+i+"="+$(this).attr("unidad_x_present")+
					"&precioventaunit"	+i+"="+$(this).attr("precioventaunit");
		i++;
	});
	datos += "&cantidad="+i;
	var parametros = $(idformulario).serialize();
	parametros += datos;
	var accion     = $(idformulario).attr('action').toLowerCase();
	//console.log('Accion: form: '+accion+'   param: '+parametros);
	var metodo     = $(idformulario).attr('method').toLowerCase();
	//console.log('Metodo: '+metodo);
	var respuesta  = $.ajax({
		url : accion,
		type: metodo,
		data: parametros
	});
	//console.log('Respuesta: '+respuesta);
	return respuesta;
}
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