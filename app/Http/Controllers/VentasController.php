<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Sucursal;
use App\Distrito;
use App\Venta;
use App\Cliente;
use App\Producto;
use App\Presentacion;
use App\Caja;
use App\Detalle_venta;
use App\Entrada;
use App\DetalleCaja;
use App\Propiedades;
use App\DetalleVentaLote;
use App\ProductoPresentacion;

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
            'listproductos'    => 'ventas.listproductos',
            'getProducto'    => 'ventas.getProducto',
            'getProductoPresentacion'    => 'ventas.getProductoPresentacion',
            'create_new' => 'clientes.create',
            'verdetalle_v' => 'ventas.verdetalle_v',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Ventas';
        $fecha           = Libreria::getParam($request->input('fecha_inicio'));
        $resultado        = Venta::listar($fecha);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cliente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total S/.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Sucursal', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comprobante', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Forma de pago', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha/Hora', 'numero' => '1');
       
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
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
        $fecha_defecto = date('Y-m-d');
        
        return view($this->folderview.'.admin')->with(compact('entidad', 'fecha_defecto','title', 'titulo_registrar', 'ruta'));
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
        $numero_doc = Libreria::numero_documento();
        $ruta = $this->rutas;
        $fecha_defecto = date('Y-m-d');
        $igv = Propiedades::all()->last()->igv;
        $cboTipos = ['CO'=>'Contado','CR'=>'Crédito'];
        $cboDocumento = ['B'=>'Boleta','F'=>'Factura'];
        $cboFormasPago = ['T'=>'Tarjeta','E'=>'Efectivo'];
        $cboPresentacion = ['0'=>'Seleccione'];
        $cboCliente = ['0'=>'Seleccione'];
        $cboProducto = ['0'=>'Seleccione'];
        return view($this->folderview.'.mant')->with(compact('venta','serie','igv','formData', 'entidad', 'boton', 'listar','cboTipos','ruta','cboDocumento','cboFormasPago','cboPresentacion','cboCliente','cboProducto','fecha_defecto','numero_doc'));
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
        $id_venta=null;
        $error = DB::transaction(function() use($request, $id_venta){
            $user = Auth::user();
            $caja = Caja::where('estado','=','A')->where('deleted_at','=',null)->get()[0];
            $venta = new Venta();
            $valor_total = $request->input('total');
            $valor_igv = $request->input('igv');
            $venta->total = $valor_total;
            $venta->subtotal = $valor_total - $valor_igv;
            $venta->descuento = 0;//$request->input('descuento');
            $venta->igv = $valor_igv;//IGV= de configuraciones
            $venta->descripcion = "";//$request->input('descripcion');
            $venta->fecha = date('Y-m-d H:i:s');
            $venta->estado = 'P';//P=Pendiente, C=cancelado
            $venta->user_id = $user->id;
            $venta->caja_id = $caja->id;
            $venta->sucursal_id = $user->sucursal_id;
            $id_cliente = $request->input('cboCliente');
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

            $numero_operacion = Libreria::codigo_operacion();
            $venta->numero_operacion = $numero_operacion;
            // $venta->codigo_venta = Count(Venta::where('caja_id','=',$caja->id)->get());
            $detalle_caja->ingreso = $venta->total;
            $detalle_caja->numero_operacion = $numero_operacion;//se debe generar automatico
            $detalle_caja->codigo_operacion =  $venta->codigo_venta;

            $venta->save();
            $id_venta = $venta->id;
            $detalle_caja->save();

            $cantidad_registros = $request->input('cantidad_registros_prod');
            for($i=0;$i< $cantidad_registros; $i++){
                // $cant = 0;
                $producto = Producto::find($request->get('prod_id'.$i.''));
                $cant = $request->get('cant_prod'.$i.'');
              
                $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto->id)->where('presentacion_id','=',$request->get('present_id'.$i))->get()[0];
                $precio_unit = $producto_presentacion->precio_venta_unitario; 
                $subtotal =  round($precio_unit *  $cant, 2);
               
                $detalle_venta = new Detalle_venta();
                $detalle_venta->producto_id =$producto->id; 
                $detalle_venta->cantidad = $cant;
                $detalle_venta->precio_unitario =$precio_unit;
                $detalle_venta->total = $subtotal;
                $detalle_venta->ventas_id = $venta->id;
                $detalle_venta->sucursal_id = $user->sucursal_id;
                $detalle_venta->producto_presentacion_id = $producto_presentacion->id;
                
                $entradas = Entrada::where('producto_presentacion_id','=', $producto_presentacion->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
                 //$entradas = Venta::listarentradas($producto->id);//Entrada::where('producto_id','=',$producto->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
                
                $lotes = "";
                    for ($j=0; $j< count($entradas) ; $j++) {
                        $cant_actual = $entradas[$j]->stock;
                            if($cant > 0){
                                if($cant > $cant_actual){
                                    $entradas[$j]->stock = 0;
                                    $entradas[$j]->save();
                                    $cant = $cant - $cant_actual;
                                    $lotes = $lotes.$cant.":".$entradas[$j]->lote.":".date('d/m/Y',strtotime($entradas[$j]->fecha_caducidad)).";";
                                }else{
                                    $entradas[$j]->stock = $cant_actual - $cant;
                                    $entradas[$j]->save();
                                    $lotes = $lotes.$cant.":".$entradas[$j]->lote.":".date('d/m/Y',strtotime($entradas[$j]->fecha_caducidad))."";
                                    $cant = 0;
                                }
                            }
                    }

                $detalle_venta->lotes = $lotes;
                $detalle_venta->save();

            }
        });
        $venta01 = Venta::all()->last();
        // $venta01 = Venta::where('id','=', 1)->select('cliente_id','id','fecha', 'serie_doc','numero_doc','total','subtotal','igv')->get()[0];
        $cliente = Cliente::where('id','=',$venta01->cliente_id)->select('dni','nombres','apellidos','ruc','razon_social','direccion')->get()[0];
        $detalle_ventas = Venta::list_detalle_ventas($venta01->id);//where('ventas_id','=',$venta01->id)->selecT('producto_id','producto_presentacion_id','cantidad')->get();
        $err01 = is_null($error) ? "OK" : $error;
        // $entradas = Entrada::where('producto_presentacion_id','=', $producto_presentacion->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
        $respuesta = array($err01,$venta01,$cliente,$detalle_ventas);

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
            $detalle_ventas = Detalle_ventas::where('ventas_id','=',$venta->id)->where('deleted_at','=',null)->get();
            foreach ($detalle_ventas as $key => $value) {
                $lotes = explode(';',$value->lotes);

                $prod_presentacion_id = $value->producto_presentacion_id;
                for($j=0; $j<count($lotes); $j ++){
                    $lot = explode(':',$lotes[$j]);
                    $cant = $lot[0];
                    $lote = $lot[1];
                    $entrada = Entrada::where('producto_presentacion_id', '=', $prod_presentacion_id)->where('lote','=',$lote)->get()[0];
                    $entrada->stock = $entrada->stock - $cant;
                    $entrada->save();
                }
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
            $fecha_venc= count($entradas)>0?date('Y-m-d',strtotime($entradas[0]->fecha_caducidad)):null;
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
            $fecha_venc= count($entradas)>0?date('Y-m-d',strtotime($entradas[0]->fecha_caducidad)):null;
            $precio_unidad ='';// count($entradas)>0?$entradas[0]->precio_venta:0;
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
    public function getProductoPresentacion(Request $request, $producto_id, $presentacion_id){
        $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto_id)->where('presentacion_id','=',$presentacion_id)->where('deleted_at','=',null)->get()[0];
        return response()->json($producto_presentacion);
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
}
