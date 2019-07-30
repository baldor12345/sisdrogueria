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
			<td>{{ Date::parse( $value->fecha )->format('d-m-Y') }}</td>
			<td>{{ $value->serie_documento.'-'.$value->num_documento }}</td>
			@if($value->tipo == 'E')
			<td>Entrada</td>
			@else
			<td>Salida</td>
			@endif
			<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div> Ver lista', array('onclick' => 'modal (\''.URL::route($ruta["verdetalle"], array($value->es_id, 'listar'=>'SI')).'\', \''.$titulo_ver.'\', this);', 'class' => 'btn btn-xs btn-info')) !!}</td>
			<!-- <td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->es_id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td> -->
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif