<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public function scopelistar($query, $fecha)
    {
        return $query->where(function($subquery) use($fecha)
            {
                if (!is_null($fecha)) {
                    $subquery->where('fecha', '>=', $fecha);
                }
            })
            ->orderBy('fecha', 'ASC');
    }

    public static function listarentradas( $producto_id){
        return  DB::table('entrada')
                ->leftjoin('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->select(
                    'entrada.id as id', 
                    'entrada.lote as lote', 
                    'entrada.precio_venta as precio_venta', 
                    'entrada.fecha_caducidad as fecha_caducidad', 
                    'entrada.estado as estado', 
                    'entrada.presentacion_id as presentacion_id', 
                    'entrada.producto_presentacion_id as producto_presentacion_id', 
                    'entrada.stock as stock',
                    'entrada.sucursal_id as sucursal_id'
            )
                ->where('producto_presentacion.producto_id', '=',$producto_id)
                ->where('entrada.stock', '>',0)
                ->orderBy('entrada.fecha_caducidad', 'ASC')->get();
    }
    public static function list_detalle_ventas($venta_id){
        return  DB::table('detalle_ventas')
        ->leftjoin('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
        ->leftjoin('producto', 'detalle_ventas.producto_id', '=', 'producto.id')
        ->leftjoin('marca', 'producto.marca_id', '=', 'marca.id')
        ->select(
            'producto.descripcion as nombre_producto', 
            'producto.sustancia_activa as sustancia_activa', 
            'detalle_ventas.lotes as lotes', 
            'detalle_ventas.cantidad as cantidad', 
            'detalle_ventas.precio_unitario as precio_unitario', 
            'detalle_ventas.total as subtotal',         
            'marca.name as nombre_marca'         
        )
        ->where('detalle_ventas.ventas_id', '=',$venta_id)
        ->where('detalle_ventas.deleted_at', '=',null)->get();
    }

}
