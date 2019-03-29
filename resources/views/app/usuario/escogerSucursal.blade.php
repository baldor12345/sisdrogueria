<!-- Page-Title -->
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\Movimiento;
use App\OperacionMenu;
$user = Auth::user();
if($user->fecha_sucursal != null){
    $fecha_sucursal = $user->fecha_sucursal;
    $fecha_sucursal = date("d-m-Y", strtotime($fecha_sucursal));
}
$hoy =new \Datetime();
$hoy = $hoy->format('d-m-Y');

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
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>


<div class="row" style="background: rgba(51,122,183,0.10);">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
			<div class="col-sm-12">
				{!! Form::open(['route' => $ruta["guardarSucursal"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
				<div class="form-group">
					{!! Form::label('sucursal_id', 'Sucursal:') !!}
					{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-md', 'id' => 'sucursal_id' , 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnGuardarSucursal', 'onclick' => 'guardarSucursal();')) !!}
				{!! Form::close() !!}
			</div>
        </div>
    </div>
</div>
<script>
	function guardarSucursal(){
		var sucursal_id = $('#sucursal_id').val();
		var respuesta="";
		var hoy = '{{$hoy}}';
		var ajax = $.ajax({
			"method": "POST",
			"url": "{{ url('/usuario/guardarSucursal') }}",
			"data": {
				"sucursal_id" : sucursal_id, 
				"hoy" : hoy,
				"_token": "{{ csrf_token() }}",
				}
		}).done(function(info){
			respuesta = info;
		}).always(function(){
			if(respuesta == "OK"){
				cargarRutaMenu('caja', 'container', '15');
				guardarSucursalCorrecto();
			}
		});
	}
</script>