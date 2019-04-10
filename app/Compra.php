<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;

class Compra extends Model
{
    use SoftDeletes;
    protected $table = 'compra';
    protected $dates = ['deleted_at'];
    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function producto(){
        return $this->belongsTo('App\Producto', 'producto_id');
    } 

    public function scopelistar($query, $titulo)
    {
        return $query->where(function($subquery) use($titulo)
                    {
                        if (!is_null($titulo)) {
                            $subquery->where('titulo', 'ILIKE', '%'.$titulo.'%');
                        }
                    })
                    ->orderBy('fecha_horaapert', 'DSC');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($producto)
        {

            $binnacle             = new Binnacle();
            $binnacle->action     = 'I';
            $binnacle->date      = date('Y-m-d H:i:s');
            $binnacle->ip         = Libreria::get_client_ip();
            $binnacle->user_id =  Auth::user()->id;
            $binnacle->table      = 'compra';
            $binnacle->detail    = $producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });

        static::updated(function($producto)
        {
            $binnacle             = new Binnacle();
            $binnacle->action     = 'U';
            $binnacle->date      = date('Y-m-d H:i:s');
            $binnacle->ip         = Libreria::get_client_ip();
            $binnacle->user_id = Auth::user()->id;
            $binnacle->table      = 'compra';
            $binnacle->detail    =$producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });
        static::deleted(function($producto)
        {
            $binnacle             = new Binnacle();
            $binnacle->action     = 'D';
            $binnacle->date      = date('Y-m-d H:i:s');
            $binnacle->ip         = Libreria::get_client_ip();
            $binnacle->user_id = Auth::user()->id;
            $binnacle->table      = 'compra';
            $binnacle->detail    = $producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });
    }


}
