<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($trabajador, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group col-6 col-md-6">
		{!! Form::label('nombres', 'Nombres:', array('class' => ' control-label')) !!}
		{!! Form::text('nombres', $trabajador != null? $trabajador->nombres:'', array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombre')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('apellidos', 'Apellidos:', array('class' => ' control-label')) !!}
		{!! Form::text('apellidos', $trabajador != null? $trabajador->apellidos:'', array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese apellidos')) !!}
	</div>
	<div class="form-group col-6 col-md-6">
		{!! Form::label('dni', 'DNI:', array('class' => ' control-label')) !!}
			{!! Form::text('dni',  $trabajador != null? $trabajador->dni:'', array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('ruc', 'RUC:', array('class' => ' control-label')) !!}
			{!! Form::text('ruc',  $trabajador != null? $trabajador->ruc:'', array('class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese RUC')) !!}
	</div>
	<div class="form-group col-6 col-md-6" >
		{!! Form::label('direccion', 'Direccion:', array('class' => ' control-label')) !!}
			{!! Form::text('direccion',  $trabajador != null? $trabajador->direccion:'', array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion')) !!}
	</div>

	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('telefono', 'Teléfono:', array('class' => 'control-label')) !!}
			{!! Form::text('telefono',  $trabajador != null? $trabajador->telefono:'', array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese numero telefono')) !!}
	</div>
	<div class="form-group col-6 col-md-6">
		{!! Form::label('celular', 'Celular:', array('class' => 'control-label')) !!}
			{!! Form::text('celular',  $trabajador != null? $trabajador->celular:'', array('class' => 'form-control input-xs', 'id' => 'celular', 'placeholder' => 'Ingrese numero celular')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('email', 'Email:', array('class' => 'control-label')) !!}
		{!! Form::text('email',  $trabajador != null? $trabajador->email:'', array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese correo electronico')) !!}
	</div>
	<div class="form-group col-6 col-md-6">
		{!! Form::label('fecha_nacimiento', 'Fecha de nacimiento: ', array('class' => '')) !!}
		{!! Form::date('fecha_nacimiento', $trabajador ==null?$fecha_default:$trabajador->fecha_nacimiento, array('class' => 'form-control input-xs', 'id' => 'fecha_nacimiento', 'placeholder' => '')) !!}
</div>
<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('fecha_ingreso', 'Fecha de ingreso: ', array('class' => '')) !!}
		{!! Form::date('fecha_ingreso', $trabajador ==null?$fecha_default:date('Y-m-d',strtotime($detalle_trabajador->fecha_ingreso)), array('class' => 'form-control input-xs', 'id' => 'fecha_ingreso', 'placeholder' => '')) !!}
	</div>
	<div class="form-group col-6 col-md-6" >
		{!! Form::label('cboTipoPersona', 'Tipo_Personal: ', array('class' => '')) !!}
		{!! Form::select('cboTipoPersona', $cboTipo_personas,$trabajador != null? $trabajador->tipo_persona_id: 0 , array('class' => 'form-control input-sm', 'id' => 'cboTipoPersona')) !!}
	</div>
	<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
		{!! Form::label('cboestado', 'Estado: ', array('class' => '')) !!}
		{!! Form::select('cboestado', $cboestados,$trabajador != null? $trabajador->estado: 'A' , array('class' => 'form-control input-sm', 'id' => 'cboestado')) !!}
	</div>
<div class="form-group col-6 col-md-6">
		{!! Form::label('cbosucursal', 'Sucursal: ', array('class' => '')) !!}
		{!! Form::select('cbosucursal', $cboSucursales,$detalle_trabajador != null? $detalle_trabajador->sucursal_id:0 , array('class' => 'form-control input-sm', 'id' => 'cbosucursal')) !!}
</div>

<div class="form-group col-6 col-md-6" style="margin-left: 3px;">
	{!! Form::label('observacion', 'Observación:', array('class' => 'control-label')) !!}
		{!! Form::text('observacion', $trabajador != null? $trabajador->observacion:'' , array('class' => 'form-control input-xs', 'id' => 'observacion', 'placeholder' => 'Ingrese una observacion')) !!}
</div>
<div class="form-group ">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('750');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>