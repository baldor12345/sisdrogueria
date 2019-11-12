@if(count($res) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}

<?php
    for ($i = 0; $i < count($res); $i++) {
    ?>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <td style="background-color: #bae9ff;"><strong>Médico: </strong></td>
                    <td style="background-color: #bae9ff;"><strong>{{$res[$i][0]->codigo." - ".$res[$i][0]->nombres." ".$res[$i][0]->apellidos}}</strong></td>
                </tr>
            </thead>
        </table>
        
        <table class="table table-bordered table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <td><strong>Item</strong></td>
                    <td><strong>Cod.</strong></td>
                    <td><strong>Descripción</strong></td>
                    <td><strong>Puntos</strong></td>
                    <td><strong>Cantidad</strong></td>
                    <td><strong>Total</strong></td>
                    <!-- <td width="6%" align="center" class="fondo"><strong>STOCK</strong></td> -->
                </tr>
            </thead>
            <tbody>
            <?php
                $item = 1;
                $cont_puntos = 0;
            ?>
                @foreach ($res[$i][1] as $key => $value)
                <tr>
                    <td><strong>{{ $item}}</strong></td>
                    <td><strong> {{$value->codigo}} </strong></td>
                    <td><strong>{{$value->descripcion." ".$value->nombre_presentacion." x ".$value->cantidad_x  }}</strong></td>
                    <td><strong>{{$value->puntos}}</strong></td>
                    <td><strong>{{$value->cantidad_unidades}}</strong></td>
                    <td><strong>{{$value->puntos*$value->cantidad_unidades}}</strong></td>
                </tr>
                <?php
                $cont_puntos += $value->puntos*$value->cantidad_unidades;
                    $item ++;
                ?>
                @endforeach
            </tbody>
         </table>
         <table class="table table-bordered table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <td><strong> </strong></td>
                    <td><strong>Total Puntos Acumulados:  </strong></td>
                    <td><strong>{{$cont_puntos}}</strong></td>
                </tr>
            </thead>
        </table>
    <?php
    }
?>

@endif