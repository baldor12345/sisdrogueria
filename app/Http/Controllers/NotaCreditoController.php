<?php

namespace App\Http\Controllers;

use App\NotaCredito;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Sucursal;
use App\Distrito;
use App\Venta;
use App\Medico;
use App\Vendedor;
use App\Cliente;
use App\Producto;
use App\Presentacion;
use App\Caja;
use App\Detalle_venta;
use App\DatosEmpresa;
use App\Entrada;
use App\DetalleCaja;
use App\Propiedades;
use App\DetalleVentaLote;
use App\ProductoPresentacion;
use App\NotaCreditoDetalle;
use DateTime;
// use App\Movimiento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class NotaCreditoController extends Controller
{
    protected $folderview      = 'app.notacredito';
    protected $tituloAdmin     = 'Notas de Crédito';
    protected $tituloRegistrar = 'Registrar Nota de Crédito';
    protected $tituloModificar = 'Modificar Nota de Crédito';
    protected $tituloEliminar  = 'Eliminar Nota de Crédito';
    // protected $tituloSerieVenta  = 'Serie venta';
    protected $rutas           = array('create' => 'notacreditos.create', 
            'edit'     => 'notacreditos.edit', 
            'delete'   => 'notacreditos.eliminar',
            'search'   => 'notacreditos.buscar',
            'index'    => 'notacreditos.index',
            'listclientes'    => 'notacreditos.listclientes',
            'listmedicos'    => 'notacreditos.listmedicos',
            'listvendedores'    => 'notacreditos.listvendedores',
            'listproductos'    => 'notacreditos.listproductos',
            'getProducto'    => 'notacreditos.getProducto',
            'getDetalletransaccion'    => 'notacreditos.getDetalletransaccion',
            'getNumeroBoleta_Factura'    => 'notacreditos.getNumeroBoleta_Factura',
            'create_new' => 'clientes.create',
            'create_med' => 'medico.create',
            'create_vend' => 'vendedor.create',
            'verdetalle_v' => 'notacreditos.verdetalle_v',
            'generarGuia' => 'notacreditos.generarGuia',
        );
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina   = $request->input('page');
        $filas    = $request->input('filas');
        $entidad  = 'NotaCredito';
        $fechai   = Libreria::getParam($request->input('fechai'));
        $fechaf   = Libreria::getParam($request->input('fechaf'));
        $numero_serie = Libreria::getParam($request->input('numero_serie'));
        $doc_dni_ruc = Libreria::getParam($request->input('doc_dni_ruc'));
        $resultado        = NotaCredito::listar($fechai, $fechaf, $numero_serie, $doc_dni_ruc);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Serie - N°Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cliente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Conprobante', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total S/.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha/Hora', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '1');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
   
        $ruta  = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
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
        $entidad          = 'NotaCredito';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $fecha_inicial = date('Y-m')."-01";
        $fecha_defecto = date('Y-m-d');
        
        return view($this->folderview.'.admin')->with(compact('entidad', 'fecha_defecto','title', 'titulo_registrar', 'ruta', 'fecha_inicial'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar  = Libreria::getParam($request->input('listar'), 'NO');
        $entidad = 'NotaCredito';
        $notacredito  = null;
        $formData  = array('notacreditos.store');
        $formData  = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton  = 'Registrar'; 
        $user = Auth::user();
        $serie = $user->sucursal->serie_notacredito;
        $serie_v = $user->sucursal->serie;
        $numero_doc = Libreria::numero_documento_nota_credito('B');
        $ruta = $this->rutas;
        $fecha_defecto = date('Y-m-d');
        $igv = Propiedades::all()->last()->igv;
        $cboTipos = ['CO'=>'Contado','CR'=>'Crédito'];
        $cboDocumento = ['B'=>'Boleta','F'=>'Factura'];
        $cboFormasPago = ['T'=>'Tarjeta','E'=>'Efectivo'];
        $cboPresentacion = ['0'=>'Seleccione'];
        $cboCliente = ['0'=>'Seleccione'];
        $cboMedico = ['0'=>'Seleccione']+$this->cboMedicos();
        $cboVendedor = ['0'=>'Seleccione']+$this->cboVendedores();
        $cboProducto = ['0'=>'Seleccione'];
        $date_now = date('Y-m-d');
        return view($this->folderview.'.mant')->with(compact('date_now','serie_v','notacredito','serie','igv','formData', 'entidad', 'boton', 'listar','cboTipos','ruta','cboDocumento','cboFormasPago','cboPresentacion','cboCliente','cboProducto','fecha_defecto','numero_doc', 'cboMedico','cboVendedor'));
    }

    public function cboMedicos(){
        $medicos = Medico::where('deleted_at','=',null)->get();
        $cboMed = array();
        foreach ($medicos as $key => $medico) {
            $cboMed[$medico->id] =$medico->codigo.' - '.$medico->nombres.' '.$medico->apellidos;
        }
        return $cboMed;
    }
    public function cboVendedores(){
        $vendedores = Vendedor::where('deleted_at','=',null)->get();
        $cboVend = array();
        foreach ($vendedores as $key => $vendedor) {
            $cboVend[$vendedor->id] = $vendedor->iniciales.' - '.$vendedor->nombres.' '.$vendedor->apellidos;
        }
        return $cboVend;
    }

    public function getDetalletransaccion(Request $request, $serie_documento, $numero_documento){
        $resultado        = NotaCredito::getTransaccion($serie_documento, $numero_documento);
        return response()->json($resultado);
    }

    public function getNumeroBoleta_Factura(Request $request, $tipo, $opcional){
        $numero_doc = Libreria::numero_documento_nota_credito($tipo);
        return response()->json($numero_doc);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
            // 'nombre' => 'required|max:50',
            //                 'direccion' => 'required|max:100',
            //                 'telefono' => 'required|max:15'
                        );
        $mensajes  = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $respuesta=array();
        $id_notacredito=null;
        $valor_total = $request->input('total_nc');
        $monto_limite =700;
        $valido =true;
        $mensaje_err ="";

        if($valido){
            // $ventaId = null;
            $error = DB::transaction(function() use($request, $id_notacredito, $valor_total){
                $user = Auth::user();
                $caja = Caja::where('estado','=','A')->where('user_id','=', $user->id)->where('deleted_at','=',null)->get()[0];

                $tipodoc = $request->input('documento');
                $clientec = Cliente::find($request->input('cliente_id'));
                $id_cliente=null;

                $notacredito =              new NotaCredito();
                $valor_igv =                $request->input('igv_nc');
                $notacredito->total =       $valor_total;
                $notacredito->subtotal =    $valor_total - $valor_igv;
                $notacredito->descuento =   0;//$request->input('descuento');
                $notacredito->igv =         $valor_igv;//IGV= de configuraciones
                $notacredito->fecha =       $request->input('fecha_nc').' '.date('H:i:s');
                $notacredito->estado =      'P';//P=Pendiente, C=cancelado
                $notacredito->user_id =     $user->id;
                $notacredito->caja_id =     $caja->id;
                $notacredito->sucursal_id = $user->sucursal_id;
                $notacredito->medico_id =   $request->input('idmedico');
                $notacredito->vendedor_id = $request->input('idvendedor');
                $notacredito->cliente_id =  $request->input('idcliente');
                $notacredito->serie_doc  =  $request->input('seriedoc_nc');
                $notacredito->numero_doc  = $request->input('numdoc_nc');
                $notacredito->comprobante = $request->input('documento');
                // $notacredito->comentario  = '';
                $notacredito->save();
                $id_notacredito = $notacredito->id;
    
                $cantidad_registros = $request->input('cantidad');
                for($i=0;$i< $cantidad_registros; $i++){
                    $cant =                         $request->get('cant_prod'.$i.'');
                    $cant_pres =                    $request->get('cant_pres'.$i.'');
                    $precioUnit =                   $request->get('precio_venta'.$i.'');
                    $producto_presentacion =        ProductoPresentacion::find($request->get('prod_id'.$i));
                    $id_prodPresent =               $producto_presentacion->id; 
                    $subtotal =                     round($precioUnit *  $cant_pres, 2);
                    $notacredito_detalle =          new NotaCreditoDetalle();
                    $notacredito_detalle->cantidad = $cant_pres;
                    $notacredito_detalle->precio_unitario = $precioUnit;
                    $notacredito_detalle->total =           $subtotal;
                    $notacredito_detalle->nota_credito_id = $notacredito->id;
                    $notacredito_detalle->sucursal_id =      $user->sucursal_id;
                    $notacredito_detalle->producto_presentacion_id = $producto_presentacion->id;
                    $notacredito_detalle->lotes =                    $request->get('lote_'.$i);
               
                    $entrada_encontrada = Entrada::where('lote',$request->get('lote_'.$i))->where('fecha_caducidad_string',$request->get('fecha_vcto'.$i))->get()[0];//Entrada::where('producto_id','=',$producto->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
                    $entrada = Entrada::find($entrada_encontrada->id);
                    $entrada->stock = $entrada_encontrada->stock+$cant_pres;
                    $entrada->save();
    
                    $notacredito_detalle->save();
    
                }
                
                return $notacredito->id;
            });

            // $venta01 = Venta::all()->last();
            $ventaId = $error;
            $venta01 = NotaCredito::find($ventaId == null?0:$ventaId);
            $codigo_medico = $venta01->medico_id != null? $venta01->medico->codigo: "";
            $iniciales_vendedor = $venta01->vendedor->iniciales;
            $datos_empresa = DatosEmpresa::find(1);
            // $venta01 = Venta::where('id','=', 1)->select('cliente_id','id','fecha', 'serie_doc','numero_doc','total','subtotal','igv')->get()[0];
            $cliente = Cliente::where('id','=',$venta01->cliente_id)->select('dni','nombres','apellidos','ruc','razon_social','direccion','telefono','email')->get()[0];
            $detalle_ventas = Venta::list_detalle_ventas2($venta01->id);//where('ventas_id','=',$venta01->id)->selecT('producto_id','producto_presentacion_id','cantidad')->get();
            $err01 = is_null($error) ? "OK" : (is_numeric($error)?"OK":$error);
            $bancos = array(
                [
                    "nombre_banco"=>"BBVA Continental",
                    "sigla_banco"=>"BBVA",
                    "numero_cuenta"=>"0011-0285-010015821445"
                ],
                [
                    "nombre_banco"=>"Banco de Crédito de Perú",
                    "sigla_banco"=>"BCP",
                    "numero_cuenta"=>"3052570348017"
                ]

            );
            // $entradas = Entrada::where('producto_presentacion_id','=', $producto_presentacion->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
            $respuesta = array($err01,$venta01,$cliente,$detalle_ventas,$codigo_medico, $iniciales_vendedor,$datos_empresa,$bancos );
        }else{
            $respuesta = array($mensaje_err);
        }

        return $respuesta; 

    }


    public function verdetalle_v($notacredito_id){
        $existe = Libreria::verificarExistencia($notacredito_id, 'nota_creditos');
        if ($existe !== true) {
            return $existe;
        }
        $entidad = 'NotaCredito';
        $notacredito  = NotaCredito::find($notacredito_id);

        $detalle_nc = NotaCreditoDetalle::where('nota_credito_id','=',$notacredito->id)->get();
        // echo $detalle_nc;
        $user = Auth::user();
        $igv = Propiedades::all()->last()->igv;
        return view($this->folderview.'.verdetalle')->with(compact('notacredito','igv','detalle_nc', 'entidad'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function show(NotaCredito $notaCredito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function edit(NotaCredito $notaCredito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotaCredito $notaCredito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotaCredito $notaCredito)
    {
        //
    }
}
