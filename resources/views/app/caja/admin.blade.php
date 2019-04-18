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
        <div class="card-box table-responsive">
			
            <div class="row">
                <div class="col-sm-12">
					{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
					<div class="form-group">
						<label for="descripcion" class="input-sm">Nro Operacion:</label>
						{!! Form::text('num_caja', '', array('class' => 'form-control input-sm', 'id' => 'num_caja')) !!}
					</div>
					<div class="form-group">
						<label for="fechaI" class="input-sm">Desde:</label>
						{!! Form::date('fechaI', '', array('class' => 'form-control input-sm', 'id' => 'fechaI')) !!}
					</div>
					<div class="form-group">
						<label for="fechaF" class="input-sm">Hasta:</label>
						{!! Form::date('fechaF', '', array('class' => 'form-control input-sm', 'id' => 'fechaF')) !!}
					</div>

					<div class="form-group">
						<label for="filas" class="input-sm">Nro Filas:</label>
						{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i> buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-xs', 'id' => 'btnBuscar1', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-xs', 'id' => 'btnNuevocaja', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo ', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-xs', 'id' => 'btnNuevoMovimiento', 'onclick' => 'modal (\''.URL::route($ruta["nuevomovimiento"], array('listar'=>'SI')).'\', \''.$titulo_nuevomovimiento.'\', this);')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre ', array('class' => 'btn btn-danger waves-effect waves-light m-l-10 btn-xs', 'id' => 'btnCierreCaja', 'onclick' => 'modal (\''.URL::route($ruta["cierrecaja"], array('listar'=>'SI')).'\', \''.$titulo_nuevomovimiento.'\', this);')) !!}
					{{-- {!! Form::button('<i class="glyphicon glyphicon-folder-open "></i> Reportes Mes', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnNuevoReporte', 'onclick' => 'modal (\''.URL::route($ruta["cargarreporte"], array('listar'=>'SI')).'\', \''.$titulo_reporte.'\', this);')) !!} --}}
					{!! Form::close() !!}
					
                </div>
            </div>

			<div id="listado{{ $entidad }}"></div>

        </div>
    </div>
</div>

<script>
	$(document).ready(function () {
		var fechaActual = new Date();
		var day = ("0" + fechaActual.getDate()).slice(-2);
		var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
		var fechai= (fechaActual.getFullYear()) +"-"+month+"-01";
		var fechaf= (fechaActual.getFullYear()) +"-"+month+"-"+day+"";
		$('#fechaI').val(fechai);
		$('#fechaF').val(fechaf);
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="login"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
	});
	
	function modalrecibopdf(url_pdf, ancho_modal, titulo_modal) {
		var a = document.createElement("a");
		a.target = "_blank";
		a.href = url_pdf;
		a.click();
	}
	
</script>