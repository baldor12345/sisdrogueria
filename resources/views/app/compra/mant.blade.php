<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($compra, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">
		<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group input-sm" style="height: 20px;" >
				{!! Form::label('producto_id', 'Producto:', array('class' => 'col-sm-1 col-xs-12 control-label', 'style'=>'height: 20px')) !!}
				<div class="col-sm-6 col-xs-12" style="height: 20px;">
					{!! Form::select('producto_id', $cboProducto, null, array('class' => 'form-control input-sm', 'id' => 'producto_id')) !!}
				</div>
			</div>
			<div class="form-group">
				<table>
					<tr style="height: 20px; padding-top:20px">
						<td>&nbsp;</td>
						<td class=" input-sm" style="text-align:right;"><b>Unidad Compra:</b></td>
						<td>{!! Form::select('presentacion_id', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'presentacion_id','style'=>'text-align: left;')) !!}</td>
						<td class=" input-sm" style="text-align:right;"><b>Cant./Presentacion:</b></td>
						<td><input class="form-control input-sm input-number"   id="cantidad" size="3" name="cantidad" type="text"></td>
						<td class=" input-sm" style="text-align:right;"><b>Unidad/Presentacion:</b></td>
						<td><input class="form-control input-sm input-number"  id="unidad_presentacion" size="7" name="unidad_presentacion" type="text"></td>
						<td class=" input-sm" style="text-align:right;"><b>P.Compra:</b></td>
						<td><input class="form-control input-sm" style="width:80px" onkeypress="return filterFloat(event,this);" id="preciocompra" size="3" name="preciocompra" type="text" style="text-align: right;"></td>
						<td class=" input-sm" style="text-align:right;"><b>Afecto:</b></td>
						<td>{!! Form::select('afecto', $cboAfecto, null, array('class' => 'form-control input-sm', 'id' => 'afecto','style'=>'text-align: left;')) !!}</td>
					</tr>
					<tr style="height: 20px; padding-top:20px">
						<td>&nbsp;</td>
						<td class=" input-sm" style="text-align:right;"><b>Fecha Venc.:</b></td>
						<td><input class="form-control input-sm" id="fechavencimiento" style="width:130px" size="6" name="fechavencimiento" type="date"></td>
						<td class=" input-sm" style="text-align:right;"><b>Unidad Base:</b></td>
						<td>{!! Form::select('unidad_id', $cboUnidad, null, array('class' => 'form-control input-sm', 'id' => 'unidad_id','style'=>'text-align: left;')) !!}</td>
						<td class=" input-sm" style="text-align:right;"><b>Factor:</b></td>
						<td><input class="form-control input-sm" id="factor" onkeypress="return filterFloat(event,this);"  size="3" name="factor" type="text" style="text-align: right;"></td>
						<td class=" input-sm" style="text-align:right;"><b>P.Venta/Unidad:</b></td>
						<td><input class="form-control input-sm" id="precioventa" onkeypress="return filterFloat(event,this);"  size="3" name="precioventa" type="text" style="text-align: right;"></td>
						<td class=" input-sm" style="text-align:right;"><b>Lote:</b></td>
						<td><input class="form-control input-sm" id="lote" size="6" style="width:80px" name="lote" type="text"></td>
						<td style="text-align:center;"><button id="btnAgregar" name="btnAgregar" class="btn btn-info btn-xs" onclick="agregar();" title="" type="button"><i class="glyphicon glyphicon-plus"></i> agregar</button></td>
					</tr>
				</table>
			</div>
			<div class="form-group">
				<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
		            <thead>
		                <tr>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="40%">Producto</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Presentacion</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Afecto</th>
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

		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group ">
				{!! Form::label('documento', 'Documento:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('documento', $cboDocumento, null, array('class' => 'form-control input-sm', 'id' => 'documento')) !!}
				</div>
			</div>

			<div class="form-group">
				{!! Form::label('credito', 'T. pago:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('credito', $cboCredito, null, array('class' => 'form-control input-sm', 'id' => 'credito', 'onchange'=>'cambiarcredito();')) !!}
				</div>
			</div>
			<div class="form-group" id="num_days" style="display:none;">
				{!! Form::label('numero_dias', 'Nro Dias:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::text('numero_dias', null, array('class' => 'form-control input-xs input-number', 'id' => 'numero_dias', 'placeholder' => '')) !!}
				</div>
			</div>
			<div class="form-group" >
				{!! Form::label('serie_documento', 'Nro Doc:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}	
				<div class="col-sm-4 col-xs-12" style="height: 25px;">
					{!! Form::text('serie_documento', null, array('class' => 'form-control input-xs', 'id' => 'serie_documento', 'placeholder' => 'serie')) !!}
				</div>
				<div class="col-sm-5 col-xs-12" style="height: 25px;">
					{!! Form::text('numero_documento', null, array('class' => 'form-control input-xs', 'id' => 'numero_documento', 'placeholder' => 'num documento')) !!}
				</div>
			</div>
			<div class="form-group " >
				{!! Form::label('fecha', 'Fecha:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha')) !!}
				</div>
			</div>
			<div class="form-group " >
				{!! Form::label('fecha_caducidad', 'Fecha Venc.:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::date('fecha_caducidad', null, array('class' => 'form-control input-xs', 'id' => 'fecha_caducidad')) !!}
				</div>
			</div>
			<br>
			<div class="form-group " >
				{!! Form::label('proveedor_id', 'Proveedor:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('proveedor_id', $cboProveedor, null, array('class' => 'form-control input-sm', 'id' => 'proveedor_id')) !!}
				</div>
			</div>
			<div class="form-group" >
				{!! Form::label('total', 'Subtotal:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-4 col-xs-12" style="height: 25px;">
					{!! Form::text('total', null, array('class' => 'form-control input-xs', 'id' => 'total', 'placeholder' => '','readonly')) !!}
				</div>
				{!! Form::label('igv', 'Igv:', array('class' => 'col-sm-1 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-4 col-xs-12" style="height: 25px;">
					{!! Form::text('igv', $igv, array('class' => 'form-control input-xs', 'id' => 'igv', 'placeholder' => '', 'readonly')) !!}
				</div>
			</div>
			<div class="form-group" >
				{!! Form::label('total_n', 'Importe Total:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::text('total_n', null, array('class' => 'form-control input-xs', 'id' => 'total_n', 'placeholder' => '', 'readonly')) !!}
				</div>
			</div>
			<br>
			<div class="form-group">
				<div class="col-lg-12 col-md-12 col-sm-12 text-right">
					{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarCompraP', 'onclick' => 'guardar_compra(\''.$entidad.'\', this)')) !!}
					{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
				</div>
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
	$('#fechavencimiento').val(fecha_horaApert);
	$('#fecha').val(fecha_horaApert);
	$('#fecha_caducidad').val(fecha_horaApert);

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

	
	$("input[name=cantidad]").change(function(event){
		var cant = parseInt($('#cantidad').val());
		var cantidad_unidad = parseInt($('#unidad_presentacion').val());
		$('#factor').val('');
		$('#factor').val(cant*cantidad_unidad);
	});

	$("input[name=unidad_presentacion]").change(function(event){
		var cant = parseInt($('#cantidad').val());
		var cantidad_unidad = parseInt($('#unidad_presentacion').val());
		$('#factor').val('');
		$('#factor').val(cant*cantidad_unidad);
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
		$.get("compra/"+$(this).val()+"", function(response, productos){
			console.log(response);
			if(response.length !=0 ){
				$('#presentacion_id').val(response[0].presentacion_id);
				$('#unidad_presentacion').val(response[0].cant_unidad_x_presentacion);
				$('#preciocompra').val(response[0].precio_compra);
				$('#precioventa').val(response[0].precio_venta_unitario);
				$('#afecto').val(response[0].afecto);
				$('#presentacion_id').prop("disabled", true);
				//$('#factor').prop("readonly", true);
			}else{
				window.alert("Producto no esta registrado en el inventario!");
			}
		});
	});

}); 

function cambiarcredito(){
	var doc = $('#credito').val();
	if(doc == 'CO'){
		$('#num_days').css('display','none');
		//document.getElementById("numero_dias").disabled = false;
	}
	if(doc == 'CR'){
		$('#num_days').css('display','block');
		$('#numero_dias').val("");
		//$('#numero_dias').prop("readonly", true);
	}
}


function agregar(){
	//datos del producto
	var producto_dat ="";
	var select = "";
	var select = document.getElementById('producto_id');
	producto_dat = select.options[select.selectedIndex].innerText;
	//datos de la presentacion
	var presentacion_dat ="";
	var select_p = "";
	var select_p = document.getElementById('presentacion_id');
	presentacion_dat = select_p.options[select_p.selectedIndex].innerText;
	
	//afecto
	var afectto_dat ="";
	var select_af = "";
	var select_af = document.getElementById('afecto');
	afectto_dat = select_af.options[select_af.selectedIndex].innerText;
	


	var preciocompra 		= $('#preciocompra').val();
	var presentacion_id 	= $('#presentacion_id').val();
	var laboratorio_id 		= $('#laboratorio_id').val();
	var precioventa 		= $('#precioventa').val();
	var cantidad 			= $('#cantidad').val();
	var fechavencimiento 	= $('#fechavencimiento').val();
	var lote 				= $('#lote').val();
	var factor 				= $('#factor').val();
	var unidad_id 				= $('#unidad_id').val();

	if($('#producto_id').val() !='0'){
		if(presentacion_id !=""){
			if(preciocompra !=""){
				if(precioventa !=""){
					if(cantidad!=""){
						if(factor !=""){
							if($('#unidad_id').val() != '0'){
								if(fechavencimiento!=""){
									if(lote!=""){
										var subtotal ="";
										subtotal = parseInt(cantidad)*parseFloat(preciocompra);
										var t_parcial =0;
										var t_igv =0;
										if($('#total').val() != ""){
											t_parcial = parseFloat($('#total').val());
											t_igv = parseFloat($('#total_n').val());
										}else{
											t_parcial=0;
											t_igv=0;
										}

										var total = t_parcial+subtotal;
										
										if(($('#afecto').val()).trim() == 'S'){
											var Igv__= parseFloat('{{ $igv }}');
											t_igv = (Igv__*subtotal) + total;
										} 
										if(($('#afecto').val()).trim() == 'N'){
											t_igv = t_igv+subtotal;
										} 
										var d = '<tr class="datos-producto" id_producto="'+$('#producto_id').val()+'" afect_="'+$('#afecto').val()+'" dat_factor="'+factor+'" id_unidad="'+$('#unidad_id').val()+'" id_laboratorio="'+laboratorio_id+'" precio_compra="'+preciocompra+'"  precio_venta="'+precioventa+'" canti="'+cantidad+'" fecha_venc="'+fechavencimiento+'" lot="'+lote+'">'+
											'<td class="input-sm" width="40%">'+producto_dat+'</td>'+
											'<td class="input-sm" width="15%" align="center">'+presentacion_dat+'</td>'+
											'<td class="input-sm" width="5%" align="center">'+afectto_dat+'</td>'+
											'<td class="input-sm" width="10%" align="center" >'+fechavencimiento+'</td>'+
											'<td class="input-sm" width="5%" align="center">'+cantidad+'</td>'+
											'<td class="input-sm" width="10%" align="center">'+preciocompra+'</td>'+
											'<td class="input-sm" width="10%" align="center">'+parseFloat(subtotal)+'</td>'+
											'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this, '+subtotal+', '+$('#afecto').val()+');" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
											'</tr>';
										$("#tabla").append(d);
										
										$('#total').val(total);
										$('#total_n').val(t_igv);

										//vaciar datos
										$('#producto_id').val(0);
										$('#unidad_id').val(0);
										$('#presentacion_id').val(0);
										$('#categoria_id').val(0);
										$('#laboratorio_id').val(0);
										$('#preciocompra').val("");
										$('#unidad_presentacion').val("");
										$('#precioventa').val("");
										$('#cantidad').val("");
										$('#fechavencimiento').val("");
										$('#factor').val("");
										$('#lote').val("");
									}else{
										window.alert("ingrese lote, debe ser obligatorio!");
										$('#lote').focus();
									}
								}else{
									window.alert("seleccione fecha de vencimiento del producto seleccionado!");
									$('#fechavencimiento').focus();
								}
							}else{
								window.alert("seleccione Unidad Base!");
								$('#unidad_id').focus();
							}

						}else{
							window.alert("factor no debe ser vacio!");
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

function quitar(t, subtotal, afecto){
	var mensaje;
    var opcion = confirm("Desea ELiminar el producto registrado?");
    if (opcion == true) {
        var td = t.parentNode;
		var tr = td.parentNode;
		var table = tr.parentNode;
		table.removeChild(tr);
		if(afecto.trim() == 'N'){
			var total_parcial = parseFloat($('#total').val());
			$('#total').val(parseFloat(total_parcial)-parseFloat(subtotal));
		}
		if(afecto.trim() == 'S'){
			var igv_ = parseFloat('{$igv}');
			var total_parcial = parseFloat($('#total').val());
			var cal_igv = (parseFloat(subtotal)*igv_);
			$('#total').val(parseFloat(total_parcial)-cal_igv);
		}
	}

}

function guardar_compra(entidad, idboton) {
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
		$(idboton).html('<i class="fa fa-check fa-lg"></i>Registrar');
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
				$(idboton).html('<i class="fa fa-check fa-lg"></i>Registrar');
			}
		}
	});
}
function submitForm_control(idformulario) {
	var i=0;
	var datos="";
	$('.datos-producto').each(function() {
		datos += 	"&id_producto"		+i+"="+$(this).attr("id_producto")+
					"&id_laboratorio"	+i+"="+$(this).attr("id_laboratorio")+
					"&precio_compra"	+i+"="+$(this).attr("precio_compra")+
					"&precio_venta"		+i+"="+$(this).attr("precio_venta")+
					"&cant"				+i+"="+$(this).attr("canti")+
					"&fecha_vencim"		+i+"="+$(this).attr("fecha_venc")+
					"&factor_"			+i+"="+$(this).attr("dat_factor")+
					"&id_unidad"		+i+"="+$(this).attr("id_unidad")+
					"&lot"				+i+"="+$(this).attr("lot");
		i++;
	});
	datos += "&cantidad="+i;
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

</script>