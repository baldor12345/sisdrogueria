<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;
use App\Personamaestro;
use App\Persona;
use App\User;
use App\Concepto;
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
* anular
*/

$venta = "'venta'";
$container = "'container'";

?>
<div style="padding-top: 50px">

@if($aperturaycierre == 0)
		
	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}

	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm btnNuevo', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}

	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Venta Rápida', array('class' => 'btn btn-secondary waves-effect waves-light m-l-10 btn-sm btnNuevo', 'disabled', 'onclick' => 'cargarRutaMenu("venta", "container", 16)')) !!}

	{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger waves-effect waves-light m-l-10 btn-sm btnCierre', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}

@else

	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}

	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm btnNuevo', 'activo' => 'si' , 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}

	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Venta Rápida', array('class' => 'btn btn-secondary waves-effect waves-light m-l-10 btn-sm btnNuevo', 'onclick' => 'cargarRutaMenu("venta", "container", 16)')) !!}

	{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger waves-effect waves-light m-l-10 btn-sm btnCierre' , 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}

@endif
<input id="monto_apertura" name="monto_apertura" type="hidden" value="{{$montoapertura}}">
<input id="ingresos_efectivo" name="ingresos_efectivo" type="hidden" value="{{$ingresos_efectivo}}">
<input id="ingresos_visa" name="ingresos_visa" type="hidden" value="{{$ingresos_visa}}">
<input id="ingresos_master" name="ingresos_master" type="hidden" value="{{$ingresos_master}}">
<input id="ingresos_total" name="ingresos_total" type="hidden" value="{{$ingresos_total}}">
<input id="egresos" name="egresos" type="hidden" value="{{$egresos}}">
<input id="saldo" name="saldo" type="hidden" value="{{$saldo}}">

</div>
@if(count($lista) == 0)
<h3 class="text-warning" style="padding-top: 40px">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" style="font-size: 70%" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		
		@foreach ($lista as $key => $value)

		@if($value->estado == 1)
		<tr style ="background-color: #ffffff !important">
		@elseif($value->estado == 0)
		<tr style ="background-color: #ff9ea2 !important">
		@endif

			<td align="center">{{ $fechaformato = date("d/m/Y H:i:s",strtotime($value->fecha))}}</td>
			
			
			<?php
			$concepto = Concepto::find($value->concepto_id);
			?>
			
			<td>{{ $concepto->concepto}}</td>

			<?php
			$persona_persona = Persona::find($value->persona_id);
			$personamaestro_persona = Personamaestro::find($persona_persona->personamaestro_id);
			?>
			@if($personamaestro_persona->razonsocial == null)
				<td>{{ $personamaestro_persona->nombres . ' ' .$personamaestro_persona->apellidos }}</td>
			@else
				<td>{{ $personamaestro_persona->razonsocial }}</td>
			@endif

			<?php
			$venta = null;
			$personamaestro_trabajador = null;
			if($value->venta_id !=null){
				$venta = Movimiento::find($value->venta_id);
				$persona_trabajador = Persona::find($venta->trabajador_id);
				$personamaestro_trabajador = Personamaestro::find($persona_trabajador->personamaestro_id);
			}
			?>
			@if($personamaestro_trabajador != null)
				<td>{{ $personamaestro_trabajador->nombres . ' ' .$personamaestro_trabajador->apellidos }}</td>
			@else
				<td align="center"> - </td>
			@endif


			
			@if($concepto->tipo == 0)
			<td align="center" style="color:green;font-weight: bold;">{{ $value->total }}</td>
			<td align="center">0.00</td>
			@elseif($concepto->tipo == 1)
			<td align="center">0.00</td>
			<td align="center" style="color:red;font-weight: bold;">{{ $value->total }}</td>
			@endif
			
			<?php
			$venta = null;
			if($value->venta_id !=null){
				$venta = Movimiento::find($value->venta_id);
			}
			?>

			@if($venta != null)
				@if($venta->montoefectivo == null)
				<td>EFECTIVO = 0.00 / 
				@else
				<td>EFECTIVO = {{$venta->montoefectivo}} /
				@endif

				@if($venta->montovisa == null)
				VISA = 0.00 / 
				@else
				VISA = {{$venta->montovisa}} /
				@endif

				@if($venta->montomaster == null)
				MASTER = 0.00 </td>
				@else
				MASTER = {{$venta->montomaster}} </td>
				@endif
			@else
			<td>EFECTIVO = {{$value->total}}</td>
			@endif

			@if( $value->comentario == null )
			<td align="center"> - </td>
			@else
			<td>{{ $value->comentario }}</td>
			@endif

			<?php
				$usuario = User::find($value->usuario_id);
				$persona_usuario = Persona::find($usuario->persona_id);
				$personamaestro_usuario = Personamaestro::find($persona_usuario->personamaestro_id);
			?>

			<td>{{ $personamaestro_usuario->nombres . ' ' . $personamaestro_usuario->apellidos }}</td>

			<td align="center">
			@if($aperturaycierre == 1)	
				@if(in_array('9',$operacionesnombres)) 
					@if($value->estado == 1)
						@if($concepto->id == 1)
							-
						@elseif ($concepto->id == 2)
							-
						@else
							{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger btnEliminar' ,'activo' => 'si')) !!}
						@endif
					@elseif($value->estado == 0)
						{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-secondary btnEliminar' ,'disabled')) !!}
					@endif
				@else
				{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger btnEliminar' ,'activo' => 'no')) !!}
				@endif
			@elseif($aperturaycierre == 0)	
				-
			@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<table class="table-bordered table-striped table-condensed" align="center">
    <thead>
        <tr>
            <th class="text-center" colspan="2">RESUMEN DE CAJA</th>
        </tr>
    </thead>
    <tbody>
		<tr>
            <th>MONTO APERTURA :</th>
            <th class="text-right"><div id ="montoapertura"></div></th>
        </tr>
        <tr>
            <th>INGRESOS :</th>
            <th class="text-right"><div id ="ingresostotal"></div></th>
        </tr>
        <tr>
            <td>EFECTIVO :</td>
            <td align="right"><div id ="ingresosefectivo"></div></td>
        </tr>
        <tr>
            <td>VISA :</td>
            <td align="right"><div id ="ingresosvisa"></div></td>
        </tr>
		<tr>
            <td>MASTERCARD :</td>
            <td align="right"><div id ="ingresosmaster"></div></td>
        </tr>
        <tr>
            <th>EGRESOS :</th>
            <th class="text-right"><div id ="egreso"></div></th>
        </tr>
        <tr>
            <th>SALDO :</th>
            <th class="text-right"><div id ="saldoo"></div></th>
        </tr>
    </tbody>
</table>

@endif
<script>
	var ingresos_total = {{$ingresos_total}};
	var ingresos_efectivo = {{$ingresos_efectivo}};
	var ingresos_visa = {{$ingresos_visa}};
	var ingresos_master = {{$ingresos_master}};
	var egresos = {{$egresos}};
	var saldo = {{$saldo}};
	var montoapertura = {{$montoapertura}};
	
	$(document).ready(function () {

		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}

		$('#ingresostotal').html(ingresos_total.toFixed(2));
		$('#ingresosefectivo').html(ingresos_efectivo.toFixed(2));
		$('#ingresosvisa').html(ingresos_visa.toFixed(2));
		$('#ingresosmaster').html(ingresos_master.toFixed(2));
		$('#egreso').html(egresos.toFixed(2));
		$('#saldoo').html(saldo.toFixed(2));
		$('#montoapertura').html(montoapertura.toFixed(2));
	});

</script>
