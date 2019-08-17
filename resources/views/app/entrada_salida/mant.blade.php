<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($entradasalida, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group ">
				{!! Form::label('documento', 'Documento:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('documento', $cboDocumento, null, array('class' => 'form-control input-sm', 'id' => 'documento')) !!}
				</div>
			</div>
			
			<div class="form-group " >
				{!! Form::label('fecha', 'Fecha:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha')) !!}
				</div>
			</div>
			<div class="form-group" >
				{!! Form::label('serie_documento', 'Nro Doc:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-3 col-xs-12" style="height: 25px;">
					{!! Form::text('serie_documento', $serie, array('class' => 'form-control input-xs', 'id' => 'serie_documento', 'placeholder' => 'serie')) !!}
				</div>
				<div class="col-sm-6 col-xs-12" style="height: 25px;">
					{!! Form::text('numero_documento', $numero, array('class' => 'form-control input-xs', 'id' => 'numero_documento', 'placeholder' => 'num documento')) !!}
				</div>
			</div>
			<div class="form-group" >
					{!! Form::textarea('comentario', null, array('class' => 'form-control input-xs', 'style'=>'height: 90px;', 'id' => 'comentario', 'placeholder' => 'Ingrese comentario!..')) !!}
			</div>
			<div class="form-group">
				<div class="col-lg-12 col-md-12 col-sm-12 text-right">
					{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarCompra', 'onclick' => 'guardar_compra(\''.$entidad.'\', this)')) !!}
					{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
				</div>
			</div>
		</div>

		<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 card-box">
			<div class="alert alert-success col-12 col-md-12" id="detalle_prod">
				<table id="tabla_temp" class="" style="">
					<tr>
						<td>Producto:</td><td style="padding-left: 10px;" colspan="6"><label id="producto_inf">: </label></td>
					</tr>
					<tr>
						<td>Stock (Unidades):</td>
						<td style="padding-left: 10px;"><label id="stock_inf" stock='0'>: </label></td>
						<td style="padding-left: 10px;">Fecha Vencimiento:</td>
						<td style="padding-left: 10px;"><label id="fecha_venc" f_venc=''>: </label></td>
						<td style="padding-left: 10px;">Lote:</td>
						<td style="padding-left: 10px;"><label id="lote_prod" lote=''>: </label></td>
					</tr>
				</table>
			</div>


			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='oculto1' style="display:none;">
				<div class="form-inline input-sm" style="height: 35px;" >
					{!! Form::label('productod', 'Producto:', array('class' => 'col-sm-1 col-xs-12 control-label', 'style'=>'height: 35px')) !!}
					<div class="col-sm-11 col-xs-12" style="height: 35px; ">
						{!! Form::select('producto_id', $cboProducto, null, array('class' => 'form-control input-sm', 'id' => 'producto_id')) !!}
					</div>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  id='oculto2' style="display:none;">
				<div class="form-inline input-sm" style="height: 35px;">
					{!! Form::label('entrada_id', 'Producto:', array('class' => 'col-sm-1 col-xs-12 control-label', 'style'=>'height: 35px')) !!}
					<div class="col-sm-11 col-xs-12" style="height: 35px;">
						{!! Form::select('entrada_id', $cboEntrada, null, array('class' => 'form-control input-sm', 'id' => 'entrada_id')) !!}
					</div>
				</div>
			</div>
			<div class="form-group">
				<table>
					<tr style="height: 10px;">
						<td>&nbsp;</td>
						<td class=" input-sm"><b>Unidad</b></td>
						<td>{!! Form::select('id_presentacion', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'id_presentacion','style'=>'text-align: right;')) !!}</td>
						<td class=" input-sm"><b>P.Compra</b></td>
						<td><input class="" style="width:60px" onkeypress="return filterFloat(event,this);" id="preciocompra" size="3" name="preciocompra" type="text" style="text-align: right;"></td>
						<td class="form-control input-sm"><b>P.Venta</b></td>
						<td><input class="" style="width:60px" id="precioventa" onkeypress="return filterFloat(event,this);"  size="3" name="precioventa" type="text" style="text-align: right;"></td>
						<td class="form-control input-sm"><b>Cantidad</b></td>
						<td><input class=" input-number" id="cantidad" size="3" name="cantidad" type="text">
						<td class="form-control input-sm"><b>Factor</b></td>
						<td><input class=" input-number" id="factor" size="3" name="factor" type="text">{!! Form::hidden('unidad_presentacion', null, array('id' => 'unidad_presentacion')) !!} {!! Form::hidden('cantidad_max', null, array('id' => 'cantidad_max')) !!}</td>
						<td class="form-control input-sm"><b>Fecha Venc.</b></td>
						<td><input class="" id="fechavencimiento" style="width:130px" size="6" name="fechavencimiento" type="text" placeholder="dd/mm/yyyy"></td>
						<td class="form-control input-sm"><b>Lote</b></td>
						<td><input class="" id="lote" size="6" style="width:80px" name="lote" type="text"></td>
						<td><button id="btnAgregar" name="btnAgregar" class="btn btn-info btn-xs" onclick="agregar();" title="" type="button"><i class="glyphicon glyphicon-plus"></i></button></td>
					</tr>
				</table>
			</div>
			<div class="form-group">
				<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
		            <thead>
		                <tr>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="45%">Producto</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Presentacion</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">F. Venc.</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Cantidad</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Precio</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Subtotal</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Elim</th>                            
		                </tr>
		            </thead>
		        </table>
			</div>
		</div>			
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	var fechaActual = new Date();
	var day = ("0" + fechaActual.getDate()).slice(-2);
	var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
	var fecha_horaApert = (fechaActual.getFullYear()) +"-"+month+"-"+day+"";
	$('#fechavencimiento').val('{{ $fecha_form }}');
	$('#fecha').val(fecha_horaApert);
	$('#fecha_caducidad').val(fecha_horaApert);
	$('#detalle_prod').hide();

	$('#documento').change(function(event){
		var documento = $('#documento').val();;
		if(documento == 'E'){
			$('#oculto1').css('display','block');
			$('#documento').prop("disabled", true);
		}
		if(documento == 'S'){
			$('#oculto2').css('display','block');
			$('#documento').prop("disabled", true);
		}
	});

	$('#producto_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listproductos'], array()) }}",
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

	$('#producto_id').change(function(event){
		$.get("entrada/"+$(this).val()+"/0219312", function(response, productos){
			if(response.length !=0 ){
				var entradas_ = response[0];
				var cbo_present = response[1];
				$('#id_presentacion').empty();
				$('#id_presentacion').append('<option value="0">Seleccione.</option>');
				$('#id_presentacion').append(cbo_present);

				$('#preciocompra').val(entradas_.precio_compra);
				$('#precioventa').val(entradas_.precio_venta_unitario);
				$('#id_presentacion').val(entradas_.presentacion_id);
				$('#unidad_presentacion').val(entradas_.cant_unidad_x_presentacion);
				$('#id_presentacion').prop("disabled", true);
				
			}else{
				window.alert("Producto no esta registrado en el inventario!");
			}
		});
	});


	$('#entrada_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listproductosalida'], array()) }}",
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

	$('#entrada_id').change(function(event){
		$.get("entrada/"+$(this).val()+"", function(response, productos){
			var entrsalida = response[0];
			var cbopresentacion = response[1];
			if(response.length !=0 ){
				$('#id_presentacion').empty();
				$('#id_presentacion').append('<option value="0">Seleccione.</option>');
				$('#id_presentacion').append(cbopresentacion);

				$('#producto_inf').text('');
				$('#fecha_venc').text('');
				$('#unidad_presentacion').val('');
				$('#cantidad_max').val('');
				$('#stock_inf').text('');
				$('#lote_prod').text('');

				$('#fechavencimiento').val('');
				$('#lote').val('');
				$('#factor').val('');
				$('#cantidad').val('');


				$('#producto_inf').text(entrsalida.descripcion);
				$('#fecha_venc').text(entrsalida.fecha_caducidad_string);
				$('#unidad_presentacion').val(entrsalida.stock);
				$('#stock_inf').text(entrsalida.stock);
				$('#cantidad_max').val(entrsalida.stock);
				$('#lote_prod').text(entrsalida.lote);

				$('#fechavencimiento').prop("readonly", true);
				$('#lote').prop("readonly", true);
				$('#fechavencimiento').val(entrsalida.fecha_caducidad_string);
				$('#lote').val(entrsalida.lote);

				$('#detalle_prod').show();
			}else{
				window.alert("Producto no esta registrado en el inventario!");
			}
		});
	});

	$('#id_presentacion').change(function(event){
		var prod_id = parseInt($('#entrada_id').val());
		$.get("entrada/"+$(this).val()+"/"+prod_id+"/234234", function(response, productos){
			if(response.length !=0 ){
				$('#preciocompra').val('');
				$('#precioventa').val('');
				$('#factor').val('');
				$('#cantidad').val('');
				$('#unidad_presentacion').val('');

				$('#preciocompra').val(response.precio_compra);
				$('#precioventa').val(response.precio_venta);
				// $('#factor').val(response.cant_unidad_x_presentacion);
				$('#unidad_presentacion').val(response.cant_unidad_x_presentacion);
				$('#factor').prop("readonly", true);
			}else{
				window.alert("Producto no esta registrado en el inventario!");
			}
		});
	});


	$("input[name=cantidad]").change(function(event){
		var cant = parseInt($('#cantidad').val());
		var cantidad_unidad = parseInt($('#unidad_presentacion').val());
		$('#factor').val('');
		$('#factor').val(cant*cantidad_unidad);

		var doc_ 				= $('#documento').val();

		if(doc_=='S'){
			$('#stock_inf').text(parseInt($('#cantidad_max').val())-parseInt($('#factor').val()));
		}
	});


}); 


