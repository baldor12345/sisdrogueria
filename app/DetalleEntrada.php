<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleEntrada extends Model
{
    use SoftDeletes;
    protected $table = 'detalle_entrada';
    protected $dates = ['deleted_at'];
    
    public function entrada(){
        return $this->belongsTo('App\Entrada','entrada_id');
    } 
    public function producto(){
        return $this->belongsTo('App\Producto','producto_id');
    } 
    public function presentacion(){
        return $this->belongsTo('App\Presentacion','presentacion_id');
    } 
    
    public function scopelistar($query,$entrada_id)
    {
        return $query->where(function($subquery) use($entrada_id)
		            {
                        
                        if (!is_null($entrada_id)) {
                            $subquery->where('entrada_id', '=', $entrada_id)->where('deleted_at','=',null);
                        }
                        
                    })
                    ->where('deleted_at','=',null)
        			->orderBy('fecha', 'ASC');
        			
    }

}
