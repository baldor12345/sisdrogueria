<div id="divMensajeError{!! $entidad !!}"></div>

	
		<div class="card-box">	
		<table width="100%" border="0px">
			<tr><td style="vertical-align:middle;" rowspan="5" width="60%">OSTEOMEDIC PERU E.I.R.L</td></tr>
			<tr><td align="center" style="border-left: 1px dotted #000;border-top: 1px dotted #000;border-right: 1px dotted #000;" >GUÍA DE REMISION</td></tr>
			<tr><td align="center" style="border-left: 1px dotted #000;border-right: 1px dotted #000;">ELECTRONICA - REMITENTE</td></tr>
			<tr><td align="center" style="border-left: 1px dotted #000;border-right: 1px dotted #000;">RUC: {{ $empresa->ruc}}</td></tr>
			<tr><td align="center" style="border-left: 1px dotted #000;border-bottom: 1px dotted #000;border-right: 1px dotted #000;">{{ $guia->serie.' - '.$guia->numero}}</td></tr>
		</table>

			<table width="100%" border="0px">
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
				<tr><td colspan="2" style= "background-color: #171717;  color:#fff; ">Nro. placa</td></tr>
				<tr><td colspan="2">{{ $guia->numero_placa}}</td></tr>
				<tr><td class="text-center input-sm" colspan="2"></td></tr>
			</table>
			
			<table width="100%" border="0px">
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

		</div>

		<div class="card-box table-responsive">	
			<table width="100%" border="0px">
					<tr>
						<td style= "background-color: #171717;  color:#fff; ">Nro.</td>
						<td style= "background-color: #171717;  color:#fff; ">Cod. Bien</td>
						<td style= "background-color: #171717;  color:#fff; ">Descripción</td>
						<td style= "background-color: #171717;  color:#fff; ">Unidad de Medida</td>
						<td style= "background-color: #171717;  color:#fff; ">Cantidad</td>
					</tr>
					@foreach ($bienes as $key => $value)
						<tr>
							<td class="text-center input-sm" >-</td>
							<td class="text-center input-sm" > - </td>
							<td class="text-center input-sm" >{{ strtoupper($value->presentacion->producto->descripcion) }}</td>
							<td class="text-center input-sm" >{{ strtoupper($value->presentacion->presentacion->nombre) }}</td>
							<td class="text-center input-sm" >{{ $value->cantidad }}</td>
						</tr>
					@endforeach
			</table>
			<table>
				<tr><td width="30%">Observaciones: </td><td>{{$guia->observaciones}}</td></tr>
			</table>
		</div>
		
	
    <div class="form-group">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            {!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cerrar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('700');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	
}); 

</script>