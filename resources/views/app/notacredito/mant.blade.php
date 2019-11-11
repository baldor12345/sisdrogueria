<style>
	.glyphicon-refresh-animate {
		-animation: spin .8s infinite linear;
		-ms-animation: spin .8s infinite linear;
		-webkit-animation: spinw .8s infinite linear;
		-moz-animation: spinm .8s infinite linear;
	}

	@keyframes spin {
		from { transform: scale(1) rotate(0deg);}
		to { transform: scale(1) rotate(360deg);}
	}
	
	@-webkit-keyframes spinw {
		from { -webkit-transform: rotate(0deg);}
		to { -webkit-transform: rotate(360deg);}
	}

	@-moz-keyframes spinm {
		from { -moz-transform: rotate(0deg);}
		to { -moz-transform: rotate(360deg);}
	}
</style>

<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($notacredito, $formData) !!}	
	<!-- datos de la nota de credito -->
	{!! Form::hidden('seriedoc_nc', 'BC'.$serie, array('id' => 'seriedoc_nc')) !!}
	{!! Form::hidden('numdoc_nc', $numero_doc, array('id' => 'numdoc_nc')) !!}
	{!! Form::hidden('total_nc', null, array('id' => 'total_nc')) !!}
	{!! Form::hidden('subtotal_nc', null, array('id' => 'subtotal_nc')) !!}
	{!! Form::hidden('igv_nc', null, array('id' => 'igv_nc')) !!}

	<!-- datos de del documento seleccionado -->
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('fecha_venc', null, array('id' => 'fecha_venc')) !!}

	{!! Form::hidden('idventas', null, array('id' => 'idventas')) !!}

	{!! Form::hidden('serieventa', null, array('id' => 'serieventa')) !!}
	{!! Form::hidden('numdocventa', null, array('id' => 'numdocventa')) !!}

	{!! Form::hidden('idcliente', null, array('id' => 'idcliente')) !!}
	{!! Form::hidden('idmedico', null, array('id' => 'idmedico')) !!}
	{!! Form::hidden('idvendedor', null, array('id' => 'idvendedor')) !!}
	{!! Form::hidden('nombrescliente', null, array('id' => 'nombrescliente')) !!}
	{!! Form::hidden('apellidoscliente', null, array('id' => 'apellidoscliente')) !!}
	{!! Form::hidden('dni_cliente', null, array('id' => 'dni_cliente')) !!}
	{!! Form::hidden('ruc', null, array('id' => 'ruc')) !!}

