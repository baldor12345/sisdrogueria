
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
			<td style="font-size: 13px" align="center">{{  number_format($value->egreso,2)   }}</td>
			<td style="font-size: 13px" align="center">{{  $value->forma_pago   }}</td>
			@if($value->cliente_id != '')
			<td style="font-size: 13px">{{  $value->apellidos.'  '.$value->nombres   }}</td>
			@else
			<td style="font-size: 13px">--</td>	
			@endif
			<td style="font-size: 13px">{{ $value->comentario }}</td>	
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_cerrarCaja.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif