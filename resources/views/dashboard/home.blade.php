<?php
use Illuminate\Support\Facades\Auth;
use App\Sucursal;
$user = Auth::user();
if($user->fecha_sucursal != null){
    $fecha_sucursal = $user->fecha_sucursal;
    $fecha_sucursal = date("d-m-Y", strtotime($fecha_sucursal));
}
$hoy =new \Datetime();
$hoy = $hoy->format('d-m-Y');
//$hora =new \Datetime();
//$hora = $hora->format('d/m/Y h:i:s a');
$cant_sucursal = Sucursal::where('empresa_id',$user->empresa_id)->count('id');
?>
@include('dashboard.header_start')

{!! Html::style('plugins/jquery-circliful/css/jquery.circliful.css') !!}

@include('dashboard.header_end')

{{-- aquí va el contenido --}}

@include('dashboard.footer_start')

@include('dashboard.footer_end')

@if($user->usertype_id == 3)
<script>
$(document).ready(function(){
    @if($cant_sucursal != 1)
        @if($user->fecha_sucursal != null)
            @if($fecha_sucursal == $hoy)
                cargarRutaMenu('caja', 'container', '15');
            @else
                cargarRutaMenu('usuario/escogerSucursal', 'container', '15');
            @endif
        @else
            cargarRutaMenu('usuario/escogerSucursal', 'container', '15');
        @endif
    @else
        cargarRutaMenu('caja', 'container', '15');
    @endif
});
</script>
@endif