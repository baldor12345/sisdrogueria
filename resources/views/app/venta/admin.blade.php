<!-- Page-Title -->
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;
use App\Movimiento;

$user = Auth::user();
/*
SELECT operacion_menu.operacion_id
FROM  operacion_menu 
inner join permiso_operacion
on permiso_operacion.operacionmenu_id = operacion_menu.id
where po.usertype_id = 4 and om.menuoption_id = 9
*/
/*$operaciones = DB::table('operacion_menu')
					->join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.operacion_id')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', 6 ],
					])->get();*/
$opcionmenu = Menuoption::where('link','=',$entidad)->orderBy('id','ASC')->first();
$operaciones = OperacionMenu::join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.*')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', $opcionmenu->id ],
					])->get();					
$operacionesnombres = array();
foreach($operaciones as $key => $value){
	$operacionesnombres[] = $value->operacion_id;
}
/*
operaciones 
1 nuevo
2 editar
3 eliminar
*/
?>
<div class="row" style="background: rgba(51,122,183,0.10);">
    <div class="col-sm-12">
        <div class="card-box table-responsive">

		{!! Form::open(['route' => $ruta["guardarventa"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'IDFORMMANTENIMIENTO'.$entidad]) !!}
			
		<h4 class="page-venta" style ="margin-top: 3px;">SELECCIONE EMPLEADO</h4><div style="width: 100%; text-align: right; margin-top: -25px;"><a type="button" id="btnMostrarEmpleados" class="btn btn-warning btn-xs glyphicon glyphicon-chevron-up" style="width: 25px; height: 23px;" value="0"></div></a>
		<div id="empleados" style=" margin: 10px 0px; display: -webkit-inline-box; width: 100%; overflow-x: scroll; border-style: groove;">
			@foreach($empleados  as $key => $value)
				<div class="empleado" id="{{ $value->id}}" style="margin: 5px; width: 120px; height: 100px; text-align: center; border-style: solid; border-color: rgb(63, 81, 181); border-radius: 10px;" >
					<img src="assets/images/empleado.png" style="width: 50px; height: 50px">
					<label style="font-size: 11px;">{{ $value->razonsocial ? $value->razonsocial : $value->nombres.' '.$value->apellidos}}</label>
				</div>
			@endforeach
			{!! Form::hidden('empleado_id',null,array('id'=>'empleado_id')) !!}
			{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
		</div>

		<h4 id="tituloDetalle" class="page-venta"><div class="col-lg-3 col-md-3 col-sm-3">DATOS DEL DOCUMENTO</div><div class="col-lg-6 col-md-6 col-sm-6">SELECCIONE SERVICIOS / PRODUCTOS</div><div class="col-lg-3 col-md-3 col-sm-3">PAGO</div></h4>

		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="col-lg-3 col-md-3 col-sm-3" id="divDatosDocumento1">
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
					{!! Form::label('sucursal_id', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
					{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id' , 'onchange' => 'generarNumeroSerie(); permisoRegistrar();')) !!}		
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
					{!! Form::label('tipodocumento_id', 'Tipo de Documento:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
					{!! Form::select('tipodocumento_id', $cboTipoDocumento, null, array('class' => 'form-control input-sm', 'id' => 'tipodocumento_id', 'onchange' => 'generarNumeroSerie();')) !!}		
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
					{!! Form::label('serieventa', 'Número:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
					{!! Form::text('serieventa', '', array('class' => 'form-control input-sm', 'id' => 'serieventa', 'data-inputmask' => "'mask': '9999-9999999'")) !!}
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
					{!! Form::label('fecha', 'Fecha:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
					{!! Form::text('fecha', '', array('class' => 'form-control input-sm', 'id' => 'fecha', 'readOnly')) !!}
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
					<div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-10px;">
						{!! Form::label('cliente', 'Cliente:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array( 'id' => 'btnclientenuevo' , 'class' => 'btn btn-success waves-effect waves-light btn-xs btnCliente', 'onclick' => 'modal (\''.URL::route($ruta["cliente"], array('listar'=>'SI')).'\', \''.$titulo_cliente.'\', this);', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO')) !!}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{!! Form::button('<i class="glyphicon glyphicon-user"></i>', array('id' => 'btnclientevarios' , 'class' => 'btn btn-primary waves-effect waves-light btn-xs btnDefecto', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'VARIOS')) !!}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('id' => 'btnclienteborrar' , 'class' => 'btn btn-danger waves-effect waves-light btn-xs btnBorrar' , 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'BORRAR')) !!}
					</div>
					{!! Form::text('cliente', '', array('class' => 'form-control input-sm', 'id' => 'cliente')) !!}
					{!! Form::hidden('cliente_id',null,array('id'=>'cliente_id')) !!}
				</div>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<h5 align="center" class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: -10px; margin-top: 0px;">SERVICIOS FRECUENTES</h5>
					<div id="servicios_frecuentes" class="col-lg-12 col-md-12 col-sm-12" style="margin: 10px; border-style: groove; width: 100%; height: 180px; overflow-y: scroll;">
						@foreach($servicios  as $key => $value)
							<div class="servicio_frecuente col-lg-3 col-md-3 col-sm-3" id="{{ $value->id}}"  precio="{{ $value->precio }}" descripcion="{{ $value->descripcion }}" editable="{{ $value->editable }}" style="margin: 5px; width: 85px; height: 75px; text-align: center; border-style: solid; border-color: rgb(63, 81, 181); border-radius: 10px;" >
								<img src="assets/images/peine_1.png" style="width: 30px; height: 30px">
								<label style="font-size: 9.5px;">{{ $value->descripcion}}</label>
							</div>
						@endforeach
					</div>
				</div>

				<div class="form-group col-lg-12 col-md-12 col-sm-12">
					<div class="col-lg-9 col-md-9 col-sm-9">
						{!! Form::label('servicio', 'Servicio/Producto:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
						{!! Form::text('servicio', '', array('class' => 'form-control input-sm', 'id' => 'servicio')) !!}
						{!! Form::hidden('servicionombre',null,array('id'=>'servicionombre')) !!}
						{!! Form::hidden('servicio_id',null,array('id'=>'servicio_id')) !!}
						{!! Form::hidden('tipo',null,array('id'=>'tipo')) !!}
						{!! Form::hidden('precio',null,array('id'=>'precio')) !!}
						{!! Form::hidden('editable',null,array('id'=>'editable')) !!}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
						{!! Form::label('cantidad', 'Cantidad:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -30px;'))!!}
						{!! Form::number('cantidad', null, array('class' => 'form-control input-sm', 'min' => '1', 'id' => 'cantidad', 'style' => "text-align:center;")) !!}
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1" style="margin-top:20px;">
						{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-primary waves-effect waves-light btn-sm btnAgregar', 'activo' => 'si' )) !!}
					</div>
					{!! Form::hidden('cant',null,array('id'=>'cant', 'value' => '0')) !!}
					<h5 align="center" class="col-lg-12 col-md-12 col-sm-12 m-t-30">LISTA SERVICIOS/PRODUCTOS</h5>
					<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="font-size: 70%; padding: 0px 0px !important;">
						<thead id="cabecera"><tr><th style="font-size: 13px !important;">Descripción</th><th style="font-size: 13px !important;">Cant</th><th style="font-size: 13px !important;">Precio Unit</th><th style="font-size: 13px !important;">Precio Acum</th><th style="font-size: 13px !important;">Eliminar</th></tr></thead>
						<tbody id="detalle"></tbody>
					</table>
				</div>
			</div>

		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				<div  class="col-lg-3 col-md-3 col-sm-3">
					<img src="assets/images/efectivo.png" style="width: 60px; height: 60px">
				</div>
				<div  class="col-lg-9 col-md-9 col-sm-9">
					{!! Form::text('montoefectivo', '', array('class' => 'form-control input-lg montos', 'id' => 'montoefectivo', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				<div  class="col-lg-3 col-md-3 col-sm-3">
					<img src="assets/images/visa.png" style="width: 60px; height: 60px">
				</div>
				<div  class="col-lg-9 col-md-9 col-sm-9">
					{!! Form::text('montovisa', '', array('class' => 'form-control input-lg montos', 'id' => 'montovisa', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				<div  class="col-lg-3 col-md-3 col-sm-3">
					<img src="assets/images/master.png" style="width: 60px; height: 40px">
				</div>
				<div  class="col-lg-9 col-md-9 col-sm-9">
					{!! Form::text('montomaster', '', array('class' => 'form-control input-lg montos', 'id' => 'montomaster', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('total', 'Total:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
				{!! Form::text('total', '', array('class' => 'form-control input-lg', 'id' => 'total', 'readOnly', 'style' => 'text-align: right; font-size: 30px;')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::label('vuelto', 'Vuelto:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
				{!! Form::text('vuelto', '', array('class' => 'form-control input-lg', 'id' => 'vuelto', 'readOnly', 'style' => 'text-align: right; font-size: 30px; color: red;', 'placeholder' => '0.00')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="text-align:right">
				{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', array( 'class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md btnGuardar', 'id' => 'btnGuardar' , 'style' => 'margin-top: 23px;' )) !!}
			</div>
		</div>

		{!! Form::close() !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="divMensajeError{!! $entidad !!}"></div>
		</div>

        </div>
    </div>
</div>
<script>
$(document).ready(function(){

	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'}); 

	//colocar total 0.00

	$("#total").val((0).toFixed(2));
	$("#vuelto").val((0).toFixed(2));

	//cant = 0
	$("#cant").val(0);

	//cantidad = 1 servicio o producto
	$("#cantidad").val(1);

	$("#tipodocumento_id").val(3);

	$("#serieventa").inputmask({"mask": "9999-9999999"});

	// a continuacion creamos la fecha en la variable date
	var date = new Date()
	// Luego le sacamos los datos año, dia, mes 
	// y numero de dia de la variable date
	var año = date.getFullYear()
	var mes = date.getMonth()
	var ndia = date.getDate()
	//Damos a los meses el valor en número
	mes+=1;
	if(mes<10) mes="0"+mes;
	if(ndia<10) ndia="0"+ndia;
	//juntamos todos los datos en una variable
	var fecha = ndia + "/" + mes + "/" + año
	$('#fecha').val(fecha);

	//CLIENTE ANÓNIMO
		$('#cliente_id').val({{ $anonimo->id }});
		$('#cliente').val('VARIOS');
		$("#cliente").prop('disabled',true);

	$('.btnDefecto').on('click', function(){
		$('#cliente_id').val({{ $anonimo->id }});
		$('#cliente').val('VARIOS');
		$("#cliente").prop('disabled',true);
	});

	$('.btnBorrar').on('click', function(){
		$('#cliente_id').val("");
		$('#cliente').val("");
		$("#cliente").prop('disabled',false);
	});

	generarNumeroSerie();

	permisoRegistrar();

	$('#btnMostrarEmpleados').on('click',function(){
		if($('#btnMostrarEmpleados').attr('value')=='0'){
			$('#btnMostrarEmpleados').attr('value','1');
			$('#empleados').css('display','none');
			$('#btnMostrarEmpleados').removeClass('glyphicon-chevron-up');
			$('#btnMostrarEmpleados').addClass('glyphicon-chevron-down');
			$('#tituloDetalle').css('margin-top','5px');
		}else if($('#btnMostrarEmpleados').attr('value')=='1'){
			$('#btnMostrarEmpleados').attr('value','0');
			$('#empleados').css('display','-webkit-inline-box');
			$('#btnMostrarEmpleados').removeClass('glyphicon-chevron-down');
			$('#btnMostrarEmpleados').addClass('glyphicon-chevron-up');
			$('#tituloDetalle').css('margin-botton','0px');
		}
	});

	$(".montos").blur(function() {
		if($('#montoefectivo').val() != ""){
			var montoefectivo = parseFloat($('#montoefectivo').val());
		}else{
			var montoefectivo = 0.00;
		}
		if($('#montovisa').val() != ""){
			var montovisa = parseFloat($('#montovisa').val());
		}else{
			var montovisa = 0.00;
		}
		if($('#montomaster').val() != ""){
			var montomaster = parseFloat($('#montomaster').val());
		}else{
			var montomaster = 0.00;
		}
		var total = parseFloat($("#total").val());
		var vuelto = montoefectivo + montovisa + montomaster - total;
		if(vuelto < 0){
			vuelto =0.00;
		}
		$('#vuelto').val(vuelto.toFixed(2));
		if(montoefectivo - vuelto  + montovisa + montomaster != total){
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
			cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual al TOTAL.</li></ul></div>';
			$('#divMensajeErrorVenta').html(cadenaError);
			$('#btnGuardar').prop('disabled', true);
		}else{
			$('#divMensajeErrorVenta').html("");
			$('#btnGuardar').prop('disabled', false);
		}
	});

	
	$(document).on('mouseover', function(){
		$('.servicio_frecuente').css('background', 'rgb(255,255,255)');
	});

	$(".servicio_frecuente").on('click', function(){
		var elemento = this;
		var idservicio_frecuente = $(this).attr('id');
		var precio = parseFloat($(this).attr('precio'));
		var descripcion = $(this).attr('descripcion');
		var editable = $(this).attr('editable');
		var cant = $("#cant").val();

		$(this).css('background', 'rgb(179,188,237)');
		
		var existe = false;

		if(cant != 0){
			$("#detalle tr").each(function(){
				if(idservicio_frecuente == this.id){
					if($(this).attr('class') == "DetalleServicio"){
						var cantidadfila = parseInt($(this).attr('cantidad'));
						var precioactual = parseFloat($(this).attr('precio'));
						cantidadfila++;
						$(this).attr('cantidad',cantidadfila);
						if(editable == 0){
							var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;">'+ cantidadfila +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio*cantidadfila).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
						}else if(editable == 1){
							var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;">'+ cantidadfila +'</td><td style="vertical-align: middle;"><input class="form-control input-xs precioeditable" style="text-align: right; width: 70px;" type="text" value="'+ (precioactual).toFixed(2) +'"></td><td class="precioacumulado" style="vertical-align: middle;">'+ (precioactual*cantidadfila).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precioactual*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
						}
						$(this).html(nuevafila);
						existe = true;
						calcularTotal();
					}
				}
			});
		}

		if(!existe){
			if(editable == 0){
				var fila =  '<tr align="center" class="DetalleServicio" id="'+ idservicio_frecuente +'" cantidad="'+ 1 +'" precio='+ (precio).toFixed(2) +'><td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;">'+ 1 +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
			}else if(editable == 1){
				var fila =  '<tr align="center" class="DetalleServicio" id="'+ idservicio_frecuente +'" cantidad="'+ 1 +'" precio='+ (precio).toFixed(2) +'><td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;">'+ 1 +'</td><td style="vertical-align: middle;"><input class="form-control input-xs precioeditable" style="text-align: right; width: 70px;" type="text" value="'+ (precio).toFixed(2) +'"></td><td class="precioacumulado" style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
			}
			$("#detalle").append(fila);
			cant++;
			$("#cant").val(cant);
			calcularTotal();
		}

		$(".precioeditable").blur(function() {
			var elemento = this;
			var precionuevo = parseFloat($(this).val());
			if(precionuevo >= 0){
				var tr = $(this).parent().parent();
				var precioactual = $(tr).attr('precio');
				var cantidad = $(tr).attr('cantidad');
				$(tr).attr('precio',(precionuevo).toFixed(2));
				var trprecioacumulado = $(tr).find('.precioacumulado');
				$(trprecioacumulado).html((precionuevo*cantidad).toFixed(2));
				var btneliminar = $(tr).find('.btnEliminar');
				$(btneliminar).attr('precio',(precionuevo*cantidad).toFixed(2));
				calcularTotal();
				$("#montoefectivo").val("");
				$("#montovisa").val("");
				$("#montomaster").val("");
				$("#vuelto").val((0).toFixed(2));
				//$('#btnGuardar').prop('disabled', false);
			}else{
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>Los campos de precios editables no deben ser negativos.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
				$('#btnGuardar').prop('disabled', true);
			}

		});
	
		
		$("#montoefectivo").val("");
		$("#montovisa").val("");
		$("#montomaster").val("");
		$("#vuelto").val((0).toFixed(2));

		

		if($('#montoefectivo').val() != ""){
			var montoefectivo = parseFloat($('#montoefectivo').val());
		}else{
			var montoefectivo = 0.00;
		}
		if($('#montovisa').val() != ""){
			var montovisa = parseFloat($('#montovisa').val());
		}else{
			var montovisa = 0.00;
		}
		if($('#montomaster').val() != ""){
			var montomaster = parseFloat($('#montomaster').val());
		}else{
			var montomaster = 0.00;
		}
		var total = parseFloat($("#total").val());
		var vuelto = montoefectivo + montovisa + montomaster - total;
		if(vuelto < 0){
			vuelto =0.00;
		}
		$('#vuelto').val(vuelto.toFixed(2));
		if(montoefectivo - vuelto  + montovisa + montomaster != total){
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
			cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual al TOTAL.</li></ul></div>';
			$('#divMensajeErrorVenta').html(cadenaError);
			$('#btnGuardar').prop('disabled', true);
		}else{
			$('#divMensajeErrorVenta').html("");
			$('#btnGuardar').prop('disabled', false);
		}

	});

//'onclick' => 'guardarventa()' ,

	$('.btnGuardar').on('click', function(){
		var sucursal = document.getElementById("sucursal_id");
		var empleado = $('#empleado_id').val();
		var cant = parseInt($("#cant"). val());
		var tipo = $('#tipodocumento_id').val();
		var total = parseFloat($("#total").val());
		var letra = "";
		if(tipo == 1){
			letra ="B";
		}else if(tipo == 2){
			letra ="F";
		}else if(tipo == 3){
			letra ="T";
		}


		var negativo = false;
		$(".precioeditable").each(function(){
			var precioeditable = parseFloat($(this).val());
			if(precioeditable >= 0){
				negativo = false;
			}else{
				negativo = true;
			}
		});
		console.log("hay negativos = " + negativo);

		if(!negativo){
			if(!empleado || cant==0){
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				if(!empleado){
					cadenaError += ' <li> El campo empleado es obligatorio.</li>';
				}
				if(cant ==0){
					cadenaError += '<li>Debe agregar mínimo un servicio o producto.</li></ul></div>';
				}
				$('#divMensajeErrorVenta').html(cadenaError);
			}else{
				swal({
					title: 'Confirmar Guardado',
					html: "<p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text +"</p><p><label>N° Venta: </label>  "+ letra+ $('#serieventa').val()+"</p><p><label>Cliente:  </label>  "+ $('#cliente').val()+"</p><p><label>Empleado:  </label>  "+ $('#empleado_nombre').val()+"</p><p><label>Total:  </label>  S/."+  total.toFixed(2) +"</p>",
					type: 'question',
					showCancelButton: true,
					confirmButtonColor: '#54b359',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Guardar Venta'
					}).then((result) => {
						if (result.value) {
							guardarventa();
							setTimeout(function(){
								cargarRutaMenu('caja', 'container', '15');
							},3000);
						}
					})
			}
		}else{
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
			cadenaError += '<li>Los campos de precios editables no deben ser negativos.</li></ul></div>';
			$('#divMensajeErrorVenta').html(cadenaError);
			$('#btnGuardar').prop('disabled', true);
		}
	});

});

function generarNumeroSerie(){
	var serieventa = null;

	var sucursal_id = $('#sucursal_id').val();

	var tipodocumento_id = $('#tipodocumento_id').val();

	var serieajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/serieventa') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"tipodocumento_id" : tipodocumento_id,
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		serieventa = info;
	}).always(function(){
		$('#serieventa').val(serieventa);
	});
}

function permisoRegistrar(){

	var aperturaycierre = null;

	var sucursal_id = $('#sucursal_id').val();

	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		aperturaycierre = info;
	}).always(function(){
		if(aperturaycierre == 0){
			$('form').find('input, textarea, button').prop('disabled',true);
			$("#tipodocumento_id").prop('disabled',true);

			$(".empleado").css('background', 'rgb(255,255,255)');

			$(".empleado").on('click', function(){
				$(".empleado").css('background', 'rgb(255,255,255)');
			});

			$('#divMensajeErrorVenta').html("");

			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';

			var surcursal_id = $('#sucursal_id').val();

			if(sucursal_id != null){
				$('#divMensajeErrorVenta').html(cadenaError);
			}


		}else if(aperturaycierre == 1){
			$('form').find('input, textarea, button').prop('disabled',false);
			$("#tipodocumento_id").prop('disabled',false);
			$("#cliente").prop('disabled',true);
			

			$(".empleado").css('background', 'rgb(255,255,255)');

			$(".empleado").on('click', function(){
				var idempleado = $(this).attr('id');
				$(".empleado").css('background', 'rgb(255,255,255)');
				$(this).css('background', 'rgb(179,188,237)');
				$('#empleado_id').attr('value',idempleado);
				$("#empleado_nombre").val($(this).children('label').html());

				setTimeout ("ocultarEmpleados();", 500); 
				$('#divMensajeErrorVenta').html("");
			});

			$('#divMensajeErrorVenta').html("");

		}
	});

	return aperturaycierre;
}
function ocultarEmpleados(){
	$('#btnMostrarEmpleados').attr('value','1');
	$('#empleados').css('display','none');
	$('#btnMostrarEmpleados').removeClass('glyphicon-chevron-up');
	$('#btnMostrarEmpleados').addClass('glyphicon-chevron-down');
	$('#tituloDetalle').css('margin-top','5px');
}

</script>

<script>
var clientes = new Bloodhound({
        datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.value);
        },
        limit: 5,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'venta/clienteautocompletar/%QUERY',
            filter: function (clientes) {
                return $.map(clientes, function (cliente) {
                    return {
                        value: cliente.value,
                        id: cliente.id,
                    };
                });
            }
        }
    });
    clientes.initialize();
    $('#cliente').typeahead(null,{
        displayKey: 'value',
        source: clientes.ttAdapter()
    }).on('typeahead:selected', function (object, datum) {
        $('#cliente').val(datum.value);
		$('#cliente_id').val(datum.id);
		$("#cliente").prop('disabled',true);
    }); 
</script>

<script>
var servicios = new Bloodhound({
        datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.descripcion);
        },
        limit: 5,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'venta/servicioautocompletar/%QUERY',
            filter: function (servicios) {
                return $.map(servicios, function (servicio) {
                    return {
						precio: servicio.precio,
						nombre: servicio.nombre,
                        descripcion: servicio.descripcion,
                        id: servicio.id,
						tipo: servicio.tipo,
						editable: servicio.editable,
                    };
                });
            }
        }
    });
    servicios.initialize();

var productos = new Bloodhound({
        datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.descripcion);
        },
        limit: 5,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'venta/productoautocompletar/%QUERY',
            filter: function (productos) {
                return $.map(productos, function (producto) {
                    return {
						precio: producto.precio,
						nombre: producto.nombre,
                        descripcion: producto.descripcion,
                        id: producto.id,
						tipo: producto.tipo,
						editable: producto.editable,
                    };
                });
            }
        }
    });
    productos.initialize();


    $('#servicio').typeahead({
		highlight: true
	},
	{
		name: 'servicios',
		displayKey: 'descripcion',
		source: servicios.ttAdapter(),
		templates: {
			header: '<h4 style="margin-left: 10px">SERVICIOS</h4>'
		}
	},
	{
		name: 'productos',
		displayKey: 'descripcion',
		source: productos.ttAdapter(),
		templates: {
			header: '<h4 style="margin-left: 10px">PRODUCTOS</h4>'
		}
	}).on('typeahead:selected', function (object, datum) {
		$('#servicio').val(datum.descripcion);
		$('#servicionombre').val(datum.nombre);
        $('#servicio_id').val(datum.id);
		$('#tipo').val(datum.tipo);
		$('#precio').val(datum.precio);
		$('#editable').val(datum.editable);
    }); 
</script>

<script>
$(document).ready(function(){
	$('.btnAgregar').on('click', function(){
		var servicio = $("#servicionombre").val();	
		var cantidad = parseInt($("#cantidad").val());
		var servicio_id = $("#servicio_id").val();
		var precio = parseFloat($("#precio").val());
		var editable = $("#editable").val();
		var cant = $("#cant"). val();
		if(servicio && cantidad && servicio_id){
			
			if(cant != 0){
				/**/	

				//cant++;

				var existe = false;
				
				$("#detalle tr").each(function(){
					if(servicio_id == this.id){
						if($(this).attr('class') == "DetalleServicio" && $("#tipo").val() == 'S'){
							var cantidadfila = parseInt($(this).attr('cantidad'));
							var precioactual = parseFloat($(this).attr('precio'));
							cantidadfila = cantidadfila + cantidad;
							$(this).attr('cantidad',cantidadfila);
							if(editable == 0){
								var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ servicio +'</td><td style="vertical-align: middle;">'+ cantidadfila +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio*cantidadfila).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
							}else if(editable == 1){
								var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ servicio +'</td><td style="vertical-align: middle;">'+ cantidadfila +'</td><td style="vertical-align: middle;"><input class="form-control input-xs precioeditable" style="text-align: right; width: 70px;" type="text" value="'+ (precioactual).toFixed(2) +'"></td><td class="precioacumulado" style="vertical-align: middle;">'+ (precioactual*cantidadfila).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precioactual*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
							}
							$(this).html(nuevafila);
							existe = true;
							calcularTotal();
						}else if($(this).attr('class') == "DetalleProducto" && $("#tipo").val() == 'P'){
							var cantidadfila = parseInt($(this).attr('cantidad'));
							cantidadfila = cantidadfila + cantidad;
							$(this).attr('cantidad',cantidadfila);
							var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ servicio +'</td><td style="vertical-align: middle;">'+ cantidadfila +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio*cantidadfila).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
							$(this).html(nuevafila);
							existe = true;
							calcularTotal();
						}
					}
				});

				$("#servicio").val("");
				$("#cantidad").val("");
				$("#servicio_id").val("");
				$("#precio").val("");	
				$("#editable").val("");
			}

			if(!existe){
				cant++;
				var fila = "";
				if($("#tipo").val() == 'S'){
					if(editable == 0){
						fila =  '<tr align="center" class="DetalleServicio" id="'+ servicio_id +'" cantidad="'+ cantidad +'" precio='+ (precio).toFixed(2) +'><td style="vertical-align: middle; text-align: left;">'+ servicio +'</td><td style="vertical-align: middle;">'+ cantidad +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio*cantidad).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio*cantidad).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
					}else if(editable == 1){
						fila =  '<tr align="center" class="DetalleServicio" id="'+ servicio_id +'" cantidad="'+ cantidad +'" precio='+ (precio).toFixed(2) +'><td style="vertical-align: middle; text-align: left;">'+ servicio +'</td><td style="vertical-align: middle;">'+ cantidad +'</td><td style="vertical-align: middle;"><input class="form-control input-xs precioeditable" style="text-align: right; width: 70px;" type="text" value="'+ (precio).toFixed(2) +'"></td><td class="precioacumulado" style="vertical-align: middle;">'+ (precio*cantidad).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio*cantidad).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
					}
				}else{
					fila =  '<tr align="center" class="DetalleProducto" id="'+ servicio_id +'" cantidad="'+ cantidad +'" precio='+ (precio).toFixed(2) +'><td style="vertical-align: middle; text-align: left;">'+ servicio +'</td><td style="vertical-align: middle;">'+ cantidad +'</td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio*cantidad).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" precio='+ (precio*cantidad).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
				}
				$("#detalle").append(fila);
				$("#cant").val(cant);
				$("#servicio").val("");
				$("#cantidad").val("");
				$("#servicio_id").val("");
				$("#precio").val("");
				$("#editable").val("");
				calcularTotal();
			}

			$(".precioeditable").blur(function() {
				var elemento = this;
				var precionuevo = parseFloat($(this).val());
				if(precionuevo >= 0){
					var tr = $(this).parent().parent();
					var precioactual = $(tr).attr('precio');
					var cantidad = $(tr).attr('cantidad');
					$(tr).attr('precio',(precionuevo).toFixed(2));
					var trprecioacumulado = $(tr).find('.precioacumulado');
					$(trprecioacumulado).html((precionuevo*cantidad).toFixed(2));
					var btneliminar = $(tr).find('.btnEliminar');
					$(btneliminar).attr('precio',(precionuevo*cantidad).toFixed(2));
					calcularTotal();
					$("#montoefectivo").val("");
					$("#montovisa").val("");
					$("#montomaster").val("");
					$("#vuelto").val((0).toFixed(2));
					//$('#btnGuardar').prop('disabled', false);
				}else{
					var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
					cadenaError += '<li>Los campos de precios editables no deben ser negativos.</li></ul></div>';
					$('#divMensajeErrorVenta').html(cadenaError);
					$('#btnGuardar').prop('disabled', true);
				}
			});

			$("#montoefectivo").val("");
			$("#montovisa").val("");
			$("#montomaster").val("");
			$("#vuelto").val((0).toFixed(2));
			//cantidad = 1 servicio o producto
			$("#cantidad").val(1);

			if($('#montoefectivo').val() != ""){
				var montoefectivo = parseFloat($('#montoefectivo').val());
			}else{
				var montoefectivo = 0.00;
			}
			if($('#montovisa').val() != ""){
				var montovisa = parseFloat($('#montovisa').val());
			}else{
				var montovisa = 0.00;
			}
			if($('#montomaster').val() != ""){
				var montomaster = parseFloat($('#montomaster').val());
			}else{
				var montomaster = 0.00;
			}
			var total = parseFloat($("#total").val());
			var vuelto = montoefectivo + montovisa + montomaster - total;
			if(vuelto < 0){
				vuelto =0.00;
			}
			$('#vuelto').val(vuelto.toFixed(2));
			if(montoefectivo - vuelto  + montovisa + montomaster != total){
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual al TOTAL.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
				$('#btnGuardar').prop('disabled', true);
			}else{
				$('#divMensajeErrorVenta').html("");
				$('#btnGuardar').prop('disabled', false);
			}
		}
	});
});
</script>

<script>

function eliminarDetalle(comp){
	var precioeliminar = parseFloat($(comp).attr('precio'));
	var cant = $("#cant"). val();
	cant--;
	$("#cant").val(cant);
	var total = parseFloat($("#total").val());
	total -= precioeliminar;
	$("#total").val(total.toFixed(2));
	$("#montoefectivo").val("");
	$("#montovisa").val("");
	$("#montomaster").val("");
	$("#vuelto").val((0).toFixed(2));
	(($(comp).parent()).parent()).remove();

	if($('#montoefectivo').val() != ""){
		var montoefectivo = parseFloat($('#montoefectivo').val());
	}else{
		var montoefectivo = 0.00;
	}
	if($('#montovisa').val() != ""){
		var montovisa = parseFloat($('#montovisa').val());
	}else{
		var montovisa = 0.00;
	}
	if($('#montomaster').val() != ""){
		var montomaster = parseFloat($('#montomaster').val());
	}else{
		var montomaster = 0.00;
	}
	var total = parseFloat($("#total").val());
	var vuelto = montoefectivo + montovisa + montomaster - total;
	if(vuelto < 0){
		vuelto =0.00;
	}
	$('#vuelto').val(vuelto.toFixed(2));
	if(montoefectivo - vuelto  + montovisa + montomaster != total){
		var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
		cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual al TOTAL.</li></ul></div>';
		$('#divMensajeErrorVenta').html(cadenaError);
		$('#btnGuardar').prop('disabled', true);
	}else{
		$('#divMensajeErrorVenta').html("");
		$('#btnGuardar').prop('disabled', false);
	}
}

function detalleventa(){
	var data = [];
	$("#detalle tr").each(function(){
		var element = $(this); // <-- en la variable element tienes tu elemento
		var tipo = element.attr('class');
		if(tipo === "DetalleServicio"){
			tipo = "S";
		}
		if(tipo === "DetalleProducto"){
			tipo = "P";
		}
		var id = element.attr('id');
		var cantidad = element.attr('cantidad');
		var precio = element.attr('precio');
	
		data.push(
			{"tipo": tipo , "id": id , "cantidad": cantidad, "precio": precio }
		);

	});
	var detalle = {"data": data};
	var json = JSON.stringify(detalle);
	var respuesta = "";
	var sucursal_id = $('#sucursal_id').val();

	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/guardardetalle') }}",
		"data": {
			"_token": "{{ csrf_token() }}",
			"sucursal_id" : sucursal_id, 
			"json": json
			}
	}).done(function(info){
		respuesta = info;
	}).always(function() {
		if (respuesta === 'OK') {
			$("#IDFORMMANTENIMIENTOVenta")[0].reset();

			//colocar total 0.00
			$("#total").val((0).toFixed(2));

			//cantidad = 1 servicio o producto
			$("#cantidad").val(1);
			
			//cant = 0
			$("#cant").val(0);

			$("#tipodocumento_id").val(3);

			// a continuacion creamos la fecha en la variable date
			var date = new Date()
			// Luego le sacamos los datos año, dia, mes 
			// y numero de dia de la variable date
			var año = date.getFullYear()
			var mes = date.getMonth()
			var ndia = date.getDate()
			//Damos a los meses el valor en número
			mes+=1;
			if(mes<10) mes="0"+mes;
			if(ndia<10) ndia="0"+ndia;
			//juntamos todos los datos en una variable
			var fecha = ndia + "/" + mes + "/" + año
			$('#fecha').val(fecha);
			
			$("#detalle").html("");

			generarNumeroSerie();

			permisoRegistrar();

			$('#cliente_id').val({{ $anonimo->id }});
			$('#cliente').val('VARIOS');

			$('#empleado_id').val("");
			$(".empleado").css('background', 'rgb(255,255,255)');
		}
	});
}

function calcularTotal(){
	var total = 0;
	$("#detalle tr").each(function(){
		var cantidad = parseInt($(this).attr('cantidad'));
		var precio = parseFloat($(this).attr('precio'));
		total += precio*cantidad;
	});
	$("#total").val(total.toFixed(2));
}

</script>


