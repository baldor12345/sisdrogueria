<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
<div class="row" style="background: rgba(51,122,183,0.10);">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row m-b-5">
                <div class="col-sm-12">
					{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
					<div class="form-group">
						<label for="numero_serie" class="input-md">N° Doc. B/F:</label>
						{!! Form::text('numero_serie', '', array('class' => 'form-control input-md', 'id' => 'numero_serie')) !!}
					</div>
					<div class="form-group">
						<label for="doc_dni_ruc" class="input-md">DNI o RUC:</label>
						{!! Form::text('doc_dni_ruc', '', array('class' => 'form-control input-md', 'id' => 'doc_dni_ruc')) !!}
					</div>
					
					<div class="form-group">
							<label for="cboTipoVentas" class="input-md">Tipo ventas:</label>
						{!! Form::select('cboTipoVentas', ['P'=>'Activas','A'=>'Anuladas'], 'P', array('class' => 'form-control input-md', 'id' => 'cboTipoVentas', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group">
							<label for="cboTipoV" class="input-md"></label>
						{!! Form::select('cboTipoV', ['CO'=>'Contado','CR'=>'Crédito'], 'CO', array('class' => 'form-control input-md', 'id' => 'cboTipoV', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group">
							<label for="cboTipoComprobante" class="input-md"></label>
						{!! Form::select('cboTipoComprobante', ['T'=>'Todos','B'=>'Boletas','F'=>'Facturas'], 'T', array('class' => 'form-control input-md', 'id' => 'cboTipoComprobante', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{{-- <div class="form-group">
						<label for="proveedor" class="input-sm">Proveedor:</label>
						{!! Form::text('proveedor', '', array('class' => 'form-control input-sm', 'id' => 'proveedor')) !!}
					</div> --}}
					<div class="form-group">
						<label for="fechai" class="input-md">Fecha Inicio:</label>
						{!! Form::date('fechai', $fecha_inicial, array('class' => 'form-control input-md', 'id' => 'fechai', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group">
						<label for="fechaf" class="input-md">Fecha Fin:</label>
						{!! Form::date('fechaf', $fecha_defecto, array('class' => 'form-control input-md', 'id' => 'fechaf',  'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group">
						<label for="filas" class="input-md">Filas a Mostrar:</label>
						{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-md', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
					{!! Form::close() !!}
		 		</div>
            </div>

			<div id="listado{{ $entidad }}"></div>
			
            <table id="datatable" class="table table-striped table-bordered">
            </table>
        </div>
    </div>
</div>
<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});

	});
</script>