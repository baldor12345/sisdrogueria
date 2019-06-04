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
			<td>{{ $value->producto }}</td>
			<td>{{ $value->presentacion}}</td>
			<td>{{ $value->stock }}</td>
			@if($value->stock < $value->stock_minimo)
			<td>{!! Form::button('<div class=""></div>===', array('class' => 'btn btn-xs btn-danger')) !!}</td>
			@else
			<td>{!! Form::button('<div class=""></div>===', array('class' => 'btn btn-xs btn-success')) !!}</td>
			@endif
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif