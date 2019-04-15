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
        $numero      = Libreria::getParam($request->input('numero'));
        $proveedor      = Libreria::getParam($request->input('proveedor'));
        $fechai      = Libreria::getParam($request->input('fechai'));
        $fechaf      = Libreria::getParam($request->input('fechaf'));
        $resultado        = Compra::listarcompra($numero, $proveedor, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
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
        $entidad          = 'EntradaSalida';
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
        $entidad        = 'EntradaSalida';
        $entradasalida       = null;
        $cboDocumento       = array(1=>'Doc. Entrada', 2=>'Doc. Salida');
        $cboTipo       = array('E'=>'Entrada', 'S'=>'Salida');
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
        $tipo = $request->input('tipo');
        if($tipo == 'E'){
            $error = DB::transaction(function() use($request){
                $entrada               = new Entrada();
                $entrada->documento = $request->input('documento');
                $entrada->numero_documento = $request->input('numero_documento');
                $entrada->fecha = $request->input('fecha');
                $entrada->comentario = $request->input('comentario');
                $entrada->total  = $request->input('total');
                $user           = Auth::user();
                $entrada->user_id = $user->id;
                $entrada->sucursal_id = $user->sucursal_id;
                $entrada->save();
    
                $cantidad = $request->input('cantidad');
                $entrada_last = Entrada::All()->last();
    
                if($cantidad >0){
                    for($i=0;$i<$cantidad; $i++){
                        $detalle_entrada = new DetalleEntrada();
                        $detalle_entrada->fecha_caducidad = $request->input("fecha_vencim".$i);
                        $detalle_entrada->precio_compra = $request->input("precio_compra".$i);
                        $detalle_entrada->precio_venta = $request->input("precio_venta".$i);
                        $detalle_entrada->stock = $request->input("cant".$i);
                        $detalle_entrada->cantidad = $request->input("cant".$i);
                        $detalle_entrada->lote = $request->input("lot".$i);
                        $detalle_entrada->producto_id = $request->input("id_producto".$i);
                        $detalle_entrada->presentacion_id = $request->input("id_presentacion".$i);
                        $detalle_entrada->marca_id = $request->input("id_laboratorio".$i);
                        $detalle_entrada->entrada_id = $entrada_last->id;
                        $detalle_entrada->save();
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
