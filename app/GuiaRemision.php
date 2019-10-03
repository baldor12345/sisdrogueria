<?php

namespace App;
use App\Librerias\Libreria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GuiaRemision extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='guia_remisions';
    public function scopelistar($query, $fechai, $fechaf, $numero_serie, $doc)
    {
        // $fechai = date("Y-m-d",strtotime($fechai."- 1 day"));
        $user = Auth::user();
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        return $query->where(function($subquery) use($fechai, $fechaf)
            {
               
            })
            ->whereBetween('fecha_emision', [$fechai, $fechaf])
            ->where('serie','LIKE','%'.$numero_serie.'%')
            ->where('doc_identidad','LIKE','%'.$numero_serie.'%')
            ->where('sucursal_id','=',$user->sucursal_id)
            ->orderBy('fecha_emision', 'DSC');
    }


}