<div class="row">	
	<div class="mb-12 col-12 col-md-12">
		<div class="form-group">
			{!! Form::label('documento', 'Documento:', array('class' => 'col-sm-2 col-xs-12 control-label input-md', 'style'=>'')) !!}
			<div class="col-sm-3 col-xs-12" style="">
				{!! Form::select('documento', $cboDocumento, null, array('class' => 'form-control input-md', 'id' => 'documento')) !!}
			</div>
			{!! Form::label('serie_documento_v', 'Nro Doc:', array('class' => 'col-sm-2 col-xs-12 control-label input-md', 'style'=>'')) !!}
			<div class="col-sm-2 col-xs-12" style="">
				{!! Form::text('serie_documento_v', 'B'.$serie_v, array('class' => 'form-control input-md', 'id' => 'serie_documento_v', 'placeholder' => 'Serie...','readonly')) !!}
			</div>
			<div class="input-group" class="col-sm-3 col-xs-12" style="">
				{!! Form::text('numero_documento_v', null, array('class' => 'form-control input-md', 'id' => 'numero_documento_v', 'placeholder' => 'Num Doc...', 'onkeypress'=>'return filterFloat(event,this)')) !!}
				<span class="input-group-btn">
					{!! Form::button('<i class="glyphicon glyphicon-refresh" id="ibtnConsultar"></i>', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnConsultar', 'onclick' => 'consultarDet();')) !!}
				</span>
			</div>
		</div>
	</div>		
</div>
<div class="row">
	<div class="card-box mb-3 col-3 col-md-3">
		<hr>
		<table id="">
			<tr><td>Serie.</td><td style="padding-left: 18px;" id="serie_doc_vista">: <strong>{{ 'BC'.$serie }}</strong></td></tr>
			<tr><td>Numero</td><td style="padding-left: 18px;" id="numero_doc_vista">: <strong>{{ $numero_doc }}</strong></td></tr>
			<tr><td>Fecha</td><td style="padding-left: 18px;">: <strong><input type="date" id="fecha_nc" name="fecha_nc" value='{{ $date_now }}'></strong></td></tr>
			<tr><td>Sub Total.</td><td style="padding-left: 18px;" id="subtotal_vista">: <strong>0.0</strong></td></tr>
			<tr><td>Igv.</td><td style="padding-left: 18px;" id="igv_vista">: <strong>0.0</strong></td></tr>
			<tr><td>Total.</td><td style="padding-left: 18px;" id="total_vista">: <strong>0.0</strong></td></tr>
		</table>
	</div>
	<div class="card-box mb-9 col-9 col-md-9">
		<table id="detalle_transaccion">
		<h3 class="text-warning" id="mensaje_det">...</h3>
		</table>
		<hr>
		<div class="mb-12 col-12 col-md-12">
			{!! Form::label('motivo', 'Motivo:', array('class' => 'col-sm-1 col-xs-12 control-label input-md', 'style'=>'')) !!}
			<div class="col-sm-11 col-xs-12" style="">
				{!! Form::text('motivo', null, array('class' => 'form-control input-sm', 'id' => 'motivo', 'placeholder' => 'Ingreso motivo')) !!}
			</div>
		</div>
	</div>	
</div>
	<div class="row">
		<div class="table-responsive">
			<table id="" class="table table-bordered table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="35%">Producto</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Presentacion</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">F. Venc.</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Lote</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Precio</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Cantidad</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Subtotal</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Elim</th>
					</tr>
				</thead>
				<tbody id="tabla">
				
				</tbody>
			</table>
			<!-- <table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
			</table> -->
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarNotacredito', 'onclick' => 'guardar_notacredito(\''.$entidad.'\', this)')) !!}
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>

{!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1450');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$("#tabla").empty();
	$('#documento').change(function(){
		var serie_v = "{{ $serie_v }}";
		var serie = "{{ $serie }}";
		$('#serie_documento_v').val($(this).val()+""+serie_v);
		$('#seriedoc_nc').val($(this).val()+"C"+serie);
		$('#serie_doc_vista').empty();
		
		document.getElementById("serie_doc_vista").innerHTML = '<strong>: '+$(this).val()+"C"+serie+'</strong>';

		if($(this).val() == 'F'){
			getNumero_doc('F');
			$("#detalle_transaccion").empty();
			$("#tabla").empty();
		}else{
			$("#tabla").empty();
			$("#detalle_transaccion").empty();
			getNumero_doc('B');
			
		}
	});

}); 
function getNumero_doc(tipo_doc){
	
	$.get("notacreditos/numerodoc/"+tipo_doc+"/1",function(response, facultad){//obtener cod_numero
		var numero_doc = response;
		$('#numdoc_nc').val(numero_doc);
		$('#numero_doc_vista').empty();
		document.getElementById("numero_doc_vista").innerHTML = '<strong>: '+numero_doc+'</strong>';
	}); 
	;
	
}

