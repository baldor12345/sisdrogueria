<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Producto;
use App\Categoria;
use App\Laboratorio;
use App\Unidad;
use App\Marca;
use App\Presentacion;
use App\User;
use App\Proveedor;
use App\Sucursal;
use App\Propiedades;
use App\ProductoPresentacion;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
   
    protected $folderview      = 'app.producto';
    protected $tituloAdmin     = 'Producto';
    protected $tituloRegistrar = 'Registrar producto';
    protected $titulo_presentacion = 'Presentaciones';
    protected $tituloModificar = 'Modificar producto';
    protected $tituloEliminar  = 'Eliminar producto';
    protected $rutas           = array('create' => 'producto.create', 
            'edit'          => 'producto.edit', 
            'delete'        => 'producto.eliminar',
            'search'        => 'producto.buscar',
            'index'         => 'producto.index',

            'presentacion'          => 'producto.presentacion',

            'listmarcas'    => 'producto.listmarcas',
            'listunidades'    => 'producto.listunidades',
            'listcategorias'     => 'producto.listcategorias',
            'listproveedores'    => 'producto.listproveedores',
            'listsucursales'    => 'producto.listsucursales'
        );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Producto';
        $descripcion      = Libreria::getParam($request->input('descripcion'));
        $codigo      = Libreria::getParam($request->input('codigo'));
        $resultado        = Producto::listarproducto($descripcion, $codigo);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Codigo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Principio Activo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Marc/Laboratorio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Categoria', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_presentacion  = $this->titulo_presentacion;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('titulo_presentacion', 'lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Producto';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboPres     = [''=>'Todos'] + Presentacion::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('cboPres', 'entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Producto';
        $producto       = null;
        $cboTipo       = array(0=>'SIN ESPECIFICAR', 1=>'GENERICO', 2=>'OTROS', 3=>'PATENTE', 4=>'SIMILAR');
        $cboAfecto       = array('S'=>'SI', 'N'=>'NO');
        $cboMarca       = array(0=>'Seleccione Marca...');
        $cboCategoria   = ['0'=>'Seleccione'] + Categoria::pluck('name', 'id')->all();
        $cboPresentacion     = ['0'=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboLaboratio   = array(0=>'Seleccione Laboratorio...');
        $cboProveedor   = array(0=>'Seleccione Proveedor...');
        $formData       = array('producto.store');
        $propiedades            = Propiedades::All()->last();
        $igv            = $propiedades->igv;
        $listdet_ ='';
        $codigo = '000'.(count(Producto::all()) + 1 );
        $ruta             = $this->rutas;
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('codigo','cboAfecto','listdet_', 'producto', 'cboPresentacion', 'cboTipo', 'igv', 'formData', 'ruta', 'entidad', 'boton', 'listar', 'cboSucursal', 'cboLaboratio', 'cboProveedor', 'cboMarca','cboCategoria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'codigo' => 'required|max:100',
            'descripcion' => 'required|max:400',
            'sustancia_activa' => 'required',
            'ubicacion'    => 'required',
            'stock_minimo'    => 'required',
            'categoria_id' => 'required|integer|exists:categoria,id,deleted_at,NULL',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $producto               = new Producto();
            $producto->codigo = $request->input('codigo');
            $producto->codigo_barra = $request->input('codigo_barra');
            $producto->descripcion = strtoupper($request->input('descripcion'));
            $producto->sustancia_activa = $request->input('sustancia_activa');
            $producto->uso_terapeutico = $request->input('uso_terapeutico');
            $producto->afecto = $request->input('afecto');
            $producto->tipo = $request->input('tipo');
            $producto->ubicacion = $request->input('ubicacion');
            $producto->stock_minimo = $request->input('stock_minimo');
            $producto->marca_id  = (intval($request->input('marca_id'))==0)?null:$request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $user           = Auth::user();
            $producto->user_id = $user->id;
            $producto->save();

            $cantidad = $request->input('cantidad');
            $producto_last = Producto::All()->last();

            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    $producto_presentacion = new ProductoPresentacion();
                    $producto_presentacion->precio_compra =  $request->input("preciocomp".$i);
                    $producto_presentacion->cant_unidad_x_presentacion =  $request->input("unidad_x_present".$i);
                    $producto_presentacion->precio_venta_unitario =  $request->input("precioventaunit".$i);
                    $producto_presentacion->producto_id = $producto_last->id;
                    $producto_presentacion->Presentacion_id = $request->input("id_present".$i);
                    $producto_presentacion->save();
                }
            }

        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboMarca = array('' => 'Seleccione') + Marca::pluck('name', 'id')->all();
        $cboCategoria = array('' => 'Seleccione') + Categoria::pluck('name', 'id')->all();
        $cboPresentacion     = [''=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboProveedor   = array('' => 'Seleccione') + Proveedor::pluck('nombre', 'id')->all();
        $cboTipo       = array(0=>'SIN ESPECIFICAR', 1=>'GENERICO', 2=>'OTROS', 3=>'PATENTE', 4=>'SIMILAR');
        $cboAfecto       = array('S'=>'SI', 'N'=>'NO');
        $producto       = Producto::find($id);
        $entidad        = 'Producto';
        $codigo ='';
        $listdet_       = DB::table('producto_presentacion')
                                ->join('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                                ->select(
                                        'producto_presentacion.id as propresent_id', 
                                        'producto_presentacion.presentacion_id as presentacion_id', 
                                        'producto_presentacion.cant_unidad_x_presentacion as cant_unidad_x_presentacion', 
                                        'producto_presentacion.precio_compra as precio_compra', 
                                        'presentacion.nombre as presentacion_nombre', 
                                        'producto_presentacion.precio_venta_unitario as precio_venta_unitario'
                                )
                                ->where('producto_presentacion.producto_id', $id)
                                ->where('producto_presentacion.deleted_at',null)->get();
        
        $formData       = array('producto.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.editar')->with(compact('codigo', 'cboAfecto', 'listdet_', 'ruta', 'producto', 'cboPresentacion', 'formData', 'entidad', 'boton', 'listar', 'cboTipo', 'cboProveedor', 'cboSucursal', 'cboMarca','cboCategoria','cboUnidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'codigo'       => 'required|max:50|unique:producto,codigo,'.$id.',id,deleted_at,NULL',
            'sustancia_activa' => 'required',
            'stock_minimo'    => 'required',
            
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $producto                 = Producto::find($id);
            $producto->codigo = $request->input('codigo');
            $producto->codigo_barra = $request->input('codigo_barra');
            $producto->descripcion = strtoupper($request->input('descripcion'));
            $producto->sustancia_activa = $request->input('sustancia_activa');
            $producto->uso_terapeutico = $request->input('uso_terapeutico');
            $producto->afecto = $request->input('afecto');
            $producto->tipo = $request->input('tipo');
            $producto->ubicacion = $request->input('ubicacion');
            $producto->stock_minimo = $request->input('stock_minimo');
            $producto->marca_id  = (intval($request->input('marca_id'))==0)?null:$request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $user           = Auth::user();
            $producto->user_id = $user->id;
            $producto->save();

            /*
            $cantidad = $request->input('cantidad');
            $detalle_present_ = ProductoPresentacion::where('producto_id',$id)->where('deleted_at',null)->get();
            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    if(intval($request->input("propresent_id".$i)) == 0){
                        $producto_presentacion = new ProductoPresentacion();
                        $producto_presentacion->precio_compra =  $request->input("preciocomp".$i);
                        $producto_presentacion->cant_unidad_x_presentacion =  $request->input("unidad_x_present".$i);
                        $producto_presentacion->precio_venta_unitario =  $request->input("precioventaunit".$i);
                        $producto_presentacion->producto_id = $id;
                        $producto_presentacion->Presentacion_id = $request->input("id_present".$i);
                        $producto_presentacion->save();
                    }
                    if(intval($request->input("propresent_id".$i)) != 0){
                        foreach ($detalle_present_ as $key => $value) {
                            echo "ids que pasan ".intval($request->input("propresent_id".$i))."  ".$value->id;
                            if($value->id == intval($request->input("propresent_id".$i))){
                                $producto_presentacion = ProductoPresentacion::find($value->id);
                                $producto_presentacion->precio_compra =  $request->input("preciocomp".$i);
                                $producto_presentacion->cant_unidad_x_presentacion =  $request->input("unidad_x_present".$i);
                                $producto_presentacion->precio_venta_unitario =  $request->input("precioventaunit".$i);
                                $producto_presentacion->producto_id = $id;
                                $producto_presentacion->Presentacion_id = $request->input("id_present".$i);
                                $producto_presentacion->save();
                            }else{
                                $producto_presentacion = ProductoPresentacion::find($value->id);
                                $producto_presentacion->delete();
                            }
                        }
                    }
                    
                    
                }
            }
            */

        });
        return is_null($error) ? "OK" : $error;
    }


    public function presentacion($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboMarca = array('' => 'Seleccione') + Marca::pluck('name', 'id')->all();
        $cboCategoria = array('' => 'Seleccione') + Categoria::pluck('name', 'id')->all();
        $cboPresentacion     = [''=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboProveedor   = array('' => 'Seleccione') + Proveedor::pluck('nombre', 'id')->all();
        $cboTipo       = array(0=>'SIN ESPECIFICAR', 1=>'GENERICO', 2=>'OTROS', 3=>'PATENTE', 4=>'SIMILAR');
        $cboAfecto       = array('S'=>'SI', 'N'=>'NO');
        $producto       = Producto::find($id);
        $entidad        = 'Producto';
        $codigo ='';
        $listdet_       = DB::table('producto_presentacion')
                                ->join('presentacion', 'producto_presentacion.presentacion_id', '=', 'presentacion.id')
                                ->select(
                                        'producto_presentacion.id as propresent_id', 
                                        'producto_presentacion.presentacion_id as presentacion_id', 
                                        'producto_presentacion.cant_unidad_x_presentacion as cant_unidad_x_presentacion', 
                                        'producto_presentacion.precio_compra as precio_compra', 
                                        'presentacion.nombre as presentacion_nombre', 
                                        'producto_presentacion.precio_venta_unitario as precio_venta_unitario'
                                )
                                ->where('producto_presentacion.producto_id', $id)
                                ->where('producto_presentacion.deleted_at',null)->get();
        
        $formData       = array('producto.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.presentacion')->with(compact('codigo', 'cboAfecto', 'listdet_', 'ruta', 'producto', 'cboPresentacion', 'formData', 'entidad', 'boton', 'listar', 'cboTipo', 'cboProveedor', 'cboSucursal', 'cboMarca','cboCategoria','cboUnidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatepresentacion(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'codigo'       => 'required|max:50|unique:producto,codigo,'.$id.',id,deleted_at,NULL',
            'sustancia_activa' => 'required',
            'stock_minimo'    => 'required',
            
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $producto                 = Producto::find($id);
            $producto->codigo = $request->input('codigo');
            $producto->codigo_barra = $request->input('codigo_barra');
            $producto->descripcion = strtoupper($request->input('descripcion'));
            $producto->sustancia_activa = $request->input('sustancia_activa');
            $producto->uso_terapeutico = $request->input('uso_terapeutico');
            $producto->afecto = $request->input('afecto');
            $producto->tipo = $request->input('tipo');
            $producto->ubicacion = $request->input('ubicacion');
            $producto->stock_minimo = $request->input('stock_minimo');
            $producto->marca_id  = (intval($request->input('marca_id'))==0)?null:$request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $user           = Auth::user();
            $producto->user_id = $user->id;
            $producto->save();

            /*
            $cantidad = $request->input('cantidad');
            $detalle_present_ = ProductoPresentacion::where('producto_id',$id)->where('deleted_at',null)->get();
            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    if(intval($request->input("propresent_id".$i)) == 0){
                        $producto_presentacion = new ProductoPresentacion();
                        $producto_presentacion->precio_compra =  $request->input("preciocomp".$i);
                        $producto_presentacion->cant_unidad_x_presentacion =  $request->input("unidad_x_present".$i);
                        $producto_presentacion->precio_venta_unitario =  $request->input("precioventaunit".$i);
                        $producto_presentacion->producto_id = $id;
                        $producto_presentacion->Presentacion_id = $request->input("id_present".$i);
                        $producto_presentacion->save();
                    }
                    if(intval($request->input("propresent_id".$i)) != 0){
                        foreach ($detalle_present_ as $key => $value) {
                            echo "ids que pasan ".intval($request->input("propresent_id".$i))."  ".$value->id;
                            if($value->id == intval($request->input("propresent_id".$i))){
                                $producto_presentacion = ProductoPresentacion::find($value->id);
                                $producto_presentacion->precio_compra =  $request->input("preciocomp".$i);
                                $producto_presentacion->cant_unidad_x_presentacion =  $request->input("unidad_x_present".$i);
                                $producto_presentacion->precio_venta_unitario =  $request->input("precioventaunit".$i);
                                $producto_presentacion->producto_id = $id;
                                $producto_presentacion->Presentacion_id = $request->input("id_present".$i);
                                $producto_presentacion->save();
                            }else{
                                $producto_presentacion = ProductoPresentacion::find($value->id);
                                $producto_presentacion->delete();
                            }
                        }
                    }
                    
                    
                }
            }
            */

        });
        return is_null($error) ? "OK" : $error;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $producto = Producto::find($id);
            $producto->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Producto::find($id);
        $entidad  = 'Producto';
        $formData = array('route' => array('producto.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
    //METOS PARA EL COMBO SELECT
    public function listmarcas(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Marca::where('name','LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function listunidades(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Unidad::where("name",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name.' ('.$tag->simbolo.')'];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function listcategorias(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Categoria::where("name",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function listproveedores(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Proveedor::where("nombre",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nombre];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function listsucursales(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Sucursal::where("nombre",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nombre];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }


}
