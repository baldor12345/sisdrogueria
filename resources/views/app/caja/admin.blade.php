<!-- Page-Title -->
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\Movimiento;
use App\OperacionMenu;
use App\Sucursal;

$user = Auth::user();
$sucursal = Sucursal::find($user->sucursal_id);
/*
SELECT operacion_menu.operacion_id
FROM  operacion_menu 
inner join permiso_operacion
on permiso_operacion.operacionmenu_id = operacion_menu.id
where po.usertype_id = 4 and om.menuoption_id = 9
*/
/*$operaciones = DB::table('operacion_menu')
					->join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.operacion_id')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', 6 ],
					])->get();*/
$opcionmenu = Menuoption::where('link','=',$entidad)->orderBy('id','ASC')->first();
$operaciones = OperacionMenu::join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.*')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', $opcionmenu->id ],
					])->get();					
$operacionesnombres = array();
foreach($operaciones as $key => $value){
	$operacionesnombres[] = $value->operacion_id;
}
/*
operaciones 
1 nuevo
2 editar
3 eliminar
*/
?>

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
			<div class="col-sm-12">
				{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
				{!! Form::hidden('page', 1, array('id' => 'page')) !!}
				{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
				<div class="form-group">
				@if($sucursal !== null)
					@if($user->usertype_id == 3)
						{!! Form::label('sucursal_id', 'Sucursal:') !!}
						{!! Form::text('sucursalnombre', $sucursal->nombre , array('class' => 'form-control input-xs', 'id' => 'sucursalnombre' , 'readOnly')) !!}
						{!! Form::hidden('sucursal_id', $user->sucursal_id , array('id' => 'sucursal_id')) !!}
					@else
						{!! Form::label('sucursal_id', 'Sucursal:') !!}
						{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_id' , 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					@endif
				@else
					{!! Form::label('sucursal_id', 'Sucursal:') !!}
					{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_id' , 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				@endif
				</div>
				<div class="form-group">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				{!! Form::close() !!}
			</div>
			<div id="listado{{ $entidad }}"></div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function () {
		if($(".btnNuevo").attr('activo')=== 'no'){
			$('.btnNuevo').attr("disabled", true);
		}
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
	});
	function pdf(entidad){
			window.open('tipousuario/pdf?descripcion='+$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').val());
	}
</script>