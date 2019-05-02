@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ $value->serie_doc.'-'.$value->numero_doc }}</td>
			
			<td>{{ $value->cliente_id == null?"":$value->cliente->nombres." ".$value->cliente->apellidos }}</td>
			<td>{{ $value->total }}</td>
			<td>{{ $value->sucursal->nombre }}</td>
			<td>{{ $value->comprobante == 'B'?'Boleta':'Factura' }}</td>
			<td>{{ $value->forma_pago == 'T'?'Tarjeta':'Efectivo' }}</td>
			{{-- <td>{{ $value->estado == 'P'?'Pendiente':($value->estado == 'C'?'Cancelado':'Anulado') }}</td> --}}
			<td>{{ Date::parse( $value->fecha )->format('d/m/Y H:i:s') }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-print"></div> Ver', array('onclick' => 'modal (\''.URL::route($ruta["verdetalle_v"], array($value->id)).'\', \''.'Detalle de Venta'.'\', this);', 'class' => 'btn btn-xs btn-info')) !!}</td>
			@if($value->estado == 'P')
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
			@else
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('class' => 'btn btn-xs btn-danger','disabled'=>true)) !!}</td>
			@endif
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif