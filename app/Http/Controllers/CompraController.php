<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Producto;
use App\Compra;
use App\DetalleCompra;
use App\User;
use App\Presentacion;
use App\Marca;
use App\Proveedor;
use App\Propiedades;
use App\Entrada;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    protected $folderview      = 'app.compra';
    protected $tituloAdmin     = 'compra';
    protected $tituloRegistrar = 'Registrar Compra';
    protected $tituloModificar = 'Modificar Compra';
    protected $tituloEliminar  = 'Eliminar Compra';
    protected $titulo_ver  = 'Detalle de Compra';
    protected $rutas           = array('create' => 'compra.create', 
            'edit'          => 'compra.edit', 
            'delete'        => 'compra.eliminar',
            'search'        => 'compra.buscar',
            'index'         => 'compra.index',
            'verdetalle'         => 'compra.verdetalle',
            'listproveedores'    => 'compra.listproveedores',
            'listproductos'    => 'compra.listproductos'
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
        $entidad          = 'Compra';
        $numero      = Libreria::getParam($request->input('numero'));
        $proveedor      = Libreria::getParam($request->input('proveedor'));
        $fechai      = Libreria::getParam($request->input('fechai'));
        $fechaf      = Libreria::getParam($request->input('fechaf'));
        $resultado        = Compra::listarcompra($numero, $proveedor, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Proveedor', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Situacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_ver  = $this->titulo_ver;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','titulo_ver'));
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
        $entidad          = 'Compra';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Compra';
        $compra       = null;
        $cboDocumento       = array(1=>'FACTURA DE COMPRA', 2=>'BOLETA DE COMPRA', 3=>'GUIA INTERNA', 4=>'NOTA DE CREDITO', 5=>'TICKET');
        $cboCredito       = array('S'=>'SI', 'N'=>'NO');
        $cboProducto       = array(0=>'Seleccione Producto...');
        $cboProveedor        = array(0=>'Seleccione Proveedor...');
        $cboPresentacion = [''=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboLaboratorio = [''=>'Seleccione'] + Marca::pluck('name', 'id')->all();
        $formData       = array('compra.store');
        $propiedades            = Propiedades::All()->last();
        $igv            = $propiedades->igv;
        $ruta             = $this->rutas;
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('compra', 'cboPresentacion', 'cboLaboratorio','cboDocumento', 'igv', 'formData', 'ruta', 'entidad', 'boton', 'listar', 'cboCredito', 'cboProducto', 'cboProveedor', 'cboMarca','cboCategoria','cboUnidad'));
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
            'documento' => 'required|max:60',
            'numero_documento' => 'required',
            'serie_documento' => 'required',
            'proveedor_id' => 'required|integer|exists:proveedor,id,deleted_at,NULL',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $compra               = new Compra();
            $compra->documento = $request->input('documento');
            $compra->numero_documento = $request->input('numero_documento');
            $compra->serie_documento = $request->input('serie_documento');
            $compra->credito = $request->input('credito');
            $compra->numero_dias = $request->input('numero_dias');
            $compra->ruc = $request->input('ruc');
            $compra->proveedor_id = $request->input('proveedor_id');
            $compra->estado = $request->input('estado');
            $compra->fecha = $request->input('fecha');
            $compra->fecha_caducidad = $request->input('fecha_caducidad');
            $compra->total  = $request->input('total');
            $compra->igv = $request->input('igv');
            $user           = Auth::user();
            $compra->user_id = $user->id;
            $compra->sucursal_id = $user->sucursal_id;
            //$compra->caja_id = $user->caja_id;
            $compra->save();

            $cantidad = $request->input('cantidad');
            $compra_last = Compra::All()->last();

            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    $detalle_compra = new DetalleCompra();
                    $detalle_compra->fecha_caducidad = $request->input("fecha_vencim".$i);
                    $detalle_compra->ubicacion = 'STAND0001';
                    $detalle_compra->precio_compra = $request->input("precio_compra".$i);
                    $detalle_compra->precio_venta = $request->input("precio_venta".$i);
                    $detalle_compra->cantidad = $request->input("cant".$i);
                    $detalle_compra->lote = $request->input("lot".$i);
                    $detalle_compra->producto_id = $request->input("id_producto".$i);
                    //$detalle_compra->presentacion_id = $request->input("id_presentacion".$i);
                    $detalle_compra->marca_id = $request->input("id_laboratorio".$i);
                    $detalle_compra->compra_id = $compra_last->id;
                    $detalle_compra->save();
                }
            }
            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    $entrada = new Entrada();
                    $entrada->fecha = $request->input('fecha');
                    $entrada->fecha_caducidad = $request->input("fecha_vencim".$i);
                    $entrada->precio_compra = $request->input("precio_compra".$i);
                    $entrada->precio_venta = $request->input("precio_venta".$i);
                    $entrada->stock = $request->input("cant".$i);
                    $entrada->lote = $request->input("lot".$i);
                    $entrada->producto_id = $request->input("id_producto".$i);
                    //$entrada->presentacion_id = $request->input("id_presentacion".$i);
                    $user           = Auth::user();
                    $entrada->user_id = $user->id;
                    $entrada->sucursal_id = $user->sucursal_id;
                    $entrada->save();
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
        $existe = Libreria::verificarExistencia($id, 'compra');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboDocumento       = array(1=>'FACTURA DE COMPRA', 2=>'BOLETA DE COMPRA', 3=>'GUIA INTERNA', 4=>'NOTA DE CREDITO', 5=>'TICKET');
        $cboCredito       = array('S'=>'SI', 'N'=>'NO');
        $cboProducto       = array(0=>'Seleccione Producto...');
        $compra       = Compra::find($id);
        $entidad        = 'Compra';
        $formData       = array('compra.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('ruta', 'compra', 'formData', 'entidad', 'boton', 'listar', 'cboTipo', 'cboProveedor', 'cboSucursal', 'cboMarca','cboCategoria','cboUnidad'));
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
        $existe = Libreria::verificarExistencia($id, 'compra');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'documento'       => 'required|max:50|unique:compra,documento,'.$id.',id,deleted_at,NULL',
            'numero_documento' => 'required',
            'serie_documento' => 'required',
            'proveedor_id' => 'required',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $compra                 = Compra::find($id);
            $compra->documento = $request->input('documento');
            $compra->numero_documento = $request->input('numero_documento');
            $compra->serie_documento = $request->input('serie_documento');
            $compra->credito = $request->input('credito');
            $compra->numero_dias = $request->input('numero_dias');
            $compra->ruc = $request->input('ruc');
            $compra->proveedor_id = $request->input('proveedor_id');
            $compra->estado = $request->input('estado');
            $compra->fecha = $request->input('fecha');
            $compra->fecha_caducidad = $request->input('fecha_caducidad');

            $compra->total  = $request->input('total');
            $compra->igv = $request->input('igv');
            $user           = Auth::user();
            $compra->user_id = $user->id;
            $compra->sucursal_id = $user->sucursal_id;
            //$compra->caja_id = $user->caja_id;
            $compra->save();
        });
        return is_null($error) ? "OK" : $error;
    }
    //para vista de ver detalle
    public function verdetalle($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'compra');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboDocumento       = array(1=>'FACTURA DE COMPRA', 2=>'BOLETA DE COMPRA', 3=>'GUIA INTERNA', 4=>'NOTA DE CREDITO', 5=>'TICKET');
        $cboCredito       = array('S'=>'SI', 'N'=>'NO');
        $compra       = Compra::find($id);
        $list_detalle_c = Compra::listardetallecompra($id)->get();
        $proveedor      = Proveedor::find($compra->proveedor_id);
        $entidad        = 'Compra';
        $formData       = array('compra.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.verdetalle')->with(compact('ruta', 'compra', 'proveedor', 'formData', 'entidad', 'boton', 'listar', 'list_detalle_c','cboDocumento','cboCredito'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'compra');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $producto = Compra::find($id);
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
        $existe = Libreria::verificarExistencia($id, 'compra');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Compra::find($id);
        $entidad  = 'Compra';
        $formData = array('route' => array('compra.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
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
    public function listproductos(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = DB::table('producto')->join('presentacion','producto.presentacion_id','presentacion.id')->select('producto.id as id','producto.presentacion_id as presentecion_id','producto.descripcion as descripcion','presentacion.nombre as presentacion')->where("producto.codigo",'LIKE', '%'.$term.'%')->orWhere("producto.codigo_barra",'LIKE', '%'.$term.'%')->orWhere("producto.descripcion",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'presentecion_id'=>$tag->presentecion_id, 'text' => $tag->descripcion.'   ['.$tag->presentacion.'] '];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

}
