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
			{{-- {!! Form::button('<i class="fa fa-plus fa-lg"></i> Registrar Nuevo', array('class' => 'btn btn-success btn-sm', 'id' => 'btnadd', 'onclick' => '')) !!} --}}
			{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md', 'id' => 'btnNuevocli', 'onclick' => 'modal (\''.URL::route($ruta["create_new"], array('listar'=>'SI')).'\', \''."Registrar Cliente".'\', this);')) !!}
		</div>
		<div class="form-group col-6 col-md-6">
			{!! Form::label('cboForma_pago', 'Forma de pago: ', array('class' => '')) !!}
			{!! Form::select('cboForma_pago', $cboFormaPago, '0' , array('class' => 'form-control input-sm', 'id' => 'cboForma_pago')) !!}
		</div>
		<div class="form-group col-6 col-md-6"  style="magin-left: 10px">
			{!! Form::label('cboComprobante', 'Comprobante Pago: ', array('class' => '')) !!}
			{!! Form::select('cboComprobante', $cboComprobante, '0' , array('class' => 'form-control input-sm', 'id' => 'cboComprobante')) !!}
		</div>
	</div>
	<div class="row">
		<div id="divInfoProducto" class="col-12 col-md-12" ></div>
	</div>

	<div class="row card-box">
		<div class="form-group col-3 col-md-3">
			{!! Form::label('cboProducto', 'Producto: ', array('class' => '')) !!}
			{!! Form::select('cboProducto', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboProducto')) !!}
		</div>
		{!! Form::hidden('precio_unidad', 0.0, array('id' => 'precio_unidad')) !!}
		{{-- {!! Form::hidden('nombre_temporal', "", array('id' => 'nombre_temporal')) !!}
		{!! Form::hidden('nombre_presentacion_temp', "", array('id' => 'nombre_presentacion_temp')) !!} --}}
		<div class="form-group col-3 col-md-3">
			{!! Form::label('cboPresentacion', 'Presentacion: ', array('class' => '')) !!}
			{!! Form::select('cboPresentacion', $cboPresentacion, '0' , array('class' => 'form-control input-sm', 'id' => 'cboPresentacion')) !!}
		</div>
		<div class="form-group col-3 col-md-3">
			{!! Form::label('cantidad', 'Cantidad:', array('class' => 'control-label')) !!}
			{!! Form::text('cantidad', null, array('class' => 'form-control input-xs', 'id' => 'cantidad', 'placeholder' => 'Cantidad')) !!}
		</div>
		<div class="form-group col-3 col-md-3">
				{!! Form::button('<i class="fa fa-plus fa-lg"></i> Add', array('class' => 'btn btn-success btn-sm', 'id' => 'btnAgregar', 'onclick' => 'agregar_producto()')) !!}
		</div>
		<div class="form-group col-12 col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed table-hover">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Cantidad</th>
							<th>Unidad/Medida</th>
							<th>Precio Unitario</th>
							<th>Sub Total S/.</th>
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
	$('#cboProducto').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listproductos'], array()) }}",
			dataType: 'json',
			delay: 250,
			data: function(params){
				return{
					q: $.trim(params.term)
				};
			},
			processResults: function(data){
				return{
					results: data
				};
			}
			
		}
	});

	$('#cboProducto').change(function(){
            // $('#selectaval').select2("val", "0");
            $.get("ventas/"+$(this).val()+"",function(response, facultad){//obtener el producto, su stock, precio_venta
                // console.log("Respuesta persona: "+response[3]);
                var producto = response[0];
                var stock = response[1];
                var precio_unidad = response[2];
                 var presentacion_id = response[3];
				 $('#nombre_temporal').val(""+producto.descripcion);
				 $('#precio_unidad').val(""+precio_unidad);
                    var msj = "<div class='alert alert-success'><strong>¡Detalles de producto: !</strong><ul><li>Producto: "+producto.descripcion+"</li>";
                    msj += "<li>Stock: "+stock+"</li><li>Precio/Unidad: "+precio_unidad+"</li></ul>";
					$('#divInfoProducto').html(msj);
					$('#divInfoProducto').show();
            });
        });
	$('#cboPresentacion').change(function(){
                
				// //  $('#nombre_temporal').val(""+producto.descripcion);
                //     var msj = "<div class='alert alert-success'><strong>¡Detalles de producto: !</strong><ul><li>Producto: "+producto.descripcion+"</li>";
                //     msj += "<li>Stock: "+stock+"</li><li>Precio/Unidad: "+precio_unidad+"</li></ul>";
				// 	$('#divInfoProducto').html(msj);
				// 	$('#divInfoProducto').show();
        });

	$('#cboCliente').select2({
		dropdownParent: $("#modal"+(contadorModal-1)),
		
		minimumInputLenght: 2,
		ajax: {
			
			url: "{{ URL::route($ruta['listclientes'], array()) }}",
			dataType: 'json',
			delay: 250,
			data: function(params){
				return{
					q: $.trim(params.term)
				};
			},
			processResults: function(data){
				return{
					results: data
				};
			}
			
		}
	});
}); 

function agregar_producto(){

	// var nombre = $('#nombre_temp').val();
	// var nombre_presentacion = $('#nombre_presentacion_temp').val();

	var nombre_producto = $('#cboProducto').text();
	var nombre_presentacion = $('#cboPresentacion').text();
	var producto_id = $('#cboProducto').val();
	var presentacion_id = $('#cboPresentacion').val();
	var precio_unidad = $('#precio_unidad').val();
	var cantidad = $('#cantidad').val();
	var subtotal = cantidad * precio_unidad;
	var fila = "<tr><td>"+nombre_producto+"</td><td>"+cantidad+"</td><td>"+nombre_presentacion+"</td><td>"+precio_unidad+"</td><td>"+subtotal+"</td></tr>";
	$("#tabla_productos").append(fila);

}


</script>