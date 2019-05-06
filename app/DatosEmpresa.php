<?php

namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatosEmpresa extends Model
{
    use SoftDeletes;
    protected $table = 'datos_empresa';
    protected $dates = ['deleted_at'];
    public function distrito(){
        return $this->belongsTo('App\Distrito','distrito_id');
    } 
    public function provincia(){
        return $this->belongsTo('App\Provincia','provincia_id');
    } 
    public function departamento(){
        return $this->belongsTo('App\Departamento','departamento_id');
    } 
}
