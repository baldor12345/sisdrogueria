<div class="form-group">
		{!! Form::label('ruc_e', 'RUC:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('ruc_e', $empresa->ruc, array('class' => 'form-control input-sm', 'id' => 'ruc', 'placeholder' => '')) !!}
	</div>
</div>
<div class="form-group">
		{!! Form::label('razon_social_e', 'Razon Social:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('razon_social_e', $empresa->razon_social, array('class' => 'form-control input-sm', 'id' => 'razon_social_e', 'placeholder' => '')) !!}
	</div>
</div>
<div class="form-group">
		{!! Form::label('direccion_e', 'Dirección:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('direccion_e', $empresa->direccion, array('class' => 'form-control input-sm', 'id' => 'direccion_e', 'placeholder' => '')) !!}
	</div>
</div>

<div class="form-group">
		{!! Form::label('telefono_e', 'Telefono:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('telefono_e', $empresa->telefono, array('class' => 'form-control input-sm', 'id' => 'telefono_e', 'placeholder' => '')) !!}
	</div>
</div>
<div class="form-group">
		{!! Form::label('email_e', 'Email:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('email_e', $empresa->email, array('class' => 'form-control input-sm', 'id' => 'email_e', 'placeholder' => '')) !!}
	</div>
</div>
<div class="form-group">
		{!! Form::label('departamento_e', 'Departamento:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('departamento_e', $empresa->departamento->nombre, array('class' => 'form-control input-sm', 'id' => 'departamento_e', 'placeholder' => '')) !!}
	</div>
</div>
<div class="form-group">
		{!! Form::label('provincia_e', 'Provincia:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('provincia_e', $empresa->provincia->nombre, array('class' => 'form-control input-sm', 'id' => 'provincia_e', 'placeholder' => '')) !!}
	</div>
</div>
<div class="form-group">
		{!! Form::label('distrito_e', 'Distrito:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('distrito_e', $empresa->distrito->nombre, array('class' => 'form-control input-sm', 'id' => 'distrito_e', 'placeholder' => '')) !!}
	</div>
</div>

<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
				<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Modificar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($empresa->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
			{{-- {!! Form::button('<i class="fa fa-check fa-lg"></i> Modificar', array('class' => 'btn btn-success btn-sm', 'id' => 'btnModificar_e', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!} --}}
		</div>
	</div>