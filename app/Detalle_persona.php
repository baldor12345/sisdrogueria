<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detalle_persona extends Model
{
    use SoftDeletes;
    protected $table = 'detalle_person';
    protected $dates = ['deleted_at'];
    
    public function person(){
        return $this->belongsTo('App\Persona','person_id');
    } 
    
    public function scopelistar($query,$nombre)
    {
        // return $query->where(function($subquery) use($nombre)
		//             {
                        
        //                 if (!is_null($nombre)) {
        //                     $subquery->where('nombres', 'LIKE', '%'.$nombre.'%')->where('dni','=',$dni)->where('tipo_persona_id','=',$tipo_persona_id);
        //                 }
                        
        //             })
        //             ->where('deleted_at','=',null)
        // 			->orderBy('nombres', 'ASC');
        			
    }

}

