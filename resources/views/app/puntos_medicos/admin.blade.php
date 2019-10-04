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
					<!-- <div class="form-group">
						<label for="cod_medico" class="input-sm">Código:</label>
						{!! Form::text('cod_medico', '', array('class' => 'form-control input-sm', 'id' => 'cod_medico')) !!}
					</div> -->
					<!-- <div class="form-group">
						<label for="nombre_medico" class="input-sm">Nombre:</label>
						{!! Form::text('nombre_medico', '', array('class' => 'form-control input-sm', 'id' => 'nombre_medico')) !!}
					</div> -->
					<div class="form-group">
						<label for="tip_busqueda" class="input-sm">Por:</label>
						{!! Form::select('anio',  $anios, $anio_actual, array('class' => 'form-control input-sm', 'id' => 'anio', 'onchange' => 'buscar_por(\''.$entidad.'\', this)')) !!}
					</div>
					<div class="form-group">
						<label for="tip_busqueda" class="input-sm">Por:</label>
						{!! Form::select('mes',  $meses, $mes_actual, array('class' => 'form-control input-sm', 'id' => 'mes', 'onchange' => 'buscar_por(\''.$entidad.'\', this)')) !!}
					</div>
					<div class="form-group">
						<label for="filas" class="input-sm">Filas a Mostrar:</label>
						{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
					{{-- <a target="_blank" href="{{ route('reportepuntosPDF') }}" class="btn btn-primary waves-effect waves-light btn-xs" ><i class="glyphicon glyphicon-download-alt" ></i> PDF</a> --}}
					{!! Form::button('<i class="glyphicon glyphicon-download-alt"></i> PDF', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'id' => 'btnPDF'.$entidad, 'onclick' => 'imprimirReporte();')) !!}
					{!! Form::close() !!}
		 		</div>
            </div>

			<div id="listado{{ $entidad }}"></div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function () {
		// var fechaActual = new Date();
		// var day = ("0" + fechaActual.getDate()).slice(-2);
		// var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
		// var fechai= (fechaActual.getFullYear()) +"-"+month+"-01";
		// var fechaf= (fechaActual.getFullYear()) +"-"+month+"-"+day+"";
		// $('#fechai').val(fechai);
		// $('#fechaf').val(fechaf);
		// $('.f2').hide();
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});


	});

	function buscar_por(entidad, selectt){
		if($(selectt).val() == 'rango'){
			$('.f1').show();
			$('.f2').show();
		}else{
			$('.f2').hide();
			$('.f1').show();
		}
		buscar('{{ $entidad }}');
	}

	function imprimirReporte(){
        
        var rutareportecuotas = "{{ URL::route($ruta['reportepuntosmedicoPDF'], array()) }}";
        rutareportecuotas += "?anio="+$('#anio').val()+"&mes="+$('#mes').val();
        
        imprimirpdf(rutareportecuotas);
    }
	function imprimirpdf(url_pdf) {
		//console.log("ruta: "+url_pdf);
		var a = document.createElement("a");
		a.target = "_blank";
		a.href = url_pdf;
		a.click();
	}

</script>