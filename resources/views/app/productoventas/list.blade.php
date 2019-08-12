@if(count($lista) == 0)
<?php
		
		?>
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<div class="table_responsive">
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
				<td>{{ $value->descripcion.' X '.$value->cantidad_x }}</td>
				<td>{{ $value->cantidad_unidades .' '.$value->sigla.'(S)'}}</td>
			</tr>
			<?php
			$contador = $contador + 1;
			?>
			@endforeach
		</tbody>
	</table>
</div>

@endif