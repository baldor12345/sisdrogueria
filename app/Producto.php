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
class Producto extends Model
{
    use SoftDeletes;

   protected $table = 'producto';   
   protected $date = 'delete_at';

	/**
	 * Método para listar las opciones de menu
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
    public function marca(){
        return $this->belongsTo('App\Marca','marca_id');
    } 

    public function presentacion(){
            return $this->belongsTo('App\Presentacion','presentacion_id');
    } 

    public function categoria(){
            return $this->belongsTo('App\Categoria','categoria_id');
    } 

    public function proveedor(){
        return $this->belongsTo('App\Proveedor','proveedor_id');
    }

    public function sucursal(){
        return $this->belongsTo('App\Sucursal','sucursal_id');
    }

	public function scopelistar($query, $nombre, $codigo)
    {
        return $query->where(function($subquery) use($nombre)
            {
                if (!is_null($nombre)) {
                    $subquery->where('descripcion', 'LIKE', '%'.$descripcion.'%');
                }
            })
            ->where(function($subquery) use($codigo)
            {
                if (!is_null($codigo)) {
                    $subquery->where('codigo', '=', $codigo);
                }
            })
            ->orderBy('descripcion', 'ASC');
    }
    
    public static function listarproducto($descripcion, $codigo){
        return  DB::table('producto')
                ->leftjoin('marca', 'producto.marca_id', '=', 'marca.id')
                ->join('categoria', 'producto.categoria_id', '=', 'categoria.id')
                ->select(
                        'producto.id as producto_id', 
                        'producto.codigo as codigo_barra', 
                        'producto.sustancia_activa as producto', 
                        'marca.name as laboratorio', 
                        'categoria.name as categoria'
                )
                ->where('producto.descripcion', 'LIKE','%'.$descripcion.'%')
                //->where('producto.codigo_barra', 'LIKE','%'.$codigo.'%')
                //->where('producto.codigo', 'LIKE','%'.$codigo.'%')
                ->where('producto.deleted_at',null);
    }

    public function scopelistarproductosventa($query,$nombre){
        return $query->where(function($subquery) use($nombre)
        {
            if (!is_null($nombre)) {
                $subquery->where("descripcion",'LIKE', '%'.$nombre.'%')->where("sustancia_activa",'LIKE', '%'.$nombre.'%');
            }
        })
        ->where('deleted_at','=',null)
        // ->limit(5)
        ->orderBy('descripcion', 'ASC')->get();
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
            $binnacle->table      = 'producto';
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
            $binnacle->table      = 'producto';
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
            $binnacle->table      = 'producto';
            $binnacle->detail    = $producto->toJson(JSON_UNESCAPED_UNICODE);
            $binnacle->recordid = $producto->id;
            $binnacle->save();
        });
    }
   
}
