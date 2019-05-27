<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
    protected $table = 'cliente';
    protected $dates = ['deleted_at'];
    
    public function scopelistar($query,$nombre_dni_ruc,$nombre="", $dni="")
    {
        
      

        return $query->where(function($subquery) use($nombre)
		            {
                        
                        if (!is_null($nombre)) {
                            $subquery->where('nombres', 'LIKE', '%'.$nombre.'%')->where('dni','=',$dni);
                        }
                        
                    })
                    ->where('deleted_at','=',null)
        			->orderBy('nombres', 'ASC');
        			
    }

    public function scopelistarclientes($query,$nombre_dni_ruc){
        return $query->where(function($subquery) use($nombre_dni_ruc)
        {
            if (!is_null($nombre_dni_ruc)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('apellidos','LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('razon_social','LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('dni','LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('ruc','LIKE', '%'.$nombre_dni_ruc.'%');
            }
        })
        ->where('deleted_at','=',null)
        // ->limit(5)
        ->orderBy('nombres', 'ASC')->get();
    }

}