function agregar(){
	//datos del producto
	var producto_dat ="";
	var select = "";
	var select = document.getElementById('producto_id');
	producto_dat = select.options[select.selectedIndex].innerText;
	//datos de la presentacion
	var presentacion_dat ="";
	var select_p = "";
	var select_p = document.getElementById('id_presentacion');
	presentacion_dat = select_p.options[select_p.selectedIndex].innerText;


	var preciocompra 		= $('#preciocompra').val();
	var id_presentacion 		= $('#id_presentacion').val();
	var precioventa 		= $('#precioventa').val();
	var cantidad 			= $('#cantidad').val();
	var fechavencimiento 	= $('#fechavencimiento').val();
	var lote 				= $('#lote').val();

	var doc_ 				= $('#documento').val();
	if(doc_ == 'E'){
		if($('#producto_id').val() !='0'){
			if(id_presentacion !=""){
				if(preciocompra !=""){
					if(precioventa !=""){
						if(cantidad!=""){
							if(fechavencimiento!=""){
								if(evaluar_fecha() == true){
									if(lote!=""){
										var subtotal ="";
										subtotal = parseInt(cantidad)*parseFloat(preciocompra);
										var t_parcial =0;
										if($('#total').val() != ""){
											t_parcial = parseFloat($('#total').val());
										}else{
											t_parcial=0;
										}
										var total = t_parcial+subtotal;
										var d = '<tr class="datos-producto" id_producto="'+$('#producto_id').val()+'" id_presentacion="'+id_presentacion+'" precio_compra="'+preciocompra+'" precio_venta="'+precioventa+'" canti="'+$('#factor').val()+'" fecha_venc="'+fechavencimiento+'" lot="'+lote+'">'+
											'<td class="input-sm" width="45%">'+producto_dat+'</td>'+
											'<td class="input-sm" width="15%" align="center">'+presentacion_dat+'</td>'+
											'<td class="input-sm" width="10%" align="center" >'+fechavencimiento+'</td>'+
											'<td class="input-sm" width="5%" align="center">'+cantidad+'</td>'+
											'<td class="input-sm" width="10%" align="center">'+preciocompra+'</td>'+
											'<td class="input-sm" width="10%" align="center">'+subtotal+'</td>'+
											'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this, '+subtotal+');" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
											'</tr>';
										$("#tabla").append(d);
										$('#total').val(total);
										//vaciar datos
										$('#id_presentacion').val(0);
										$('#producto_id').empty();
										$('#producto_id').append('<option value="0">Seleccione Producto..........................</option>');

										$('#preciocompra').val("");
										$('#precioventa').val("");
										$('#cantidad').val("");
										$('#factor').val("");
										$('#fechavencimiento').val("");
										$('#lote').val("");
									}else{
										window.alert("ingrese lote!");
										$('#lote').focus();
									}
								}else{
									window.alert("formato de fecha de vencimiento incorrecto revise, asugurese que tenga el formato siguiente dd/mm/yyyy o mm/yyyy!");
									$('#fechavencimiento').focus();
								}
								
							}else{
								window.alert("seleccione fecha de vencimiento!");
								$('#fechavencimiento').focus();
							}
						}else{
							window.alert("ingrese cantidad a comprar!");
							$('#cantidad').focus();
						}	
					}else{
						window.alert("ingrese precio de venta!");	
						$('#precioventa').focus();
					}
				}else{
					window.alert("ingrese precio de compra!");
					$('#preciocompra').focus();
				}
			}else{
				window.alert("Seleccione presentacion!");
				$('#presentacion_id').focus();
			}
		}else{
			window.alert("seleccione un producto!");
			$('#producto_id').focus();
		}
	}
	
	if(doc_ == 'S'){
		var entrada_dat ="";
		var select = "";
		var select = document.getElementById('entrada_id');
		entrada_dat = select.options[select.selectedIndex].innerText;
		if($('#entrada_id').val() !='0'){
			if(id_presentacion !=""){
				if(preciocompra !=""){
					if(precioventa !=""){
						if($('#cantidad').val()!=""){
							console.log($('#factor').val()+"  "+$('#cantidad_max').val());
							if(parseInt($('#factor').val()) <= parseInt($('#cantidad_max').val())){

								if(fechavencimiento!=""){
									if(lote!=""){
										var subtotal ="";
										subtotal = parseInt(parseInt($('#factor').val()))*parseFloat(preciocompra);
										var d = '<tr class="datos-entrada" id_entrada="'+$('#entrada_id').val()+'" id_presentacion="'+id_presentacion+'" precio_compra="'+preciocompra+'" precio_venta="'+precioventa+'" cantidad_entrada="'+$('#factor').val()+'" c_entrada="'+$('#cantidad').val()+'"  fecha_venc="'+fechavencimiento+'" lot="'+lote+'">'+
											'<td class="input-sm" width="45%">'+entrada_dat+'</td>'+
											'<td class="input-sm" width="15%" align="center">'+presentacion_dat+'</td>'+
											'<td class="input-sm" width="10%" align="center" >'+fechavencimiento+'</td>'+
											'<td class="input-sm" width="5%" align="center">'+parseInt($('#factor').val())+'</td>'+
											'<td class="input-sm" width="10%" align="center">'+preciocompra+'</td>'+
											'<td class="input-sm" width="10%" align="center">'+subtotal+'</td>'+
											'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this, '+subtotal+');" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
											'</tr>';
										$("#tabla").append(d);
										//vaciar datos
										$('#entrada_id').empty();
										$('#entrada_id').append('<option value="0">Seleccione Producto..........................</option>');
										$('#id_presentacion').val(0);
										$('#preciocompra').val("");
										$('#precioventa').val("");
										$('#cantidad').val("");
										$('#factor').val("");
										$('#fechavencimiento').val("");
										$('#lote').val("");
										$('#detalle_prod').hide();
									}else{
										window.alert("ingrese lote!");
										$('#lote').focus();
									}
								}else{
									window.alert("seleccione fecha de vencimiento!");
									$('#fechavencimiento').focus();
								}

							}else{
								window.alert("cantidad ingresada incorrecta!");
								$('#factor').focus();	
							}
						}else{
							window.alert("ingrese cantidad!");
							$('#cantidad').focus();
						}	
					}else{
						window.alert("ingrese precio de venta!");	
						$('#precioventa').focus();
					}
				}else{
					window.alert("ingrese precio de compra!");
					$('#preciocompra').focus();
				}
			}else{
				window.alert("Seleccione presentacion!");
				$('#presentacion_id').focus();
			}
		}else{
			window.alert("seleccione un producto!");
			$('#producto_id').focus();
		}
	}
	
	
}

