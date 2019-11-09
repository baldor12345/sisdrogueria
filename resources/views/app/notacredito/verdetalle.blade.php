<div id="divMensajeError{!! $entidad !!}"></div>

	<div class="row">
		<div class="card-box">	
			<table>
				<tr><td>Fecha de Emisión.</td><td style="padding-left: 18px;">: <strong>{{ date('d/m/Y',strtotime($notacredito->fecha)) }}</strong></td><td style="padding-left: 18px;">Fecha de Venc.</td><td style="padding-left: 18px;">: <strong>{{ date('d/m/Y',strtotime($notacredito->fecha == null?$notacredito->fecha:$notacredito->fecha )) }}</strong></td></tr>
				<tr><td>Señor(es)</td><td style="padding-left: 18px;">: <strong>{{ $notacredito->cliente->dni == null?$notacredito->cliente->razon_social:$notacredito->cliente->nombres.' '.$notacredito->cliente->apellidos }}</strong></td><td style="padding-left: 18px;">DNI/RUC</td><td style="padding-left: 18px;">: <strong>{{ $notacredito->cliente_id == null?'':($notacredito->cliente->ruc == null? $notacredito->cliente->dni:$notacredito->cliente->ruc)}}</strong></td></tr>
				<tr><td>Direccion del Cliente</td><td style="padding-left: 18px;">: <strong>{{ $notacredito->cliente_id == null?'':$notacredito->cliente->direccion}}</strong></td><td style="padding-left: 18px;">Tipo de Moneda</td><td style="padding-left: 18px;">: <strong>SOLES</strong></td></tr>
				<tr><td>Condicion de Pag.</td><td style="padding-left: 18px;">: <strong></strong></td><td style="padding-left: 18px;">Serie-Numero</td><td style="padding-left: 18px;">: <strong>{{ $notacredito->serie_doc."-".$notacredito->numero_doc}}</strong></td></tr>
				
			</table>
		</div>
	</div>
	<div class="row">
		<div class="card-box table-responsive">	
			<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th class="text-center input-sm" width="10%">Cantidad</th>
						<th class="text-center input-sm" width="10%">Unidad Medida</th>
						<th class="text-center input-sm" width="40%">Descripción</th>
						<th class="text-center input-sm" width="10%">Lote</th>
						<th class="text-center input-sm" width="10%">F. VCTO</th>
						<th class="text-center input-sm" width="10%">Valor Unit. sin IGV</th>
						<th class="text-center input-sm" width="10%">Sub Total</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($detalle_nc as $key => $value)
						<tr>
							<td class="text-center input-sm" width="10%">{{ $value->cantidad }}</td>
							<td class="text-center input-sm" width="10%">{{ strtoupper($value->presentacion->presentacion->nombre) }}</td>
							<td class=" input-sm" width="40%">{{ strtoupper($value->productopresentacion->producto->descripcion.' - '.$value->presentacion->presentacion->nombre.' x '.$value->presentacion->cant_unidad_x_presentacion.' Unidades') }}</td>
							<td class="text-center input-sm" width="10%">{{ $value->lotes}}</td>
							<td class="text-center input-sm" width="10%">{{ $value->lotes }}</td>
							<td class="text-center input-sm" width="10%">{{ round(($value->productopresentacion->producto->afecto == 'S'?$value->precio_unitario/1.18:$value->precio_unitario) , 2) }}</td>
							<td class="text-center input-sm" width="10%">{{ round(($value->productopresentacion->producto->afecto == 'S'?$value->total/1.18:$value->total),2) }}</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>
		<div class="card-box col-4 col-md-4">
			
			<dl class="dl-horizontal">
				{{-- <dt style="text-align: left">IGV</dt><dd>: <strong>{{ $notacredito->valor_igv }}</strong></dd> --}}
				<dt style="">IGV</dt><dd>: <strong>{{ round($notacredito->igv,2) }}</strong></dd>
				<dt style="">Sub Total</dt><dd>: <strong>{{ round($notacredito->total - $notacredito->igv,2) }}</strong></dd>
				<dt style="">TOTAL</dt><dd>: <strong>{{ round($notacredito->total, 2) }}</strong></dd>
			</dl>
			
		</div>
	</div>
    <div class="form-group">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            {!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cerrar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('900');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	
}); 

</script>