<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class Venta extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='ventas';

    protected $primaryKey='id';

    public function caja(){
        return $this->belongsTo('App\Caja','caja_id');
    } 
    public function sucursal(){
        return $this->belongsTo('App\Sucursal','sucursal_id');
    } 
    public function user(){
        return $this->belongsTo('App\User','user_id');
    } 
    public function cliente(){
        return $this->belongsTo('App\Cliente','cliente_id');
    } 
    public function medico(){
        return $this->belongsTo('App\Medico','medico_id');
    } 
    public function vendedor(){
        return $this->belongsTo('App\Vendedor','vendedor_id');
    } 

    // public function scopelistardetalle($query, $venta_id){
    //     return $query->where(function($subquery) use($fecha)
    //     {
    //         if (!is_null($fecha)) {
    //             $subquery->where('fecha', '>=', $fecha);
    //         }
    //     })
    //     ->orderBy('fecha', 'ASC');
    // }
    // public function comprobante(){
    //     return $this->belongsTo('App\Comprobante','comprobante_id');
    // } 
    // public function formapago(){
    //     return $this->belongsTo('App\FormaPago','forma_pago_id');
    // } 

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $fechai, $fechaf, $numero_serie, $estado, $tipo)
    {
        // $fechai = date("Y-m-d",strtotime($fechai."- 1 day"));
        $user = Auth::user();
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        return $query->where(function($subquery) use($fechai, $fechaf, $estado)
            {
                if (!is_null($fechai)) {
                    
                }
            })
            ->whereBetween('fecha', [$fechai, $fechaf])
            ->where('estado','=',$estado)
            ->where('tipo_pago','=',$tipo)
            ->where('numero_doc','LIKE','%'.$numero_serie.'%')
            ->where('sucursal_id','=',$user->sucursal_id)
            ->orderBy('fecha', 'DSC');
    }

    public static function listarproductosvendidos($nombre, $fechai, $fechaf)
    {
        $user = Auth::user();
        $fechai = date("Y-m-d",strtotime($fechai));
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        return  DB::table('producto')
                ->leftjoin('detalle_ventas', 'detalle_ventas.producto_id', '=', 'producto.id')
                ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
                ->leftjoin('producto_presentacion', 'detalle_ventas.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->leftjoin('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                ->select(
                     'producto.descripcion as descripcion',
                     'presentacion.nombre as nombre_presentacion',
                     'presentacion.sigla as sigla',
                     'producto_presentacion.cant_unidad_x_presentacion as cantidad_x',
                    
                    // DB::raw("SUM(detalle_ventas.cantidad * producto_presentacion.cant_unidad_x_presentacion) as cantidad_unidades")
                    DB::raw("SUM(detalle_ventas.cantidad) as cantidad_unidades")
                    // DB::raw("SUM(detalle_ventas.cantidad) as cantidad_unidades")
            )
                ->where('ventas.fecha', '>=',$fechai)
                ->where('ventas.fecha', '<=',$fechaf)
                ->where('ventas.sucursal_id','=',$user->sucursal_id)
                // ->where('producto.descripcion', 'LIKE','%'.$nombre.'%')
                ->groupBy('producto.id','producto.descripcion','producto_presentacion.id','presentacion.nombre','presentacion.sigla','producto_presentacion.cant_unidad_x_presentacion');
                // ->orderBy('cantidad_unidades', 'ASC')->get();
    }

    public static function listarentradas( $producto_id){
        $user = Auth::user();
        return  DB::table('entrada')
                ->leftjoin('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->leftjoin('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                ->select(
                    'entrada.id as id', 
                    'entrada.lote as lote', 
                    // 'entrada.precio_venta as precio_venta', 
                    'entrada.fecha_caducidad as fecha_caducidad', 
                    'entrada.fecha_caducidad_string as fecha_caducidad_string', 
                    'entrada.estado as estado', 
                    //  'entrada.presentacion_id as presentacion_id', 
                    'entrada.producto_presentacion_id as producto_presentacion_id', 
                    'entrada.stock as stock',
                    'entrada.sucursal_id as sucursal_id'
            )
                ->where('producto_presentacion.producto_id', '=',$producto_id)
                ->where('entrada.stock', '>',0)
                ->where('entrada.sucursal_id','=',$user->sucursal_id)
                ->orderBy('entrada.fecha_caducidad', 'ASC')->get();
    }
    public static function list_detalle_ventas($venta_id){
        $user = Auth::user();
        return  DB::table('detalle_ventas')
        ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
        ->leftjoin('producto', 'detalle_ventas.producto_id', '=', 'producto.id')
        ->leftjoin('presentacion', 'producto.unidad_id', '=', 'presentacion.id')
        ->leftjoin('marca', 'producto.marca_id', '=', 'marca.id')
        ->leftjoin('producto_presentacion', 'producto_presentacion.id', '=', 'detalle_ventas.producto_presentacion_id')
        ->select(
            'producto.descripcion as nombre_producto', 
            'producto.sustancia_activa as sustancia_activa', 
            'detalle_ventas.lotes as lotes', 
            'detalle_ventas.cantidad as cantidad', 
            'detalle_ventas.precio_unitario as precio_unitario', 
            'detalle_ventas.total as subtotal',
            'marca.name as nombre_marca',
            'producto.afecto as afecto',  
            'presentacion.nombre as unidad_base',
            'producto_presentacion.id as id_presentacion_v'
        )
        ->where('detalle_ventas.ventas_id', '=',$venta_id)
        ->where('ventas.sucursal_id','=',$user->sucursal_id)
        ->where('detalle_ventas.deleted_at', '=',null)->get();
    }

    public  static function puntos_acumulados_medico($fechai, $fechaf, $tipobusqueda){
        $user = Auth::user();
        $fecha1 = null;
        $fecha2 = null;
        $fechai = date("Y-m-d",strtotime($fechai));
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        
        if($tipobusqueda == 'dia'){
            $fecha1 = date('d', strtotime($fechai));
        }else if($tipobusqueda == 'mes'){
            $fecha1 = date('m', strtotime($fechai));
        }else if($tipobusqueda == 'anio'){
            $fecha1 = date('Y', strtotime($fechai));
        }

        return  DB::table('detalle_ventas')
        ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
        ->leftjoin('medico', 'ventas.medico_id', '=', 'medico.id')
        // ->leftjoin('producto', 'detalle_ventas.producto_id', '=', 'producto.id')
        ->leftjoin('producto_presentacion', 'producto_presentacion.id', '=', 'detalle_ventas.producto_presentacion_id')
        ->select(
            'medico.nombres as nombres_medico', 
            'medico.apellidos as apellidos_medico', 
            'medico.codigo as codigo_medico', 
            DB::raw('sum(detalle_ventas.puntos_acumulados) as puntos')
        )
        ->whereBetween('ventas.fecha', [$fechai, $fechaf])
        // ->where('ventas.fecha', '>=',$fechai)
        // ->where('ventas.fecha', '<=',$fechaf)
        ->where('ventas.sucursal_id','=',$user->sucursal_id)
        // ->where('ventas.sucursal_id','=',$user->sucursal_id)
        ->where('detalle_ventas.deleted_at', '=',null)
        ->groupBy('medico.nombres','medico.apellidos','medico.codigo');


    }

}
