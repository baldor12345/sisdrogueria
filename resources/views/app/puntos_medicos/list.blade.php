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
	
		$contador = 1;
		?>
		@foreach ($lista as $key => $value)
		
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ $value->codigo_medico }}</td>
			<td>{{ $value->apellidos_medico.' '.$value->nombres_medico }}</td>
			<td>{{ $value->puntos }}</td>
			{{-- <td>{{ $value->puntos }}</td>
			<td>{{ $value->puntos }}</td> --}}
		</tr>
		
		
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif