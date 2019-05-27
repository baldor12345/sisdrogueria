<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medico extends Model
{
    use SoftDeletes;
    protected $table = 'medico';
    protected $dates = ['deleted_at'];
    
    public function scopelistar($query,$nombre_dni_cod)
    {
        
        return $query->where(function($subquery) use($nombre_dni_cod)
        {
            if (!is_null($nombre_dni_cod)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_dni_cod.'%')->orwhere('apellidos','LIKE', '%'.$nombre_dni_cod.'%')->orwhere('dni','LIKE', '%'.$nombre_dni_cod.'%')->orwhere('codigo','LIKE', '%'.$nombre_dni_cod.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->orderBy('nombres', 'ASC');//->get();
        			
    }

    public function scopelistarmedicos($query,$nombre_dni_cod){
        return $query->where(function($subquery)use($nombre_dni_ruc)
        {
            if (!is_null($nombre_dni_cod)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_dni_cod.'%')->orwhere('apellidos','LIKE', '%'.$nombre_dni_cod.'%')->orwhere('dni','LIKE', '%'.$nombre_dni_cod.'%')->orwhere('codigo','LIKE', '%'.$nombre_dni_cod.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->limit(5)
        ->orderBy('nombres', 'ASC')->get();
    }

}

