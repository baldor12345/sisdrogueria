<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleSalida extends Model
{
    use SoftDeletes;
    protected $table = 'detalle_salida';
    protected $dates = ['deleted_at'];
    
    public function salida(){
        return $this->belongsTo('App\Salida','salida_id');
    } 
    public function producto(){
        return $this->belongsTo('App\Producto','producto_id');
    } 
    public function presentacion(){
        return $this->belongsTo('App\Presentacion','presentacion_id');
    } 
    
    public function scopelistar($query,$id_salida)
    {
        return $query->where(function($subquery) use($id_salida)
		            {
                        
                        if (!is_null($id_salida)) {
                            $subquery->where('salida_id', '=', $id_salida)->where('deleted_at','=',null);
                        }
                        
                    })
                    ->where('deleted_at','=',null)
        			->orderBy('fecha', 'ASC');
        			
    }

}
