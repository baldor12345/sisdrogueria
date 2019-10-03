
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
	table{
        border-collapse: collapse;
    }
    td{
        font-size: 10px;
    }
    h1{
        font-size: 21px;
        text-align:center;
        font-weight: bold;
    }
    .tabla2 {
        margin-bottom: 10px;
    }

    .tabla3 td{
        border: 0.9px solid #000;
        text-align : left;;
    }
    .emisor{
        color: red;
    }
    .linea{
        border-bottom: 1px dotted #000;
    }
    .border{
        border: 1px solid #000;
    }
    .fondo{
        background-color: #dfdfdf;
    }
    .fisico{
        color: #fff;
    }
    .fisico td{
        color: #fff;
    }
    .fisico .border{
        border: 1px solid #fff;
    }
    .fisico .tabla3 td{
        border: 1px solid #fff;
    }
    .fisico .linea{
        border-bottom: 1px dotted #fff;
    }
</style>

</head>
<body>
    <table width="100%" border="0px" class="">
        <tr>
            <td align="center" style="font-size: 13px" colspan="2"   width="100%"><br> OSTEOMEDIC </td>
        </tr>
        
        <tr>
            <td style="font-size: 10px" colspan="2"  align="center"><strong>{{ $titulo }}</strong></td>
        </tr>  
        <tr>
            <td colspan="2"  width="100%"><strong></strong></td>
        </tr>  
        <tr>
            <td cellspacing="9" cellpadding="2" width="20%"><strong>FECHA Y HORA: </strong> </td>
            <td style="font-size: 10px" cellpadding="2" width="80%">{{ Date::parse( $fecha )->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td cellspacing="9" cellpadding="2" colspan="2" width="100%"><strong>Mes: {{ $meses[$mes]." - ".$anio }}</strong> </td>
        </tr>
        
    </table>
    <br>
    <br>
	
    <?php
    for ($i = 0; $i < count($resultado); $i++) {
    ?>
        <br>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <td width="10%"><strong>Médico: </strong></td>
                    <td width="90%"><strong>{{$resultado[$i][0]->codigo." - ".$resultado[$i][0]->nombres." ".$resultado[$i][0]->apellidos}}</strong></td>
                </tr>
            </thead>
        </table>
        
        
        <table width="100%" class="tabla3">
            <thead>
                <tr>
                    <td width="10%" align="center" class="fondo"><strong>Item</strong></td>
                    <td width="10%" align="center" class="fondo"><strong>Cod.</strong></td>
                    <td width="50%" align="center" class="fondo"><strong>Descripción</strong></td>
                    <td width="10%" align="center" class="fondo"><strong>Puntos</strong></td>
                    <td width="10%" align="center" class="fondo"><strong>Cantidad</strong></td>
                    <td width="10%" align="center" class="fondo"><strong>Total</strong></td>
                    <!-- <td width="6%" align="center" class="fondo"><strong>STOCK</strong></td> -->
                </tr>
            </thead>
            <tbody>
            <?php
                $item = 1;
                $cont_puntos = 0;
            ?>
                @foreach ($resultado[$i][1] as $key => $value)
                <tr>
                    <td width="10%" align="center" ><strong>{{ $item}}</strong></td>
                    <td width="10%" align="center" ><strong> {{$value->codigo}} </strong></td>
                    <td width="50%" align="center" ><strong>{{$value->descripcion." ".$value->nombre_presentacion." x ".$value->cantidad_x  }}</strong></td>
                    <td width="10%" align="center" ><strong>{{$value->puntos}}</strong></td>
                    <td width="10%" align="center" ><strong>{{$value->cantidad_unidades}}</strong></td>
                    <td width="10%" align="center" ><strong>{{$value->puntos_acumulados}}</strong></td>
                </tr>
                <?php
                $cont_puntos += $value->puntos_acumulados;
                    $item ++;
                ?>
                @endforeach
            </tbody>
         </table>
         <table width="100%">
            <thead>
                <tr>
                    <td width="65%"><strong> </strong></td>
                    <td width="25%"><strong>Total Puntos Acumulados:  </strong></td>
                    <td width="10%"><strong>{{$cont_puntos}}</strong></td>
                </tr>
            </thead>
        </table>
    <?php
    }
?>
               
			
</body>
</html>