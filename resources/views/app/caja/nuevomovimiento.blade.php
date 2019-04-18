<script>
function cargarselect2(entidad){
	var select = $('#tipo_id').val();

	if(select == ''){
		$('#concepto_id').html('<option value="" selected="selected">Seleccione</option>');
		return false;
	}

	route = 'caja/cargarselect/' + select + '?entidad=' + entidad + '&t=si';

	$.ajax({
		url: route,
		headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
		type: 'GET',
		beforeSend: function() {
			$('#concepto_id').html('<option value="" selected="selected">Seleccione</option>');
		},
		success: function(res){
			$('#concepto_id').html(res);
		}
	});
}
</script>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($caja, $formData) !!}	

{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
{!! Form::hidden('caja_id', $num_caja[0]->id, array('id' => 'caja_id')) !!}
<div class="row">
	<div class="col-md-6">
		<fieldset >    
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                {!! Form::label('numero_caja', 'Nro Caja:') !!}<div class="" style="display: inline-block;color: red;">*</div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::text('numero_caja', $num_caja[0]->num_caja, array('class' => 'form-control input-xs', 'id' => 'numero_caja', 'placeholder' => 'Ingrese nombre', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                {!! Form::label('numero_operacion', 'Nro Oper.:') !!}
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::text('numero_operacion', $numero_operacion, array('class' => 'form-control input-xs', 'id' => 'numero_operacion', 'placeholder' => 'Ingrese nombre', 'readonly')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                {!! Form::label('fecha', 'Fecha:') !!}<div class="" style="display: inline-block;color: red;">*</div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'Ingrese nombre')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                {!! Form::label('cajero', 'Cajero:') !!}<div class="" style="display: inline-block;color: red;">*</div>
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
                    {!! Form::text('comentario', null, array('class' => 'form-control input-xs', 'id' => 'comentario', 'placeholder' => 'Ingrese comentario')) !!}
                </div>
            </div>
		</fieldset>	
	</div>
    <div class="col-md-6">
        <fieldset>   
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                    {!! Form::label('tipo_id', 'Tipo:') !!}<div class="" style="display: inline-block;color: red;">*</div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::select('tipo_id', $cboTipo, null, array('class' => 'form-control input-xs', 'id' => 'tipo_id', 'onchange' => 'cargarselect2("concepto")')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                    {!! Form::label('concepto_id', 'Concepto:') !!}<div class="" style="display: inline-block;color: red;">*</div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::select('concepto_id', $cboConcepto, null, array('class' => 'form-control input-xs', 'id' => 'concepto_id')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                {!! Form::label('total', 'Total:') !!}
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::text('total', null, array('class' => 'form-control input-xs', 'id' => 'total', 'placeholder' => 'Ingrese nombre', 'onkeypress'=>'return filterFloat(event,this)' )) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                    {!! Form::label('forma_pago', 'Forma Pago:') !!}<div class="" style="display: inline-block;color: red;">*</div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::select('forma_pago', $cboFormaPago, null, array('class' => 'form-control input-xs', 'id' => 'forma_pago')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 10px">
                    {!! Form::label('persona_id', 'Entregado A:') !!}
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    {!! Form::select('persona_id', $cboPersona, null, array('class' => 'form-control input-xs', 'id' => 'persona_id')) !!}
                </div>
            </div>

		</fieldset>
    </div>

	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarMovimiento', 'onclick' => 'guardar_movimiento(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
</div>	
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('800');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$('.input-number').on('input', function () { 
    	this.value = this.value.replace(/[^0-9]/g,'');
	});

    var fechaActual = new Date();
    var day = ("0" + fechaActual.getDate()).slice(-2);
    var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
    var fechai= (fechaActual.getFullYear()) +"-"+month+"-01";
    var fechaf= (fechaActual.getFullYear()) +"-"+month+"-"+day+"";
    $('#fecha').val(fechai);


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

function guardar_movimiento (entidad, x) {
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