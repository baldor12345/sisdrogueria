<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='ventas';

    protected $primaryKey='id';

    public function caja(){
        return $this->belongsTo('App\Caja','caja_id');
    } 

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $fecha)
    {
        return $query->where(function($subquery) use($fecha)
		            {
		            	if (!is_null($fecha)) {
		            		$subquery->where('fecha_hora', '>=', $fecha);
		            	}
		            })
        			->orderBy('fecha_hora', 'ASC');
    }
}
