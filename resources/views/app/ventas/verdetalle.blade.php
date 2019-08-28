<div id="divMensajeError{!! $entidad !!}"></div>

	<div class="row">
		<div class="card-box">	
			<table>
				<tr><td>Fecha de Emisión.</td><td style="padding-left: 18px;">: <strong>{{ date('d/m/Y',strtotime($venta->fecha)) }}</strong></td><td style="padding-left: 18px;">Fecha de Venc.</td><td style="padding-left: 18px;">: <strong>{{ date('d/m/Y',strtotime($venta->fecha_venc == null?$venta->fecha:$venta->fecha_venc )) }}</strong></td></tr>
				<tr><td>Señor(es)</td><td style="padding-left: 18px;">: <strong>{{ $venta->cliente->dni == null?$venta->cliente->razon_social:$venta->cliente->nombres.' '.$venta->cliente->apellidos }}</strong></td><td style="padding-left: 18px;">DNI/RUC</td><td style="padding-left: 18px;">: <strong>{{ $venta->cliente_id == null?'':($venta->cliente->ruc == null? $venta->cliente->dni:$venta->cliente->ruc)}}</strong></td></tr>
				<tr><td>Direccion del Cliente</td><td style="padding-left: 18px;">: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->direccion}}</strong></td><td style="padding-left: 18px;">Tipo de Moneda</td><td style="padding-left: 18px;">: <strong>SOLES</strong></td></tr>
				<tr><td>Condicion de Pag.</td><td style="padding-left: 18px;">: <strong>{{ $venta->tipo_pago == 'CO'?'AL CONTADO':'A CRÉDITO'}}</strong></td><td style="padding-left: 18px;">Serie-Numero</td><td style="padding-left: 18px;">: <strong>{{ $venta->serie_doc."-".$venta->numero_doc}}</strong></td></tr>
				@if($venta->tipo_pago == 'CR')
				<tr><td>N° Días a pagar</td><td style="padding-left: 18px;">: <strong>{{  $venta->dias }}</strong></td><td style="padding-left: 18px;">Fecha venc. cred.</td><td style="padding-left: 18px;">: <strong>{{  date("d/m/Y",strtotime($venta->fecha."+ ".$venta->dias." day")) }}</strong></td></tr>
				@endif
				
			</table>

			{{-- <dl class="dl-horizontal">
				<dt style="text-align: left"><label>Fecha de Vencimiento</label></dt><dd>: <strong>{{ date('d/m/Y',strtotime($venta->fecha)) }}</strong></dd>
				<dt style="text-align: left">Fecha de emisión</dt><dd>: <strong>{{ date('d/m/Y',strtotime($venta->fecha)) }}</strong></dd>
				<dt style="text-align: left">Señor(es)</dt><dd>: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->nombres.' '.$venta->cliente->apellidos }}</strong></dd>
				<dt style="text-align: left">RUC</dt><dd>: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->ruc}}</strong></dd>
				<dt style="text-align: left">Direccion del Cliente</dt><dd>: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->direccion}}</strong></dd>
				<dt style="text-align: left">Tipo de Moneda</dt><dd>: <strong>SOLES</strong></dd>
				<dt style="text-align: left">Observación</dt><dd>: <strong>{{ $venta->tipo_pago == 'CO'?'AL CONTADO':'A CRÉDITO'}}</strong></dd>
			</dl> --}}

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
					@foreach ($detalle_ventas as $key => $value)
						<tr>
							<td class="text-center input-sm" width="10%">{{ $value->cantidad }}</td>
							<td class="text-center input-sm" width="10%">{{ strtoupper($value->presentacion->presentacion->nombre) }}</td>
							<td class=" input-sm" width="40%">{{ strtoupper($value->producto->descripcion.' - '.$value->presentacion->presentacion->nombre.' x '.$value->presentacion->cant_unidad_x_presentacion.' Unidades') }}</td>
							<?php
								$lot = "";
								$fecha_v = "";
								$tmp = explode(';',$value->lotes);
								$cant = count($tmp);
								for($i = 0; $i<$cant; $i++){
									$tmp1 = explode(':',$tmp[$i]);
									if($i <($cant-1)){
										$lot = $lot.$tmp1[1].', ';
										$fecha_v = $fecha_v.$tmp1[2].', ';
									}else{
										$lot = $lot.$tmp1[1].'';
										$fecha_v = $fecha_v.$tmp1[2].'';
									}
								}
							?>
							{{-- <td class="text-center input-sm" width="10%">{{ $value->lotes}}</td> --}}
							<td class="text-center input-sm" width="10%">{{ $lot}}</td>
							<td class="text-center input-sm" width="10%">{{ $fecha_v }}</td>
							<td class="text-center input-sm" width="10%">{{ round($value->precio_unitario/1.18, 2) }}</td>
							<td class="text-center input-sm" width="10%">{{ round($value->total/1.18,2) }}</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>
		<div class="card-box col-4 col-md-4">
			
			<dl class="dl-horizontal">
				{{-- <dt style="text-align: left">IGV</dt><dd>: <strong>{{ $venta->valor_igv }}</strong></dd> --}}
				<dt style="">IGV</dt><dd>: <strong>{{ round($venta->igv,2) }}</strong></dd>
				<dt style="">Sub Total</dt><dd>: <strong>{{ round($venta->total - $venta->igv,2) }}</strong></dd>
				<dt style="">TOTAL</dt><dd>: <strong>{{ round($venta->total, 2) }}</strong></dd>
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