
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="row">
	<div class="col-md-12">	
		<div class="form_editar">
				{!! Form::hidden('fila_editar', '', array('id' => 'fila_editar')) !!}
			<div class="form-group text-left form_editar">
				{!! Form::label('presentacion', 'Presentacion:', array('class' => ' c text-left')) !!}
				{!! Form::select('presentacion', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'presentacion')) !!}
			</div>
			<div class="form-group text-left form_editar" style="margin-left: 3px">
				{!! Form::label('precio_com', 'Precio Compra:', array('class' => ' text-left')) !!}
				{!! Form::text('precio_com', null, array('class' => 'form-control input-sm', 'id' => 'precio_com', 'placeholder' => 'precio compra')) !!}
			</div>
			<div class="form-group  text-left form_editar" style="margin-left: 3px">
					{!! Form::label('cantidad_p', 'Cantidad:', array('class' => '')) !!}
					{!! Form::text('cantidad_p', null, array('class' => 'form-control input-sm', 'id' => 'cantidad_p', 'placeholder' => 'cantidad')) !!}
				</div>
			<div class="form-group text-left form_editar" style="margin-left: 3px">
					{!! Form::label('precio_venta_p', 'Precio Venta:', array('class' => '')) !!}
					{!! Form::text('precio_venta_p', null, array('class' => 'form-control input-sm', 'id' => 'precio_venta_p', 'placeholder' => 'precio_venta')) !!}
			</div>
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Edi', array('class' => 'btn btn-warning btn-sm form_editar', 'id' => 'btnEdit', 'onclick' => 'modif_pres();')) !!}
		</div>
		
		<fieldset class="form_vista"> 
			<div class="panel panel-default form_vista">
				<div class="form-inline form_vista">
					<table class="form_vista">
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
							<td><button id="btnAgregar" name="btnAgregar" class="btn btn-info btn-xs" onclick="agregar();" title="" type="button"><i class="glyphicon glyphicon-plus"></i> Agregar</button></td>
						</tr>
					</table>
				</div>
				<br>
				<div class="form-inline">
					<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
						<thead>
							<tr>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="3%">#</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="37%">Presentacion</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="20%">P. Compra</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Cant. Uds</th>
								<th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">P. Venta</th>
								<th bgcolor="#E0ECF8" colspan="2" class="text-center input-sm" width="5%">Elim</th>                            
							</tr>
						</thead>
						@if($listdet_=='')
						@else
						<tbody id="dat_comp">
							<?php $cont=0;?>
							@foreach($listdet_ as $key => $value)
								<?php $cont++; ?>
								<tr class='datos-presentacion dat_r{{ $cont }}' id_producto_presentacion='{{$value->propresent_id}}' id_present='{{ $value->presentacion_id }}'  preciocomp='{{ $value->precio_compra}}'  unidad_x_present='{{ $value->cant_unidad_x_presentacion}}' precioventaunit='{{ $value->precio_venta_unitario }}'>
									<td id='cont' class="input-sm" align="center"><?php echo $cont; ?></td>
									<td id='pres_nombre{{ $cont  }}'class="input-sm" align="center">{{ $value->presentacion_nombre }} </td>
									<td id='precio_c{{ $cont }}' class="input-sm" align="center">{{ $value->precio_compra }} </td>
									<td id='cant_u{{ $cont }}' class="input-sm" align="center">{{ $value->cant_unidad_x_presentacion }} </td>
									<td id='precio_vu{{ $cont }}' class="input-sm" align="center">{{ $value->precio_venta_unitario }}</td>
									<td class="input-sm" align="center">{!! Form::button('<i class="glyphicon glyphicon-pencil"></i>', array('class' => 'btn btn-info btn-xs', 'id' => 'btnEdit', 'onclick' => 'editar(\'dat_r'.$cont.'\', \''.$cont.'\');')) !!}</td>

									<td class="input-sm" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this);" title="" type="button" disabled="true"><i class="glyphicon glyphicon-remove"></i></button></td>
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

	$('.form_editar').hide();

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
	var contador = 0;
	$('.datos-presentacion').each(function(){
contador ++;
	})
	if(parseInt($('#present_id').val()) != 0){
		if(unidad_x_presentacion != ''){
			var d = '<tr class="datos-presentacion dat_r'+contador+'" id_present="'+$('#present_id').val()+'"  preciocomp="'+preciocompra+'"  unidad_x_present="'+unidad_x_presentacion+'" precioventaunit="'+precioventaunitario+'">'+
				'<td class="input-sm" width="2%" align="center">'+contador+'</td>'+
				'<td class="input-sm" width="38%" align="center">'+presentacion_dat+'</td>'+
				'<td class="input-sm" width="20%" align="center">'+preciocompra+'</td>'+
				'<td class="input-sm" width="15%" align="center">'+unidad_x_presentacion+'</td>'+
				'<td class="input-sm" width="15%" align="center">'+precioventaunitario+'</td>'+
				'<td width="5%" align="center"><button id="btnEditar" name="btnEditar"  class="btn btn-info btn-xs" onclick="editar(\'dat_r'+contador+'\', '+contador+');" title="" type="button"><i class="glyphicon glyphicon-pencil" ></i></button></td>'+
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
function editar( classfila, cont_fila){
	var mensaje;
	var cantidad = $('.'+classfila).attr('unidad_x_present');
	var precio_compra =  $('.'+classfila).attr('preciocomp');
	var precio_venta =  $('.'+classfila).attr('precioventaunit');
	var presentacion_id = $('.'+classfila).attr('id_present');
	$('.form_editar').show();
	$('#presentacion').val(presentacion_id);
	$('#precio_com').val(precio_compra);
	$('#cantidad_p').val(cantidad);
	$('#precio_venta_p').val(precio_venta);
	$('#fila_editar').val(cont_fila);
	$('.form_vista').hide();
    // var opcion = confirm("Desea ELiminar el producto registrado?");
	// console.log("editar "+item);
	// console.log("datos que pasan "+this.dat_comp[item]);
	// $('#preciocompra').val(this.tabla[item]);
	
	
    // if (opcion == true) {
    //     var td = t.parentNode;
	// 	var tr = td.parentNode;
	// 	var table = tr.parentNode;
	// 	table.removeChild(tr);
	// }
}
function modif_pres(){
	var num_fila = $('#fila_editar').val();
	var fila = 'dat_r'+num_fila;

	var presentacion_id = $('#presentacion').val();
	var nombrepress = $('#presentacion option:selected').html();
	var precio = $('#precio_com').val();
	var cantidad_p = $('#cantidad_p').val();
	var precio_v_p = $('#precio_venta_p').val();

	$('.'+fila).attr('unidad_x_present',cantidad_p );
	$('.'+fila).attr('preciocomp',precio);
	$('.'+fila).attr('precioventaunit',precio_v_p );
	$('.'+fila).attr('id_present',presentacion_id );

	$('#pres_nombre'+num_fila).text(nombrepress);
	$('#precio_c'+num_fila).text(precio);
	$('#cant_u'+num_fila).text(cantidad_p);
	$('#precio_vu'+num_fila).text(precio_v_p);

	$('.form_editar').hide();
	$('.form_vista').show();



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