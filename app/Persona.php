<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    protected $table = 'person';
    protected $dates = ['deleted_at'];
    

    
    public function scopelistar($query, $name)
    {
        return $query->where(function($subquery) use($name)
		            {
		            	if (!is_null($name)) {
		            		$subquery->where('name', 'LIKE', '%'.$name.'%');
		            	}
		            })
        			->orderBy('menuoptioncategory_id', 'ASC')
        			->orderBy('order', 'ASC');
    }
/*
    public function scopelistar($query, $name, $type)
    {   
        $sql = "(CONCAT(apellidos,'',nombres) LIKE '%.$name.%' OR RAZONSOCIAL LIKE '%.$name.%')";
        $sql = $sql." AND TYPE = '.$type.' AND SECONDTYPE IN('N','S') OR CASE '.$type.' WHEN 'C' THEN (TYPE IN ('P','T') AND SECONDTYPE = 'S') WHEN 'P' THEN (TYPE = 'C' AND SECONDTYPE = 'S') ELSE (TYPE = 'C' AND SECONDTYPE = 'S') END";
        //echo $sql;        
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        return $query->where(function($subquery) use($name)
		            {
		            	if (!is_null($name)) {
                            $subquery->where(DB::raw('CONCAT(apellidos," ",nombres)'), 'LIKE', '%'.$name.'%')->orWhere('razonsocial','LIKE','%'.$name.'%');
		            	}
                    })
                    ->leftJoin('persona', 'personamaestro.id', '=', 'persona.personamaestro_id')
        			->where(function($subquery) use($type)
		            {
		            	if (!is_null($type)) {
                            //$subquery->where('type', '=', $type)->orWhere('secondtype','=','S');
                            //$IN = " ('P','T')";
                            if($type == 'C'){
                                $subquery->where('persona.type', '=', $type)->orwhere('persona.secondtype','=', $type)->orwhere('persona.type','=', 'T');
                            }else if($type == 'P'){
                                $subquery->where('persona.type', '=', $type)->orwhere('persona.secondtype','=', $type)->orwhere('persona.type','=', 'T');
                            }else if($type == 'E'){
                                $subquery->where('persona.type', '=', $type)->orwhere('persona.secondtype','=', $type)->orwhere('persona.type','=', 'T');
                            }
                        }		            		
                    })
                    ->where('persona.empresa_id', '=', $empresa_id)
                    ->where('persona.personamaestro_id', '!=', 2)
                    ->whereNull('personamaestro.deleted_at')
                    ->orderBy('nombres', 'ASC')->orderBy('apellidos', 'ASC')->orderBy('razonsocial', 'ASC');                   
    }*/

}

