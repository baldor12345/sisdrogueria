
<style>
a.disabled {
   pointer-events: none;
   cursor: default;
}
</style>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style="font-size: 14px" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td style="font-size: 13px">{{ $contador }}</td>
			<td style="font-size: 13px"  align="center">{{  $value->num_caja   }}</td>
			<td style="font-size: 13px"  align="center">{{  $value->numero_operacion   }}</td>
			<td style="font-size: 13px" >{{  $value->user_login   }}</td>
			<td style="font-size: 13px" align="center">{{  Date::parse( $value->fecha )->format('d/m/Y')  }}</td>
			<td style="font-size: 13px" align="center">{{  $value->concepto_nombre   }}</td>
			<td style="font-size: 13px; color:green; font-weight: bold;" align="center">{{  number_format($value->ingreso,2) }}</td>
			<td style="font-size: 13px; color:red; font-weight: bold;" align="center">{{  number_format($value->egreso,2)   }}</td>
			@if($value->forma_pago == 'CO')
			<td style="font-size: 13px">Contado</td>
			@elseif($value->forma_pago == 'CR')
			<td style="font-size: 13px">Credito</td>
			@else
			<td style="font-size: 13px">--</td>	
			@endif
			@if($value->cliente_id != '')
			<td style="font-size: 13px">{{  $value->cliente_apellidos.'  '.$value->cliente_nombres   }}</td>
			@elseif($value->personal_id != '')
			<td style="font-size: 13px">{{  $value->personal_apellidos.'  '.$value->personal_nombres   }}</td>
			@else
			<td style="font-size: 13px">--</td>
			@endif
			<td style="font-size: 13px">{{ $value->comentario }}</td>	
			<td>{!! Form::button('<div class="glyphicon glyphicon-print"></div>', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_cerrarCaja.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-usd"></div>', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_cerrarCaja.'\', this);', 'class' => 'btn btn-xs btn-success')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div>', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_cerrarCaja.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>

<table class="table-bordered table-striped table-condensed" align="center">
	<thead>
		<tr>
			<th class="text-center" style="font-size: 13px" colspan="2">Resumen de Caja</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th style="font-size: 13px">Ingresos :</th>
			<th class="text-right" style="font-size: 13px">{{ number_format($ingresos,2) }}</th>
		</tr>
		<tr>
			<th style="font-size: 13px">Egresos :</th>
			<th class="text-right" style="font-size: 13px">{{ number_format($egresos,2) }}</th>
		</tr>
		<tr>
			<th style="font-size: 13px">Saldo :</th>
			<th class="text-right" style="font-size: 13px">{{ number_format(($ingresos-$egresos),2) }}</th>
		</tr>
	</tbody>
</table>
@endif