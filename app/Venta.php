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
    public function scopelistar02($query, $fechai, $fechaf, $numero_serie, $estado, $tipo)
    {
        // $fechai = date("Y-m-d",strtotime($fechai."- 1 day"));
        $user = Auth::user();
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        return $query->where(function($subquery) use($fechai, $fechaf, $estado)
            {
               
            })
            ->whereBetween('fecha', [$fechai, $fechaf])
            ->where('estado','=',$estado)
            ->where('tipo_pago','=',$tipo)
            ->where('numero_doc','LIKE','%'.$numero_serie.'%')
            ->where('sucursal_id','=',$user->sucursal_id)
            ->orderBy('fecha', 'DSC');
    }

    public function scopelistar($query, $fechai, $fechaf, $numero_serie, $estado, $tipo, $doc_dni_ruc)
    {
        // $fechai = date("Y-m-d",strtotime($fechai."- 1 day"));
        $user = Auth::user();
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        // $doc_dni == ""? NULL: $doc_dni;
        // $doc_ruc == ""? NULL: $doc_ruc;
        return  DB::table('ventas')
        ->leftjoin('cliente', 'ventas.cliente_id', '=', 'cliente.id')
        ->select(
            'ventas.id as id',
            'ventas.serie_doc as serie_doc',
            'ventas.numero_doc as numero_doc',
            'cliente.nombres as nombres',
            'cliente.apellidos as apellidos',
            'cliente.razon_social as razon_social',
            'cliente.dni as dni',
            'cliente.ruc as ruc',
            'ventas.total as total',
            'ventas.subtotal as subtotal',
            'ventas.comprobante as comprobante',
            'ventas.tipo_pago as tipo_pago',
            'ventas.forma_pago as forma_pago',
            'ventas.fecha as fecha',
            'ventas.fecha_venc as fecha_venc',
            'ventas.dias as dias',
            'ventas.cliente_id as cliente_id',
            'ventas.sucursal_id as sucursal_id',
            'ventas.estado as estado'
            
        )
            ->whereBetween('ventas.fecha', [$fechai, $fechaf])
            ->where(function($subquery) use($doc_dni_ruc)
            {
                if (!is_null($doc_dni_ruc)) {
                    $subquery->where("cliente.ruc",'LIKE', '%'.$doc_dni_ruc.'%')->orwhere('cliente.dni','LIKE', '%'.$doc_dni_ruc.'%');
                }
            })
            ->where('ventas.estado','=',$estado)
            ->where('ventas.tipo_pago','=',$tipo)
            ->where('ventas.numero_doc','LIKE','%'.$numero_serie.'%')
            // ->where('cliente.ruc','LIKE','%'.$doc_ruc.'%')
            // ->where('cliente.dni','LIKE','%'.$doc_dni.'%')
            ->where('ventas.sucursal_id','=',$user->sucursal_id)
            ->orderBy('ventas.fecha', 'DSC');

        ///********************************* */
        return $query->where(function($subquery) use($nombre_ini)
        {
            if (!is_null($nombre_ini)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_ini.'%')->orwhere('apellidos','LIKE', '%'.$nombre_ini.'%')->orwhere('dni','LIKE', '%'.$nombre_ini.'%')->orwhere('iniciales','LIKE', '%'.$nombre_ini.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->orderBy('nombres', 'ASC');//->get();
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
    public static function prod_vendidos_medico($medico_id, $anio, $mes)
    {
        $user = Auth::user();
        // $fechai = date("Y-m-d",strtotime($fechai));
        // $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        return  DB::table('producto')
                ->leftjoin('detalle_ventas', 'detalle_ventas.producto_id', '=', 'producto.id')
                ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
                ->leftjoin('producto_presentacion', 'detalle_ventas.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->leftjoin('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                ->select(
                     'producto.descripcion as descripcion',
                     'producto.codigo as codigo',
                     'presentacion.nombre as nombre_presentacion',
                     'presentacion.sigla as sigla',
                     'producto_presentacion.cant_unidad_x_presentacion as cantidad_x',
                     'producto_presentacion.puntos as puntos',
                    DB::raw("SUM(detalle_ventas.cantidad) as cantidad_unidades"),
                    DB::raw("SUM(detalle_ventas.puntos_acumulados) as puntos_acumulados")
            )
                ->whereYear('ventas.fecha',$anio)
                ->whereMonth('ventas.fecha',$mes)
                ->where('ventas.sucursal_id','=',$user->sucursal_id)
                ->where('ventas.medico_id','=',$medico_id)
                ->groupBy('producto.id','producto.descripcion','producto_presentacion.id','presentacion.nombre','presentacion.sigla','producto_presentacion.cant_unidad_x_presentacion','producto_presentacion.puntos','producto.codigo')->get();
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

    public static function list_detalle_ventas2($venta_id){
        $user = Auth::user();
        return  DB::table('detalle_ventas')
        ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
        ->leftjoin('producto', 'detalle_ventas.producto_id', '=', 'producto.id')
        ->leftjoin('presentacion', 'producto.unidad_id', '=', 'presentacion.id')
        ->leftjoin('marca', 'producto.marca_id', '=', 'marca.id')
        ->leftjoin('producto_presentacion', 'producto_presentacion.id', '=', 'detalle_ventas.producto_presentacion_id')
        ->leftjoin('presentacion as present', 'present.id', '=', 'producto_presentacion.presentacion_id')
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
            'producto_presentacion.id as id_presentacion_v',
            'producto_presentacion.cant_unidad_x_presentacion as cantidad_por_presentacion',
            'present.nombre as nombre_presentacion',
            'present.sigla as sigla_presentacioon'
        )
        ->where('detalle_ventas.ventas_id', '=',$venta_id)
        ->where('ventas.sucursal_id','=',$user->sucursal_id)
        ->where('detalle_ventas.deleted_at', '=',null)->get();
    }

    public  static function puntos_acumulados_medico($anio, $mes){
        $user = Auth::user();

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
        
        ->whereYear('ventas.fecha',$anio)
        ->whereMonth('ventas.fecha',$mes)
        ->where('ventas.sucursal_id','=',$user->sucursal_id)
        ->where('detalle_ventas.deleted_at', '=',null)
        ->groupBy('medico.nombres','medico.apellidos','medico.codigo');


    }

    public  static function puntos_acumulados_medico_mes($anio, $mes){
        $user = Auth::user();
        // $fechai = date("Y-m-d",strtotime($fechai));
        // $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        
        
        return  DB::table('detalle_ventas')
        ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
        ->leftjoin('medico', 'ventas.medico_id', '=', 'medico.id')
        ->leftjoin('producto', 'detalle_ventas.producto_id', '=', 'producto.id')
        // ->leftjoin('producto_presentacion', 'producto_presentacion.id', '=', 'detalle_ventas.producto_presentacion_id')
        ->select(
            'medico.nombres as nombres_medico', 
            'medico.apellidos as apellidos_medico', 
            'medico.codigo as codigo_medico', 
            DB::raw('sum(detalle_ventas.puntos_acumulados) as puntos')
        )
        ->whereYear('ventas.fecha', $anio)
        ->whereMonth('ventas.fecha', $mes)
        ->where('ventas.sucursal_id','=',$user->sucursal_id)
        // ->where('ventas.sucursal_id','=',$user->sucursal_id)
        ->where('detalle_ventas.deleted_at', '=',null)
        ->groupBy('medico.nombres','medico.apellidos','medico.codigo');


    }

}
