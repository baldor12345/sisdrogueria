<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($sucursal, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group col-12 col-md-12">
		{!! Form::label('nombre', 'Nombre:', array('class' => '')) !!}
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('telefono', 'Teléfono:', array('class' => '')) !!}
			{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese telefono')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('direccion', 'Dirección:', array('class' => '')) !!}
			{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese dirección')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('serie', 'Serie:', array('class' => '')) !!}
			{!! Form::text('serie', null, array('class' => 'form-control input-xs', 'id' => 'serie', 'placeholder' => 'Ingrese serie')) !!}
	</div>
	{{-- <div class="form-group col-12 col-md-12">
		{!! Form::label('serie_boleta', 'Serie Boleta:', array('class' => '')) !!}
			{!! Form::text('serie_boleta', null, array('class' => 'form-control input-xs', 'id' => 'serie_boleta', 'placeholder' => 'Ingrese serie')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('serie_factura', 'Serie Factura:', array('class' => '')) !!}
			{!! Form::text('serie_factura', null, array('class' => 'form-control input-xs', 'id' => 'serie_factura', 'placeholder' => 'Ingrese serie')) !!}
	</div> --}}
	<div class="form-group col-12 col-md-12">
		{!! Form::label('departamento', 'Departamento: ', array('class' => '')) !!}
		{!! Form::select('departamento', $cboDepartamentos, $sucursal !=null? $sucursal->departamento->nombre: 0 , array('class' => 'form-control input-sm', 'id' => 'departamento')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('provincia', 'Provincia: ', array('class' => '')) !!}
		{!! Form::select('provincia', $cboProvincias, $sucursal !=null? $sucursal->provincia->nombre: 0 , array('class' => 'form-control input-sm', 'id' => 'provincia')) !!}
	</div>
	<div class="form-group col-12 col-md-12">
		{!! Form::label('distrito', 'Distrito: ', array('class' => '')) !!}
		{!! Form::select('distrito', $cboDistritos, $sucursal !=null? $sucursal->distrito_id: 0 , array('class' => 'form-control input-sm', 'id' => 'distrito')) !!}
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('450');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('#departamento').change(function(){
		$.get("provincia/"+$(this).val()+"",function(response, facultad){//
			$('#provincia').empty();
			var cantidad = response[0];
			var listProvincias = response[1];
			var cboProvincias = '<option value="0">Seleccione</option>';
			for(var i=0; i<cantidad; i++){
				cboProvincias +='<option value="'+listProvincias[i].id+'">'+listProvincias[i].nombre+'</option>'
			}
			$('#provincia').append(cboProvincias);
		});
	});

	$('#provincia').change(function(){
		$.get("distrito/"+$(this).val()+"",function(response, facultad){//
			$('#distrito').empty();
			var cantidad = response[0];
			var listDistritos = response[1];
			var cboDistritos = '<option value="0">Seleccione</option>';
			for(var i=0; i<cantidad; i++){
				cboDistritos +='<option value="'+listDistritos[i].id+'">'+listDistritos[i].nombre+'</option>'
			}
			$('#distrito').append(cboDistritos);
		});
	});


}); 
</script>