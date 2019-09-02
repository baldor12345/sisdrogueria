
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::open(['route' => $ruta["guardarpresentacion"], 'method' => 'GET', 'onsubmit' => 'return false;', 'class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formMantenimientoProductoPresentacion']) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
{!! Form::hidden('idproducto', $idproducto, array('id' => 'idproducto')) !!}
<div class="form-group col-12 col-md-12">
	{!! Form::label('present_id', 'Presentación: ', array('class' => '')) !!}
	{!! Form::select('present_id', $cboPresentacion, null, array('class' => 'form-control input-sm', 'id' => 'present_id')) !!}
</div>
<div class="form-group col-12 col-md-12">
	{!! Form::label('preciocompra', 'P.Compra:', array('class' => '')) !!}
		{!! Form::text('preciocompra', null, array('class' => 'form-control input-xs','id' => 'preciocompra', 'placeholder' => 'Ingrese Precio de compra')) !!}
</div>
<div class="form-group col-12 col-md-12">
	{!! Form::label('unidad_x_presentacion', 'Cantidad:', array('class' => '')) !!}
		{!! Form::text('unidad_x_presentacion', null, array('class' => 'form-control input-xs input-number', 'id' => 'unidad_x_presentacion', 'placeholder' => 'Ingrese cantidad')) !!}
</div>
<div class="form-group col-12 col-md-12">
	{!! Form::label('precioventaunitario', 'P.Venta:', array('class' => '')) !!}
		{!! Form::text('precioventaunitario', null, array('class' => 'form-control input-xs', 'id' => 'precioventaunitario', 'placeholder' => 'Ingrese precio de venta')) !!}
</div>
<div class="form-group col-12 col-md-12">
	{!! Form::label('puntos', 'Puntos:', array('class' => '')) !!}
		{!! Form::text('puntos', null, array('class' => 'form-control input-xs input-number', 'id' => 'puntos', 'placeholder' => 'Ingrese puntos')) !!}
</div>




<div class="form-group">
    <div class="col-lg-12 col-md-12 col-sm-12 text-right">
        {!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarProductoPresentacion', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
        {!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
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

	$("#modal"+(contadorModal-1)).on('hidden.bs.modal', function(){
		$('.modal'+(contadorModal-2)).css('pointer-events','auto');
	});

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