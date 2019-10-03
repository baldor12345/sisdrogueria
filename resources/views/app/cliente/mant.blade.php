<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($cliente, $formData) !!}	
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group col-6 col-md-6" >
		{!! Form::label('cboTipoDocumento', 'Tipo Documento: ', array('class' => '')) !!}
		@if($cliente != null)
		{!! Form::select('cboTipoDocumento1', $cboTipoDocumento,$cliente != null?($cliente->dni == null? 'ruc': 'dni'):'dni' , array('class' => 'form-control input-sm', 'id' => 'cboTipoDocumento1','disabled'=>true)) !!}
		{!! Form::hidden('cboTipoDocumento', $cliente != null?($cliente->dni == null? 'ruc': 'dni'):'dni', array('id' => 'cboTipoDocumento')) !!}
		@else
		{!! Form::select('cboTipoDocumento', $cboTipoDocumento,'dni' , array('class' => 'form-control input-sm', 'id' => 'cboTipoDocumento')) !!}
		@endif
	</div>
	<div class="form-group  col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('doc', $cliente != null?($cliente->dni == null? 'RUC:': 'DNI:'):'DNI:', array('class' => 'control-label doclb')) !!}
		<div class="input-group" style="">
			{{-- {!! Form::text('doc', null, array('class' => 'form-control input-sm', 'id' => 'doc', 'placeholder' => 'N° DNI', 'maxlength'=>'8')) !!} --}}
			{!! Form::text('doc', $cliente != null?($cliente->dni == null? $cliente->ruc:$cliente->dni): '', array('class' => 'form-control input-sm', 'id' => 'doc', 'placeholder' => 'Ingrese numero doc.','maxlength'=>'11', 'onkeypress'=>'return filterFloat(event,this)')) !!}
			<span class="input-group-btn">
				{!! Form::button('<i class="glyphicon glyphicon-refresh" id=""></i>', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnConsultar', 'onclick' => 'consultaDNIRUC();')) !!}
			</span>
		</div>
	</div>
	{{-- <div class="form-group col-6 col-md-6"  style="margin-left: 3px;">
		{!! Form::label('doc', 'N° Documento:', array('class' => ' control-label')) !!}
		{!! Form::text('doc', $cliente != null?($cliente->dni == null? $cliente->ruc:$cliente->dni): '', array('class' => 'form-control input-xs', 'id' => 'doc', 'placeholder' => 'Ingrese numero doc.','maxlength'=>'11')) !!}
	</div> --}}
	@if($cliente != null)
		@if($cliente->dni != null)
			<div class="form-group col-6 col-md-6 clas_dni">
				{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}
				{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
			</div>
			<div class="form-group col-6 col-md-6 clas_dni" style="margin-left: 3px;">
				{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}
				{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
			</div>
			<div class="form-group col-12 col-md-12 clas_ruc" style="display: none;">
				{!! Form::label('razon_social', 'Razon Social:', array('class' => ' control-label')) !!}
				{!! Form::text('razon_social', null, array('class' => 'form-control input-xs', 'id' => 'razon_social', 'placeholder' => 'Ingrese Razon Social')) !!}
			</div>
		@else
			<div class="form-group col-6 col-md-6 clas_dni" style="display: none;">
				{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}
				{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
			</div>
			<div class="form-group col-6 col-md-6 clas_dni" style="margin-left: 3px; display: none;">
				{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}
				{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
			</div>
			<div class="form-group col-12 col-md-12 clas_ruc">
				{!! Form::label('razon_social', 'Razon Social:', array('class' => ' control-label')) !!}
				{!! Form::text('razon_social', null, array('class' => 'form-control input-xs', 'id' => 'razon_social', 'placeholder' => 'Ingrese Razon Social')) !!}
			</div>
		@endif
	@else
		<div class="form-group col-6 col-md-6 clas_dni">
			{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}
			{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
		</div>
		<div class="form-group col-6 col-md-6 clas_dni" style="margin-left: 3px;">
			{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}
			{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
		</div>
		<div class="form-group col-12 col-md-12 clas_ruc" style="display: none;">
			{!! Form::label('razon_social', 'Razon Social:', array('class' => ' control-label')) !!}
			{!! Form::text('razon_social', null, array('class' => 'form-control input-xs', 'id' => 'razon_social', 'placeholder' => 'Ingrese Razon Social')) !!}
		</div>
	@endif
	{{-- <div class="form-group col-6 col-md-6 clas_dni">
		{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
	</div>
	<div class="form-group col-6 col-md-6 clas_dni" style="margin-left: 3px;">
		{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}
		{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
	</div> --}}

	{{-- <div class="form-group col-12 col-md-12">
		{!! Form::label('codigo_medico', 'Código de Médico Asignado:', array('class' => ' control-label')) !!}
		{!! Form::text('codigo_medico', null, array('class' => 'form-control input-xs', 'id' => 'codigo_medico', 'placeholder' => 'Código Médico asignado')) !!}
	</div> --}}

	{{-- <div class="form-group col-12 col-md-12 clas_ruc">
		{!! Form::label('razon_social', 'Razon Social:', array('class' => ' control-label')) !!}
		{!! Form::text('razon_social', null, array('class' => 'form-control input-xs', 'id' => 'razon_social', 'placeholder' => 'Ingrese Razon Social')) !!}
	</div> --}}

	<div class="form-group col-6 col-md-6" >
		{!! Form::label('direccion', 'Direccion:', array('class' => ' control-label')) !!}
			{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion')) !!}
	</div>

	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('telefono', 'Teléfono:', array('class' => 'control-label')) !!}
			{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese numero telefono' , 'onkeypress'=>'return filterFloat(event,this)')) !!}
	</div>
	<div class="form-group col-6 col-md-6">
		{!! Form::label('celular', 'Celular:', array('class' => 'control-label')) !!}
			{!! Form::text('celular', null, array('class' => 'form-control input-xs', 'id' => 'celular', 'placeholder' => 'Ingrese numero celular', 'onkeypress'=>'return filterFloat(event,this)')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('email', 'Email:', array('class' => 'control-label')) !!}
			{!! Form::text('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese correo electronico')) !!}
	</div>

<div class="form-group ">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{{-- {!! Form::button('<i class="fa fa-check fa-lg"></i> ConsultarDni', array('class' => 'btn btn-success btn-sm', 'id' => 'btnConsultarDni', 'onclick' => 'consultaRUC()')) !!} --}}
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarcl', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('750');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	console.log('Gastelo');
	// $('.clas_ruc').hide();

	
	$('#cboTipoDocumento').change(function(){
		
		if($(this).val() == 'dni'){
			$('.clas_ruc').hide();
			$('.clas_dni').show();
			$('.doclb').text('DNI: ');
			
		}else{
			$('.clas_dni').hide();
			$('.clas_ruc').show();
			$('.doclb').text('RUC: ');
		}
	});
	
	
	/*$('#doc').keyup(function(e){
		if($(this).val().length >= 8){
			consultaDNIRUC();
		}
	});*/

	$("#modal"+(contadorModal - 1)).on('hidden.bs.modal', function () {
		$('.modal' + (contadorModal-2)).css('pointer-events','auto'); 
	});
}); 

function consultaRUC(){
    var ruc = $("#doc").val();
    $.ajax({
        type: 'GET',
        url: "http://localhost/SunatPHP/demo.php",
        data: "ruc="+ruc,
        beforeSend(){
        },
        success: function (data, textStatus, jqXHR) {
            $("#razon_social").val(data.RazonSocial);
            $("#direccion").val(data.Direccion);
            // $("#txtNombres").focus();
            // $("#txtDireccion").focus();
        }
    });
}

function consultaDNIRUC(){
	var tipodoc = $('#cboTipoDocumento').val();
	var doc = $("#doc").val();
	var param = "";

	$.get("clientes/"+doc+"/"+tipodoc,function(response, facultad){
		if(response[0]=='OK'){
			$('#btnGuardarcl').prop('disabled',true);
			// var nombrecompleto = "";
			if(tipodoc == 'dni'){
				$('#nombres').val(response[1].nombres);
				$('#apellidos').val(response[1].apellidos);
				// nombrecompleto = response[1].nombres+" "+response[1].apellidos;
			}else{
				$('#razon_social').val(response[1].razon_social);
				
			}

			
			$('#direccion').val(response[1].direccion);
			$('#telefono').val(response[1].telefono);
			$('#celular').val(response[1].celular);
			$('#email').val(response[1].email);
		}else{
			$('#nombres').val("");
			$('#apellidos').val("");
			$('#razon_social').val("");
			$('#direccion').val("");
			$('#telefono').val("");
			$('#celular').val("");
			$('#email').val("");

			$('#btnGuardarcl').prop('disabled',false);
			if(doc != '00000000' && doc.length >7){
				if(tipodoc == 'dni'){
					param = "accion=consultaDNI&dni="+doc;
					$.ajax({
						url: 'clientes/buscarclienteSunat',
						headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
						type: 'POST',
						data: ""+param,
						beforeSend: function(){ 
							// alert("Consultando...");
						},
						success: function(res){
							if(res.apepat == undefined){
								$('#divMensajeError{{ $entidad }}').html("<div class='alert alert-danger'>El DNI ingresado es incorrecto</div>");
								$('#divMensajeError{{ $entidad }}').show();
							}else{
								$('#divMensajeError{{ $entidad }}').hide();
								$("#nombres").val(res.nombres);
								$("#apellidos").val(res.apepat+" "+res.apemat);
							}
						}
					}).fail(function(){
						mostrarMensaje ("Error de servidor", "ERROR");
					});
				}else{
					consultaRUC();
				}
			}
		}
	});
	
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