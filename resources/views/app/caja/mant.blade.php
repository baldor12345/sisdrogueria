@if($caja_abierta == 0)
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($caja, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

<div class="form-group">
	{!! Form::label('num_caja', 'Nro Caja:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('num_caja', $num_caja, array('class' => 'form-control input-xs', 'id' => 'num_caja', 'placeholder' => 'Ingrese titulo', 'readonly')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('concepto_id', 'Concepto:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::select('concepto_id', $cboConcepto, null, array('class' => 'form-control input-xs', 'id' => 'concepto_id')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('fecha_horaApert', 'F. Apertura:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::date('fecha_horaApert', null, array('class' => 'form-control input-xs', 'id' => 'fecha_horaApert', 'placeholder' => '')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('hora_apertura', 'H. Apertura:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::time('hora_apertura', null, array('class' => 'form-control input-xs', 'id' => 'hora_apertura', 'placeholder' => '')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('monto_ini', 'Monto In.:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('monto_ini', $ingresos, array('class' => 'form-control input-xs', 'id' => 'monto_ini', 'placeholder' => 'S/.','readonly')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('cajero', 'Cajero:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('cajero', $cajero_dat->apellidos.' '.$cajero_dat->nombres, array('class' => 'form-control input-xs', 'id' => 'cajero', 'placeholder' => 'Ingrese titulo', 'readonly')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('descripcion', 'Descripcion:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::textarea('descripcion', null, array('class' => 'form-control input-xs', 'cols'=>'10', 'rows'=>'rows', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion')) !!}
	</div>
</div>


<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarcaja', 'onclick' => 'aperturarcaja(\''.$entidad.'\', this)')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="usertype_id"]').focus();
		configurarAnchoModal('450');
		$('#fecha_horaApert').val('{{ $fecha_apertura }}');
		$('#hora_apertura').val('{{ $hora_apertura }}');
		
		
	}); 
	function aperturarcaja(entidad, idboton){
		var last_day = '{{$limit_day}}';
		var fecha_select = $('#fecha_horaApert').val();
		console.log(last_day+" "+fecha_select);
		if(fecha_select > last_day){
			guardar(entidad, idboton);
		}else{
			document.getElementById("divMensajeError{{ $entidad }}").innerHTML = "<div class='alert alert-danger' role='alert'><span >la fecha de apertura debe ser mayor que "+last_day+"</span></div>";
				$('#divMensajeError{{ $entidad }}').show();
		}

		
	}
</script>
@else
<h3 class="text-warning">Cerrar caja aperturada antes de aperturar nueva caja, Gracias!.</h3>
@endif