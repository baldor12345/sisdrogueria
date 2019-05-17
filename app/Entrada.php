<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;
use DateTime;
class Entrada extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table='entrada';

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $descripcion)
    {
        return $query->where(function($subquery) use($descripcion)
		            {
		            	if (!is_null($descripcion)) {
		            		$subquery->where('name', 'LIKE', '%'.$descripcion.'%');
		            	}
		            })
        			->orderBy('name', 'ASC');
    }

    public static function listEntradas($producto_id){
        return  DB::table('entrada')
                ->leftjoin('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->leftjoin('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                ->select(
                    'entrada.id as id', 
                    'entrada.lote as lote', 
                    // 'entrada.precio_venta as precio_venta', 
                    'entrada.fecha_caducidad as fecha_caducidad', 
                    'entrada.estado as estado', 
                    //  'entrada.presentacion_id as presentacion_id', 
                    'entrada.producto_presentacion_id as producto_presentacion_id', 
                    'entrada.stock as stock',
                    'entrada.sucursal_id as sucursal_id'
            )
                ->where('producto_presentacion.producto_id', '=',$producto_id)
                ->where('entrada.stock', '>',0)
                ->orderBy('entrada.fecha_caducidad', 'ASC')->get();
    }
}
