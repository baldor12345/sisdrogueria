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
        public static function listar($numero, $fechai, $fechaf){
                return  DB::table('entrada')
                        ->leftJoin('proveedor', 'entrada.proveedor_id', '=', 'proveedor.id')
                        ->select(
                                'entrada.id as compra_id', 
                                'entrada.fecha as compra_fecha', 
                                'proveedor.nombre as proveedor_nombre',
                                'entrada.numero_documento as numero_documento',
                                'entrada.estado as estado',
                                'entrada.total as total'
                        )
                        ->where('entrada.numero_documento', 'LIKE','%'.$numero.'%')
                        ->where('entrada.fecha', '>=', $fechai)
                        ->where('entrada.fecha', '<=', $fechaf)
                        ->where('entrada.deleted_at',null)
                        ->orderBy('entrada.fecha', 'DSC');
        }

        public static function listardetalleentrada($id){
                return  DB::table('detalle_entrada')
                        ->join('producto', 'detalle_entrada.producto_id', '=', 'producto.id')
                        ->join('presentacion', 'detalle_entrada.presentacion_id', '=', 'presentacion.id')
                        ->select(
                                'producto.descripcion as descripcion', 
                                'detalle_entrada.fecha_caducidad as fecha_caducidad', 
                                'detalle_entrada.stock as cantidad', 
                                'presentacion.nombre as presentacion_nombre', 
                                'detalle_entrada.precio_compra as precio_compra'
                        )
                        ->where('detalle_entrada.entrada_id', '=', $id)
                        ->where('detalle_entrada.deleted_at',null);
        }
            
    public static function listarlotescaducidad($lote, $fechai, $fechaf){
        return  DB::table('detalle_compra')
                ->join('producto', 'detalle_compra.producto_id', '=', 'producto.id')
                ->join('marca', 'detalle_compra.marca_id', '=', 'marca.id')
                ->select(
                        'producto.descripcion as producto', 
                        'detalle_compra.lote as lote', 
                        'detalle_compra.fecha_caducidad as fecha_venc', 
                        'detalle_compra.cantidad as cantidad', 
                        'marca.name as laboratorio'
                )
                ->where('detalle_compra.lote', 'LIKE','%'.$lote.'%')
                ->where('detalle_compra.fecha_caducidad', '>=', $fechai)
                ->where('detalle_compra.fecha_caducidad', '<=', $fechaf)
                ->where('detalle_compra.deleted_at',null)
                ->orderBy('detalle_compra.fecha_caducidad', 'DSC');
    }
    
    public static function listarstock_producto($descripcion, $presentacion_id){
        return  DB::table('detalle_compra')
                ->join('producto', 'detalle_compra.producto_id', '=', 'producto.id')
                ->join('presentacion', 'detalle_compra.presentacion_id', '=', 'presentacion.id')
                ->select(
                        'producto.descripcion as producto', 
                        'producto.stock_minimo as stock_minimo', 
                        'presentacion.nombre as presentacion', 
                        DB::raw('sum(detalle_compra.cantidad) as stock')
                )
                ->where('producto.descripcion', 'LIKE','%'.$descripcion.'%')
                ->where('presentacion.id', 'LIKE','%'.$presentacion_id.'%')
                ->groupBy('presentacion.id','producto.descripcion','producto.stock_minimo','presentacion.nombre','detalle_compra.cantidad');
                //->orderBy('detalle_compra.fecha_caducidad', 'DSC');
    }

}
