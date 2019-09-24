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
{!! Form::model($venta, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('afecto', null, array('id' => 'afecto')) !!}
	{!! Form::hidden('stockg', null, array('id' => 'stockg')) !!}
	{!! Form::hidden('fecha_vencg', null, array('id' => 'fecha_vencg')) !!}
<div class="row">				
	<div class="mb-3 col-12 col-md-12">
		<div class="panel-title">Datos de Guia</div>
		<div class="panel-body">
			<div class="col-4 col-md-4">
				<div class="form-group">
					{!! Form::label('fecha_emision', 'Fecha de Emision:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::date('fecha_emision', $fecha_defecto, array('class' => 'form-control input-sm', 'id' => 'fecha_emision')) !!}
					</div>
				</div>

				<div class="form-group credito">
					{!! Form::label('fecha_traslado', 'Fecha de inicio traslado:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::date('fecha_traslado', $fecha_defecto, array('class' => 'form-control input-sm', 'id' => 'fecha_traslado')) !!}
					</div>
				</div>
				<div class="form-group ">
					{!! Form::label('serie', 'Nro Doc:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-3 col-xs-12" style="">
						{!! Form::text('serie', 'G'.$serie, array('class' => 'form-control input-sm', 'id' => 'serie', 'placeholder' => 'serie')) !!}
					</div>
					<div class="col-sm-6 col-xs-12" style="">
						{!! Form::text('numero', $numero_doc, array('class' => 'form-control input-sm', 'id' => 'numero', 'placeholder' => 'num documento')) !!}
					</div>
				</div>
				
				<div class="form-group text-right">
					{!! Form::label('motivo', 'Motivo de Traslado:', array('class' => 'col-sm-3 col-xs-12 control-label input-md', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('motivo', null, array('class' => 'form-control input-md', 'id' => 'motivo', 'placeholder' => '')) !!}
					</div>
				</div>
			</div>
			<div class="col-4 col-md-4">
				<div class="form-group " >
					{!! Form::label('modalidad_transporte', 'Modalidad de transporte:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('modalidad_transporte', '', array('class' => 'form-control input-sm', 'id' => 'modalidad_transporte', 'placeholder' => 'Ibgrese modalidad de transporte')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('pesobruto_total', 'Peso Bruto Total:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('pesobruto_total', '', array('class' => 'form-control input-sm', 'id' => 'pesobruto_total', 'placeholder' => 'Peso bruto total KGM', 'onkeypress'=>'return filterFloat(event,this)')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('nombres_destinatario', 'Apellidos y nombres:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('nombres_destinatario', '', array('class' => 'form-control input-sm', 'id' => 'nombres_destinatario', 'placeholder' => 'Apellidos y Nombres, denominacion o razon', 'onkeypress'=>'')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('doc_identidad', 'Doc. Identidad:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('doc_identidad', '', array('class' => 'form-control input-sm', 'id' => 'doc_identidad', 'placeholder' => 'Ingrese documento de identidad ', 'onkeypress'=>'return filterFloat(event,this)')) !!}
					</div>
				</div>
			</div>
			<div class="col-4 col-md-4">
				<div class="form-group " >
					{!! Form::label('direccion_partida', 'Direccion Partida:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('direccion_partida', '', array('class' => 'form-control input-sm', 'id' => 'direccion_partida', 'placeholder' => 'Ibgrese direccion de partida', 'onkeypress'=>'')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('direccion_llegada', 'Direccion llegada:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('direccion_llegada', '', array('class' => 'form-control input-sm', 'id' => 'direccion_llegada', 'placeholder' => 'Direccion de llegada', 'onkeypress'=>'')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('numero_placa', 'N° Placa transporte:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('numero_placa', '', array('class' => 'form-control input-sm', 'id' => 'numero_placa', 'placeholder' => 'Ingrese numero de placa', 'onkeypress'=>'')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('tipodoc_conductor', 'Tipo Doc. conductor:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('tipodoc_conductor', '', array('class' => 'form-control input-sm', 'id' => 'tipodoc_conductor', 'placeholder' => 'Ingrese tipo de documento (DNI, RUC, CARNET, ETC..)', 'onkeypress'=>'return filterFloat(event,this)')) !!}
					</div>
				</div>
				<div class="form-group " >
					{!! Form::label('numerodoc_conductor', 'N° Doc. Conductor:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'')) !!}
					<div class="col-sm-9 col-xs-12" style="">
						{!! Form::text('numerodoc_conductor', '', array('class' => 'form-control input-sm', 'id' => 'numerodoc_conductor', 'placeholder' => 'Ingrese N° Doc. del Conductor', 'onkeypress'=>'return filterFloat(event,this)')) !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="row card-box">
		<div class="alert alert-success col-12 col-md-12" id="detalle_prodg">
			<table id="tabla_temp" class="" style="">
				<tr><td>Producto</td><td style="padding-left: 10px;"><label id="producto_infg">: </label></td></tr>
				{{-- <tr><td>Fecha Venc.:</td><td><label id="fecha_v_infg" fecha_v=''></label></td></tr> --}}
				<tr><td style="padding-left: 10px;"> Stock (Unidades)</td><td style="padding-left: 10px;"><label id="stock_infg" stock='0'>: </label></td><td style="padding-left: 10px;"> Fecha Venc.</td><td style="padding-left: 10px;"><label id="fecha_v_infg" fecha_v=''>: </label></td></tr>
				<tr><td>Unidad</td><td><label id="unidad_infg" lote=''>: </label></td><td id="cant_unidades_titulog" style="padding-left: 10px;">Cantidad Unidades</td><td style="padding-left: 10px;"><label cantidad_u="0" id="cant_unidades_infg">: </label></td><td style="padding-left: 10px;"> Afecto</td><td style="padding-left: 10px;"><label id='afecto_infg' afecto=''>: </label></td></tr>
			</table>
		</div>
		
		<div class="form-group">
			<div class="form-group col-5 col-md-5 col-sm-12 col-xs-12 text-left">
				{!! Form::label('cboProductog', 'Producto:', array('class' => ' col-sm-12 text-left')) !!}
				{!! Form::select('cboProductog', $cboProducto, null, array('class' => 'form-control input-sm', 'id' => 'cboProductog')) !!}
			</div>
			<div class="form-group col-4 col-md-4 col-sm-12 col-xs-12 text-left" style="margin-left: 3px">
				{!! Form::label('cboPresentaciong', 'Unidad:', array('class' => ' col-md-12 text-left')) !!}
				{!! Form::select('cboPresentaciong', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'cboPresentaciong')) !!}
			</div>
			<div class="form-group col-1 col-md-1 text-left" style="margin-left: 3px">
				{!! Form::label('cantidadg', 'Cantidad:', array('class' => 'col-sm-12 col-xs-12')) !!}
				{!! Form::text('cantidadg', null, array('class' => 'form-control input-sm', 'id' => 'cantidadg', 'placeholder' => 'Cantidad',  'onkeypress'=>'return filterFloat(event,this)')) !!}
			</div>
			
			<div class="form-group col-1 col-md-1 text-left" style="margin-left: 3px">
				{!! Form::label('btnAgregarg', 'Agregar:', array('class' => 'col-sm-12 text-left')) !!}
				{!! Form::button('<i class="fa fa-plus fa-lg"></i> Agregar', array('class' => 'btn btn-success btn-sm', 'id' => 'btnAgregarg', 'onclick' => 'agregar()')) !!}
			</div>
			
		</div>
		<div class="table-responsive">
			<table id="tablag" class="table table-bordered table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th bgcolor="#E0ECF8" class="text-center input-sm" 	>Lote</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" 	>Descripción</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm"	>Unidad de medida</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm" 	>Cantidad</th>
						<th bgcolor="#E0ECF8" class="text-center input-sm"	>Elim</th>
					</tr>
				</thead>
			</table>
		</div>
		<div class="form-group col-6 col-md-6 text-left" style="margin-left: 3px">
			{!! Form::label('observaciones', 'Observaciones:', array('class' => 'col-sm-12 col-xs-12')) !!}
			{!! Form::text('observaciones', null, array('class' => 'form-control input-sm', 'id' => 'observaciones', 'placeholder' => 'Precio')) !!}
		</div> 
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarVentag', 'onclick' => 'guardar_guia(\''.$entidad.'\', this)')) !!}
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelarg'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>

{!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$('#detalle_prodg').hide();
	$('#cboProductog').select2({
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


	$('#cboProductog').change(function(){

		$.get("ventas/"+$(this).val()+"",function(response, facultad){//obtener el producto, su stock, precio_venta
			var producto = response[0];
			var total_seleccionado = cantidad_seleccionado(producto.id);

			var stock = response[1] - total_seleccionado;
			var precio_unidad = response[2];
			var cboPresentacion = response[3];
			var fecha_venc = response[4];
			var lote = response[5];

				$('#producto_infg').text(": "+producto.descripcion);
				$('#stock_infg').text(": "+stock);
				$('#stockg').val(stock);
				$('#unidad_infg').text("");
				$('#afecto_infg').text(producto.afecto == 'S'? ": SI":": NO");
				$('#afecto_infg').attr('afecto',producto.afecto);
				$('#unidad_infg').attr('lote',lote);
			
				$('#fecha_v_infg').text(fecha_venc!=null?": "+fecha_venc:": ");
				$('#fecha_vencg').val(fecha_venc!=null?fecha_venc:"");
				$('#afectog').val(producto.afecto);
				$('#cboPresentaciong').empty();
				$('#cboPresentaciong').append('<option value="0">Seleccione.</option>');
				$('#cboPresentaciong').append(cboPresentacion);
				$('#detalle_prodg').show();
		});
	});

	$('#cboPresentaciong').change(function(){
		var producto_id = $('#cboProductog').val();
		$.get("ventas/"+producto_id+"/"+$('#cboPresentaciong').val()+"",function(response, facultad){//
			var productoPresentacion = response;
			var precio_unidad = productoPresentacion.precio_venta_unitario;
			var cantidad_unidades_presentacion = productoPresentacion.cant_unidad_x_presentacion;
			var cantidad = $('#cantidadg').val()==""?1:$('#cantidadg').val();
			var total_unidades = cantidad_unidades_presentacion * cantidad;
			$('#unidad_infg').text(": "+ $('#cboPresentaciong option:selected').html());
			$('#cant_unidades_infg').text(": "+cantidad_unidades_presentacion);
			$('#cant_unidades_infg').attr('cantidad_u',cantidad_unidades_presentacion);
			$('#cant_unidades_titulog').text("Cantidad Unidades/"+$('#cboPresentaciong option:selected').html()+" ");
		});      
			
	});

}); 
function getNumero_doc(tipo_doc){
	
	$.get("ventas/numerodoc/"+tipo_doc+"/1",function(response, facultad){//obtener cod_numero
		 numero_doc = response+"";
		 $('#numero_documento').val(numero_doc);
	}); 
	;
	
}

function cantidad_seleccionado(producto_id){
	var cantidad_total = 0;
	$('.datos-producto').each(function() {
		var cant = parseFloat($(this).attr("cantidad"));
		var prod_id = parseFloat($(this).attr("producto_id"));
		var pres_id = parseFloat($(this).attr("presentacion_id"));
		if(prod_id == producto_id){
			cantidad_total += cant;
		}
	});
	return cantidad_total;
}
function contar_registros(){
	var cont =0;
	$('.datos-producto').each(function() {
		cont ++;
	});
	return cont;
}

function agregar(){

	var cant_u_present = parseInt($('#cant_unidades_infg').attr('cantidad_u'));
	var nombre_producto = $('#cboProductog option:selected').html();
	var nombre_presentacion = $('#cboPresentaciong option:selected').html();
	var afecto = $('#afecto_infg').attr('afecto');
	var presentacion_id = parseInt($('#cboPresentaciong').val());
	var producto_id = parseInt($('#cboProductog').val());

	var cantidad = parseInt($('#cantidadg').val());
	var cantidad_total = cantidad * cant_u_present;
	var fechavencimiento = $('#fecha_vencg').val();

	var lote = $('#unidad_infg').attr('lote');
	var stock = $('#stockg').val();
	if(stock >= cantidad_total){
	if(producto_id!= '0'){
		if(presentacion_id !='0'){
			if(cantidad!=""){
				
				var d = '<tr class="datos-producto" producto_id="'+producto_id+'" cantidad="'+cantidad_total+'" cantidad_presentacion="'+cantidad+'" presentacion_id="'+presentacion_id+'" fecha_venc="'+fechavencimiento+'" afecto="'+afecto+'" >'+
					'<td class="input-sm" align="center">'+lote+'</td>'+
					'<td class="input-sm" >'+nombre_producto+'</td>'+
					'<td class="input-sm"  align="center">'+nombre_presentacion+'</td>'+
					'<td class="input-sm"  align="center">'+cantidad+'</td>'+
					'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this);" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
					'</tr>';
				$("#tablag").append(d);

				//vaciar datos
				$('#cboProductog').val(0);
				$('#cboProductog').empty();
				$('#cboProductog').append('<option value="0">Seleccione</option>');
				$('#cboPresentaciong').empty();
				$('#cboPresentaciong').append('<option value="0">Seleccione</option>');
				$('#afectog').val('');
				
				$('#stockg').val('');
				$('#fecha_vencg').val('');
				$('#producto_infg').val("");
				
				$('#stock_infg').val("");
				$('#cant_unidades_infg').val("");
				$('#unidad_infg').val("");
				$('#fecha_v_infg').val("");
				
				$('#detalle_prodg').hide();
			}else{
				alert("ingrese cantidad!");
				$('#cantidadg').focus();
			}
		}else{
			alert("seleccione Unidad!");
			$('#cboPresentacion').focus();
		}
	}else{
		alert("seleccione Un Producto!");
		$('#cboProducto').focus();
	}
	}else{
		alert("No hay stock suficiente para la cantidad solicitada!");
		$('#cantidad').focus();
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
		var valores = calcularPrecio();
	}

}

function guardar_guia(entidad, idboton) {
	var correcto = false;

		if(contar_registros() > 0){
			correcto = true;
		}else{
			alert('No a seleccionado ningun producto');
		}

	var idformulario = IDFORMMANTENIMIENTO + entidad;
	if(correcto){
		var data         = submitForm_guia(idformulario);
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

					cerrarModal();
					if (listar === 'SI') {
						buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
					}        
				} else {
				$('#divMensajeError'+entidad).html('<div class="alert alert-danger"><strong>'+respuesta[0]+'</strong></div>');
				$(idboton).removeClass('disabled');
				$(idboton).removeAttr('disabled');
				$(idboton).html('<i class="fa fa-check fa-lg"></i>Guardar');
				}
				
			}
		});
	}
	
}
function submitForm_guia(idformulario) {
	var i=0;
	var datos="";
	$('.datos-producto').each(function() {
		datos += 	"&prod_id"+i+"="+$(this).attr("producto_id")+
					"&present_id"	+i+"="+$(this).attr("presentacion_id")+
					"&cant_pres"	+i+"="+$(this).attr("cantidad_presentacion")+
					"&cant_prod"+i+"="+$(this).attr("cantidad");
		i++;
	});

	datos += "&cantidad_registros_prod="+i;
	var parametros = $(idformulario).serialize();
	parametros += datos;
	var accion     = $(idformulario).attr('action').toLowerCase();
	var metodo     = $(idformulario).attr('method').toLowerCase();
	
	var respuesta  = $.ajax({
		url : accion,
		type: metodo,
		data: parametros
	});
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