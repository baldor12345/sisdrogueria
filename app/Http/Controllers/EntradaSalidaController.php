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
use App\Salida;
use App\DetalleEntrada;
use App\MantenimientoProducto;
use App\DetalleSalida;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EntradaSalidaController extends Controller
{
    protected $folderview      = 'app.entrada_salida';
    protected $tituloAdmin     = 'Entradas y Salidas';
    protected $tituloRegistrar = 'Registrar Entrada o Salida';
    protected $tituloModificar = 'Modificar Entrada o Salida';
    protected $tituloEliminar  = 'Eliminar Entrada o Salida';
    protected $titulo_ver  = 'Detalle de Entrada o Salida';
    protected $rutas           = array('create' => 'entrada_salida.create', 
            'edit'          => 'entrada_salida.edit', 
            'delete'        => 'entrada_salida.eliminar',
            'search'        => 'entrada_salida.buscar',
            'index'         => 'entrada_salida.index',
            'verdetalle'         => 'entrada_salida.verdetalle',
            'listproveedores'    => 'entrada_salida.listproveedores',
            'listproductos'    => 'entrada_salida.listproductos'
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
        $numero      = Libreria::getParam($request->input('producto'));
        $presentacion_id      = Libreria::getParam($request->input('presentacion_id'));
        $fechai      = Libreria::getParam($request->input('fechai'));
        $fechaf      = Libreria::getParam($request->input('fechaf'));
        $resultado        = MantenimientoProducto::listar($numero,$presentacion_id, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Producto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Presentacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'F. Caducidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'P. Venta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Lote', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
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
        $entidad          = 'EntradaSalida';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboPresentacion     = [''=>'Todos'] + Presentacion::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('cboPresentacion', 'entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'EntradaSalida';
        $entradasalida       = null;
        $cboDocumento       = array('E'=>'Doc. Entrada', 'S'=>'Doc. Salida');
        //$cboTipo       = array('E'=>'Entrada', 'S'=>'Salida');
        $cboProducto       = array(0=>'Seleccione Producto...');
        $cboProveedor        = array(0=>'Seleccione Proveedor...');
        $cboPresentacion = [''=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboLaboratorio = [''=>'Seleccione'] + Marca::pluck('name', 'id')->all();
        $formData       = array('entrada_salida.store');
        $propiedades            = Propiedades::All()->last();
        $igv            = $propiedades->igv;
        $ruta             = $this->rutas;
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('entradasalida', 'cboTipo', 'cboPresentacion','cboLaboratorio','cboDocumento', 'igv', 'formData', 'ruta', 'entidad', 'boton', 'listar', 'cboCredito', 'cboProducto', 'cboProveedor', 'cboMarca','cboCategoria','cboTipo'));
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
            'numero_documento' => 'required',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error =null;
        $tipo = $request->input('documento');
        if($tipo == 'E'){
            $error = DB::transaction(function() use($request){
                $cantidad = $request->input('cantidad');
                if($cantidad >0){
                    for($i=0;$i<$cantidad; $i++){
                        $entrada_existente = Entrada::where('lote',trim($request->input("lot".$i)))->whereDate('fecha_caducidad',$request->input("fecha_vencim".$i))->where('deleted_at',null)->get();
                        if(count($entrada_existente) != 0){
                            $entrada    = Entrada::find($entrada_existente[0]->id);
                            $entrada->precio_compra = $request->input("precio_compra".$i);
                            $entrada->precio_venta = $request->input("precio_venta".$i);
                            $entrada->stock = intval($request->input("cant".$i))+$entrada_existente[0]->stock;
                            $entrada->save();
                        }
                        if(count($entrada_existente) == 0){
                            $entrada    = new Entrada();
                            $entrada->fecha = $request->input('fecha');
                            $entrada->fecha_caducidad = $request->input('fecha_vencim'.$i);
                            $entrada->precio_compra = $request->input("precio_compra".$i);
                            $entrada->precio_venta = $request->input("precio_venta".$i);
                            $entrada->stock = $request->input("cant".$i);
                            $entrada->lote = $request->input("lot".$i);
                            $entrada->producto_id = $request->input("id_producto".$i);
                            $user           = Auth::user();
                            $entrada->user_id = $user->id;
                            $entrada->sucursal_id = $user->sucursal_id;
                            $entrada->save();
                        }
                        
                    }
                }
            });
        }
        if($tipo == 'S'){
            $error = DB::transaction(function() use($request){
                $salida               = new salida();
                $salida->documento = $request->input('documento');
                $salida->numero_documento = $request->input('numero_documento');
                $salida->fecha = $request->input('fecha');
                $salida->comentario = $request->input('comentario');
                $salida->total  = $request->input('total');
                $user           = Auth::user();
                $salida->user_id = $user->id;
                $salida->sucursal_id = $user->sucursal_id;
                $salida->save();
    
                $cantidad = $request->input('cantidad');
                $salida_last = Salida::All()->last();
    
                if($cantidad >0){
                    for($i=0;$i<$cantidad; $i++){
                        $detalle_salida = new DetalleSalida();
                        $detalle_salida->fecha_caducidad = $request->input("fecha_vencim".$i);
                        $detalle_salida->precio_compra = $request->input("precio_compra".$i);
                        $detalle_salida->precio_venta = $request->input("precio_venta".$i);
                        $detalle_salida->stock = $request->input("cant".$i);
                        $detalle_salida->cantidad = $request->input("cant".$i);
                        $detalle_salida->lote = $request->input("lot".$i);
                        $detalle_salida->producto_id = $request->input("id_producto".$i);
                        $detalle_salida->presentacion_id = $request->input("id_presentacion".$i);
                        $detalle_salida->marca_id = $request->input("id_laboratorio".$i);
                        $detalle_salida->salida_id = $salida_last->id;
                        $detalle_salida->save();
                    }
                }
            });
        }
        
        return is_null($error) ? "OK" : $error;
    }

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
        $existe = Libreria::verificarExistencia($id, 'entrada_salida');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboDocumento       = array(1=>'FACTURA DE COMPRA', 2=>'BOLETA DE COMPRA', 3=>'GUIA INTERNA', 4=>'NOTA DE CREDITO', 5=>'TICKET');
        $cboCredito       = array('S'=>'SI', 'N'=>'NO');
        $cboProducto       = array(0=>'Seleccione Producto...');
        $entrada_salida       = EntradaSalida::find($id);
        $entidad        = 'EntradaSalida';
        $formData       = array('entrada_salida.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('ruta', 'entrada_salida', 'formData', 'entidad', 'boton', 'listar', 'cboTipo', 'cboProveedor', 'cboSucursal', 'cboMarca','cboCategoria','cboUnidad'));
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
        $existe = Libreria::verificarExistencia($id, 'entrada_salida');
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
        $existe = Libreria::verificarExistencia($id, 'entrada');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboDocumento       = array(1=>'FACTURA DE COMPRA', 2=>'BOLETA DE COMPRA', 3=>'GUIA INTERNA', 4=>'NOTA DE CREDITO', 5=>'TICKET');
        $cboCredito       = array('S'=>'SI', 'N'=>'NO');
        $entrada       = Entrada::find($id);
        $list_detalle_c = MantenimientoProducto::listardetalleentrada($id)->get();
        $proveedor      = ($entrada->proveedor_id != null)?Proveedor::find($entrada->proveedor_id):$proveedor="";
        $entidad        = 'EntradaSalida';
        $formData       = array('entrada_salida.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.verdetalle')->with(compact('ruta', 'entrada', 'proveedor', 'formData', 'entidad', 'boton', 'listar', 'list_detalle_c','cboDocumento','cboCredito'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'entrada_salida');
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
        $tags = Producto::where("codigo",'LIKE', '%'.$term.'%')->orWhere("codigo_barra",'LIKE', '%'.$term.'%')->orWhere("descripcion",'LIKE', '%'.$term.'%')->orWhere('deleted_at',null)->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->descripcion];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }
}
