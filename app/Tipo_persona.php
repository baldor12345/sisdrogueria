<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class Tipo_persona extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='tipo_persona';

    protected $primaryKey='id';
    
    // public function departamento(){
    //     return $this->belongsTo('App\Departamento','departamento_id');
    // } 

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $titulo)
    {
        return $query->where(function($subquery) use($titulo)
		            {
		            	if (!is_null($titulo)) {
		            		$subquery->where('titulo', 'LIKE', '%'.$titulo.'%');
		            	}
		            })
        			->orderBy('titulo', 'ASC');
    }
}
