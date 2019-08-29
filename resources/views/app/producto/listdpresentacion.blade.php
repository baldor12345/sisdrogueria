@if(count($listdet_) == 0)
	<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
    <thead>
        <tr>
            <th bgcolor="#E0ECF8" class="text-center input-sm" width="3%">#</th>
            <th bgcolor="#E0ECF8" class="text-center input-sm" width="37%">Presentacion</th>
            <th bgcolor="#E0ECF8" class="text-center input-sm" width="20%">P. Compra</th>
            <th bgcolor="#E0ECF8" class="text-center input-sm" width="12%">Cant. Uds</th>
            <th bgcolor="#E0ECF8" class="text-center input-sm" width="12%">P. Venta</th>
            <th bgcolor="#E0ECF8" class="text-center input-sm" width="11%">Puntos</th>
            <th bgcolor="#E0ECF8" colspan="2" class="text-center input-sm" width="5%">Elim</th>                            
        </tr>
    </thead>
    @if($listdet_=='')
    @else
    <tbody id="dat_comp">
        <?php $cont=0;?>
        @foreach($listdet_ as $key => $value)
            <?php $cont++; ?>
            <tr>
                <td class="input-sm" align="center"><?php echo $cont; ?></td>
                <td class="input-sm" align="center">{{ $value->presentacion_nombre }} </td>
                <td class="input-sm" align="center">{{ $value->precio_compra }} </td>
                <td  class="input-sm" align="center">{{ $value->cant_unidad_x_presentacion }} </td>
                <td  class="input-sm" align="center">{{ $value->precio_venta_unitario }}</td>
                <td  class="input-sm" align="center">{{ $value->puntos }}</td>
                <td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> ',array('onclick'=>'modal(\''.URL::route($ruta["editarpresentacion"],array($value->propresent_id,'listar'=>'SI')).'\',\''.$titulo_editar.'\',this);', 'class'=>'btn btn-xs btn-warning')) !!}</td>
                <td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> ', array('onclick'=>'modal(\''.URL::route($ruta["deletepresentacion"], array($value->propresent_id, 'SI')).'\',\''.$titulo_eliminar.'\',this);','class'=>'btn btn-xs btn-danger')) !!}</td>
            </tr>
        @endforeach
    </tbody>
    @endif
</table>
@endif