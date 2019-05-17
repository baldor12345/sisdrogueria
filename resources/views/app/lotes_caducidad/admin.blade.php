<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            {{--
            <ol class="breadcrumb pull-right">
                <li><a href="#">Minton</a></li>
                <li><a href="#">Tables</a></li>
                <li class="active">Datatable</li>
            </ol>
            --}}
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
						<label for="lote" class="input-sm">Lote:</label>
						{!! Form::text('lote', '', array('class' => 'form-control input-sm', 'id' => 'lote')) !!}
					</div>
					<div class="form-group">
						<label for="fechai" class="input-sm">Desde:</label>
						{!! Form::date('fechai', '', array('class' => 'form-control input-sm', 'id' => 'fechai')) !!}
					</div>
					<div class="form-group">
						<label for="fechaf" class="input-sm">Hasta:</label>
						{!! Form::date('fechaf', '', array('class' => 'form-control input-sm', 'id' => 'fechaf')) !!}
					</div>
					<div class="form-group">
						<label for="filas" class="input-sm">Filas a Mostrar:</label>
						{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-xs', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
					<!--{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-xs', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}-->
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
		$('#fechai').val(fechai);
		$('#fechaf').val(fechaf);
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