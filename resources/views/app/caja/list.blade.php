
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
			<td style="font-size: 14px">{{ $contador }}</td>
			<td style="font-size: 14px">{{ Date::parse( $value->fecha )->format('Y-m-d')  }}</td>
			<td style="font-size: 14px">{{ $value->num_caja }}</td>	
			<td style="font-size: 14px">{{ $value->concepto_nombre }}</td>	
			<td style="font-size: 14px">{{ $value->cliente_apellidos.' '.$value->cliente_nombres }}</td>
			<td style="font-size: 14px">{{ $value->ingreso }}</td>
			<td style="font-size: 14px">{{ $value->egreso }}</td>

			@if ($value->ingreso !== null)
			<td style="font-size: 14px">{{ $value->ingreso  }}</td>
			@else
			<td id="cerrado" style="font-size: 14px" >-</td>
			@endif
			@if ($value->egreso !== null)
			<td style="font-size: 14px">{{ $value->egreso  }}</td>
			@else
			<td id="cerrado" style="font-size: 14px" >-</td>
			@endif
			<td style="font-size: 14px">{{ $value->forma_pago }}</td>
			<td style="font-size: 14px">{{ $value->comentario }}</td>
			<td style="font-size: 14px">{{ $value->user_login }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div> Cierre', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-secondary','disabled' )) !!}</td>
			
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif