<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detalle_venta extends Model
{
    use SoftDeletes;
    protected $table = 'detalle_ventas';
    protected $dates = ['deleted_at'];
    
    public function venta(){
        return $this->belongsTo('App\Ventas','ventas_id');
    } 
    public function producto(){
        return $this->belongsTo('App\Producto','producto_id');
    } 
    
    public function scopelistar($query,$id_venta)
    {
        return $query->where(function($subquery) use($nombre)
		            {
                        
                        if (!is_null($id_venta)) {
                            $subquery->where('ventas_id', '=', $id_venta)->where('deleted_at','=',null);
                        }
                        
                    })
                    ->where('deleted_at','=',null)
        			->orderBy('nombres', 'ASC');
        			
    }

}
