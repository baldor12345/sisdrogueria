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
			
			<td>{{ $value->dni == null?$value->razon_social."":$value->nombres." ".$value->apellidos }}</td>
			<td>{{ round($value->total, 2) }}</td>
			{{-- <td>{{ $value->sucursal->nombre }}</td> --}}
			<td>{{ $value->comprobante == 'B'?'Boleta':'Factura' }}</td>
			
			<td>{{ Date::parse( $value->fecha )->format('d/m/Y H:i:s') }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div> Ver Detalle', array('onclick' => 'modal (\''.URL::route($ruta["verdetalle_v"], array($value->id)).'\', \''.'Detalle de Venta'.'\', this);', 'class' => 'btn btn-xs btn-info')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif