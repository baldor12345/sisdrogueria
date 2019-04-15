
<style>
a.disabled {
   pointer-events: none;
   cursor: default;
}
</style>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th style="font-size: 14px" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td style="font-size: 14px">{{ $contador }}</td>
			<td style="font-size: 14px">{{  $value->num_caja   }}</td>
			<td style="font-size: 14px">{{ $value->sucursal->nombre }}</td>	
			<td style="font-size: 14px">{{  Date::parse( $value->fecha_horaapert )->format('d/m/Y')}}</td>	
			<td style="font-size: 14px">{{  Date::parse( $value->fecha_horacierre )->format('d/m/Y') }}</td>
			<td style="font-size: 14px">{{ $value->monto_iniciado }}</td>
			<td style="font-size: 14px">{{ $value->monto_cierre }}</td>
			<td style="font-size: 14px">{{ $value->descripcion }}</td>
			{{-- <td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div> Cierre', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-secondary','disabled' )) !!}</td> --}}
			{{-- <td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div> Cierre', array('onclick' => '', 'class' => 'btn btn-xs btn-secondary','disabled' )) !!}</td> --}}
			
			<?php if($caja_last->id == $value->id){ if($value->estado == 'C'){ ?>
				<td>{!! Form::button('<div class="glyphicon glyphicon-refresh"></div> Reaperturar', array('onclick' => 'modal (\''.URL::route($ruta["cargarreapertura"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_reapertura.'\', this);','class' => 'btn btn-xs btn-warning')) !!}</td>
				
			<?php }else if($value->estado == 'A'){ ?>
				<td>{!! Form::button('<div class="glyphicon glyphicon-refresh"></div> Reaperturar', array('onclick' => 'modal (\''.URL::route($ruta["cargarreapertura"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_reapertura.'\', this);','class' => 'btn btn-xs btn-warning','disabled')) !!}</td>
			<?php } }else if($caja_last->id != $value->id){?>
				<td>{!! Form::button('<div class="glyphicon glyphicon-refresh"></div> Reaperturar', array('onclick' => 'modal (\''.URL::route($ruta["cargarreapertura"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_reapertura.'\', this);','class' => 'btn btn-xs btn-warning','disabled')) !!}</td>
			<?php }?>

			@if ($value->estado === 'C')
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div> Cierre', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_cerrarCaja.'\', this);', 'class' => 'btn btn-xs btn-secondary','disabled' )) !!}</td>
			@else
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove-circle"></div> Cierre', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_cerrarCaja.'\', this);', 'class' => 'btn btn-xs btn-secondary')) !!}</td>
			@endif
			
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif