<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class Distrito extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='distrito';

    protected $primaryKey='id';
    
    public function provincia(){
        return $this->belongsTo('App\Provincia','provincia_id');
    } 

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $nombre)
    {
     
        return $query->where(function($subquery) use($nombre)
            {
                if (!is_null($nombre)) {
                    $subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
                }
            })
            ->orderBy('nombre', 'ASC');
    }

    public function listarDistritosProvincia($departamento_id){
        return $query->where(function($subquery) use($provincia_id)
        {
            if (!is_null($provincia_id)) {
                $subquery->where('provincia_id', '=', $provincia_id)->where('deleted_at','=',null);
            }
        })->orderBy('nombre', 'ASC');
    }

}
