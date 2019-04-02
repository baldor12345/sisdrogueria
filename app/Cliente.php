<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
    protected $table = 'cliente';
    protected $dates = ['deleted_at'];
    
    public function scopelistar($query,$nombre, $dni)
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

}

