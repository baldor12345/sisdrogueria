<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($compra, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">	
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group ">
				{!! Form::label('documento', 'Documento:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('documento', $cboDocumento, $compra->documento, array('class' => 'form-control input-sm', 'id' => 'documento')) !!}
				</div>
			</div>

            <div class="form-group" >
				{!! Form::label('numero_documento', 'Nro Doc:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-3 col-xs-12" style="height: 25px;">
					{!! Form::text('serie', $compra->serie_documento, array('class' => 'form-control input-xs', 'id' => 'serie', 'placeholder' => 'num documento')) !!}
				</div>
				<div class="col-sm-6 col-xs-12" style="height: 25px;">
					{!! Form::text('serie', $compra->numero_documento, array('class' => 'form-control input-xs', 'id' => 'serie', 'placeholder' => 'num documento')) !!}
				</div>
                
			</div>

            <div class="form-group" >
				{!! Form::label('numero_dias', 'Proveedor:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::text('numero_dias', $proveedor->nombre, array('class' => 'form-control input-xs input-number', 'id' => 'numero_dias', 'placeholder' => '')) !!}
				</div>
			</div>
		</div>		
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-box">    	
			<div class="form-group">
				{!! Form::label('credito', 'Credito:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::select('credito', $cboCredito, $compra->credito, array('class' => 'form-control input-sm', 'id' => 'credito')) !!}
				</div>
			</div>
			<div class="form-group " >
				{!! Form::label('fecha', 'Fecha:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha')) !!}
				</div>
			</div>
			<div class="form-group" >
				{!! Form::label('total', 'Total:', array('class' => 'col-sm-3 col-xs-12 control-label input-sm', 'style'=>'height: 25px')) !!}
				<div class="col-sm-9 col-xs-12" style="height: 25px;">
					{!! Form::text('total', $compra->total, array('class' => 'form-control input-xs', 'id' => 'total', 'placeholder' => '','readonly')) !!}
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
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="50%">Producto</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">F. Venc.</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Cantidad</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="10%">Precio</th>
		                    <th bgcolor="#E0ECF8" class="text-center input-sm" width="15%">Subtotal</th>
		                </tr>
		            </thead>
                    <tbody>
                        @foreach ($list_detalle_c as $key => $value)
                            <tr>
                                <td>{{ $value->descripcion }}</td>
                                <td>{{ Date::parse( $value->fecha_caducidad )->format('d-m-Y') }}</td>
                                <td>{{ $value->cantidad }}</td>
                                <td>{{ $value->precio_compra }}</td>
                                <td>{{ number_format($value->precio_compra*$value->cantidad,2) }}</td>
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
	configurarAnchoModal('800');
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