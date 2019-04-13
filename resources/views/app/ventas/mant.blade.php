<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($venta, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('total', 0, array('id' => 'total')) !!}
	<div class="row card-box">
		<div class="form-group col-9 col-md-9">
			{!! Form::label('cboCliente', 'Cliente: ', array('class' => '')) !!}
			{!! Form::select('cboCliente', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboCliente')) !!}
		</div>
		<div class="form-group col-2 col-md-2" style="magin-left: 10px">
				{!! Form::label('btnadd', 'Nuevo: ', array('class' => 'control-label col-12 col-md-12')) !!}
			{!! Form::button('<i class="fa fa-plus fa-lg"></i> Registrar Nuevo', array('class' => 'btn btn-success btn-sm', 'id' => 'btnadd', 'onclick' => '')) !!}
		</div>
		<div class="form-group col-6 col-md-6">
			{!! Form::label('cboForma_pago', 'Forma de pago: ', array('class' => '')) !!}
			{!! Form::select('cboForma_pago', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboForma_pago')) !!}
		</div>
		<div class="form-group col-6 col-md-6"  style="magin-left: 10px">
			{!! Form::label('cboComprobante', 'Comprobante Pago: ', array('class' => '')) !!}
			{!! Form::select('cboComprobante', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboComprobante')) !!}
		</div>
	</div>
	
	<div class="row card-box">
		<div class="form-group col-3 col-md-3">
			{!! Form::label('cboProducto', 'Producto: ', array('class' => '')) !!}
			{!! Form::select('cboProducto', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboProducto')) !!}
		</div>
		<div class="form-group col-3 col-md-3">
			{!! Form::label('cboUnidad', 'Medida: ', array('class' => '')) !!}
			{!! Form::select('cboUnidad', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboUnidad')) !!}
		</div>
		<div class="form-group col-3 col-md-3">
			{!! Form::label('cantidad', 'Cantidad:', array('class' => 'control-label')) !!}
			{!! Form::text('cantidad', null, array('class' => 'form-control input-xs', 'id' => 'cantidad', 'placeholder' => 'Cantidad')) !!}
		</div>
		<div class="form-group col-3 col-md-3">
				{!! Form::button('<i class="fa fa-plus fa-lg"></i> Add', array('class' => 'btn btn-success btn-sm', 'id' => 'btnAgregar', 'onclick' => '')) !!}
		</div>
		<div class="form-group col-12 col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed table-hover">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Cantidad</th>
							<th>Precio Unitario</th>
							<th>Total S/.</th>
						</tr>
					</thead>
					<tbody id="tabla_productos">

					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => '')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('750');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>