function consultarDet(){

	$.get("notacreditos/"+$('#serie_documento_v').val()+"/"+$('#numero_documento_v').val()+"",function(response, facultad){//obtener el producto, su stock, precio_venta
		$("#detalle_transaccion").empty();
		$("#tabla").empty();
		$("#mensaje_det").empty();
		if(response.length == 0){
			$("#detalle_transaccion").empty();
			$('$mensaje_det').innerHtml('No se encontraron resultados');
		}
		var datos_cliente ="";
		var datos_dni_ruc ="";
		if(response[0].dni == null){
			datos_cliente = response[0].razon_social;
			datos_dni_ruc = response[0].ruc;
		}else{
			datos_cliente = response[0].nombres+' '+response[0].apellidos;
			datos_dni_ruc = response[0].dni;
		}
		var d = '<tr>'+
					'<td>Fecha de Emision.</td>:'+
					'<td style="padding-left: 18px;" id="fecha_emision">:<strong> '+response[0].fecha_emision+'</strong></td>'+
					'<td style="padding-left: 18px;">Fecha de Venc.</td>'+
					'<td style="padding-left: 18px;">: <strong> '+response[0].fecha_venc+'</strong></td>'+
				'</tr>'+
				'<tr>'+
					'<td>Señor(es).</td>:'+
					'<td style="padding-left: 18px;" id="fecha_emision">:<strong> '+datos_cliente+'</strong></td>'+
					'<td style="padding-left: 18px;">DNI/RUC.</td>'+
					'<td style="padding-left: 18px;">: <strong> '+response[0].dni+'</strong></td>'+
				'</tr>'+
				'<tr>'+
					'<td>Direccion del Cliente.</td>:'+
					'<td style="padding-left: 18px;" id="fecha_emision">:<strong> '+response[0].direccion+'</strong></td>'+
					'<td style="padding-left: 18px;">Tipo de Moneda.</td>'+
					'<td style="padding-left: 18px;">: <strong> SOLES</strong></td>'+
				'</tr>'+
				'<tr>'+
					'<td>Condicion de Pago.</td>:'+
					'<td style="padding-left: 18px;" id="fecha_emision">:<strong> AL CONTADO</strong></td>'+
					'<td style="padding-left: 18px;">Serie-Numero.</td>'+
					'<td style="padding-left: 18px;">: <strong> '+response[0].serie_doc+'-'+response[0].numero_doc+'</strong></td>'+
				'</tr>';
			$("#detalle_transaccion").append(d);
			$("#idventas").val(response[0].ventas_id);
			$("#serieventa").val(response[0].serie_doc);
			$("#numdocventa").val(response[0].numero_doc);
			$("#idventas").val(response[0].ventas_id);
			$("#idcliente").val(response[0].cliente_id);
			$("#idmedico").val(response[0].medico_id);
			$("#idvendedor").val(response[0].vendedor_id);
			$("#nombrescliente").val(response[0].nombres);
			$("#apellidoscleinte").val(response[0].apellidos);



			var data_d = JSON.stringify(response[1]);
			var data_js = JSON.parse(data_d);
			console.log(response);
			for(var i=0; i<data_js.length; i++){
				var dato_splitpc = data_js[i].lote.split(';');
				var cant_un =  0;
				var lote =  "";
				var fecha_v = "";

				for(var k=0; k<dato_splitpc.length; k++){
					var dato_splitdp = dato_splitpc[k].split(':');
					cant_un    =  (dato_splitdp[0]/data_js[i].cant_unidad_x_presentacion);
					lote 		= dato_splitdp[1];
					fecha_v 	= dato_splitdp[2];
					var dat_cant_lot_fv = ""+dato_splitdp[0]+":"+dato_splitdp[1]+":"+dato_splitdp[2]+"";

					var table_d = 	'<tr class="datos-producto" id="dat-prod'+i+''+k+'" producto_id="'+data_js[i].producto_id+'" cantidad="'+data_js[i].cantidad_total+'" cantidad_presentacion="'+cant_un+'" presentacion_id="'+data_js[i].presentacion_id+'"  precio_venta="'+data_js[i].precio_unitario+'" fecha_venc="'+fecha_v+'" afecto="'+data_js[i].producto_afecto+'" lote_producto="'+dat_cant_lot_fv+'"  detalle_venta_id="'+data_js[i].detalle_venta_id+'">'+
									'<td class="input-sm" width="35%">'+data_js[i].producto_descripcion+' - '+data_js[i].presentacion_nombre+' x '+data_js[i].cant_unidad_x_presentacion+' Unidades'+'</td>'+
									'<td class="input-sm" width="15%" align="center">'+data_js[i].presentacion_nombre+'</td>'+
									'<td class="input-sm" width="10%" align="center" >'+fecha_v+'</td>'+
									'<td class="input-sm" width="5%" align="center">'+lote+'</td>'+
									'<td class="input-sm" width="10%" align="center">'+data_js[i].precio_unitario+'</td>'+
									'<td class="input-sm" width="10%" align="center"><input type="number" readonly="true" value='+cant_un+' id="cantidad_updated'+i+''+k+'" name="cantidad_updated'+i+''+k+'" onclick="cambiarCant('+i+','+k+','+cant_un+','+data_js[i].precio_unitario+')"  min="1" max="'+cant_un+'"></td>'+
									'<td class="input-sm" width="10%" align="center" id="dat-subtotal'+i+''+k+'" >'+parseFloat((parseInt(cant_un)*parseFloat(data_js[i].precio_unitario)))+'</td>'+
									'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this, '+(parseInt(cant_un)*parseFloat(data_js[i].precio_unitario))+');" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
								'</tr>';
					$("#tabla").append(table_d);
					cant_un =  (data_js[i].cantidad - (dato_splitdp[0]/data_js[i].cant_unidad_x_presentacion));
				}
				
			}
			calcularPrecio();
		});
}

