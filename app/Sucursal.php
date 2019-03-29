<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class Sucursal extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='sucursal';

    protected $primaryKey='id';


    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $descripcion)
    {
        return $query->where(function($subquery) use($descripcion)
		            {
		            	if (!is_null($descripcion)) {
                            $user = Auth::user();
                            $empresa_id = $user->empresa_id;
                            $subquery->where('nombre', 'LIKE', '%'.$descripcion.'%')
                                     ->where('empresa_id', "=", $empresa_id);
		            	}else{
                            $user = Auth::user();
                            $empresa_id = $user->empresa_id;
                            $subquery->where('empresa_id', "=", $empresa_id);
		            	}
		            })
        			->orderBy('nombre', 'ASC');
    }
}
