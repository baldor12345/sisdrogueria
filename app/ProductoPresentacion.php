<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;

class ProductoPresentacion extends Model
{
    use SoftDeletes;
    protected $table='producto_presentacion';

    protected $primaryKey='id';

    public function presentacion(){
        return $this->belongsTo('App\Presentacion','presentacion_id');
    } 
    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */

}
