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
use App\Marca;
use App\Proveedor;
use App\Propiedades;
use App\Entrada;
use App\EntradaSalida;
use App\DetalleEntrada;
use App\MantenimientoProducto;
use App\EntradaSalidaDetalle;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Date\Date;

class EntradaSalidaController extends Controller
{
    protected $folderview      = 'app.entrada_salida';
    protected $tituloAdmin     = 'Entradas y Salidas';
    protected $tituloRegistrar = 'Registrar Entrada o Salida';
    protected $tituloModificar = 'Modificar Entrada o Salida';
    protected $tituloEliminar  = 'Eliminar Entrada o Salida';
    protected $titulo_ver  = 'Detalle';
    protected $rutas           = array('create' => 'entrada_salida.create', 
            'edit'          => 'entrada_salida.edit', 
            'delete'        => 'entrada_salida.eliminar',
            'search'        => 'entrada_salida.buscar',
            'index'         => 'entrada_salida.index',
            'verdetalle'         => 'entrada_salida.verdetalle',
            'listproveedores'    => 'entrada_salida.listproveedores',
            'listproductos'    => 'entrada_salida.listproductos',
            'listproductosalida'    => 'entrada_salida.listproductosalida'
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
        $entidad          = 'EntradaSalida';
        $numero      = Libreria::getParam($request->input('numero_doc'));
        $presentacion_id      = Libreria::getParam($request->input('tipo'));
        $fechai      = Libreria::getParam($request->input('fechai'));
        $fechaf      = Libreria::getParam($request->input('fechaf'));
        $resultado        = EntradaSalida::listar($numero,$presentacion_id, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro Documento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo', 'numero' => '1');
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
        $cboPresentacion     = [''=>'Seleccione','E'=>'Entrada','S'=>'Salida'];
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
        $cboDocumento       = [''=>'Seleccione']+ array('E'=>'Doc. Entrada', 'S'=>'Doc. Salida');
        //$cboTipo       = array('E'=>'Entrada', 'S'=>'Salida');
        $cboProducto       = array(0=>'           Seleccione Producto........................................');
        $cboEntrada       = array(0=>'            Seleccione Producto.........................................');
        $cboProveedor        = array(0=>'Seleccione Proveedor...');
        $cboPresentacion = ['0'=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        $cboLaboratorio = ['0'=>'Seleccione'] + Marca::pluck('name', 'id')->all();
        $cboFecha       = array('N'=>'NO', 'S'=>'SI');
        $numero_operacion   = Libreria::codigo_operacion();
        $formData       = array('entrada_salida.store');
        $propiedades            = Propiedades::All()->last();
        $igv            = $propiedades->igv;

        $serie =1;
        $numero =1;
        $entr = EntradaSalida::All();
        $count_entr = count($entr)== 0?0:count($entr);
        if(strlen($count_entr)==1){
            $serie ="D00".($count_entr+1);
            $numero ="00000".($count_entr+1);
        }
        if(strlen($count_entr)==2){
            $serie ="D0".($count_entr+1);
            $numero ="0000".($count_entr+1);
        }
        if(strlen($count_entr)==3){
            $serie ="D".($count_entr+1);
            $numero ="00".($count_entr+1);
        }
        $fecha_form = date('d-m-Y');
        $ruta             = $this->rutas;
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('fecha_form', 'cboFecha','serie', 'numero', 'numero_operacion', 'entradasalida', 'cboEntrada', 'cboPresentacion','cboLaboratorio','cboDocumento', 'igv', 'formData', 'ruta', 'entidad', 'boton', 'listar', 'cboCredito', 'cboProducto', 'cboProveedor', 'cboMarca','cboCategoria','cboTipo'));
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
        $tipo = $request->input('doc');
        if($tipo == 'E'){
            $error = DB::transaction(function() use($request){

                $EntradaSalida    = new EntradaSalida();
                $EntradaSalida->fecha = $request->input('fecha');
                $EntradaSalida->tipo = 'E';
                $EntradaSalida->serie_documento = $request->input('serie_documento');
                $EntradaSalida->num_documento = $request->input('numero_documento');
                $EntradaSalida->descripcion = $request->input('comentario');
                $user           = Auth::user();
                $EntradaSalida->user_id = $user->id;
                $EntradaSalida->sucursal_id = $user->sucursal_id;
                $EntradaSalida->save();
                $entrada_salida_last    = EntradaSalida::where('sucursal_id',$user->sucursal_id)->orderBy('created_at','DSC')->take(1)->get();

                $cantidad = $request->input('cantidad');
                if($cantidad >0){
                    for($i=0;$i<$cantidad; $i++){
                        $entrada_salida_detalle    = new EntradaSalidaDetalle();
                        $entrada_salida_detalle->fecha_caducidad_string = trim($request->input("fecha_vencim".$i));
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
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        if(strpos($fec_string, $punto) !== false){
                            $fech_explode = explode('.', $fec_string);
                            if(count($fech_explode) == 2){
                                if(strlen($fech_explode[1]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        if(strpos($fec_string, $dos_puntos) !== false){
                            $fech_explode = explode(':', $fec_string);
                            if(count($fech_explode) == 2){
                                if(strlen($fech_explode[1]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        if(strpos($fec_string, $guion) !== false){
                            $fech_explode = explode('-', $fec_string);
                            if(count($fech_explode) == 2){
                                if(strlen($fech_explode[1]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1]."-".$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        
                        $entrada_salida_detalle->fecha_completa = $request->input("fecha_co".$i);
                        $entrada_salida_detalle->precio_compra = $request->input("precio_compra".$i);
                        $entrada_salida_detalle->precio_venta = $request->input("precio_venta".$i);
                        $entrada_salida_detalle->cantidad = $request->input("cant".$i);
                        $entrada_salida_detalle->lote = $request->input("lot".$i);
                        $entrada_salida_detalle->entrada_salida_id = $entrada_salida_last[0]->id;
                        $entrada_salida_detalle->producto_presentacion_id = $request->input("id_producto".$i);
                        $entrada_salida_detalle->save();

                        $prod_m                 = ProductoPresentacion::find($request->input("id_producto".$i));
                        $prod_m->precio_compra = $request->input("precio_compra".$i);
                        $prod_m->precio_venta_unitario = $request->input("precio_venta".$i);
                        $prod_m->save();

                        $fecha_v = (count(Entrada::where('fecha_caducidad_string', $request->input("fecha_vencim".$i))->where('lote', $request->input("lot".$i))->where('producto_presentacion_id',$request->input("id_producto".$i))->get()) == 0)?null:(Entrada::where('fecha_caducidad_string', $request->input("fecha_vencim".$i))->where('lote', $request->input("lot".$i))->where('producto_presentacion_id',$request->input("id_producto".$i))->get()[0]);
                        if(count($fecha_v) != 0){
                            $entrada    = Entrada::find($fecha_v->id);
                            $entrada->stock = $request->input("cant".$i)+$fecha_v->stock;
                            $entrada->save(); 
                        }

                        if(count($fecha_v) == 0){
                            $entrada    = new Entrada();
                            $entrada->fecha = $request->input('fecha');
                            $entrada->fecha_caducidad_string = trim($request->input('fecha_vencim'.$i));
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
                            $entrada->stock = $request->input("cant".$i);
                            $entrada->lote = $request->input("lot".$i);
                            $entrada->producto_presentacion_id = $request->input("id_producto".$i);
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

                $EntradaSalida    = new EntradaSalida();
                $EntradaSalida->fecha = $request->input('fecha');
                $EntradaSalida->tipo = 'S';
                $EntradaSalida->serie_documento = $request->input('serie_documento');
                $EntradaSalida->num_documento = $request->input('numero_documento');
                $EntradaSalida->descripcion = $request->input('comentario');
                $user           = Auth::user();
                $EntradaSalida->user_id = $user->id;
                $EntradaSalida->sucursal_id = $user->sucursal_id;
                $EntradaSalida->save();
                $entrada_salida_last    = EntradaSalida::where('sucursal_id',$user->sucursal_id)->orderBy('created_at','DSC')->take(1)->get();


                $cantidad = $request->input('cantidad');
                if($cantidad >0){
                    for($i=0;$i<$cantidad; $i++){

                        $entrada_salida_detalle    = new EntradaSalidaDetalle();
                        $entrada_salida_detalle->fecha_caducidad_string = $request->input("fecha_vencim".$i);
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
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        if(strpos($fec_string, $punto) !== false){
                            $fech_explode = explode('.', $fec_string);
                            if(count($fech_explode) == 2){
                                if(strlen($fech_explode[1]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        if(strpos($fec_string, $dos_puntos) !== false){
                            $fech_explode = explode(':', $fec_string);
                            if(count($fech_explode) == 2){
                                if(strlen($fech_explode[1]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        if(strpos($fec_string, $guion) !== false){
                            $fech_explode = explode('-', $fec_string);
                            if(count($fech_explode) == 2){
                                if(strlen($fech_explode[1]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[1]."-".$fech_explode[0]."-01"));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                                
                            }
                            if(count($fech_explode) == 3){
                                if(strlen($fech_explode[2]) == 2){
                                    $fecha_for = date("Y-m-d",strtotime("20".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }else{
                                    $fecha_for = date("Y-m-d",strtotime("".$fech_explode[2]."-".$fech_explode[1].$fech_explode[0]));
                                    $entrada_salida_detalle->fecha_caducidad = $fecha_for;
                                }
                            }
                        }
                        $entrada_salida_detalle->precio_compra = $request->input("precio_compra".$i);
                        $entrada_salida_detalle->precio_venta = $request->input("precio_venta".$i);
                        $entrada_salida_detalle->cantidad = $request->input("cantid".$i);
                        $entrada_salida_detalle->lote = $request->input("lot".$i);
                        $entrada_salida_detalle->entrada_salida_id = $entrada_salida_last[0]->id;
                        $entrada = Entrada::find($request->input("id_entrad".$i));
                        $entrada_salida_detalle->producto_presentacion_id = $entrada->producto_presentacion_id;
                        $entrada_salida_detalle->save();

                        
                        $entrada->stock = $entrada->stock-intval($request->input("cantid".$i));
                        $entrada->save();
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

    //listar el objeto persona por dni
    public function getEntrada(Request $request, $term){
        if($request->ajax()){
            $tags = DB::table('entrada')
                    ->join('producto_presentacion','entrada.producto_presentacion_id','producto_presentacion.id')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto.unidad_id','presentacion.id')
                    ->select(
                        'presentacion.id as presentacion_id',
                        'presentacion.nombre as presentacion_nombre',
                        'entrada.fecha_caducidad_string as fecha_caducidad_string',
                        'producto_presentacion.precio_compra as precio_compra',
                        'producto_presentacion.precio_venta_unitario as precio_venta',
                        'entrada.stock as stock',
                        'entrada.lote as lote'
                        )
                    ->where("entrada.id",'=',$term)->get();
            return response()->json($tags);
        }
    }

    public function getDetalleREntrada(Request $request, $term, $dni){
        if($request->ajax()){
            $tags = DB::table('producto_presentacion')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto_presentacion.presentacion_id','presentacion.id')
                    ->select(
                        'producto_presentacion.presentacion_id as presentacion_id',
                        'producto_presentacion.cant_unidad_x_presentacion as cant_unidad_x_presentacion',
                        'producto_presentacion.precio_compra as precio_compra',
                        'producto_presentacion.precio_venta_unitario as precio_venta_unitario'
                        )
                    ->where("producto_presentacion.id",'=',$term)->get();
            return response()->json($tags);
        }
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
        $existe = Libreria::verificarExistencia($id, 'entrada_salida');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboTipo       = array('E'=>'Entrada', 'S'=>'Salida');
        $entrada_salida       = EntradaSalida::find($id);
        $fecha = Date::parse($entrada_salida->fecha)->format('Y-m-d');

        $list_detalle_es = EntradaSalida::listdetalleES($id)->get();
        $entidad        = 'EntradaSalida';
        $formData       = array('entrada_salida.update', $id);
        $ruta           = $this->rutas;
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.verdetalle')->with(compact('fecha', 'ruta', 'entrada_salida', 'proveedor', 'formData', 'entidad', 'boton', 'listar', 'list_detalle_es','cboTipo'));
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
            $entrada_salida = EntradaSalida::find($id);
            $detalle = EntradaSalidaDetalle::where('entrada_salida_id', $entrada_salida->id)->get();
            if($entrada_salida->tipo == 'E'){
                foreach ($detalle as $key => $value) {
                    $entrada_ = Entrada::where('fecha_caducidad_string' , $value->fecha_caducidad_string)->where('lote', $value->lote)->where('producto_presentacion_id', $value->producto_presentacion_id)->get()[0];
                    // echo "datos ".$entrada_ ;
                    if(count($entrada_) != 0){
                        if($entrada_->stock >= $value->cantidad){
                            $entrada_->stock = ($entrada_->stock-$value->cantidad);
                            $entrada_->save();
                            $value->delete();
                        }else{
                            
                        }
                    }
                }
                $entrada_salida->delete();
            }
            if($entrada_salida->tipo == 'S'){
                foreach ($detalle as $key => $value) {
                    $entrada_s = Entrada::where('fecha_caducidad_string' , $value->fecha_caducidad_string)->where('lote', $value->lote)->where('producto_presentacion_id', $value->producto_presentacion_id)->get()[0];
                    if(count($entrada_s) != 0){
                        if($entrada_s->stock >= $value->cantidad){
                            $entrada_s->stock = ($entrada_s->stock+$value->cantidad);
                            $entrada_s->save();
                            $value->delete();
                        }else{
                            
                        }
                    }
                }
                $entrada_salida->delete();
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
        $existe = Libreria::verificarExistencia($id, 'entrada_salida');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = EntradaSalida::find($id);
        $entidad  = 'EntradaSalida';
        $formData = array('route' => array('entrada_salida.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
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
        $tags = DB::table('producto_presentacion')
                        ->join('presentacion','producto_presentacion.presentacion_id','presentacion.id')
                        ->join('producto','producto_presentacion.producto_id','producto.id')
                        ->select(
                            'producto_presentacion.id as id',
                            'producto_presentacion.presentacion_id as presentecion_id',
                            'producto.descripcion as descripcion',
                            'producto.sustancia_activa as sustancia_activa',
                            'presentacion.nombre as presentacion',
                            'producto.deleted_at as deleted_at'
                        )
                        ->where("producto.codigo",'LIKE', '%'.$term.'%')
                        ->orWhere("producto.codigo_barra",'LIKE', '%'.$term.'%')
                        ->orWhere("producto.sustancia_activa",'LIKE', '%'.$term.'%')
                        ->orWhere("producto.descripcion",'LIKE', '%'.$term.'%')->limit(8)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            /*if($tag->deleted_at == null){
                 $formatted_tags[] = ['id' => $tag->id, 'presentecion_id'=>$tag->presentecion_id, 'text' => $tag->descripcion.' '.$tag->sustancia_activa.'   ['.$tag->presentacion.'] '];
            }*/
            if($tag->deleted_at == null){
                 $formatted_tags[] = ['id' => $tag->id, 'presentecion_id'=>$tag->presentecion_id, 'text' => $tag->descripcion.' - ['.$tag->presentacion.'] '];
            }
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }


    public function listproductosalida(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $user = Auth::user();
        $tags = DB::table('entrada')
                    ->join('producto_presentacion','entrada.producto_presentacion_id','producto_presentacion.id')
                    ->join('producto','producto_presentacion.producto_id','producto.id')
                    ->join('presentacion','producto.unidad_id','presentacion.id')
                    ->select(
                        'producto_presentacion.id as producto_id',
                        'entrada.id as entrada_id',
                        'producto.descripcion as descripcion',
                        'producto.sustancia_activa as sustancia_activa',
                        'presentacion.nombre as presentacion',
                        'entrada.stock as stock',
                        'entrada.deleted_at as deleted_at',
                        'entrada.lote as lote',
                        'entrada.sucursal_id as sucursal_id'
                        )
                    ->where("producto.codigo",'LIKE', '%'.$term.'%')
                    ->orWhere("producto.codigo_barra",'LIKE', '%'.$term.'%')
                    ->orWhere("producto.descripcion",'LIKE', '%'.$term.'%')
                    ->orWhere("producto.sustancia_activa",'LIKE', '%'.$term.'%')
                    ->orWhere("entrada.lote",'LIKE', '%'.$term.'%')
                    ->limit(8)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            if($tag->stock != 0 && $tag->deleted_at == null && $tag->sucursal_id == $user->sucursal_id){
                $formatted_tags[] = ['id' => $tag->entrada_id,  'text' => $tag->descripcion.' '.$tag->sustancia_activa.'   ['.$tag->presentacion.' - '.$tag->lote.'] '];
            }
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }
}
