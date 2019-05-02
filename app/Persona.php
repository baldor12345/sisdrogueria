<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    protected $table = 'person';
    protected $dates = ['deleted_at'];
    
    public function tipo_persona(){
        return $this->belongsTo('App\Tipo_persona','tipo_persona_id');
    } 
    
    public function scopelistar($query, $nombre, $dni)
    {
        return $query->where(function($subquery) use($nombre, $dni)
            {
                // if($tipo_persona_id == 0){
                //     $subquery->where('nombres', 'LIKE', '%'.$nombre.'%')->where('dni','=',$dni);//->where('tipo_persona_id','=', 1);
                // }else{
                    if (!is_null($nombre)) {
                        $subquery->where('nombres', 'LIKE', '%'.$nombre.'%')->where('dni','LIKE','%'.$dni.'%');
                    }
                // }
            })
            // ->where('tipo_persona_id','=',$tipo_persona_id)
            ->where('deleted_at','=',null)
            ->orderBy('nombres', 'ASC');
        			
    }
    
    public function scopelistarpersonas($query,$nombre_dni_ruc){
        return $query->where(function($subquery) use($nombre_dni_ruc)
        {
            if (!is_null($nombre_dni_ruc)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('apellidos','LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('dni','LIKE', '%'.$nombre_dni_ruc.'%')->orwhere('ruc','LIKE', '%'.$nombre_dni_ruc.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->limit(5)
        ->orderBy('nombres', 'ASC')->get();
    }

}

