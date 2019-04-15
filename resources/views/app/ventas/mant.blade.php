<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($venta, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('stock_temp', 0, array('id' => 'stock_temp')) !!}
	<div class="row card-box">
		<div class="form-group col-9 col-md-9">
			{!! Form::label('cboCliente', 'Cliente: ', array('class' => '')) !!}
			{!! Form::select('cboCliente', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboCliente')) !!}
		</div>
		<div class="form-group col-2 col-md-2" style="margin-left: 3px">
			{!! Form::label('btnadd', 'Nuevo: ', array('class' => 'control-label col-12 col-md-12')) !!}
			{{-- {!! Form::button('<i class="fa fa-plus fa-lg"></i> Registrar Nuevo', array('class' => 'btn btn-success btn-sm', 'id' => 'btnadd', 'onclick' => '')) !!} --}}
			{!! Form::button('<i class="glyphicon glyphicon-plus"></i> ', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md', 'id' => 'btnNuevocli', 'onclick' => 'modal (\''.URL::route($ruta["create_new"], array('listar'=>'SI')).'\', \''."Registrar Cliente".'\', this);')) !!}
		</div>
		<div class="form-group col-6 col-md-6">
			{!! Form::label('cboForma_pago', 'Forma de pago: ', array('class' => '')) !!}
			{!! Form::select('cboForma_pago', $cboFormaPago, '0' , array('class' => 'form-control input-sm', 'id' => 'cboForma_pago')) !!}
		</div>
		<div class="form-group col-6 col-md-6"  style="margin-left: 3px">
			{!! Form::label('cboComprobante', 'Comprobante Pago: ', array('class' => '')) !!}
			{!! Form::select('cboComprobante', $cboComprobante, '0' , array('class' => 'form-control input-sm', 'id' => 'cboComprobante')) !!}
		</div>
	</div>
	<div class="row">
		<div id="divInfoProducto" class="col-12 col-md-12" ></div>
	</div>

	<div class="row card-box">
		<div class="form-group col-6 col-md-6">
			{!! Form::label('cboProducto', 'Producto: ', array('class' => '')) !!}
			{!! Form::select('cboProducto', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboProducto')) !!}
		</div>
		{!! Form::hidden('precio_unidad', 0.0, array('id' => 'precio_unidad')) !!}
		{{-- {!!Form::hidden('nombre_temporal',"",array('id'=>'nombre_temporal'))!!}
		{!! Form::hidden('nombre_presentacion_temp', "", array('id' => 'nombre_presentacion_temp')) !!}  --}}
		<div class="form-group col-3 col-md-3" style="margin-left: 3px">
			{!! Form::label('cboPresentacion', 'Presentacion: ', array('class' => '')) !!}
			{!! Form::select('cboPresentacion', $cboPresentacion, '0' , array('class' => 'form-control input-sm', 'id' => 'cboPresentacion')) !!}
		</div>
		<div class="form-group col-2 col-md-2" style="margin-left: 3px">
			{!! Form::label('cantidad', 'Cantidad:', array('class' => 'control-label')) !!}
			{!! Form::text('cantidad', null, array('class' => 'form-control input-xs', 'id' => 'cantidad', 'placeholder' => 'Cantidad')) !!}
		</div>
		<div class="form-group col-1 col-md-1" style="margin-left: 3px">
				{!! Form::button('<i class="fa fa-plus fa-lg"></i> ', array('class' => 'btn btn-success btn-sm', 'id' => 'btnAgregar', 'onclick' => 'agregar_producto()')) !!}
		</div>
		<div class="form-group col-12 col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed table-hover">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Cantidad</th>
							<th>Unidad/Medida</th>
							<th>Precio Unitario</th>
							<th>Sub Total S/.</th>
							<th>Eliminar</th>
						</tr>
					</thead>
					<tbody id="tabla_productos">

					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group col-12 col-md-12" >
			{!! Form::label('total_p', 'Total: ', array('class' => '', 'id'=>'total_p')) !!}
			{!!Form::hidden('total',0,array('id'=>'total'))!!}
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar_venta(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('850');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$('#cboProducto').select2({
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

	$('#cboProducto').change(function(){
            // $('#selectaval').select2("val", "0");
            $.get("ventas/"+$(this).val()+"",function(response, facultad){//obtener el producto, su stock, precio_venta
                // console.log("Respuesta persona: "+response[3]);
                var producto = response[0];
                var stock = response[1];
                var precio_unidad = response[2];
                 var presentacion_id = response[3];
				 $('#nombre_temporal').val(""+producto.descripcion);
				 $('#precio_unidad').val(""+precio_unidad);
				 $('#stock_temp').val(stock);
                    var msj = "<div class='alert alert-success productos'><strong>¡Detalles de producto: !</strong><ul><li>Producto: "+producto.descripcion+"</li>";
                    msj += "<li>Stock: "+stock+"</li><li>Precio/Unidad: "+precio_unidad+"</li></ul>";
					$('#divInfoProducto').html(msj);
					$('#divInfoProducto').show();
            });
        });
	$('#cboPresentacion').change(function(){
                
				// //  $('#nombre_temporal').val(""+producto.descripcion);
                //     var msj = "<div class='alert alert-success'><strong>¡Detalles de producto: !</strong><ul><li>Producto: "+producto.descripcion+"</li>";
                //     msj += "<li>Stock: "+stock+"</li><li>Precio/Unidad: "+precio_unidad+"</li></ul>";
				// 	$('#divInfoProducto').html(msj);
				// 	$('#divInfoProducto').show();
        });

	$('#cboCliente').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listclientes'], array()) }}",
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

function agregar_producto(){

	// var nombre = $('#nombre_temp').val();
	// var nombre_presentacion = $('#nombre_presentacion_temp').val();
	if($('#stock_temp').val() > 0 || $('#cantidad').val() < $('#stock_temp').val()){
		if($("#cboProducto").val() != '0' && $('#cboPresentacion').val() != '0' && $('#cantidad').val() != ""){
		var nombre_producto = $('#cboProducto option:selected').html();
		var nombre_presentacion = $('#cboPresentacion option:selected').html();
		var producto_id = $('#cboProducto').val();
		var presentacion_id = $('#cboPresentacion').val();
		var precio_unidad = parseFloat($('#precio_unidad').val());
		var cantidad = parseFloat($('#cantidad').val());
		var subtotal = cantidad * precio_unidad;
		var total = parseFloat($('#total').val()) + subtotal;
		$('#total_p').text('Total: '+total);
		$('#total').val(total);
		var fila = '<tr class="producto_venta" producto_id='+producto_id+' presentacion_id='+presentacion_id+' cantidad='+cantidad+'  subtotal='+subtotal+'><td>'+nombre_producto+"</td><td>"+cantidad+"</td><td>"+nombre_presentacion+"</td><td>"+precio_unidad+"</td><td>"+subtotal+"</td>";
		fila += '<td width="5%" align="center"><button id="btnQuitarProd" name="btnQuitarprod"  class="btn btn-danger btn-sm" onclick="quitar(this, '+subtotal+');" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td></tr>';
		$("#tabla_productos").append(fila);
		$('#cboProducto').val(null);
		$('#cboPresentacion').val(0);
		$('#precio_unidad').val(0);
		$('#cantidad').val("");
		$('#stock_temp').val(0);
	}else{
		alert("Seleccione un producto, presentacion e ingrese su cantidad");
	}
	}else{
		alert("No existe stock suficiente para el producto seleccionado");
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
		 var total_parcial = parseFloat($('#total').val())-subtotal;
		 $('#total').val(total_parcial);
		 $('#total_p').text('Total: '+total_parcial);
	}
}


function guardar_venta(entidad, idboton) {
	var idformulario = IDFORMMANTENIMIENTO + entidad;
	var data         = submitForm_venta(idformulario);
	var respuesta    = '';
	var listar       = 'NO';
	if ($(idformulario + ' :input[id = "listar"]').length) {
		var listar = $(idformulario + ' :input[id = "listar"]').val()
	};
	data.done(function(msg) {
		respuesta = msg;
		$(idboton).button('loading');
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
function submitForm_venta(idformulario) {
	var i=0;
	var datos="";
	$('.producto_venta').each(function() {
		datos += 	"&producto_id"		+i+"="+$(this).attr("producto_id")+
					"&presentacion_id"	+i+"="+$(this).attr("presentacion_id")+
					"&subtotal"	+i+"="+$(this).attr("subtotal")+
					"&cantidad"	+i+"="+$(this).attr("cantidad");
		i++;
	});
	datos += "&cantidad_registros="+i;
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