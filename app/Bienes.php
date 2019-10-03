<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Bienes extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='bienes';
    public function presentacion(){
        return $this->belongsTo('App\ProductoPresentacion', 'producto_presentacion_id');
    }
}
