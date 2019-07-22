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
			<td rowspan="{{ count($listPresentaciones[$value->producto_id]) }}">{{ $contador }}</td>
			<td rowspan="{{ count($listPresentaciones[$value->producto_id]) }}">{{ $value->producto }}</td>
			<?php
			$ind = 0;
			$sum_unidades = 0;
			foreach ($listPresentaciones[$value->producto_id] as $key => $prod_present) {
				$cant_prest = $prod_present->cant_unidad_x_presentacion;
				$valor = intval($value->stock/$cant_prest);
			?>
				@if($ind == 0)
					<td>{{ $prod_present->presentacion->nombre}}</td>
					<td>{{ $valor." ".$prod_present->presentacion->sigla.'(s)'.($cant_prest > 1?" + ".($value->stock - $valor * $cant_prest).' unds': "" )}}</td>
					@if($value->stock < $value->stock_minimo)
					<td rowspan="{{ count($listPresentaciones[$value->producto_id]) }}">{!! Form::button('<div class=""></div>===', array('class' => 'btn btn-xs btn-danger')) !!}</td>
					@else
					<td rowspan="{{ count($listPresentaciones[$value->producto_id]) }}">{!! Form::button('<div class=""></div>===', array('class' => 'btn btn-xs btn-success')) !!}</td>
					@endif
				</tr>
				@else
				<tr>
					<td>{{ $prod_present->presentacion->nombre}}</td>
					<td>{{ $valor." ".$prod_present->presentacion->sigla.'(s)'.($cant_prest > 1?" + ".($value->stock - $valor * $cant_prest).' unds': "" )}}</td>
					
					{{-- <td>{{ $valor." ".($cant_prest > 1?"+ ".($value->stock - $valor * $cant_prest): "" )}}</td> --}}

				</tr>
				
				@endif

			
			<?php
			$ind++;
			}
			?>
		
		
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif