<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendedor extends Model
{
    use SoftDeletes;
    protected $table = 'vendedor';
    protected $dates = ['deleted_at'];
    
    public function scopelistar($query,$nombre_ini)
    {
        
        return $query->where(function($subquery) use($nombre_ini)
        {
            if (!is_null($nombre_ini)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_ini.'%')->orwhere('apellidos','LIKE', '%'.$nombre_ini.'%')->orwhere('dni','LIKE', '%'.$nombre_ini.'%')->orwhere('iniciales','LIKE', '%'.$nombre_ini.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->orderBy('nombres', 'ASC');//->get();
        			
    }

    public function scopelistarvendedores($query,$nombre_ini){
        return $query->where(function($subquery)use($nombre_ini)
        {
            if (!is_null($nombre_ini)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_ini.'%')->orwhere('apellidos','LIKE', '%'.$nombre_ini.'%')->orwhere('dni','LIKE', '%'.$nombre_ini.'%')->orwhere('iniciales','LIKE', '%'.$nombre_ini.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->limit(5)
        ->orderBy('nombres', 'ASC')->get();
    }

}

