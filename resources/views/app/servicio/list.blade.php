<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;

$user = Auth::user();
/*
SELECT operacion_menu.operacion_id
FROM  operacion_menu 
inner join permiso_operacion
on permiso_operacion.operacionmenu_id = operacion_menu.id
where po.usertype_id = 4 and om.menuoption_id = 9
*/
/*$operaciones = DB::table('operacion_menu')
					->join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.operacion_id')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', 6 ],
					])->get();*/
$opcionmenu = Menuoption::where('link','=',$entidad)->orderBy('id','ASC')->first();
$operaciones = OperacionMenu::join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.*')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', $opcionmenu->id ],
					])->get();					
$operacionesnombres = array();
foreach($operaciones as $key => $value){
	$operacionesnombres[] = $value->operacion_id;
}
/*
operaciones 
1 nuevo
2 editar
3 eliminar
*/
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
			<td>{{ $value->descripcion }}</td>
			<td>{{ $value->precio }}</td>
			@if($value->tipo_comision == 0)
			<td>PORCENTAJE ( % )</td>
			@elseif($value->tipo_comision == 1)
			<td>MONTO ( S/. )</td>
			@endif

			@if($value->tipo_comision == 0)
			<td>{{ intval($value->comision) }}</td>
			@elseif($value->tipo_comision == 1)
			<td>{{ $value->comision }}</td>
			@endif

			@if($value->frecuente == 0)
			<td align="center">&#10008;</td>
			@elseif($value->frecuente == 1)
			<td align="center">&#10004;</td>
			@endif

			@if($value->editable == 0)
			<td align="center">&#10008;</td>
			@elseif($value->editable == 1)
			<td align="center">&#10004;</td>
			@endif

			<td>
			@if(in_array('2',$operacionesnombres))
			{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning btnEditar' ,'activo' => 'si')) !!}
			@else
			{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning btnEditar' , 'activo' => 'no')) !!}
			@endif
			</td>
			<td>
			@if(in_array('3',$operacionesnombres)) 
			{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger btnEliminar' ,'activo' => 'si')) !!}
			@else
			{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger btnEliminar' ,'activo' => 'no')) !!}
			@endif
			</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif
<script>
	$(document).ready(function () {
		if($(".btnEditar").attr('activo')=== 'no'){
			$('.btnEditar').attr("disabled", true);
		}
		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}
	});
</script>
