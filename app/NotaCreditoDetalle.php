<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaCreditoDetalle extends Model
{
    use SoftDeletes;
    protected $table = 'nota_credito_detalles';
    protected $dates = ['deleted_at'];
    
    public function venta(){
        return $this->belongsTo('App\Ventas','ventas_id');
    } 
    public function producto(){
        return $this->belongsTo('App\Producto','producto_id');
    } 
    public function productopresentacion(){
        return $this->belongsTo('App\ProductoPresentacion','producto_presentacion_id');
    } 
    public function presentacion(){
        return $this->belongsTo('App\ProductoPresentacion','producto_presentacion_id');
    } 
}
