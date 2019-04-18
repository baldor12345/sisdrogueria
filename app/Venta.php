<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='ventas';

    protected $primaryKey='id';

    public function caja(){
        return $this->belongsTo('App\Caja','caja_id');
    } 
    public function sucursal(){
        return $this->belongsTo('App\Sucursal','sucursal_id');
    } 
    public function user(){
        return $this->belongsTo('App\User','user_id');
    } 
    public function cliente(){
        return $this->belongsTo('App\Cliente','cliente_id');
    } 

    // public function scopelistardetalle($query, $venta_id){
    //     return $query->where(function($subquery) use($fecha)
    //     {
    //         if (!is_null($fecha)) {
    //             $subquery->where('fecha', '>=', $fecha);
    //         }
    //     })
    //     ->orderBy('fecha', 'ASC');
    // }
    // public function comprobante(){
    //     return $this->belongsTo('App\Comprobante','comprobante_id');
    // } 
    // public function formapago(){
    //     return $this->belongsTo('App\FormaPago','forma_pago_id');
    // } 

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $fecha)
    {
        return $query->where(function($subquery) use($fecha)
            {
                if (!is_null($fecha)) {
                    $subquery->where('fecha', '>=', $fecha);
                }
            })
            ->orderBy('fecha', 'ASC');
    }
}
