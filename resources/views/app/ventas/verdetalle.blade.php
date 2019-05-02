<div id="divMensajeError{!! $entidad !!}"></div>

	<div class="row">
		<div class="card-box">	
			<dl class="dl-horizontal">
				<dt style="text-align: left"><label>Fecha de Vencimiento</label></dt><dd>: <strong>{{ date('d/m/Y',strtotime($venta->fecha)) }}</strong></dd>
				<dt style="text-align: left">Fecha de emisión</dt><dd>: <strong>{{ date('d/m/Y',strtotime($venta->fecha)) }}</strong></dd>
				<dt style="text-align: left">Señor(es)</dt><dd>: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->nombres.' '.$venta->cliente->apellidos }}</strong></dd>
				<dt style="text-align: left">RUC</dt><dd>: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->ruc}}</strong></dd>
				<dt style="text-align: left">Direccion del Cliente</dt><dd>: <strong>{{ $venta->cliente_id == null?'':$venta->cliente->direccion}}</strong></dd>
				<dt style="text-align: left">Tipo de Moneda</dt><dd>: <strong>SOLES</strong></dd>
				<dt style="text-align: left">Observación</dt><dd>: <strong>{{ $venta->tipo_pago == 'CO'?'AL CONTADO':'A CRÉDITO'}}</strong></dd>
			</dl>
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
						<th class="text-center input-sm" width="10%">Valor Unitario</th>
						<th class="text-center input-sm" width="10%">Sub Total</th>
					</tr>
				</thead>
				<tbody>
						@foreach ($detalle_ventas as $key => $value)
						<tr>
							<td class="text-center input-sm" width="10%">{{ $value->cantidad }}</td>presentacion
							<td class="text-center input-sm" width="10%">{{ strtoupper($value->presentacion->presentacion->nombre) }}</td>
							<td class=" input-sm" width="40%">{{ strtoupper($value->producto->descripcion.' - '.$value->producto->sustancia_activa) }}</td>
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
							<td class="text-center input-sm" width="10%">{{ $lot}}</td>
							<td class="text-center input-sm" width="10%">{{ $fecha_v }}</td>
							<td class="text-center input-sm" width="10%">{{ $value->precio_unitario }}</td>
							<td class="text-center input-sm" width="10%">{{ $value->total }}</td>
						</tr>
					@endforeach

				</tbody>
			</table>
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