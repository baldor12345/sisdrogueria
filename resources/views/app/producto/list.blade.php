
@if(count($lista)==0)
<h3 class="text-warning">No se encontraron resultados</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
			<th	@if((int)$value['numero'] > 1) colspan = "{{ $value['numero'] }}" @endif> {!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
			$contador = $inicio+1;
		?>
		@foreach($lista as $key => $value)
			<tr>
				<td>{{ $contador }}</td>
				<td>{{ $value->codigo_barra }} </td>
				<td>{{ $value->descripcion }} </td>
				<td>{{ $value->producto }} </td>
				<td>{{ $value->laboratorio }}</td>
				<td>{{ $value->categoria }}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Edit.',array('onclick'=>'modal(\''.URL::route($ruta["edit"],array($value->producto_id,'listar'=>'SI')).'\',\''.$titulo_modificar.'\',this);', 'class'=>'btn btn-xs btn-warning')) !!}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Present.',array('onclick'=>'modal(\''.URL::route($ruta["presentacion"],array($value->producto_id,'listar'=>'SI')).'\',\''.$titulo_presentacion.'\',this);', 'class'=>'btn btn-xs btn-info')) !!}</td>
				<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Elim.', array('onclick'=>'modal(\''.URL::route($ruta["delete"], array($value->producto_id, 'SI')).'\',\''.$titulo_eliminar.'\',this);','class'=>'btn btn-xs btn-danger')) !!}</td>
				<?php
				$contador = $contador+1;
				?>
			</tr>
		@endforeach
	</tbody>
</table>
@endif