<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($entrada_salida, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">	
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group ">
				{!! Form::label('tipo', 'Tipo:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('tipo', $cboTipo, $entrada_salida->tipo, array('class' => 'form-control input-sm', 'id' => 'tipo', 'disabled')) !!}
				</div>
			</div>

            <div class="form-group" >
				{!! Form::label('numero_documento', 'Nro Doc:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::text('serie', $entrada_salida->num_documento, array('class' => 'form-control input-xs', 'id' => 'serie', 'placeholder' => 'num documento','readonly')) !!}
				</div>
			</div>
		</div>		
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group " >
				{!! Form::label('fecha', 'Fecha:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::date('fecha', $fecha, array('class' => 'form-control input-xs', 'id' => 'fecha', 'readonly')) !!}
				</div>
			</div>
			<div class="form-group " >
				{!! Form::label('comentario', 'Comentario:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::textarea('comentario', $entrada_salida->comentario, array('class' => 'form-control input-xs' , 'cols'=>'10', 'rows'=>'rows', 'id' => 'comentario', 'readonly')) !!}
				</div>
			</div>
		</div>		
	</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group">
				<table id="tabla" class="table table-bordered table-striped table-condensed table-hover">
		            <thead>
		                <tr>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="40%">Producto</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="20%">Presentacion</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="15%">F. Venc.</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">Cantidad</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">P. Compra</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="5%">P. Venta</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-xs" width="10%">Lote</th>
		                </tr>
		            </thead>
                    <tbody>
                        @foreach ($list_detalle_es as $key => $value)
                            <tr>
                                <td class="text-left input-xs">{{ $value->producto.' '.$value->sustancia_activa }}</td>
                                <td class="text-center input-xs">{{ $value->presentacion }}</td>
								@if($value->fecha_completa == 'N')
                                <td class="text-center input-xs">{{ Date::parse( $value->fecha_caducidad )->format('m-Y') }}</td>
								@else
                                <td class="text-center input-xs">{{ Date::parse( $value->fecha_caducidad )->format('d-m-Y') }}</td>
								@endif
								<td class="text-center input-xs">{{ $value->cantidad }}</td>
								<td class="text-center input-xs">{{ $value->precio_compra }}</td>
								<td class="text-center input-xs">{{ $value->precio_venta }}</td>
                                <td class="text-center input-xs">{{ $value->lote }}</td>
                            </tr>
                        @endforeach
                    </tbody>
		        </table>
			</div>
		</div>
    </div>
    <div class="form-group">
        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
            {!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
        </div>
    </div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1000');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	var fechaActual = new Date();
	var day = ("0" + fechaActual.getDate()).slice(-2);
	var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
	var fecha_horaApert = (fechaActual.getFullYear()) +"-"+month+"-"+day+"";
	$('#fechavencimiento').val(fecha_horaApert);
	$('#fecha').val(fecha_horaApert);
	$('#fecha_caducidad').val(fecha_horaApert);

}); 

</script>