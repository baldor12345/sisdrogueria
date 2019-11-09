<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotaCredito extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table='nota_creditos';

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
    public function medico(){
        return $this->belongsTo('App\Medico','medico_id');
    } 
    public function vendedor(){
        return $this->belongsTo('App\Vendedor','vendedor_id');
    }

    public function scopelistar($query, $fechai, $fechaf, $numero_serie, $doc_dni_ruc)
    {
        // $fechai = date("Y-m-d",strtotime($fechai."- 1 day"));
        $user = Auth::user();
        $fechaf = date("Y-m-d",strtotime($fechaf."+ 1 day"));
        // $doc_dni == ""? NULL: $doc_dni;
        // $doc_ruc == ""? NULL: $doc_ruc;
        return  DB::table('nota_creditos')
        ->leftjoin('cliente', 'nota_creditos.cliente_id', '=', 'cliente.id')
        ->select(
            'nota_creditos.id as id',
            'nota_creditos.serie_doc as serie_doc',
            'nota_creditos.numero_doc as numero_doc',
            'cliente.nombres as nombres',
            'cliente.apellidos as apellidos',
            'cliente.razon_social as razon_social',
            'cliente.dni as dni',
            'cliente.ruc as ruc',
            'nota_creditos.total as total',
            'nota_creditos.subtotal as subtotal',
            'nota_creditos.comprobante as comprobante',
            'nota_creditos.fecha as fecha',
            'nota_creditos.cliente_id as cliente_id',
            'nota_creditos.sucursal_id as sucursal_id',
            'nota_creditos.estado as estado'
            
        )
            ->whereBetween('nota_creditos.fecha', [$fechai, $fechaf])
            ->where(function($subquery) use($doc_dni_ruc)
            {
                if (!is_null($doc_dni_ruc)) {
                    $subquery->where("cliente.ruc",'LIKE', '%'.$doc_dni_ruc.'%')->orwhere('cliente.dni','LIKE', '%'.$doc_dni_ruc.'%');
                }
            })
            ->where('nota_creditos.numero_doc','LIKE','%'.$numero_serie.'%')
            ->where('nota_creditos.sucursal_id','=',$user->sucursal_id)
            ->orderBy('nota_creditos.fecha', 'DSC');

        return $query->where(function($subquery) use($nombre_ini)
        {
            if (!is_null($nombre_ini)) {
                $subquery->where("nombres",'LIKE', '%'.$nombre_ini.'%')->orwhere('apellidos','LIKE', '%'.$nombre_ini.'%')->orwhere('dni','LIKE', '%'.$nombre_ini.'%')->orwhere('iniciales','LIKE', '%'.$nombre_ini.'%');
            }
        })
        ->where('deleted_at','=',null)
        ->orderBy('nombres', 'ASC');//->get();
    }

    public static function getTransaccion($serie_documento, $numero_documento){
        $query1 =  DB::table('ventas')
                    ->join('cliente', 'ventas.cliente_id', '=', 'cliente.id')
                    ->select(
                        'ventas.id as ventas_id',
                        'ventas.fecha as fecha_emision',
                        'ventas.fecha_venc as fecha_venc',
                        'ventas.serie_doc as serie_doc',
                        'ventas.numero_doc as numero_doc',
                        'ventas.tipo_pago as tipo_pago',
                        'ventas.cliente_id as cliente_id',
                        'ventas.medico_id as medico_id',
                        'ventas.vendedor_id as vendedor_id',
                        'cliente.nombres as nombres',
                        'cliente.apellidos as apellidos',
                        'cliente.dni as dni',
                        'cliente.ruc as ruc',
                        'cliente.razon_social as razon_social',
                        'cliente.direccion as direccion'
                    )
                    ->where('ventas.serie_doc', $serie_documento)
                    ->where('ventas.numero_doc', $numero_documento)->get()[0];
        $query2 =  DB::table('detalle_ventas')
                    ->join('ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
                    ->join('producto_presentacion', 'detalle_ventas.producto_presentacion_id', '=', 'producto_presentacion.id')
                    ->join('producto', 'producto_presentacion.producto_id', '=', 'producto.id')
                    ->join('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                    ->select(
                        'detalle_ventas.id as detalle_venta_id',
                        'detalle_ventas.cantidad as cantidad',
                        'detalle_ventas.precio_unitario as precio_unitario',
                        'detalle_ventas.descuento as descuento',
                        'detalle_ventas.total as cantidad_total',
                        'detalle_ventas.lotes as lote',
                        'producto_presentacion.id as producto_id',
                        'producto_presentacion.cant_unidad_x_presentacion as cant_unidad_x_presentacion',
                        'producto.descripcion as producto_descripcion',
                        'producto.afecto as producto_afecto',
                        'presentacion.id as presentacion_id',
                        'presentacion.nombre as presentacion_nombre'
                    )
                    ->where('ventas.id', $query1->ventas_id)->get();
        $query = array();
        $query[0] = $query1;
        $query[1] = $query2;
        return $query; 
    }

}
