<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleVentaLote extends Model
{
    use SoftDeletes;
    protected $table = 'detalleventa_lote';
    protected $dates = ['deleted_at'];
    
    public function detalleventa(){
        return $this->belongsTo('App\Detalle_venta','detalle_venta_id');
    } 
    public function entrada(){
        return $this->belongsTo('App\Entrada','entrada_id');
    } 
    
    public function scopelistar($query,$detalleventa_id)
    {
        return $query->where(function($subquery) use($detalleventa_id)
            {
                if (!is_null($detalleventa_id)) {
                    $subquery->where('detalle_venta_id', '=', $detalleventa_id);
                }
            })
            ->where('deleted_at','=',null)
            ->orderBy('nombre', 'ASC');
        			
    }

    // public function scopelistar($query)
    // {
    //     return $query->where(function($subquery)
	// 	            {
                        
    //                     // if (!is_null($id_venta)) {
    //                     //     $subquery->where('ventas_id', '=', $id_venta)->where('deleted_at','=',null);
    //                     // }
                        
    //                 })
    //                 ->where('deleted_at','=',null)
    //     			   ->orderBy('id', 'ASC');
        			
    // }

}
