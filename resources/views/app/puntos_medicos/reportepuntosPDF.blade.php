
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
            <td style="font-size: 10px" colspan="5" cellpadding="2">{{ Date::parse( $fecha )->format('d/m/Y H:i') }}</td>
        </tr>

        <tr>
            {{-- <td cellspacing="9" cellpadding="2" colspan="2"><strong>{{ '     ' }}USUARIO:</strong></td>
            <td style="font-size: 10px" colspan="5" cellpadding="2">{{ $usuario->apellidos.' '.$usuario->nombres }}<strong>   {{ '  DNI: '}} </strong>{{ $usuario->dni}}</td>
        --}}
            <td cellspacing="9" cellpadding="1" colspan="2"><strong>{{ '     ' }}USUARIO:</strong> </td>
            <td style="font-size: 10px" colspan="2" cellpadding="2">{{  $usuario->apellidos.' '.$usuario->nombres  }}</td>
            <td cellspacing="9" cellpadding="1" colspan="2"><strong>{{ '     ' }}DNI:</strong> </td>
            <td style="font-size: 10px" colspan="2" cellpadding="2">{{  $usuario->dni }}</td>
        </tr>

        <tr>
            {{-- <td cellspacing="9" cellpadding="2" colspan="2"><strong>{{ '     ' }}SUCURSAL:</strong> </td>
            <td style="font-size: 10px" colspan="5" cellpadding="2">{{ $sucursal->nombre }}<strong>   {{ '  DIRECCION: '}} </strong>{{ $sucursal->direccion }}</td>
    --}}
            <td cellspacing="9" cellpadding="1" colspan="2"><strong>{{ '     ' }}SUCURSAL:</strong> </td>
            <td style="font-size: 10px" colspan="2" cellpadding="2">{{ $sucursal->nombre }}</td>
            <td cellspacing="9" cellpadding="1" colspan="2"><strong>{{ '     ' }}DIRECCION:</strong> </td>
            <td style="font-size: 10px" colspan="2" cellpadding="2">{{  $sucursal->direccion }}</td>
        </tr>

        <tr>
            <td cellspacing="9" cellpadding="2" colspan="2"><strong>{{ '     ' }}FECHA INICIO:</strong> </td>
            <td style="font-size: 10px" colspan="2" cellpadding="2">{{ Date::parse($fecha_inicio)->format('d/m/Y') }}</td>
            <td cellspacing="9" cellpadding="1" colspan="2"><strong>{{ '     ' }}FECHA FINAL:</strong> </td>
            <td style="font-size: 10px" colspan="2" cellpadding="2">{{ Date::parse($fecha_fin)->format('d/m/Y') }}</td>
        </tr>
        
    </table>
    <br>
    <br>
	<table width="100%" class="tabla3">
            <thead>
				<tr>
					<td width="5%" align="center" class="fondo"><strong>#</strong></td>
					<td width="20%" align="center" class="fondo"><strong>COD. MEDICO</strong></td>
					<td width="40%" align="center" class="fondo"><strong>APELLIDOS Y NOMBRES</strong></td>
					<td width="34%" align="center" class="fondo"><strong>PUNTOS ACUMULADOS</strong></td>
					<!-- <td width="6%" align="center" class="fondo"><strong>STOCK</strong></td> -->
				</tr>
			</thead>
            <tbody>
                <?php
                $contador = 1;
                ?>
                @foreach ($lista as $key => $value)
                <tr>
                    <td width="5%" align="center" class="">{{ $contador }}</td>
                    <td width="20%" align="center" class="">{{ $value->codigo_medico }}</td>
                    <td width="40%" align="left" class="">{{ $value->apellidos_medico.' '.$value->nombres_medico }}</td>
                    {{-- <td width="5%" align="center" class="fondo">{{ $value->nombres_medico }}</td> --}}
                    <td width="34%" align="centes" >{{ $value->puntos }}</td>
                </tr>
                
                <?php
                $contador = $contador + 1;
                ?>
                @endforeach
			</tbody>
    </table>
</body>
</html>