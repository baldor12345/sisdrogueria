<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Producto;
use App\ProductoPresentacion;
use App\Compra;
use App\DetalleCompra;
use App\User;
use App\Presentacion;
use App\Categoria;
use App\Marca;
use App\Proveedor;
use App\Propiedades;
use App\Entrada;
use App\Caja;
use App\DetalleCaja;
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
            'buscard'       => 'compra.buscard',
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
        $cabecera[]       = array('valor' => 'Tipo Pago', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '1');
        
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
        $cboCredito       = ['CO'=>'Contado','CR'=>'Crédito'];
        $cboProducto       = array(0=>'Seleccione Producto...');
        $cboProveedor        = array(0=>'Seleccione Proveedor...');
        $cboPresentacion = ['0'=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboUnidad = ['0'=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboAfecto       = array('S'=>'SI', 'N'=>'NO');
        $cboFecha       = array('N'=>'NO', 'S'=>'SI');
        $formData       = array('compra.store');
        $propiedades            = Propiedades::All()->last();
        $numero_operacion   = Libreria::codigo_operacion();
        $igv            = $propiedades->igv;
        $ruta             = $this->rutas;
        $fech_form = date('d/m/Y');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('fech_form', 'cboFecha','cboAfecto', 'numero_operacion', 'cboUnidad', 'cboCategoria', 'compra', 'cboPresentacion', 'cboLaboratorio','cboDocumento', 'igv', 'formData', 'ruta', 'entidad', 'boton', 'listar', 'cboCredito', 'cboProducto', 'cboProveedor', 'cboMarca','cboCategoria','cboUnidad'));
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
            $compra->tipo_pago = $request->input('credito');
            $compra->numero_dias = $request->input('numero_dias');
            $compra->proveedor_id = $request->input('proveedor_id');
            $compra->estado = $request->input('estado');
            $compra->fecha = $request->input('fecha');
            $compra->fecha_caducidad = $request->input('fecha_caducidad');
            $compra->total  = $request->input('total_n');
            $compra->igv = $request->input('igv_t');
            $user           = Auth::user();
            $compra->user_id = $user->id;
            $compra->sucursal_id = $user->sucursal_id;
            $compra->save();

            $cantidad = $request->input('cantidad');
            $compra_last = Compra::All()->last();

            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    $detalle_compra = new DetalleCompra();
                    $detalle_compra->fecha_caducidad_string = trim($request->input("fecha_vencim".$i));
                    $fec_string = trim($request->input("fecha_vencim".$i));
                    $slash   = '/';
                    $punto   = '.';
                    $dos_puntos   = ':';
                    $guion   = '-';
                    $fecha_for = "";

                    if(strpos($fec_string, $slash) !== false){
                        $fech_explode = explode('/', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    if(strpos($fec_string, $punto) !== false){
                        $fech_explode = explode('.', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    if(strpos($fec_string, $dos_puntos) !== false){
                        $fech_explode = explode(':', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    if(strpos($fec_string, $guion) !== false){
                        $fech_explode = explode('-', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $detalle_compra->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    $detalle_compra->ubicacion = '';
                    $detalle_compra->precio_compra = $request->input("precio_compra".$i);
                    $detalle_compra->fecha_completa = $request->input("fecha_co".$i);
                    $detalle_compra->precio_venta = $request->input("precio_venta".$i);
                    $detalle_compra->cantidad = $request->input("cant".$i);
                    $detalle_compra->lote = $request->input("lot".$i);
                    $detalle_compra->producto_presentacion_id = $request->input("id_producto".$i);
                    $detalle_compra->compra_id = $compra_last->id;
                    $detalle_compra->save();

                    $prod_m                 = ProductoPresentacion::find($request->input("id_producto".$i));
                    $prod_m->precio_compra = $request->input("precio_compra".$i);
                    $prod_m->precio_venta_unitario = $request->input("precio_venta".$i);
                    $prod_m->save();

                    $prod_ = Producto::find($prod_m->producto_id);
                    $prod_->afecto = $request->input("afecto_1".$i);
                    $prod_->save();

                }
            }
            if($cantidad >0){
                for($i=0;$i<$cantidad; $i++){
                    $entrada = new Entrada();
                    $entrada->fecha = $request->input('fecha');
                    $entrada->fecha_caducidad_string = trim($request->input("fecha_vencim".$i));
                    $fec_string = trim($request->input("fecha_vencim".$i));
                    $slash   = '/';
                    $punto   = '.';
                    $dos_puntos   = ':';
                    $guion   = '-';
                    $fecha_for = "";

                    if(strpos($fec_string, $slash) !== false){
                        $fech_explode = explode('/', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    if(strpos($fec_string, $punto) !== false){
                        $fech_explode = explode('.', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    if(strpos($fec_string, $dos_puntos) !== false){
                        $fech_explode = explode(':', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    if(strpos($fec_string, $guion) !== false){
                        $fech_explode = explode('-', $fec_string);
                        if(count($fech_explode) == 2){
                            if(strlen($fech_explode[1]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                            
                        }
                        if(count($fech_explode) == 3){
                            if(strlen($fech_explode[2]) == 2){
                                $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }else{
                                $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                $entrada->fecha_caducidad = $fecha_for;
                            }
                        }
                    }
                    $entrada->fecha_completa = $request->input("fecha_co".$i);
                    $entrada->stock = $request->input("factor_".$i);
                    $entrada->lote = $request->input("lot".$i);
                    $entrada->producto_presentacion_id = $request->input("id_producto".$i);
                    $user = Auth::user();
                    $entrada->user_id = $user->id;
                    $entrada->sucursal_id = $user->sucursal_id;
                    $entrada->save();
                }
            }
            $user = Auth::user();
            $caja = Caja::where('estado','=','A')->where('sucursal_id',$user->sucursal_id)->where('deleted_at','=',null)->get()[0];

            $detalle_caja = new DetalleCaja();
            $detalle_caja->caja_id = $caja->id;
            $detalle_caja->forma_pago = $request->input('credito');
            $detalle_caja->concepto_id = 6;
            $detalle_caja->estado = 'P';
            $detalle_caja->fecha = $request->input('fecha').date(' H:i:s');
            $detalle_caja->ingreso = 0;
            $detalle_caja->egreso = $request->input('total_n');
            $detalle_caja->numero_operacion = $request->input('numero_documento');
            $detalle_caja->save();
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
        $cboDocumento       = array(1=>'FACTURA DE COMPRA', 2=>'BOLETA DE COMPRA', 3=>'GUIA INTERNA', 4=>'NOTA DE CREDITO', 5=>'TICKET');
        $cboCredito       = ['CO'=>'Contado','CR'=>'Crédito'];
        $compra       = Compra::find($id);
        $proveedor      = Proveedor::find($compra->proveedor_id);
        $entidad        = 'Compra1';
        $ruta           = $this->rutas;
        return view($this->folderview.'.verdetalle')->with(compact('ruta', 'compra', 'id', 'proveedor', 'formData', 'entidad', 'boton', 'listar','cboDocumento','cboCredito'));
    }

    public function buscard(Request $request){
        $entidad ='Compra1';
        $id =  Libreria::getParam($request->input('idc'));
        $lista = Compra::listardetallecompra($id)->get();
        $inicio           = 0;
        $ruta             = $this->rutas;
        $titulo_eliminar = "Eliminar Registro";
        return view($this->folderview.'.listd')->with(compact('titulo_eliminar','lista', 'entidad', 'cabecera', 'ruta', 'inicio'));
    }

    //listar el objeto producto_presentacion por dni
    public function getProductoPresentacion(Request $request, $term){
        if($request->ajax()){
            $tags = DB::table('producto_presentacion')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto_presentacion.presentacion_id','presentacion.id')
                    ->join('categoria','producto.categoria_id','categoria.id')
                    ->select(
                        'producto_presentacion.id as id',
                        'producto_presentacion.presentacion_id as presentacion_id',
                        'producto_presentacion.cant_unidad_x_presentacion as cant_unidad_x_presentacion',
                        'producto_presentacion.precio_compra as precio_compra',
                        'producto_presentacion.precio_venta_unitario as precio_venta_unitario',
                        'producto.sustancia_activa as descripcion',
                        'producto.afecto as afecto',
                        'presentacion.nombre as presentacion',
                        'categoria.id as categoria_id'
                    )
                    ->where("producto_presentacion.id",'=',$term)->get();
            return response()->json($tags);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'detalle_compra');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $compra_d = DetalleCompra::find($id);
            $compra = Compra::find($compra_d->compra_id);
            $entrada_ = Entrada::where('fecha_caducidad_string' , $compra_d->fecha_caducidad_string)->where('lote', $compra_d->lote)->where('producto_presentacion_id', $compra_d->producto_presentacion_id)->get()[0];
            $prod_pr = ProductoPresentacion::find($compra_d->producto_presentacion_id);

            if(count($entrada_) != 0){
                $entrada_->stock = ($entrada_->stock-($compra_d->cantidad*$prod_pr->cant_unidad_x_presentacion));
                $entrada_->save();
            }
            
            $prop_last = Propiedades::all()->last();
            $compra->total = ($compra->total-$compra_d->precio_compra*$compra_d->cantidad);
            $compra->igv = ($compra->igv-($compra_d->precio_compra*$compra_d->cantidad)*$prop_last->igv);
            
            $caja_ = DetalleCaja::where('numero_operacion', $compra->numero_documento)->where('deleted_at',null)->get();
            $caja_[0]->egreso = $caja_[0]->egreso-$compra_d->precio_compra*$compra_d->cantidad;
            $caja_[0]->save();
            $compra_d->delete();
            $compra->save();
            if($caja_[0]->egreso == 0){
                $caja_[0]->delete();
            }

            $compra_d = DetalleCompra::where('compra_id', $compra_d->compra_id )->where('deleted_at', null)->count();
            if($compra_d <= 0){
                $compra->delete();
            }
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
        $existe = Libreria::verificarExistencia($id, 'detalle_compra');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $entidad  = 'Compra1';
        $modelo   = DetalleCompra::find($id);

        $find_c   = Compra::find($modelo->compra_id);
        $delete_ent =  Entrada::where('lote',$modelo->lote)->where('fecha_caducidad_string',$modelo->fecha_caducidad_string)->where('deleted_at',null)->get()[0];
        
        $prod_pr = ProductoPresentacion::find($modelo->producto_presentacion_id);
        if($delete_ent->stock >= ($modelo->cantidad*$prod_pr->cant_unidad_x_presentacion)){
            $formData = array('route' => array('compra.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimientoCompra1', 'autocomplete' => 'off');
            $boton    = 'Eliminar';
            return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
        }else{
            return view($this->folderview.'.messagecompra')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
        }
        
        


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
        $tags = DB::table('producto_presentacion')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto_presentacion.presentacion_id','presentacion.id')
                    ->select(
                        'producto_presentacion.id as p_p_id',
                        'producto_presentacion.presentacion_id as presentacion_id',
                        'producto.descripcion as descripcion',
                        'producto.sustancia_activa as sustancia_activa',
                        'producto.deleted_at as deleted_at',
                        'presentacion.nombre as presentacion'
                    )
                    ->where("producto.codigo",'LIKE', '%'.$term.'%')
                    ->orwhere("producto.codigo_barra",'LIKE', '%'.$term.'%')
                    ->orwhere("producto.sustancia_activa",'LIKE', '%'.$term.'%')
                    ->orwhere("producto.descripcion",'LIKE', '%'.$term.'%')->limit(8)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            if($tag->deleted_at == null){
                $formatted_tags[] = ['id' => $tag->p_p_id, 'text' => $tag->descripcion.'-'.$tag->sustancia_activa.'   ['.$tag->presentacion.'] '];
            }
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    /*
    public function listproductos(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = DB::table('producto_presentacion')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto_presentacion.presentacion_id','presentacion.id')
                    ->join('categoria','producto.categoria_id','categoria.id')
                    ->join('marca','producto.marca_id','marca.id')
                    ->select(
                        'producto_presentacion.id as id',
                        'producto_presentacion.presentacion_id as presentacion_id',
                        'marca.id as marca_id',
                        'marca.name as marca_nombre',
                        'producto_presentacion.cant_unidad_x_presentacion as cant_unidad_x_presentacion',
                        'producto_presentacion.precio_compra as precio_compra',
                        'producto_presentacion.precio_venta_unitario as precio_venta_unitario',
                        'producto.descripcion as descripcion',
                        'producto.sustancia_activa as sustancia_activa',
                        'presentacion.nombre as presentacion'
                    )
                    ->where("producto.codigo",'LIKE', '%'.$term.'%')
                    ->orwhere("producto.codigo_barra",'LIKE', '%'.$term.'%')
                    ->orwhere("presentacion.nombre",'LIKE', '%'.$term.'%')
                    ->orwhere("producto.sustancia_activa",'LIKE', '%'.$term.'%')
                    ->orwhere("producto.descripcion",'LIKE', '%'.$term.'%')->limit(8)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->sustancia_activa.'   ['.$tag->presentacion.'] '];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }*/

}
