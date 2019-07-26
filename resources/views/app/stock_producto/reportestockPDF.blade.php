
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
            <td align="center" style="font-size: 13px" colspan="9"><br> OSTEOMEDIC </td>
            <td rowspan="3" colspan="2" align="right" ><img src="" width="140" height="100" /></td>
        </tr>
        <tr>
            <td style="font-size: 10px" colspan="9" rowspan="" align="center"><strong>{{ $titulo }}</strong></td>
        </tr>   
        <tr>
            <td cellspacing="9" cellpadding="2" colspan="2"><strong>{{ '     ' }}FECHA Y HORA:</strong> </td>
            <td style="font-size: 10px" colspan="5" cellpadding="2">{{ Date::parse( $fecha )->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td cellspacing="9" cellpadding="2" colspan="2"><strong>{{ '     ' }}USUARIO:</strong></td>
            <td style="font-size: 10px" colspan="5" cellpadding="2">{{ $usuario[0]->apellidos.' '.$usuario[0]->nombres }}<strong>   {{ '  DNI: '}} </strong>{{ $usuario[0]->dni}}</td>
        </tr>
        <tr>
            <td cellspacing="9" cellpadding="2" colspan="2"><strong>{{ '     ' }}SUCURSAL:</strong> </td>
            <td style="font-size: 10px" colspan="5" cellpadding="2">{{ $sucursal[0]->nombre }}<strong>   {{ '  DIRECCION: '}} </strong>{{ $sucursal[0]->direccion }}</td>
        </tr>
        
    </table>
    <br>
    <br>
	<table width="100%" class="tabla3">
            <thead>
				<tr>
					<td width="5%" align="center" class="fondo"><strong>#</strong></td>
					<td width="40%" align="center" class="fondo"><strong>PRODUCTO</strong></td>
					<td width="20%" align="center" class="fondo"><strong>PRESENTACION</strong></td>
					<td width="34%" align="center" class="fondo"><strong>STOCK</strong></td>
					<!-- <td width="6%" align="center" class="fondo"><strong>STOCK</strong></td> -->
				</tr>
			</thead>
            <tbody>
                <?php
                $contador = $inicio + 1;
                ?>
                @foreach ($lista as $key => $value)
                <tr>
                    <td width="5%" align="center" class="fondo" rowspan="{{ count($listPresentaciones[$value->producto_id]) }}">{{ $contador }}</td>
                    <td width="40%" align="left" rowspan="{{ count($listPresentaciones[$value->producto_id]) }}">{{ $value->producto }}</td>
                    <?php
                    $ind = 0;
                    $sum_unidades = 0;
                    foreach ($listPresentaciones[$value->producto_id] as $key => $prod_present) {
                        $cant_prest = $prod_present->cant_unidad_x_presentacion;
                        $valor = intval($value->stock/$cant_prest);
                    ?>
                        @if($ind == 0)
                            <td width="20%" align="left" >{{ $prod_present->presentacion->nombre}}</td>
                            <td width="34%" align="left" >{{ $valor." ".$prod_present->presentacion->sigla.'(s)'.($cant_prest > 1?" + ".($value->stock - $valor * $cant_prest).' unds': "" )}}</td>
                            <!-- @if($value->stock < $value->stock_minimo)
                            <td width="6%" align="left" rowspan="{{ count($listPresentaciones[$value->producto_id]) }}"><div style="background: white"></div></td>
                            @else
                            <td width="6%" align="left" rowspan="{{ count($listPresentaciones[$value->producto_id]) }}"><div></div></td>
                            @endif -->
                        </tr>
                        @else
                        <tr>
                            <td width="20%" align="left">{{ $prod_present->presentacion->nombre}}</td>
                            <td width="34%" align="left" >{{ $valor." ".$prod_present->presentacion->sigla.'(s)'.($cant_prest > 1?" + ".($value->stock - $valor * $cant_prest).' unds': "" )}}</td>
                            
                            {{-- <td>{{ $valor." ".($cant_prest > 1?"+ ".($value->stock - $valor * $cant_prest): "" )}}</td> --}}

                        </tr>
                        
                        @endif

                    
                    <?php
                    $ind++;
                    }
                    ?>
                
                
                <?php
                $contador = $contador + 1;
                ?>
                @endforeach
			</tbody>
    </table>
</body>
</html>