function quitar(t, subtotal){
	var mensaje;
    var opcion = confirm("Desea ELiminar el producto registrado?");
    if (opcion == true) {
        var td = t.parentNode;
		var tr = td.parentNode;
		var table = tr.parentNode;
		table.removeChild(tr);
		var total_parcial = parseFloat($('#total').val());
		$('#total').val(parseFloat(total_parcial)-subtotal);
	}

}

function guardar_compra(entidad, idboton) {
	$cont =0;
	$('.datos-entrada').each(function() {
		$cont++;
	});
	$('.datos-producto').each(function() {
		$cont++;
	});

	if($cont !=0){
		var idformulario = IDFORMMANTENIMIENTO + entidad;
		var data         = submitForm_control(idformulario);
		var respuesta    = '';
		var listar       = 'NO';
		if ($(idformulario + ' :input[id = "listar"]').length) {
			var listar = $(idformulario + ' :input[id = "listar"]').val()
		};
		$('#btnGuardarCompra').button('loading');
		data.done(function(msg) {
			respuesta = msg;
		}).fail(function(xhr, textStatus, errorThrown) {
			respuesta = 'ERROR';
			$('#btnGuardarCompra').removeClass('disabled');
			$('#btnGuardarCompra').removeAttr('disabled');
			$('#btnGuardarCompra').html('<i class="fa fa-check fa-lg"></i>Guardar');
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
					$('#btnGuardarCompra').removeClass('disabled');
					$('#btnGuardarCompra').removeAttr('disabled');
					$('#btnGuardarCompra').html('<i class="fa fa-check fa-lg"></i>Guardar');
				}
			}
		});
	}else{
		window.alert("ingrese productos!");
	}
	
}
function submitForm_control(idformulario) {
	var i=0;
	var datos="";
	var dato_documento = $('#documento').val();
	if(dato_documento =='E'){
		$('.datos-producto').each(function() {
			datos += 	"&id_producto"		+i+"="+$(this).attr("id_producto")+
						"&id_presentacion"	+i+"="+$(this).attr("id_presentacion")+
						"&precio_compra"	+i+"="+$(this).attr("precio_compra")+
						"&precio_venta"		+i+"="+$(this).attr("precio_venta")+
						"&cant"				+i+"="+$(this).attr("canti")+
						"&fecha_vencim"			+i+"="+$(this).attr("fecha_venc")+
						"&lot"			+i+"="+$(this).attr("lot");
			i++;
		});
	}

	if(dato_documento =='S'){
		$('.datos-entrada').each(function() {
			datos += 	"&id_entrad"		+i+"="+$(this).attr("id_entrada")+
						"&id_presentacion"	+i+"="+$(this).attr("id_presentacion")+
						"&precio_compra"	+i+"="+$(this).attr("precio_compra")+
						"&precio_venta"		+i+"="+$(this).attr("precio_venta")+
						"&cantid"				+i+"="+$(this).attr("cantidad_entrada")+
						"&salida_c"				+i+"="+$(this).attr("c_entrada")+
						"&fecha_vencim"			+i+"="+$(this).attr("fecha_venc")+
						"&lot"			+i+"="+$(this).attr("lot");
			i++;
		});
	}	
	datos += "&cantidad="+i;
	datos += "&doc="+dato_documento;
	var parametros = $(idformulario).serialize();
	parametros += datos;
	var accion     = $(idformulario).attr('action').toLowerCase();
	console.log('Accion: form: '+accion+'   param: '+parametros);
	var metodo     = $(idformulario).attr('method').toLowerCase();
	console.log('Metodo: '+metodo);
	var respuesta  = $.ajax({
		url : accion,
		type: metodo,
		data: parametros
	});
	console.log('Respuesta: '+respuesta);
	return respuesta;
}


