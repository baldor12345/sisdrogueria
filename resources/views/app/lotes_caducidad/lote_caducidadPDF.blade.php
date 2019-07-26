
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
	<div class="contenedor">
		<table border="0" cellspacing="3" cellpadding="2" style="margin: 50px;" class="table table-striped">
			<tr>
				<td align="center" style="font-size: 15px" colspan="4">{{ $titulo }}</td>
			</tr>
		</table>
	</div>

	<table width="100%" class="tabla3">
            <thead>
				<tr>
					<td width="5%" align="center" class="fondo"><strong>#</strong></td>
					<td width="40%" align="center" class="fondo"><strong>PRODUCTO</strong></td>
					<td width="20%" align="center" class="fondo"><strong>LOTE</strong></td>
					<td width="10%" align="center" class="fondo"><strong>FECHA VENC.</strong></td>
					<td width="6%" align="center" class="fondo"><strong>CANTIDAD</strong></td>
					<td width="30%" align="center" class="fondo"><strong>MARCA/LABORATORIO</strong></td>
				</tr>
			</thead>
            <tbody>

                <?php
                $contador = $inicio + 1;
                ?>
                @foreach ($lista as $key => $value)
                <tr>
                <td>{{ $contador }}</td>
                    <td>{{ $value->producto }}</td>
                    <td>{{ $value->lote }}</td>
                    <td>{{ $value->fecha_venc_string}}</td>
                    <td>{{ $value->cantidad }}</td>
                    <td>{{ $value->laboratorio }}</td>
                </tr>
                <?php
                $contador = $contador + 1;
                ?>
                @endforeach
			</tbody>
    </table>
</body>
</html>