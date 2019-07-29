<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;


class MantenimientoProducto extends Model
{
        public static function listar($producto, $presentacion, $fechai, $fechaf){
                return  DB::table('entrada')
                        ->join('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                        ->join('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                        ->join('presentacion', 'entrada.presentacion_id', '=', 'presentacion.id')
                        ->select(
                                'entrada.id as entrada_id',
                                'producto.descripcion as descripcion', 
                                'presentacion.nombre as presentacion', 
                                'entrada.fecha_caducidad as fecha_cad', 
                                'entrada.fecha_caducidad_string as fecha_cad_string', 
                                'entrada.precio_venta as precio_venta', 
                                'entrada.lote as lote', 
                                'entrada.stock as stock'
                        )
                        ->where('producto.descripcion', 'LIKE','%'.$producto.'%')
                        ->where('producto_presentacion.presentacion_id', 'LIKE','%'.$presentacion.'%')
                        ->where('entrada.fecha_caducidad', '>=', $fechai)
                        ->where('entrada.fecha_caducidad', '<=', $fechaf)
                        ->where('entrada.deleted_at',null)
                        ->orderBy('entrada.deleted_at', 'DSC');
        }
            
    public static function listarlotescaducidad($lote, $fechai, $fechaf){
        $user = Auth::user();
        return  DB::table('entrada')
                ->join('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->join('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                ->join('marca', 'producto.marca_id', '=', 'marca.id')
                ->select(
                        'producto.descripcion as producto', 
                        'entrada.lote as lote', 
                        'entrada.fecha_caducidad as fecha_venc', 
                        'entrada.fecha_caducidad_string as fecha_venc_string', 
                        'entrada.stock as cantidad', 
                        'marca.name as laboratorio'
                )
                ->where('entrada.lote', 'LIKE','%'.$lote.'%')
                ->where('entrada.stock', '!=',0)
                ->where('entrada.sucursal_id', '=',$user->sucursal_id)
                ->where('entrada.fecha_caducidad', '>=', $fechai)
                ->where('entrada.fecha_caducidad', '<=', $fechaf)
                ->where('entrada.deleted_at',null)
                ->orderBy('entrada.fecha_caducidad', 'DSC');
    }
    
    public static function listarstock_producto($descripcion, $presentacion_id){
        $user = Auth::user();
        return  DB::table('entrada')
                ->join('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->join('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                // ->join('presentacion', 'producto.unidad_id', '=', 'presentacion.id')
                ->select(
                        'producto.id as producto_id',
                        // 'producto_presentacion.id as ppe_id',
                        
                        'producto.descripcion as producto',
                        'producto.stock_minimo as stock_minimo',
                        // 'presentacion.nombre as presentacion', 
                        
                        DB::raw('sum(entrada.stock) as stock')
                )
                ->where('producto.descripcion', 'LIKE','%'.$descripcion.'%')
                // ->where('producto.unidad_id', 'LIKE','%'.$presentacion_id.'%')
                ->where('entrada.stock', '!=',0)
                ->where('entrada.sucursal_id', '=',$user->sucursal_id)
                ->where('entrada.deleted_at',null)
                ->groupBy('producto.id','producto.descripcion','producto.stock_minimo');
                // ->groupBy('presentacion.id','producto.id','producto.descripcion','producto.stock_minimo','presentacion.nombre','producto_presentacion.id');
                //->orderBy('detalle_compra.fecha_caducidad', 'DSC');
    }

}
