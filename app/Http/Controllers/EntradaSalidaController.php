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
                    $detalle_compra->presentacion_id = $request->input("id_presentacion".$i);
                    $detalle_compra->marca_id = $request->input("id_laboratorio".$i);
                    $detalle_compra->compra_id = $compra_last->id;
                    $detalle_compra->save();
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
