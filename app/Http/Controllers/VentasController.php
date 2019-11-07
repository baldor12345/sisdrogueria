<?php

namespace App\Http\Controllers;

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
use DateTime;
// use App\Movimiento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentasController extends Controller
{

    protected $folderview      = 'app.ventas';
    protected $tituloAdmin     = 'Ventas';
    protected $tituloRegistrar = 'Registrar Venta';
    protected $tituloModificar = 'Modificar Vneta';
    protected $tituloEliminar  = 'Eliminar venta';
    // protected $tituloSerieVenta  = 'Serie venta';
    protected $rutas           = array('create' => 'ventas.create', 
            'edit'     => 'ventas.edit', 
            'delete'   => 'ventas.eliminar',
            'search'   => 'ventas.buscar',
            'index'    => 'ventas.index',
            'listclientes'    => 'ventas.listclientes',
            'listmedicos'    => 'ventas.listmedicos',
            'listvendedores'    => 'ventas.listvendedores',
            'listproductos'    => 'ventas.listproductos',
            'getProducto'    => 'ventas.getProducto',
            'getProductoPresentacion'    => 'ventas.getProductoPresentacion',
            'getNumeroBoleta_Factura'    => 'ventas.getNumeroBoleta_Factura',
            'create_new' => 'clientes.create',
            'create_med' => 'medico.create',
            'create_vend' => 'vendedor.create',
            'verdetalle_v' => 'ventas.verdetalle_v',
            'generarGuia' => 'ventas.generarGuia',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina   = $request->input('page');
        $filas    = $request->input('filas');
        $entidad  = 'Ventas';
        $fechai   = Libreria::getParam($request->input('fechai'));
        $fechaf   = Libreria::getParam($request->input('fechaf'));
        $estado   = Libreria::getParam($request->input('cboTipoVentas'));
        $numero_serie = Libreria::getParam($request->input('numero_serie'));
        $doc_dni_ruc = Libreria::getParam($request->input('doc_dni_ruc'));
        $tipo = Libreria::getParam($request->input('cboTipoV'));
        $tipoComp = Libreria::getParam($request->input('cboTipoComprobante'));
        $resultado        = Venta::listar($fechai, $fechaf, $numero_serie, $estado, $tipo, $doc_dni_ruc,$tipoComp);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Serie - N°Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cliente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total S/.', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Sucursal', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comprobante', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Forma de pago', 'numero' => '1');
        if($tipo == 'CR'){
            $cabecera[]       = array('valor' => 'Días Restantes', 'numero' => '1');
        }
        $cabecera[]       = array('valor' => 'Fecha/Hora', 'numero' => '1');
       
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');
        
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
        $entidad          = 'Ventas';
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
        $entidad = 'Ventas';
        $venta  = null;
        $formData  = array('ventas.store');
        $formData  = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton  = 'Registrar'; 
        $user = Auth::user();
        $serie = $user->sucursal->serie;
        $numero_doc = Libreria::numero_documento('B');
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
        return view($this->folderview.'.mant')->with(compact('venta','serie','igv','formData', 'entidad', 'boton', 'listar','cboTipos','ruta','cboDocumento','cboFormasPago','cboPresentacion','cboCliente','cboProducto','fecha_defecto','numero_doc', 'cboMedico','cboVendedor'));
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
        $id_venta=null;
        $valor_total = $request->input('total');
        $monto_limite =700;
        $valido =true;
        $mensaje_err ="";
        if( $valor_total > $monto_limite){
            // echo("valor: ".$valor_total);
            // echo($cliente->dni);
            if( $request->input('doccliente') == ""){
                $valido = false;
                $mensaje_err = "Para ventas mayor a s/. ".$monto_limite.", debe seleccionar un cliente con DNI válido.";
            }

            if($request->input('doccliente') == "00000000"){
                $valido = false;
                $mensaje_err = "Para ventas mayor a s/. ".$monto_limite.", debe seleccionar un cliente con DNI válido.";           
            }
        }
        if($valido){
            // $ventaId = null;
            $error = DB::transaction(function() use($request, $id_venta, $valor_total){
                $user = Auth::user();
                $caja = Caja::where('estado','=','A')->where('user_id','=', $user->id)->where('deleted_at','=',null)->get()[0];

                $tipodoc = $request->input('documento');
                $clientec = Cliente::where(( $tipodoc == 'B'?'dni':'ruc'),'=',$request->input('doccliente'))->where('deleted_at','=',null)->get();
                $id_cliente=null;
                if(count($clientec) == 0){
                    $clientenuevo = new Cliente();
                    if($tipodoc == 'B'){
                        $clientenuevo->dni = $request->input('doccliente');
                        $clientenuevo->nombres = $request->input('nombrecompleto');
                        $clientenuevo->apellidos = $request->input('apellidocompleto');
                        // $clientenuevo->nombres = $request->input('nombrescliente');
                        // $clientenuevo->apellidos = $request->input('apellidoscliente');
                    }else{
                        $clientenuevo->ruc = $request->input('doccliente');
                        $clientenuevo->razon_social = $request->input('razon_socialv');
                    }
                    $clientenuevo->direccion = $request->input('direccioncliente');
                    $clientenuevo->telefono = $request->input('telefono');
                    $clientenuevo->email = $request->input('email');
                    $clientenuevo->save();

                    $id_cliente = $clientenuevo->id;
                }else{
                    $client = Cliente::find($clientec[0]->id);
                    $client->direccion = $request->input('direccioncliente');
                    $client->telefono = $request->input('telefono');
                    $client->email = $request->input('email');
                    $client->save();
                    $id_cliente = $client->id;
                }

                $venta = new Venta();
                $valor_igv = $request->input('igv');
                $venta->total = $valor_total;
                $venta->subtotal = $valor_total - $valor_igv;
                $venta->descuento = 0;//$request->input('descuento');
                $venta->igv = $valor_igv;//IGV= de configuraciones
                $venta->descripcion = "";//$request->input('descripcion');
                $venta->fecha =$request->input('fecha').' '.date('H:i:s');
                $venta->fecha_venc = $request->input('fechafi').' '.date('H:i:s');
                $venta->estado = 'P';//P=Pendiente, C=cancelado
                $venta->user_id = $user->id;
                $venta->caja_id = $caja->id;
                $venta->sucursal_id = $user->sucursal_id;
                
                $id_medico = $request->input('cboMedico');
                $id_vendedor = $request->input('cboVendedor');
                if($id_medico != 0){
                    $venta->medico_id = $id_medico;
                }
                $venta->vendedor_id = $id_vendedor;
                if($id_cliente != 0){
                    $venta->cliente_id = $id_cliente;
                }
                $venta->comprobante =  $request->input('documento');
                $venta->tipo_pago   =  $request->input('tipo_venta');
                $venta->forma_pago  =  $request->input('forma_pago');
                $venta->serie_doc  =  $request->input('serie_documento');
                $venta->numero_doc  =  $request->input('numero_documento');
                $venta->dias = $request->input('dias');
                
                
                $detalle_caja = new DetalleCaja();
                $detalle_caja->caja_id = $caja->id;
                if($id_cliente != 0){
                    $detalle_caja->cliente_id = $id_cliente;
                }
                $detalle_caja->forma_pago = $request->input('forma_pago');
                $detalle_caja->concepto_id = 5;
                $detalle_caja->estado = 'P';
                $detalle_caja->fecha = date('Y-m-d H:i:s');
    
                // $numero_operacion = Libreria::codigo_operacion();
                $numero_operacion =  Libreria::codigo_operacion();
                $venta->numero_operacion = $numero_operacion;
                // $venta->codigo_venta = Count(Venta::where('caja_id','=',$caja->id)->get());
                $detalle_caja->ingreso = $venta->total;
                $detalle_caja->numero_operacion = $numero_operacion;//se debe generar automatico
                $detalle_caja->codigo_operacion =  $numero_operacion;
    
                $venta->save();
                $id_venta = $venta->id;
                $detalle_caja->save();
    
                $cantidad_registros = $request->input('cantidad_registros_prod');
                for($i=0;$i< $cantidad_registros; $i++){
                    // $cant = 0;
                    $producto = Producto::find($request->get('prod_id'.$i.''));
                    $cant = $request->get('cant_prod'.$i.'');
                    $cant_pres = $request->get('cant_pres'.$i.'');
                    $precioUnit = $request->get('precio_venta'.$i.'');
                    // $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto->id)->where('presentacion_id','=',$request->get('present_id'.$i))->get()[0];
                    $producto_presentacion = ProductoPresentacion::find($request->get('present_id'.$i));
                    //$precio_unit = $producto_presentacion->precio_venta_unitario; 
                    $id_prodPresent = $producto_presentacion->id; 
                    $subtotal =  round($precioUnit *  $cant_pres, 2);
                    $detalle_venta = new Detalle_venta();
                    $detalle_venta->producto_id =$producto->id; 
                    $detalle_venta->cantidad = $cant_pres;
                    $detalle_venta->precio_unitario =$precioUnit;
                    $detalle_venta->total = $subtotal;
                    $detalle_venta->ventas_id = $venta->id;
                    $detalle_venta->sucursal_id = $user->sucursal_id;
                    $detalle_venta->producto_presentacion_id = $producto_presentacion->id;
                    $puntos_acum =  $producto_presentacion->puntos == null?0: $producto_presentacion->puntos;
                    $detalle_venta->puntos_acumulados = $cant_pres * $puntos_acum;
               
                    //$entradas = Entrada::where('producto_presentacion_id','=', $id_prodPresent)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
                    $entradas = Venta::listarentradas($producto->id);//Entrada::where('producto_id','=',$producto->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
                    $lotes = "";
                        for ($j=0; $j< count($entradas) ; $j++) {
                            $entrad = Entrada::find($entradas[$j]->id);
                            $cant_actual = $entrad->stock;
                                if($cant > 0){
                                    if($cant > $cant_actual){
                                        $entrad->stock = 0;
                                        $entrad->save();
                                        $cant = $cant - $cant_actual;
                                        // $lotes = $lotes.$cant.":".$entrad->lote.":".date('d/m/Y',strtotime($entrad->fecha_caducidad)).";";
                                        $lotes = $lotes.$cant.":".$entrad->lote.":".$entrad->fecha_caducidad_string.";";
                                    }else{
                                        $entrad->stock = $cant_actual - $cant;
                                        $entrad->save();
                                        // $lotes = $lotes.$cant.":".$entrad->lote.":".date('d/m/Y',strtotime($entrad->fecha_caducidad))."";
                                        $lotes = $lotes.$cant.":".$entrad->lote.":".$entrad->fecha_caducidad_string."";
                                        $cant = 0;
                                    }
                                }
                        }
    
                    $detalle_venta->lotes = $lotes;
                    $detalle_venta->save();
    
                }
                
                return $venta->id;
            });

            // $venta01 = Venta::all()->last();
            $ventaId = $error;
            $venta01 = Venta::find($ventaId == null?0:$ventaId);
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
    public function edit(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'unidad');
        if ($existe !== true) {
            return $existe;
        }
        $listar  = Libreria::getParam($request->input('listar'), 'NO');
        $entidad = 'Ventas';
        $venta  = Venta::find($id);
        $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->where('deleted_at','=',null)->get();
        $formData  = array('ventas.update');
        $formData  = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton  = 'Modificar'; 
        $user = Auth::user();
        $ruta = $this->rutas;
        $igv = Propiedades::all()->last()->igv;
        $cboTipos = ['CO'=>'Contado','CR'=>'Crédito'];
        $cboDocumento = ['V'=>'Voleta','F'=>'Factura'];
        $cboFormasPago = ['T'=>'Tarjeta','E'=>'Efectivo'];
        $cboPresentacion = ['0'=>'Seleccione'];
        $cboCliente = ['0'=>'Seleccione'];
        $cboProducto = ['0'=>'Seleccione'];
        return view($this->folderview.'.mant')->with(compact('venta','igv','formData', 'entidad', 'boton', 'listar','cboTipos','ruta','cboDocumento','cboFormasPago','cboPresentacion','cboCliente','cboProducto','detalle_ventas'));
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
        $existe = Libreria::verificarExistencia($id, 'ventas');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array();
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
       
        $error = DB::transaction(function() use($request, $id){
            
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
        $existe = Libreria::verificarExistencia($id, 'ventas');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $venta = Venta::find($id);
            $venta->estado = 'A';//A= Anulado
            // $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->get();
            // for($i=0;$i<count($detalle_ventas);$i++){
            //     $detalle_ventas[$i]->delete();
            // }

            $venta->save();
            $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->where('deleted_at','=',null)->get();
            foreach ($detalle_ventas as $key => $value) {
                $lotes = explode(';',$value->lotes);
                
                // $prod_presentacion_id = $value->producto_presentacion_id;
                for($j=0; $j<count($lotes); $j ++){
                    $lot = explode(':',$lotes[$j]);
                    $cant = $lot[0];
                    $lote = $lot[1];
                    // echo("Present_id: ".$prod_presentacion_id." - Lote: ".$lote);
                    $entrada = Entrada::find(Entrada::idEntrada($value->producto_id, $lote));
                    // $entrada = Entrada::where('producto_presentacion_id', '=', $prod_presentacion_id)->leftjoin()->where('lote','=',$lote)->get()[0];
                    // echo("entrada:v ". $entrada);
                    $entrada->stock = $entrada->stock + $cant;
                    $entrada->save();
                }
            }
            $detalle_caja = DetalleCaja::where('codigo_operacion','=', $venta->numero_operacion)->get()[0];
            $detalle_caja->delete();
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
        $mensaje = null;
        $existe = Libreria::verificarExistencia($id, 'ventas');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Venta::find($id);
        $entidad  = 'Ventas';
        $formData = array('route' => array('ventas.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }

    public function listclientes(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Cliente::listarclientes($term);
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->dni == null?$tag->razon_social:$tag->nombres." ".$tag->apellidos];
        }
        return \Response::json($formatted_tags);
    }
    public function listmedicos(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Medico::listarmedicos($term);
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->codigo."-".$tag->nombres." ".$tag->apellidos];
        }
        return \Response::json($formatted_tags);
    }
    public function listvendedores(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Vendedor::listarvendedores($term);
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->iniciales."-".$tag->nombres." ".$tag->apellidos];
        }
        return \Response::json($formatted_tags);
    }

    public function listproductos(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Producto::listarproductosventa($term);
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' =>$tag->descripcion." - ".$tag->sustancia_activa.""];
        }
        return \Response::json($formatted_tags);
    }

    public function getProductoOriginAL(Request $request, $producto_id){
        if($request->ajax()){
            $producto = Producto::find($producto_id);
            $entradas = Venta::listarentradas($producto_id);//Entrada::where('producto_id','=',$producto->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
            $stock = 0;
            // $fecha_venc= count($entradas)>0?date('Y-m-d',strtotime($entradas[0]->fecha_caducidad)):null;
            $fecha_venc= count($entradas)>0?$entradas[0]->fecha_caducidad_string:null;
            $precio_unidad = count($entradas)>0?$entradas[0]->precio_venta:0;
            $lote = count($entradas)>0?$entradas[0]->lote:0;
            $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto_id)->where('deleted_at','=',null)->get();
            
            $cboPresentacion = '';
            foreach ($producto_presentacion as $key => $value) {
                $cboPresentacion =  $cboPresentacion.'<option value="'.$value->presentacion->id.'">'.$value->presentacion->nombre.'</option>';
            }
            if(count($entradas) > 0){
                foreach ($entradas as $key => $value) {
                    $stock += $value->stock;
                }
            }
        
            $res = array($producto, $stock, $precio_unidad,$cboPresentacion, $fecha_venc, $lote);
            return response()->json($res);
        }
    }

    public function getProducto(Request $request, $producto_id){
        if($request->ajax()){
            $producto = Producto::find($producto_id);
            
            $entradas = Venta::listarentradas($producto_id);//Entrada::where('producto_id','=',$producto->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
            $stock = 0;
            // $fecha_venc= count($entradas)>0?date('Y-m-d',strtotime($entradas[0]->fecha_caducidad_string)):null;
            $fecha_venc= count($entradas)>0?$entradas[0]->fecha_caducidad_string:null;
            $precio_unidad ='';// count($entradas)>0?$entradas[0]->precio_venta:0;
            $lote = count($entradas)>0?$entradas[0]->lote:0;
            $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto_id)->where('deleted_at','=',null)->get();
            
            $cboPresentacion = '';
            foreach ($producto_presentacion as $key => $value) {
                $cboPresentacion =  $cboPresentacion.'<option value="'.$value->id.'">'.$value->presentacion->nombre.' X '.$value->cant_unidad_x_presentacion.'</option>';
            }
            if(count($entradas) > 0){
                foreach ($entradas as $key => $value) {
                    $stock += $value->stock;
                }
            }
        
            $res = array($producto, $stock, $precio_unidad,$cboPresentacion, $fecha_venc, $lote);
            return response()->json($res);
        }
    }
    public function getProductoPresentacion(Request $request, $producto_id, $prod_presentacion_id){
        $producto_presentacion = ProductoPresentacion::where('id','=',$prod_presentacion_id)->where('deleted_at','=',null)->get()[0];
        return response()->json($producto_presentacion);
    }
    public function getNumeroBoleta_Factura(Request $request, $tipo, $opcional){
        $numero_doc = Libreria::numero_documento($tipo);
        return response()->json($numero_doc);
    }

    public function verdetalle_v($venta_id){
        $existe = Libreria::verificarExistencia($venta_id, 'ventas');
        if ($existe !== true) {
            return $existe;
        }
        $entidad = 'Ventas';
        $venta  = Venta::find($venta_id);

        $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->get();
        $user = Auth::user();
        // $ruta = $this->rutas;
        $igv = Propiedades::all()->last()->igv;
        return view($this->folderview.'.verdetalle')->with(compact('venta','igv','detalle_ventas', 'entidad'));
    }

    function getNumDias_dosFechas($fecha_inicial, $fecha_final){
        $fecha_init= date("Y-m-d", strtotime($fecha_inicial));
        $fecha_inicial = new DateTime($fecha_init);

        $fecha_fin= date("Y-m-d", strtotime($fecha_final));
        $fecha_final = new DateTime($fecha_fin);

        $diferencia = $fecha_inicial->diff($fecha_final);
        $numeroDias = $diferencia->format('%R%a días');
        return $numeroDias;
    }

    function generarGuia($venta_id){
        $existe = Libreria::verificarExistencia($venta_id, 'ventas');
        if ($existe !== true) {
            return $existe;
        }
        $listado_bienes = Detalle_venta::where('ventas_id','=',$venta_id)->get();
       
        $listar  = Libreria::getParam('NO', 'NO');
        $entidad = 'Guia';
        $guia  = null;
        $formData  = array('guias.store');
        $formData  = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton  = 'Registrar'; 
        $user = Auth::user();
        $serie = $user->sucursal->serie;
        $numero_doc = Libreria::numero_doc_guia();
        $ruta = $this->rutas;
        $fecha_defecto = date('Y-m-d');
        $cboPresentacion = ['0'=>'Seleccione'];
        $cboProducto = ['0'=>'Seleccione'];
       
        $empresa = DatosEmpresa::all()->first();

        $cboMotivos = [
            'VENTA' => 'VENTA',
            'COMPRA' => 'COMPRA',
            'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA',
            'TRASLADO DE BIENES PARA TRANSFORMACION' => 'TRASLADO DE BIENES PARA TRANSFORMACION',
            'TRASLADO EMISOR ITINERANTE DE COMPROBANTES DE PAGO' => 'TRASLADO EMISOR ITINERANTE DE COMPROBANTES DE PAGO',
            'TRASLADO ZONA PRIMARIA' => 'TRASLADO ZONA PRIMARIA',
            'IMPORTACION' => 'IMPORTACION',
            'EXPORTACION' => 'EXPORTACION',
            'OTROS MOTIVOS' => 'OTROS MOTIVOS',
            ];
        $cboTiposDocDestino = [
            'DNI'=>'DNI',
            'RUC'=>'RUC',
            'PASAPORTE'=>'PASAPORTE',
            'DT.S/RUC'=>'DT.S/RUC',
            'C.EXT'=>'C.EXT',
            'CED.DIPL'=>'CED.DIPL',
        ];
        $cboTransportes = [
            'PUBLICO' => 'PUBLICO',
            'PRIVADO' => 'PRIVADO',
        ];

        return view('app.guia.mant')->with(compact('guia','serie','formData', 'entidad', 'boton', 'listar','ruta','cboPresentacion','cboProducto','fecha_defecto','numero_doc','cboMotivos','cboTiposDocDestino','cboTransportes','listado_bienes','empresa'));
    
    }

  
}