$('.input-number').on('input', function () { 
	this.value = this.value.replace(/[^0-9]/g,'');
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

function evaluar_fecha(){
	var date_af = $('#fechavencimiento').val();
	var punto=".";
	var dos_puntos =":";
	var slash = "/";
	var gion = "-";
	
	if(date_af.indexOf(punto) !== -1){
		var date_formt = date_af.split(punto);
		if(date_formt.length == 2){
			if(date_formt[0]>0 && date_formt[0]<13){
				return true;
			}else{
				return false;
			}
		}
		if(date_formt.length == 3){
			if(date_formt[0]<32 && date_formt[0]>0){
				if(date_formt[1]>0 && date_formt[1]<13){
					return true;
				}else{
					return false;
				}
			}
		}
	}
	if(date_af.indexOf(dos_puntos) !== -1){
		var date_formt = date_af.split(dos_puntos);
		if(date_formt.length == 2){
			if(date_formt[0]>0 && date_formt[0]<13){
				return true;
			}else{
				return false;
			}
		}
		if(date_formt.length == 3){
			if(date_formt[0]<32 && date_formt[0]>0){
				if(date_formt[1]>0 && date_formt[1]<13){
					return true;
				}else{
					return false;
				}
			}
		}
	}
	if(date_af.indexOf(slash) !== -1){
		var date_formt = date_af.split(slash);
		if(date_formt.length == 2){
			if(date_formt[0]>0 && date_formt[0]<13){
				return true;
			}else{
				return false;
			}
		}
		if(date_formt.length == 3){
			if(date_formt[0]<32 && date_formt[0]>0){
				if(date_formt[1]>0 && date_formt[1]<13){
					return true;
				}else{
					return false;
				}
			}
		}
	}
	if(date_af.indexOf(gion) !== -1){
		var date_formt = date_af.split(gion);
		if(date_formt.length == 2){
			if(date_formt[0]>0 && date_formt[0]<13){
				return true;
			}else{
				return false;
			}
		}
		if(date_formt.length == 3){
			if(date_formt[0]<32 && date_formt[0]>0){
				if(date_formt[1]>0 && date_formt[1]<13){
					return true;
				}else{
					return false;
				}
			}
		}
	}
	
}

</script>