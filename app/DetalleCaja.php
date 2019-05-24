<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCaja extends Model
{
    use SoftDeletes;
    protected $table = 'detalle_caja';
    protected $dates = ['deleted_at'];
    
    public function comprobante(){
        return $this->belongsTo('App\Comprobante','comprobante_id');
    } 
    public function concepto(){
        return $this->belongsTo('App\Concepto','concepto_id');
    } 
    public function forma_pago(){
        return $this->belongsTo('App\FormaPago','forma_pago_id');
    } 
    public function cliente(){
        return $this->belongsTo('App\Cliente','cliente_id');
    } 
    public function personal(){
        return $this->belongsTo('App\Person','personal_id');
    } 
    
    public function scopelistar($query,$caja_id)
    {
        return $query->where(function($subquery) use($caja_id)
		            {
                        if (!is_null($caja_id)) {
                            $subquery->where('caja_id', '=', $caja_id)->where('deleted_at','=',null);
                        }
                    })
                    ->where('deleted_at','=',null)
        			->orderBy('fecha', 'ASC');
        			
    }

}
