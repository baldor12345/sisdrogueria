@if(count($lista) == 0)
	<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
        <tr>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="4%">#</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="35%">Producto</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="16%">Presentacion</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="15%">F. Venc.</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">Cantidad</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">P. Compra</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">P. Venta</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="10%">Lote</th>
            <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">Oper</th>
        </tr>
    </thead>
	<tbody>
		<?php
			$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
            <td class="text-left input-xs">{{ $contador}}</td>
            <td class="text-left input-xs">{{ $value->producto.' '.$value->sustancia_activa }}</td>
            <td class="text-center input-xs">{{ $value->presentacion }}</td>
            <td class="text-center input-xs">{{ $value->fecha_caducidad_string }}</td>
            <td class="text-center input-xs">{{ intval($value->cantidad/$value->cantidad_unidad_x_presentacion) }}</td>
            <td class="text-center input-xs">{{ $value->precio_compra }}</td>
            <td class="text-center input-xs">{{ $value->precio_venta }}</td>
            <td class="text-center input-xs">{{ $value->lote }}</td>
            <td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
        </tr>
		<?php
			$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif