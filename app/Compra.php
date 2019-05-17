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

class Compra extends Model
{
    use SoftDeletes;
    protected $table = 'compra';
    protected $dates = ['deleted_at'];
    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    } 

    public function scopelistar($query, $titulo)
    {
        return $query->where(function($subquery) use($titulo)
                    {
                        if (!is_null($titulo)) {
                            $subquery->where('titulo', 'ILIKE', '%'.$titulo.'%');
                        }
                    })
                    ->orderBy('fecha_horaapert', 'DSC');
    }

    public static function listarcompra($numero, $proveedor, $fechai, $fechaf){
        return  DB::table('compra')
                ->join('proveedor', 'compra.proveedor_id', '=', 'proveedor.id')
                ->select(
                        'compra.id as compra_id', 
                        'compra.fecha as compra_fecha', 
                        'proveedor.nombre as proveedor_nombre',
                        'compra.serie_documento as serie_documento',
                        'compra.numero_documento as numero_documento',
                        'compra.estado as estado',
                        'compra.tipo_pago as tipo_pago',
                        'compra.total as total'
                )
                ->where('compra.numero_documento', 'LIKE','%'.$numero.'%')
                ->where('proveedor.nombre', 'LIKE','%'.$proveedor.'%')
                ->where('compra.fecha', '>=', $fechai)
                ->where('compra.fecha', '<=', $fechaf)
                ->where('compra.deleted_at',null)
                ->orderBy('compra.fecha', 'DSC');
    }
    public static function listardetallecompra($id){
        return  DB::table('detalle_compra')
                ->join('producto_presentacion', 'detalle_compra.producto_presentacion_id', '=', 'producto_presentacion.id')
                ->join('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                ->join('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                ->select(
                        'producto.descripcion as descripcion', 
                        'producto.sustancia_activa as sustancia_activa', 
                        'detalle_compra.fecha_caducidad as fecha_caducidad', 
                        'detalle_compra.cantidad as cantidad', 
                        'presentacion.nombre as presentacion_nombre', 
                        'detalle_compra.precio_compra as precio_compra'
                )
                ->where('detalle_compra.compra_id', '=', $id)
                ->where('detalle_compra.deleted_at',null);
    }


    public static function boot()
    {
        parent::boot();

        static::created(function($producto)
        {

            $binnacle             = new Binnacle();
            $binnacle->action     = 'I';
            $binnacle->date      = date('Y-m-d H:i:s');
            $binnacle->ip         = Libreria::get_client_ip();
            $binnacle->user_id =  Auth::user()->id;
            $binnacle->table      = 'compra';
            $binnacle->detail    = $producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });

        static::updated(function($producto)
        {
            $binnacle             = new Binnacle();
            $binnacle->action     = 'U';
            $binnacle->date      = date('Y-m-d H:i:s');
            $binnacle->ip         = Libreria::get_client_ip();
            $binnacle->user_id = Auth::user()->id;
            $binnacle->table      = 'compra';
            $binnacle->detail    =$producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });
        static::deleted(function($producto)
        {
            $binnacle             = new Binnacle();
            $binnacle->action     = 'D';
            $binnacle->date      = date('Y-m-d H:i:s');
            $binnacle->ip         = Libreria::get_client_ip();
            $binnacle->user_id = Auth::user()->id;
            $binnacle->table      = 'compra';
            $binnacle->detail    = $producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });
    }


}
