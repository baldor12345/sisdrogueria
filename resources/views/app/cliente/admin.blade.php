<!-- Page-Title -->
<div class="row">
		<div class="col-sm-12">
			<div class="page-title-box">
				<h4 class="page-title">{{ $title }}</h4>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">
	
				<div class="row m-b-10">
					<div class="col-sm-12">
						{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
						{!! Form::hidden('page', 1, array('id' => 'page')) !!}
						{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
						<div class="form-group">
							{!! Form::label('name', 'Nombre:') !!}
							{!! Form::text('name', '', array('class' => 'form-control input-xs', 'id' => 'name')) !!}
						</div>
						<div class="form-group">
							{!! Form::label('dni', 'DNI:') !!}
							{!! Form::text('dni', '', array('class' => 'form-control input-xs', 'id' => 'dni')) !!}
						</div>
						<div class="form-group">
							{!! Form::label('filas', 'Filas a mostrar:')!!}
							{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
						</div>
						<div class="form-group">
							{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
						</div>
						{!! Form::close() !!}
					</div>
				</div>
	
				<div id="listado{{ $entidad }}"></div>
			</div>
		</div>
	</div>
	
	<script>
		$(document).ready(function () {
			buscar('{{ $entidad }}');
			init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
			$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="nombre"]').keyup(function (e) {
				var key = window.event ? e.keyCode : e.which;
				if (key == '13') {
					buscar('{{ $entidad }}');
				}
			});
		});
	</script>