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
			
			<td>{{ $value->cliente->dni == null?$value->cliente->razon_social."":$value->cliente->nombres." ".$value->cliente->apellidos }}</td>
			<td>{{ round($value->total, 2) }}</td>
			{{-- <td>{{ $value->sucursal->nombre }}</td> --}}
			<td>{{ $value->comprobante == 'B'?'Boleta':'Factura' }}</td>
			<td>{{ $value->forma_pago == 'T'?'Tarjeta':'Efectivo' }}</td>
			@if($value->tipo_pago == 'CR')
				@php
					$fecha_init= date("Y-m-d");
					$fecha_inicial = new DateTime($fecha_init);

					$fecha_fin= date("Y-m-d", strtotime(date("Y-m-d",strtotime($value->fecha."+ ".$value->dias." day"))));
					$fecha_final = new DateTime($fecha_fin);

					$diferencia = $fecha_inicial->diff($fecha_final);
					$numeroDias = $diferencia->format('%R%a días');
				@endphp
				<td>{{ $numeroDias." " }}</td>
			@endif
			
			{{-- <td>{{ $value->estado == 'P'?'Pendiente':($value->estado == 'C'?'Cancelado':'Anulado') }}</td> --}}
			<td>{{ Date::parse( $value->fecha )->format('d/m/Y H:i:s') }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div> Ver', array('onclick' => 'modal (\''.URL::route($ruta["verdetalle_v"], array($value->id)).'\', \''.'Detalle de Venta'.'\', this);', 'class' => 'btn btn-xs btn-info')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-print"></div> PDF', array('onclick' => 'window.open(\'http://localhost/clifacturacion/controlador/contComprobante.php?funcion=generarPDF&numero='.$value->serie_doc.'-'.$value->numero_doc.'\',\'_blank\');', 'class' => 'btn btn-xs btn-info')) !!}</td>
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