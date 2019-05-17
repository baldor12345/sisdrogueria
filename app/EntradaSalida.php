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
class EntradaSalida extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table='entrada_salida';

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public static function listar($num_doc, $tipo, $fechai, $fechaf){
        return  DB::table('entrada_salida')
                    ->select(
                            'entrada_salida.id as es_id',
                            'entrada_salida.tipo as tipo', 
                            'entrada_salida.fecha as fecha', 
                            'entrada_salida.num_documento as num_documento', 
                            'entrada_salida.serie_documento as serie_documento', 
                            'entrada_salida.descripcion as descripcion'
                    )
                    ->where('entrada_salida.num_documento', 'LIKE','%'.$num_doc.'%')
                    ->where('entrada_salida.tipo', 'LIKE','%'.$tipo.'%')
                    ->where('entrada_salida.fecha', '>=', $fechai)
                    ->where('entrada_salida.fecha', '<=', $fechaf)
                    ->where('entrada_salida.deleted_at',null)
                    ->orderBy('entrada_salida.deleted_at', 'DSC');
    }

    public static function listdetalleES($id){
        return  DB::table('entrada_salida_detalle')
                ->join('producto_presentacion', 'entrada_salida_detalle.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->join('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                ->join('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                ->select(
                        'producto.descripcion as producto', 
                        'producto.sustancia_activa as sustancia_activa', 
                        'presentacion.nombre as presentacion', 
                        'entrada_salida_detalle.fecha_caducidad as fecha_caducidad', 
                        'entrada_salida_detalle.precio_compra as precio_compra', 
                        'entrada_salida_detalle.precio_venta as precio_venta', 
                        'entrada_salida_detalle.cantidad as cantidad', 
                        'entrada_salida_detalle.lote as lote'
                )
                ->where('entrada_salida_detalle.entrada_salida_id', $id)
                ->where('entrada_salida_detalle.deleted_at',null);
    }

}
