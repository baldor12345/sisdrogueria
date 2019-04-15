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
                        ->join('producto', 'entrada.producto_id', '=', 'producto.id')
                        ->join('presentacion', 'producto.presentacion_id', '=', 'presentacion.id')
                        ->select(
                                'entrada.id as entrada_id',
                                'producto.descripcion as descripcion', 
                                'presentacion.nombre as presentacion', 
                                'entrada.fecha_caducidad as fecha_cad', 
                                'entrada.precio_venta as precio_venta', 
                                'entrada.lote as lote', 
                                'entrada.stock as stock'
                        )
                        ->where('producto.descripcion', 'LIKE','%'.$producto.'%')
                        ->where('presentacion.id', 'LIKE','%'.$presentacion.'%')
                        ->where('entrada.fecha_caducidad', '>=', $fechai)
                        ->where('entrada.fecha_caducidad', '<=', $fechaf)
                        ->where('entrada.deleted_at',null)
                        ->orderBy('entrada.deleted_at', 'DSC');
        }
            
    public static function listarlotescaducidad($lote, $fechai, $fechaf){
        return  DB::table('entrada')
                ->join('producto', 'entrada.producto_id', '=', 'producto.id')
                ->join('marca', 'producto.marca_id', '=', 'marca.id')
                ->select(
                        'producto.descripcion as producto', 
                        'entrada.lote as lote', 
                        'entrada.fecha_caducidad as fecha_venc', 
                        'entrada.stock as cantidad', 
                        'marca.name as laboratorio'
                )
                ->where('entrada.lote', 'LIKE','%'.$lote.'%')
                ->where('entrada.fecha_caducidad', '>=', $fechai)
                ->where('entrada.fecha_caducidad', '<=', $fechaf)
                ->where('entrada.deleted_at',null)
                ->orderBy('entrada.fecha_caducidad', 'DSC');
    }
    
    public static function listarstock_producto($descripcion, $presentacion_id){
        return  DB::table('entrada')
                ->join('producto', 'entrada.producto_id', '=', 'producto.id')
                ->join('presentacion', 'producto.presentacion_id', '=', 'presentacion.id')
                ->select(
                        'producto.descripcion as producto', 
                        'producto.stock_minimo as stock_minimo', 
                        'presentacion.nombre as presentacion', 
                        DB::raw('sum(entrada.stock) as stock')
                )
                ->where('producto.descripcion', 'LIKE','%'.$descripcion.'%')
                ->where('presentacion.id', 'LIKE','%'.$presentacion_id.'%')
                ->groupBy('presentacion.id','producto.descripcion','producto.stock_minimo','presentacion.nombre');
                //->orderBy('detalle_compra.fecha_caducidad', 'DSC');
    }

}
