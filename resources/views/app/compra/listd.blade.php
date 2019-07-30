@if(count($lista) == 0)
	<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
        <tr>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="4%">#</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="36%">Producto</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Presentacion</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">F. Venc.</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Cantidad</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Precio</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Subtotal</th>
        <th bgcolor="#E0ECF8" class="text-center input-sm" width="5%">Subtotal</th>
        </tr>
    </thead>
	<tbody>
		<?php
			$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
            <td class="text-left input-xs">{{ $contador}}</td>
            <td class="text-left input-xs">{{ $value->descripcion.' '.$value->sustancia_activa }}</td>
            <td class="text-center input-xs">{{ $value->presentacion_nombre }}</td>
            <td class="text-center input-xs">{{ $value->fecha_caducidad_string }}</td>
            <td class="text-center input-xs">{{ $value->cantidad }}</td>
            <td class="text-center input-xs">{{ $value->precio_compra }}</td>
            <td class="text-center input-xs">{{ number_format($value->precio_compra*$value->cantidad,2) }}</td>
            <td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
        </tr>
		<?php
			$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
@endif