function contar_registros(){
	var cont =0;
	$('.datos-producto').each(function() {
		cont ++;
	});
	return cont;
}

function calcularPrecio(){
	var porcent_igv = 0.18;

	var totalAfecto = 0;
	var totalInafecto = 0;
	$('.datos-producto').each(function() {
		var precio = parseFloat($(this).attr("precio_venta"));
		var cantidad = parseFloat($(this).attr("cantidad_presentacion"));
		var afecto = $(this).attr("afecto") == 'S'?true:false;
		console.log('Precio: '+precio+' cant: '+cantidad+ ' afecto: '+afecto);
		if(afecto){
			totalAfecto += precio * cantidad;
		}else{
			totalInafecto += precio * cantidad;
		}
	});

	var total = totalAfecto + totalInafecto;
	var subtotal = (totalAfecto>0? totalAfecto/1.18: 0) + totalInafecto;
	var igv = total - subtotal;

	var res = [igv,subtotal, total];

	$('#total_nc').val(RoundDecimal(total));
	$('#subtotal_nc').val(RoundDecimal(subtotal));
	$('#igv_nc').val(RoundDecimal(igv));
	document.getElementById("total_vista").innerHTML = '<strong>: '+RoundDecimal(total, 2)+'</strong>';
	document.getElementById("subtotal_vista").innerHTML = '<strong>: '+RoundDecimal(subtotal, 2)+'</strong>';
	document.getElementById("igv_vista").innerHTML = '<strong>: '+RoundDecimal(igv, 2)+'</strong>';
	return res;
}

function quitar(t, subtotal){
	var mensaje;
    var opcion = confirm("Desea ELiminar el producto registrado?");
    if (opcion == true) {
        var td = t.parentNode;
		var tr = td.parentNode;
		var table = tr.parentNode;
		table.removeChild(tr);
		var valores = calcularPrecio();
	}

}

function guardar_notacredito(entidad, idboton) {
	var correcto = false;

	var comentario = $('#motivo').val();
	if(comentario !== ""){
		var idformulario = IDFORMMANTENIMIENTO + entidad;
		var data         = submitForm_venta(idformulario);
		var respuesta    = '';
		var listar       = 'NO';
		if ($(idformulario + ' :input[id = "listar"]').length) {
			var listar = $(idformulario + ' :input[id = "listar"]').val()
		};
		data.done(function(msg) {
			respuesta = msg;
			if(respuesta[0]== 'OK'){
				$(idboton).button('loading');
			}
			
		}).fail(function(xhr, textStatus, errorThrown) {
			respuesta = 'ERROR';
			$(idboton).removeClass('disabled');
			$(idboton).removeAttr('disabled');
			$(idboton).html('<i class="fa fa-check fa-lg"></i>Guardar');
		}).always(function() {
			if(respuesta === 'ERROR'){
			}else{
				if (respuesta[0] === 'OK') {

					var numserie_venta 		= JSON.stringify(respuesta[1]);
					var numdoc_venta		= JSON.stringify(respuesta[2]);
					var notacredito 		= JSON.stringify(respuesta[3]);
					var cliente 			= JSON.stringify(respuesta[4]);
					var detalle_notacredito = JSON.stringify(respuesta[5]);
					var codigo_medico 		= JSON.stringify(respuesta[6]);
					var iniciales_vendedor 	= JSON.stringify(respuesta[7]);

					// var lsentradas = respuesta[4];
					declarar(numserie_venta, numdoc_venta, notacredito,cliente,detalle_notacredito,$("#documento").val(),codigo_medico,iniciales_vendedor);
					
					cerrarModal();
					if (listar === 'SI') {
						buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
					}        
				} else {
				// alert(respuesta[0]);
				$('#divMensajeError'+entidad).html('<div class="alert alert-danger"><strong>'+respuesta[0]+'</strong></div>');
				//mostrarErrores(respuesta, idformulario, entidad);
				$(idboton).removeClass('disabled');
				$(idboton).removeAttr('disabled');
				$(idboton).html('<i class="fa fa-check fa-lg"></i>Guardar');
				}
				
			}
		});
	}else{
		$('#divMensajeError{{ $entidad }}').html("<div class='alert alert-danger'>por favor ingrese motivo, es obligatorio</div>");
	}
	
}

