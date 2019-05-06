<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>

<div class="row">
		{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
		
		{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
		{!! Form::close() !!}
    <div class="col-3 col-md-2 col-sm-12">
	</div>
    <div class="col-6 col-md-8 col-sm-12">
        <div class="card-box">
			<div class="row m-b-30">
					<div id="listado{{ $entidad }}"></div>

					
			</div>
        </div>
	</div>
	<div class="col-3 col-md-2 col-sm-12">
	</div>
</div>

<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		
	});
</script>