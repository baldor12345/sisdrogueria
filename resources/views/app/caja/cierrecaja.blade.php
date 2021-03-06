@if($count_caja != 0)
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($caja, $formData) !!}	

{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
{!! Form::hidden('caja_id', $caja_dat[0]->id, array('id' => 'caja_id')) !!}
<div class="row">
	<fieldset >    
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
			{!! Form::label('numero_caja', 'Nro Caja:') !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::text('numero_caja', $caja_dat[0]->num_caja, array('class' => 'form-control input-xs', 'id' => 'numero_caja', 'placeholder' => 'Ingrese nombre', 'readonly')) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
			{!! Form::label('fecha', 'F. Cierre:') !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'Ingrese nombre')) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
			{!! Form::label('hora_cierre', 'H. Cierre:') !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::time('hora_cierre', null, array('class' => 'form-control input-xs', 'id' => 'hora_cierre', 'placeholder' => '')) !!}
			</div>
		</div>

		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
				{!! Form::label('concepto_id', 'Concepto:') !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('concepto_id', $cboConcepto, null, array('class' => 'form-control input-xs', 'id' => 'concepto_id')) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
			{!! Form::label('monto_cierre', 'Monto Cie.:') !!}<div class="" style="display: inline-block;color: red;">*</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::text('monto_cierre', $saldo, array('class' => 'form-control input-xs', 'id' => 'monto_cierre', 'placeholder' => 'Ingrese nombre', 'onkeypress'=>'return filterFloat(event,this)', 'readonly')) !!}
			</div>
		</div>
		
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
			{!! Form::label('cajero', 'Cajero:') !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::text('cajero', $cajero_dat->apellidos.' '.$cajero_dat->nombres, array('class' => 'form-control input-xs', 'id' => 'cajero', 'placeholder' => 'Ingrese nombre', 'readonly')) !!}
			</div>
		</div>

		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
			{!! Form::label('comentario', 'Comentario:') !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::textarea('comentario', null, array('class' => 'form-control input-xs', 'cols'=>'10', 'rows'=>'rows' ,'id' => 'comentario', 'placeholder' => 'Ingrese comentario')) !!}
			</div>
		</div>
	</fieldset>	

	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarMovimiento', 'onclick' => 'cerrarcaja(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
</div>	
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('400');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$('.input-number').on('input', function () { 
    	this.value = this.value.replace(/[^0-9]/g,'');
	});

    $('#fecha').val('{{ $fecha_cierre }}');
	$('#hora_cierre').val('{{ $hora_cierre }}');


	$('#persona_id').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listpersonas'], array()) }}",
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

function cerrarcaja(entidad, idboton){
	var last_day = '{{$limit_day}}';
	var fecha_select = $('#fecha').val();
	console.log(last_day+" "+fecha_select);
	if(fecha_select >= last_day){
		guardar_cerrar_caja(entidad, idboton);
	}else{
		document.getElementById("divMensajeError{{ $entidad }}").innerHTML = "<div class='alert alert-danger' role='alert'><span >la fecha de apertura debe ser mayor que "+last_day+"</span></div>";
			$('#divMensajeError{{ $entidad }}').show();
	}

	
}


function guardar_cerrar_caja (entidad, x) {
    var idformulario = IDFORMMANTENIMIENTO + entidad;
    var data         = submitForm(idformulario);
    var respuesta    = '';
    var listar       = 'NO';
    if ($(idformulario + ' :input[id = "listar"]').length) {
        var listar = $(idformulario + ' :input[id = "listar"]').val()
    };
    $('#btnGuardarMovimiento').button('loading');
    data.done(function(msg) {
        respuesta = msg;
    }).fail(function(xhr, textStatus, errorThrown) {
        respuesta = 'ERROR';
        $('#btnGuardarMovimiento').removeClass('disabled');
        $('#btnGuardarMovimiento').removeAttr('disabled');
        $('#btnGuardarMovimiento').html('<i class="fa fa-check fa-lg"></i>Guardar');
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
                $('#btnGuardarMovimiento').removeClass('disabled');
                $('#btnGuardarMovimiento').removeAttr('disabled');
                $('#btnGuardarMovimiento').html('<i class="fa fa-check fa-lg"></i>Guardar');
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

@else
<h3 class="text-warning">No hay caja aperturada actualmente!.</h3>
@endif