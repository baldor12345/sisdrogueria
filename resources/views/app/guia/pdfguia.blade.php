<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
	</head>
	<body>
	
		<table width="100%" border="0px">
			{{-- <tr><td style="vertical-align:middle;" rowspan="5" width="60%">OSTEOMEDIC PERU E.I.R.L</td></tr> --}}
			<tr><td width="60%"></td><td width="36%" align="center" style="border-left: 1px dotted #000;border-top: 1px dotted #000;border-right: 1px dotted #000;" >GUÍA DE REMISION</td></tr>
			<tr><td style="vertical-align:middle;" width="60%">OSTEOMEDIC PERU E.I.R.L</td><td width="36%" align="center" style="border-left: 1px dotted #000;border-right: 1px dotted #000;">ELECTRONICA - REMITENTE</td></tr>
			<tr><td width="60%"></td><td width="36%" align="center" style="border-left: 1px dotted #000;border-right: 1px dotted #000;">RUC: {{ $empresa->ruc}}</td></tr>
			<tr><td width="60%"></td><td width="36%" align="center" style="border-left: 1px dotted #000;border-bottom: 1px dotted #000;border-right: 1px dotted #000;">{{ $guia->serie.' - '.$guia->numero}}</td></tr>
		</table>
		<br>
		<br>

		<table width="90%" border="0px">
			<tr><td colspan="2">DATOS DEL TRASLADO</td></tr>
			<tr><td width="60%">Fecha de Emision: </td><td>{{$guia->fecha_emision}}</td></tr>
			<tr><td width="60%">Fecha de Inicio del traslado: </td><td>{{$guia->fecha_inicio_traslado}}</td></tr>
			<tr><td width="60%">Motivo de traslado: </td><td>{{$guia->motivo_traslado}}</td></tr>
			<tr><td width="60%">Modalidad de transporte: </td><td>{{$guia->modalidad_transporte}}</td></tr>
			<tr><td width="60%">Peso Bruto Tota de la Guia (KGM): </td><td>{{$guia->pesobruto_total}}</td></tr>
			<tr><td class="text-center input-sm" colspan="2"></td></tr>
		
			<tr><td colspan="2">DATOS DEL DESTINATARIO</td></tr>
			<tr><td width="60%">Apellidos y Nombres, denominación o razón: </td><td>{{$guia->nombres_destinatario}}</td></tr>
			<tr><td width="60%">Documento de Identidad: </td><td>{{$guia->doc_identidad}}</td></tr>
			<tr><td class="text-center input-sm" colspan="2"></td></tr>
			<tr><td colspan="2">DATOS DEL PUNTO DE PARTIDA Y PUNTO DE LLEGADA</td></tr>
			<tr><td width="60%">Dirección del punto de partida: </td><td>{{$guia->direccion_partida}}</td></tr>
			<tr><td width="60%">Dirección del punto de llegada: </td><td>{{$guia->direccion_llegada}}</td></tr>
			<tr><td class="text-center input-sm" colspan="2"></td></tr>
			<tr><td colspan="2">DATOS DEL TRANSPORTE</td></tr>
			<tr><td colspan="2">Datos de los Vehiculos</td></tr>
			<tr><td style= "background-color: #171717;  color:#fff; " colspan="2">Nro. placa</td></tr>
			<tr><td colspan="2">{{ $guia->numero_placa}}</td></tr>
			<tr><td ></td><td></td></tr>
		</table>
		<table>
			<tr><td colspan="3">Datos de los Conductores</td></tr>
			<tr>
				<td style= "background-color: #171717;  color:#fff; ">Nro.</td>
				<td style= "background-color: #171717;  color:#fff; ">Tipo Doc.</td>
				<td style= "background-color: #171717; color:#fff; ">Nro. docu.</td>
			</tr>
			<tr>
				<td>1</td>
				<td>{{$guia->tipodoc_conductor}}</td>
				<td>{{$guia->numerodoc_conductor}}</td>
			</tr>
		</table>
		<br>
		<br>

			
		<table id="tabla" width="100%" border="0px">
			<thead>
				<tr>
					<th width="8%" style= "background-color: #171717;  color:#fff; " align="center">Nro.</th>
					<th width="10%" style= "background-color: #171717;  color:#fff; " align="center">Cod. Bien</th>
					<th width="53%" style= "background-color: #171717;  color:#fff; " align="center">Descripción</th>
					<th width="20%" style= "background-color: #171717;  color:#fff; " align="center">Unidad de Medida</th>
					<th width="10%" style= "background-color: #171717;  color:#fff; " align="center">Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$contador =1;
				?>
				@foreach ($bienes as $key => $value)
					<tr>
						<td width="8%" align="center"  style="font-size: 10px">{{ $contador }}</td>
						<td width="10%" align="center" style="font-size: 10px"> - </td>
						<td width="53%" align="center" style="font-size: 10px">{{ strtoupper($value->presentacion->producto->descripcion) }}</td>
						<td width="20%" align="center" style="font-size: 10px">{{ strtoupper($value->presentacion->presentacion->nombre) }}</td>
						<td width="10%" align="center" style="font-size: 10px">{{ $value->cantidad }}</td>
					</tr>
					<?php
					$contador ++;
					?>
				@endforeach

			</tbody>
		</table>
		<br>
		<br>
		<table>
			<tr><td width="30%">Observaciones: </td><td>{{$guia->observaciones}}</td></tr>
		</table>
	</body>
</html>		