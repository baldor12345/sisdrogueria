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

class Caja extends Model
{
    use SoftDeletes;
    protected $table = 'caja';
    protected $dates = ['deleted_at'];
    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    } 
    public function sucursal(){
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    } 

    public static function getIdUser()
    {
        $id = Auth::id();
        return $id;
    }

    public function scopelistar($query)
    {
        $user_id = Auth::id();
        return $query->where(function($subquery) use($user_id)
                    {
                      
                        $subquery->where('deleted_at', '=', null);
                        
                    })
                    ->where('sucursal_id','=',$user_id)
                    ->orderBy('fecha_horaapert', 'DSC');
    }

    public static function listardetallecaja($concepto, $cliente, $fechai, $fechaf){
        return  DB::table('detalle_caja')
                ->join('caja', 'detalle_caja.caja_id', '=', 'caja.id')
                ->join('user', 'caja.user_id', '=', 'user.id')
                ->leftJoin('cliente', 'detalle_caja.cliente_id', '=', 'cliente.id')
                ->join('forma_pago', 'detalle_caja.forma_pago_id', '=', 'forma_pago.id')
                ->join('comprobante', 'detalle_caja.comprobante_id', '=', 'comprobante.id')
                ->join('concepto', 'detalle_caja.concepto_id', '=', 'concepto.id')
                ->select(
                        'caja.num_caja as num_caja', 
                        'detalle_caja.fecha as fecha', 
                        'detalle_caja.id as id', 
                        'detalle_caja.numero_operacion as numero_operacion', 
                        'concepto.nombre as concepto_nombre', 
                        'cliente.nombres as cliente_nombres', 
                        'user.login as user_login', 
                        'cliente.apellidos as cliente_apellidos',
                        'detalle_caja.ingreso as ingreso', 
                        'detalle_caja.egreso as egreso',  
                        'forma_pago.nombre as forma_pago',  
                        'forma_pago.nombre as estado', 
                        'detalle_caja.comentario as comentario', 
                        'comprobante.nombre as comprobante_nombre'
                )
                ->where('concepto.id', 'LIKE','%'.$concepto.'%')
                ->where('cliente.nombres', 'LIKE','%'.$cliente.'%')
                ->where('detalle_caja.fecha', '>=', $fechai)
                ->where('detalle_caja.fecha', '<=', $fechaf)
                ->where('detalle_caja.deleted_at',null)
                ->orderBy('detalle_caja.fecha', 'DSC');
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
            $binnacle->table      = 'caja';
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
            $binnacle->table      = 'caja';
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
            $binnacle->table      = 'caja';
            $binnacle->detail    = $producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });
    }
}
