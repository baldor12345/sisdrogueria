<?php
use App\Serieventa;
use App\Persona;
?>
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
			<td>{{ $value->razonsocial ? $value->razonsocial : $value->nombres.' '.$value->apellidos }}</td>
			<td>{{ $value->comision_acum }}</td>
			<?php
				$persona = Persona::find($value->id);
			?>
			<td>{!! Form::button('<div class="glyphicon glyphicon-usd"></div> Pagar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($persona->id, 'listar'=>'SI')).'\', \''.$titulo_pagar.'\', this);', 'class' => 'btn btn-xs btn-success')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif