<?php
namespace App;

use DateTime;
use App\Librerias\Libreria;
// use App\ProductoPresentacion;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Reminders\RemindableInterface;
class Entrada extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table='entrada';
    public function productoPresentacion(){
        return $this->belongsTo('App\ProductoPresentacion','producto_presentacion_id');
    } 
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

    public static function idEntrada($producto_id, $lote){
        $result =  DB::table('entrada')
                ->leftjoin('producto_presentacion', 'entrada.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->select(
                    'entrada.id as id'
                )
                ->where('producto_presentacion.producto_id', '=',$producto_id)
                ->where('entrada.lote', '=',$lote)->get()[0];
        return $result->id;
    }

    public function scopelistarsalida($query,$term){
        return $query->where(function($subquery) use($term)
        {
            if (!is_null($term)) {
                $subquery->join('producto_presentacion','entrada.producto_presentacion_id','producto_presentacion.id')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto.unidad_id','presentacion.id')
                    ->select(
                        'producto_presentacion.id as producto_id',
                        'entrada.id as entrada_id',
                        'producto.descripcion as descripcion',
                        'producto.sustancia_activa as sustancia_activa',
                        'presentacion.nombre as presentacion',
                        'entrada.lote as lote'
                        )
                    //->where("producto.codigo",'LIKE', '%'.$term.'%')
                    //->orWhere("producto.codigo_barra",'LIKE', '%'.$term.'%')
                    ->orWhere("producto.descripcion",'LIKE', '%'.$term.'%')
                    ->orWhere("producto.sustancia_activa",'LIKE', '%'.$term.'%')
                    ->orWhere("entrada.lote",'LIKE', '%'.$term.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->limit(5)->get();
    }
   


}
