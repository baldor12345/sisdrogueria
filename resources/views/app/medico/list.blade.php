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
		/*COMO VA A TRAER LOS CLIENTES SON SUBTYPE S VALIDAMOS QUE SOLO SEAN PERSONAS */
		?>
		@foreach ($lista as $key => $value)
		<?php
			$nombreCompleto = $value->apellidos." ".$value->nombres;
		?>
			<tr>
				<td>{{ $contador }}</td>
				<td>{{ $value->codigo ==null?" - " :$value->codigo }}</td>
				<td>{{ $nombreCompleto}}</td>
				<td>{{ $value->telefono == null? " - ": $value->telefono}}</td>
				<td>{{ $value->direccion == null? " - ": $value->direccion}}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.': '.$nombreCompleto.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_eliminar.': '.$nombreCompleto.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
			</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table
@endif