function submitForm_venta(idformulario) {
	var i=0;
	var datos="";
	$('.datos-producto').each(function() {
		datos += 	"&prod_id"+i+"="+$(this).attr("producto_id")+
					"&present_id"	+i+"="+$(this).attr("presentacion_id")+
					"&cant_pres"	+i+"="+$(this).attr("cantidad_presentacion")+
					"&precio_venta"	+i+"="+$(this).attr("precio_venta")+
					"&cant_prod"+i+"="+$(this).attr("cantidad")+
					"&lote_"+i+"="+$(this).attr("lote_producto")+
					"&fecha_vcto"+i+"="+$(this).attr("fecha_venc")+
					"&iddetalleventa"+i+"="+$(this).attr("detalle_venta_id");
		i++;
	});
	datos +="&cantidad="+i;
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


function declarar(venta,cliente,detalla_ventas,idtipodoc,medico,vendedor){
 	if(idtipodoc=="B"){
 		tipodocumento = "Boleta";
 	}else{
 		tipodocumento = "Factura";
 	}
    var ajax_function = $.ajax({
        async:true,    
        cache:false,
        type: 'GET',
        url: "http://localhost/clifacturacion/controlador/contComprobante.php?funcion=enviar"+tipodocumento,
        data: "venta="+venta+"&cliente="+cliente+"&medico="+medico+"&vendedor="+vendedor+"&detalle="+detalla_ventas,
        success: function (data, textStatus, jqXHR) {
            alert("Enviado");
        },
        beforeSend: function (xhr) {
            //alert("Enviando informacion");
        }
    });
}



function RoundDecimal(numero, decimales) {
	numeroRegexp = new RegExp('\\d\\.(\\d){' + decimales + ',}');   // Expresion regular para numeros con un cierto numero de decimales o mas
	if (numeroRegexp.test(numero)) {         // Ya que el numero tiene el numero de decimales requeridos o mas, se realiza el redondeo
		return Number(numero.toFixed(decimales));
	} else {
		return Number(numero.toFixed(decimales)) === 0 ? 0 : numero;  // En valores muy bajos, se comprueba si el numero es 0 (con el redondeo deseado), si no lo es se devuelve el numero otra vez.
	}
}

$('.input-number').on('input', function () { 
	this.value = this.value.replace(/[^0-9]/g,'');
});

function cambiarCant(i, k, cant, precio_unitario) {
	var idcontatenado = '#'+'dat-prod'+i+k;
	var idcontatevalor = '#'+'cantidad_updated'+i+k;
	var idcontatevalorsub = "dat-subtotal"+i+k;

	$(idcontatevalor).val("");
	var dat = prompt('Ingrese cantidad menor que '+cant);
	if(dat <=cant){
		$(idcontatenado).attr("cantidad_presentacion", dat);
		$(idcontatevalor).val(dat);
		document.getElementById(idcontatevalorsub).innerHTML = ''+(dat*precio_unitario)+'';
		calcularPrecio();
	}else{
		alert("el numero ingresado debe ser menor que "+cant);
		$(idcontatevalor).val("");
	}

	
	
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