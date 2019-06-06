<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($cliente, $formData) !!}	
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group col-6 col-md-6" >
		{!! Form::label('cboTipoDocumento', 'Tipo Documento: ', array('class' => '')) !!}
		@if($cliente != null)
		{!! Form::select('cboTipoDocumento', $cboTipoDocumento,$cliente != null?($cliente->dni == null? 'ruc': 'dni'):'dni' , array('class' => 'form-control input-sm', 'id' => 'cboTipoDocumento','disabled'=>true)) !!}
		@else
		{!! Form::select('cboTipoDocumento', $cboTipoDocumento,'dni' , array('class' => 'form-control input-sm', 'id' => 'cboTipoDocumento')) !!}
		@endif
	</div>
	<div class="form-group  col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('doc', 'DNI:', array('class' => 'control-label')) !!}
		<div class="input-group" style="">
			{{-- {!! Form::text('doc', null, array('class' => 'form-control input-sm', 'id' => 'doc', 'placeholder' => 'N° DNI', 'maxlength'=>'8')) !!} --}}
			{!! Form::text('doc', $cliente != null?($cliente->dni == null? $cliente->ruc:$cliente->dni): '', array('class' => 'form-control input-sm', 'id' => 'doc', 'placeholder' => 'Ingrese numero doc.','maxlength'=>'11')) !!}
			<span class="input-group-btn">
				{!! Form::button('<i class="glyphicon glyphicon-refresh" id="ibtnConsultar"></i>', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnConsultar', 'onclick' => 'consultaDNIRUC();')) !!}
			</span>
		</div>
	</div>
	{{-- <div class="form-group col-6 col-md-6"  style="margin-left: 3px;">
		{!! Form::label('doc', 'N° Documento:', array('class' => ' control-label')) !!}
		{!! Form::text('doc', $cliente != null?($cliente->dni == null? $cliente->ruc:$cliente->dni): '', array('class' => 'form-control input-xs', 'id' => 'doc', 'placeholder' => 'Ingrese numero doc.','maxlength'=>'11')) !!}
	</div> --}}

	<div class="form-group col-6 col-md-6 clas_dni">
		{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
	</div>
	<div class="form-group col-6 col-md-6 clas_dni" style="margin-left: 3px;">
		{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}
		{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
	</div>

	{{-- <div class="form-group col-12 col-md-12">
		{!! Form::label('codigo_medico', 'Código de Médico Asignado:', array('class' => ' control-label')) !!}
		{!! Form::text('codigo_medico', null, array('class' => 'form-control input-xs', 'id' => 'codigo_medico', 'placeholder' => 'Código Médico asignado')) !!}
	</div> --}}

	<div class="form-group col-12 col-md-12 clas_ruc">
		{!! Form::label('razon_social', 'Razon Social:', array('class' => ' control-label')) !!}
		{!! Form::text('razon_social', null, array('class' => 'form-control input-xs', 'id' => 'razon_social', 'placeholder' => 'Ingrese Razon Social')) !!}
	</div>

	<div class="form-group col-6 col-md-6" >
		{!! Form::label('direccion', 'Direccion:', array('class' => ' control-label')) !!}
			{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion')) !!}
	</div>

	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('telefono', 'Teléfono:', array('class' => 'control-label')) !!}
			{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese numero telefono')) !!}
	</div>
	<div class="form-group col-6 col-md-6">
		{!! Form::label('celular', 'Celular:', array('class' => 'control-label')) !!}
			{!! Form::text('celular', null, array('class' => 'form-control input-xs', 'id' => 'celular', 'placeholder' => 'Ingrese numero celular')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('email', 'Email:', array('class' => 'control-label')) !!}
			{!! Form::text('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese correo electronico')) !!}
	</div>

<div class="form-group ">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> ConsultarDni', array('class' => 'btn btn-success btn-sm', 'id' => 'btnConsultarDni', 'onclick' => 'consultaRUC()')) !!}
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('750');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	
	$('.clas_ruc').hide();

	
	$('#cboTipoDocumento').change(function(){
		
		if($(this).val() == 'dni'){
			$('.clas_ruc').hide();
			$('.clas_dni').show();
			$('#dias').val(0);
		}else{
			$('.clas_dni').hide();
			$('.clas_ruc').show();
		}
	});
	
	
	// $('#doc').keyup(function(e){
		
	// 	if($(this).val().length ==8){
	// 		consultaDNI();
	// 	}else{
	// 		$("#nombres").val("");
 	// 		$("#apellidos").val("");
	// 	}
	// });

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

	var param = "";
	var doc = $("#doc").val();
	var tipodoc = $('#cboTipoDocumento').val();
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

</script>