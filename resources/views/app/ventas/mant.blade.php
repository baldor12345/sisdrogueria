<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($venta, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('afecto', null, array('id' => 'afecto')) !!}
	{{-- {!! Form::hidden('precio_unidad', null, array('id' => 'precio_unidad')) !!} --}}
	{!! Form::hidden('stock', null, array('id' => 'stock')) !!}
	{!! Form::hidden('fecha_venc', null, array('id' => 'fecha_venc')) !!}

	<div class="form-group row">
		
		<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ">  
			<div class="card-box">
				<div class="alert alert-success col-12 col-md-12" id="detalle_prod">
					<table id="tabla_temp" class="" style="">
						<tr><td>Producto</td><td style="padding-left: 10px;"><label id="producto_inf">: </label></td></tr>
						{{-- <tr><td>Fecha Venc.:</td><td><label id="fecha_v_inf" fecha_v=''></label></td></tr> --}}
						<tr><td>Precio Venta s/.</td><td style="padding-left: 10px;"><label id="precio_inf" precio='0'>: </label></td><td style="padding-left: 10px;"> Stock (Unidades)</td><td style="padding-left: 10px;"><label id="stock_inf" stock='0'>: </label></td><td style="padding-left: 10px;"> Fecha Venc.</td><td style="padding-left: 10px;"><label id="fecha_v_inf" fecha_v=''>: </label></td></tr>
						<tr><td>Unidad</td><td><label id="unidad_inf" lote=''>: </label></td><td id="cant_unidades_titulo" style="padding-left: 10px;">Cantidad Unidades</td><td style="padding-left: 10px;"><label cantidad_u="0" id="cant_unidades_inf">: </label></td><td style="padding-left: 10px;"> Afecto</td><td style="padding-left: 10px;"><label id='afecto_inf' afecto=''>: </label></td></tr>
					</table>
				</div>
				<div class="form-group">
	
					<div class="form-group col-5 col-md-5 col-sm-6 col-xs-12 text-left">
						{!! Form::label('cboProducto', 'Producto:', array('class' => ' col-md-12 text-left')) !!}
						{!! Form::select('cboProducto', $cboProducto, null, array('class' => 'form-control input-sm', 'id' => 'cboProducto')) !!}
					</div>
					<div class="form-group col-4 col-md-4 text-left" style="margin-left: 3px">
						{!! Form::label('cboPresentacion', 'Unidad:', array('class' => ' col-md-12 text-left')) !!}
						{!! Form::select('cboPresentacion', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'cboPresentacion')) !!}
					</div>
					<div class="form-group col-1 col-md-1 text-left" style="margin-left: 3px">
						{!! Form::label('cantidad', 'Cantidad:', array('class' => 'col-sm-3 col-xs-12')) !!}
						{!! Form::text('cantidad', null, array('class' => 'form-control input-sm', 'id' => 'cantidad', 'placeholder' => 'Cantidad',  'onkeypress'=>'return filterFloat(event,this)')) !!}
					</div>
					<div class="form-group col-1 col-md-1 text-left" style="margin-left: 3px">
						{!! Form::label('precio_unidad', 'Precio:', array('class' => 'col-sm-3 col-xs-12')) !!}
						{!! Form::text('precio_unidad', null, array('class' => 'form-control input-sm', 'id' => 'precio_unidad', 'placeholder' => 'Precio',  'onkeypress'=>'return filterFloat(event,this)')) !!}
					</div>
					<div class="form-group col-1 col-md-1 text-left" style="margin-left: 3px">
						{!! Form::label('btnAgregar', 'Agregar:', array('class' => 'col-md-12 text-left')) !!}
						{!! Form::button('<i class="fa fa-plus fa-lg"></i> Agregar', array('class' => 'btn btn-success btn-sm', 'id' => 'btnAgregar', 'onclick' => 'agregar()')) !!}
					</div>
					
				</div>
				<div class="table-responsive">
					<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
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
					</table>
				</div>
			</div> 
		</div>

		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="card-box">
				<div class="form-group ">
					{!! Form::label('documento', 'Documento:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-9 col-xs-12" style="height: 25px;">
						{!! Form::select('documento', $cboDocumento, null, array('class' => 'form-control input-sm', 'id' => 'documento')) !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('cboCliente', 'Cliente:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', '')) !!}
					<div class="input-group" style="">
						{!! Form::select('cboCliente', $cboCliente, null, array('class' => 'form-control input-md', 'id' => 'cboCliente')) !!}
						<span class="input-group-btn">
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnNuevoCli', 'onclick' => 'modal (\''.URL::route($ruta["create_new"], array('listar'=>'SI')).'\', \''."Registrar Cliente".'\', this);')) !!}
							
							{{-- {!! Form::button('<i class="fa fa-plus fa-lg"></i> ', array('class' => 'btn btn-success btn-sm', 'id' => 'btnNuevoCli', 'onclick' => '')) !!} --}}
						</span>
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('cboMedico', 'Medico:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', '')) !!}
					<div class="input-group" style="">
						{!! Form::select('cboMedico', $cboMedico, null, array('class' => 'form-control input-md', 'id' => 'cboMedico')) !!}
						<span class="input-group-btn">
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnNuevoMed', 'onclick' => 'modal (\''.URL::route($ruta["create_med"], array('listar'=>'SI')).'\', \''."Registrar Medico".'\', this);')) !!}
							
							{{-- {!! Form::button('<i class="fa fa-plus fa-lg"></i> ', array('class' => 'btn btn-success btn-sm', 'id' => 'btnNuevoCli', 'onclick' => '')) !!} --}}
						</span>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('cboVendedor', 'Vendedor:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', '')) !!}
					<div class="input-group" style="">
						{!! Form::select('cboVendedor', $cboMedico, null, array('class' => 'form-control input-md', 'id' => 'cboVendedor')) !!}
						<span class="input-group-btn">
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnNuevoVend', 'onclick' => 'modal (\''.URL::route($ruta["create_vend"], array('listar'=>'SI')).'\', \''."Registrar Vendedor".'\', this);')) !!}
							
							{{-- {!! Form::button('<i class="fa fa-plus fa-lg"></i> ', array('class' => 'btn btn-success btn-sm', 'id' => 'btnNuevoCli', 'onclick' => '')) !!} --}}
						</span>
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('tipo_venta', 'Tipo:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-9 col-xs-12" style="height: 25px;">
						{!! Form::select('tipo_venta', $cboTipos, null, array('class' => 'form-control input-sm', 'id' => 'tipo_venta', 'onchange'=>'cambiarcredito();')) !!}
					</div>
				</div>
				<div class="form-group credito" >
					{!! Form::label('dias', 'Dias:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-9 col-xs-12" style="height: 25px;">
						{!! Form::text('dias', 0, array('class' => 'form-control input-xs', 'id' => 'dias', 'placeholder' => 'N° dias')) !!}
					</div>
				</div>

				<div class="form-group contado">
					{!! Form::label('forma_pago', 'Forma de Pago:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm contado', 'style'=>'height: 25px')) !!}
					<div class="col-sm-9 col-xs-12" style="height: 25px;">
						{!! Form::select('forma_pago', $cboFormasPago, null, array('class' => 'form-control input-sm', 'id' => 'forma_pago', 'onchange'=>'cambiarcredito();')) !!}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('serie_documento', 'Nro Doc:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-3 col-xs-12" style="height: 25px;">
						{!! Form::text('serie_documento', 'B'.$serie, array('class' => 'form-control input-xs', 'id' => 'serie_documento', 'placeholder' => 'serie','readonly')) !!}
					</div>
					<div class="col-sm-6 col-xs-12" style="height: 25px;">
						{!! Form::text('numero_documento', $numero_doc, array('class' => 'form-control input-xs', 'id' => 'numero_documento', 'placeholder' => 'num documento','readonly')) !!}
					</div>
				</div>

				<div class="form-group">
					{!! Form::label('fecha', 'Fecha:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-9 col-xs-12" style="height: 25px;">
						{!! Form::date('fecha', $fecha_defecto, array('class' => 'form-control input-xs', 'id' => 'fecha')) !!}
					</div>
				</div>
				<br>
				
				<div class="form-group" >
					{!! Form::label('subtotal', 'Sub Total:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-4 col-xs-12" style="height: 25px;">
						{!! Form::text('subtotal', 0, array('class' => 'form-control input-xs', 'id' => 'subtotal', 'placeholder' => '','readonly')) !!}
					</div>
					{!! Form::label('igv', 'Igv:', array('class' => 'col-sm-2 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-3 col-xs-12" style="height: 25px;">
						{!! Form::text('igv', 0, array('class' => 'form-control input-xs', 'id' => 'igv', 'placeholder' => '', 'readonly')) !!}
					</div>
				</div>

				<div class="form-group" >
					{!! Form::label('total', 'Total:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
					<div class="col-sm-4 col-xs-12" style="height: 25px;">
						{!! Form::text('total', null, array('class' => 'form-control input-xs', 'id' => 'total', 'placeholder' => '','readonly')) !!}
					</div>
				</div>

				<br>
				<div class="form-group">
					<div class="col-lg-12 col-md-12 col-sm-12 text-right">
						{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarVenta', 'onclick' => 'guardar_venta(\''.$entidad.'\', this)')) !!}
						{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
					</div>
				</div>
			</div>	
		</div>	
	</div>
{!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$('#detalle_prod').hide();
	$('.credito').hide();
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
	$('#cboCliente').empty();
	$('#cboCliente').append('<option value="1">Varios</option>');

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

	$('#cboMedico').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listmedicos'], array()) }}",
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
	$('#cboVendedor').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listvendedores'], array()) }}",
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
			var producto = response[0];
			var total_seleccionado = cantidad_seleccionado(producto.id);

			var stock = response[1] - total_seleccionado;
			var precio_unidad = response[2];
			var cboPresentacion = response[3];
			var fecha_venc = response[4];
			var lote = response[5];

				$('#producto_inf').text(": "+producto.descripcion);
				$('#stock_inf').text(": "+stock);
				$('#stock').val(stock);
				$('#precio_unidad').val(precio_unidad);
				// $('#stock_inf').attr('stock',stock);
				$('#precio_inf').text(": "+precio_unidad);
				// $('#precio_inf').attr('precio',precio_unidad);
				$('#unidad_inf').text("");
				$('#afecto_inf').text(producto.afecto == 'S'? ": SI":": NO");
				$('#afecto_inf').attr('afecto',producto.afecto);
				$('#unidad_inf').attr('lote',lote);
			
				$('#fecha_v_inf').text(fecha_venc!=null?": "+fecha_venc:": ");
				$('#fecha_venc').val(fecha_venc!=null?fecha_venc:"");
				$('#afecto').val(producto.afecto);
				// $('#fecha_v_inf').attr('fecha_v',""+fecha_venc);
				$('#cboPresentacion').empty();
				$('#cboPresentacion').append('<option value="0">Seleccione.</option>');
				$('#cboPresentacion').append(cboPresentacion);
				
				$('#detalle_prod').show();
		});
	});

	$('#tipo_venta').change(function(){
		if($(this).val() == 'CO'){
			$('.credito').hide();
			$('.contado').show();
			$('#dias').val(0);
		}else{
			$('.contado').hide();
			$('.credito').show();
		}
	});
	$('#documento').change(function(){
		var serie = "{{ $serie }}";
		$('#serie_documento').val($(this).val()+''+serie);
		if($(this).val() == 'F'){
			$('#cboCliente').empty();
			$('#cboCliente').append('<option value="0">Seleccione Cliente</option>');
		}else{
			$('#cboCliente').empty();
			$('#cboCliente').append('<option value="1">Varios</option>');
		}
	});

	$('#cboPresentacion').change(function(){
		var producto_id = $('#cboProducto').val();
		$.get("ventas/"+producto_id+"/"+$('#cboPresentacion').val()+"",function(response, facultad){//obtener el producto, su stock, precio_venta
			// console.log("Respuesta persona: "+response[3]);
			var productoPresentacion = response;
			var precio_unidad = productoPresentacion.precio_venta_unitario;
			// var precio_unidad = productoPresentacion.precio_venta_unitario * productoPresentacion.cant_unidad_x_presentacion;
			var cantidad_unidades_presentacion = productoPresentacion.cant_unidad_x_presentacion;
			var cantidad = $('#cantidad').val()==""?1:$('#cantidad').val();
			var total_unidades = cantidad_unidades_presentacion * cantidad;
				$('#unidad_inf').text(": "+ $('#cboPresentacion option:selected').html());
				$('#cant_unidades_inf').text(": "+cantidad_unidades_presentacion);
				$('#cant_unidades_inf').attr('cantidad_u',cantidad_unidades_presentacion);
				$('#cant_unidades_titulo').text("Cantidad Unidades/"+$('#cboPresentacion option:selected').html()+" ");
				$('#precio_inf').text(": "+precio_unidad);
				// $('#precio_inf').text(precio_unidad);
				$('#precio_unidad').val(precio_unidad)
		});      
			
	});

	
	// $("input[name=cantidad]").change(function(event){
	// 	// var cant = parseInt($('#cantidad').val());
	// 	// var cantidad_unidad = parseInt($('#unidad_presentacion').val());
	// 	// $('#factor').val('');
	// 	// $('#factor').val(cant*cantidad_unidad);
	// });


}); 

function selectTipo(combo){//tipo a Credito o a Contado
	$(combo).val();
	if($(combo).val() == 'CR'){//Credito

	}else{//Contado

	}
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

function calcularPrecio(){
	var subtotal = 0;
	var total = 0;
	var igv = 0;
	var porcent_igv = 0.18;
	$('.datos-producto').each(function() {
		var tmp_igv = 0;
		var tmp_valor_sub = 0;
		var precio = parseFloat($(this).attr("precio_venta"));
		// var cantidad = parseFloat($(this).attr("cantidad"));
		var cantidad = parseFloat($(this).attr("cantidad_presentacion"));
		var afecto = $(this).attr("afecto");
		tmp_valor_sub = precio * cantidad;
		subtotal += tmp_valor_sub;
		if(afecto == 'S'){
			tmp_igv = porcent_igv*tmp_valor_sub;
		}
		igv += tmp_igv;
	});

	total = subtotal;
	subtotal = total - igv;


	var res = [igv,subtotal, total];

	$('#total').val(total);
	$('#subtotal').val(subtotal);
	$('#igv').val(igv);
	return res;
}

function agregar(){

	var cant_u_present = parseInt($('#cant_unidades_inf').attr('cantidad_u'));
	var nombre_producto = $('#cboProducto option:selected').html();
	var nombre_presentacion = $('#cboPresentacion option:selected').html();
	var afecto = $('#afecto_inf').attr('afecto');
	var presentacion_id = parseInt($('#cboPresentacion').val());
	var producto_id = parseInt($('#cboProducto').val());

	var precioventa = parseFloat($('#precio_unidad').val());
	var cantidad = parseInt($('#cantidad').val());
	var cantidad_total = cantidad * cant_u_present;
	var fechavencimiento = $('#fecha_venc').val();


	// var igv = parseFloat($('#igv').val());
	// var subtotal = parseFloat($('#subtotal').val()!=""?$('#subtotal').val():0);
	// var total = parseFloat($('#total').val()!=""?$('#total').val():0);

	var lote = $('#unidad_inf').attr('lote');
	var stock = $('#stock').val();
	if(stock >= cantidad_total){
	if(producto_id!= '0'){
		if(presentacion_id !='0'){
			if(cantidad!=""){
				// var subtotal_1 = cantidad * precioventa;
				// subtotal += subtotal_1;

				var subtotal_2 = cantidad * precioventa;
				// if($('#afecto').val() == 'S'){
				// 	total += subtotal_2 + (18/100 * subtotal);
				// }else{
				// 	total += subtotal_2;
				// }
				
				// subtotal = parseInt(cantidad)*parseFloat(preciocompra);
			
				var d = '<tr class="datos-producto" producto_id="'+producto_id+'" cantidad="'+cantidad_total+'" cantidad_presentacion="'+cantidad+'" presentacion_id="'+presentacion_id+'"  precio_venta="'+precioventa+'"  fecha_venc="'+fechavencimiento+'" afecto="'+afecto+'" >'+
					'<td class="input-sm" width="35%">'+nombre_producto+'</td>'+
					'<td class="input-sm" width="15%" align="center">'+nombre_presentacion+'</td>'+
					'<td class="input-sm" width="10%" align="center" >'+fechavencimiento+'</td>'+
					'<td class="input-sm" width="5%" align="center">'+lote+'</td>'+
					'<td class="input-sm" width="10%" align="center">'+precioventa+'</td>'+
					'<td class="input-sm" width="10%" align="center">'+cantidad+'</td>'+
					'<td class="input-sm" width="10%" align="center">'+subtotal_2+'</td>'+
					'<td width="5%" align="center"><button id="btnQuitar" name="btnQuitar"  class="btn btn-danger btn-xs" onclick="quitar(this, '+subtotal_2+');" title="" type="button"><i class="glyphicon glyphicon-remove"></i></button></td>'+
					'</tr>';
				$("#tabla").append(d);

				var valores = calcularPrecio();
				// console.log("Valores RES: "+valores);
				// var igv = valores[0];
				// var subtotal = valores[1];
				// var total = valores[2];

				// $('#total').val(total);
				// $('#subtotal').val(subtotal);
				// $('#igv').val((18/100 * subtotal));
				//vaciar datos
				$('#cboProducto').val(0);
				$('#cboProducto').empty();
				$('#cboProducto').append('<option value="0">Seleccione</option>');
				$('#cboPresentacion').empty();
				$('#cboPresentacion').append('<option value="0">Seleccione</option>');
				$('#afecto').val('');
				$('#precio_unidad').val('');
				$('#stock').val('');
				$('#fecha_venc').val('');
				$('#producto_inf').val("");
				$('#precio_inf').val("");
				$('#stock_inf').val("");
				$('#cant_unidades_inf').val("");
				$('#unidad_inf').val("");
				$('#fecha_v_inf').val("");
				// $('#lote').val("");
				$('#detalle_prod').hide();
			}else{
				alert("ingrese cantidad!");
				$('#cantidad').focus();
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

function quitar(t, subtotal){
	var mensaje;
    var opcion = confirm("Desea ELiminar el producto registrado?");
    if (opcion == true) {
        var td = t.parentNode;
		var tr = td.parentNode;
		var table = tr.parentNode;
		table.removeChild(tr);
		var valores = calcularPrecio();
		// var total_parcial = parseFloat($('#total').val());
		// $('#total').val(parseFloat(total_parcial)-subtotal);
	}

}

function guardar_venta(entidad, idboton) {
	var correcto = false;
	if(contar_registros() > 0){
		if($('#cboCliente').val() != '0'){
			correcto = true;
		}else{
			alert('Debe seleccionar un cliente antes de guardar.');
		}
	}else{
		alert('No a seleccionado ningun pruducto');
	}
	var idformulario = IDFORMMANTENIMIENTO + entidad;
	if(correcto){
		
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

					var venta = JSON.stringify(respuesta[1]);
					var cliente = JSON.stringify(respuesta[2]);
					var detalla_ventas = JSON.stringify(respuesta[3]);
					// var lsentradas = respuesta[4];
					declarar(venta,cliente,detalla_ventas,$("#documento").val());
					
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
	}
	
}

function submitForm_venta(idformulario) {
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


function declarar(venta,cliente,detalla_ventas,idtipodoc){
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
        data: "venta="+venta+"&cliente="+cliente+"&detalle="+detalla_ventas,
        success: function (data, textStatus, jqXHR) {
            alert("Enviado");
        },
        beforeSend: function (xhr) {
            //alert("Enviando informacion");
        }
    });
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