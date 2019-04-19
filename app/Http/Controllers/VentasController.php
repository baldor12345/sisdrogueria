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
        $cabecera[]       = array('valor' => 'Fecha/Hora', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total S/.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Sucursal', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comprobante', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Forma de pago', 'numero' => '1');
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
        $ruta = $this->rutas;
        $igv = Propiedades::all()->last()->igv;
        $cboTipos = ['CO'=>'Contado','CR'=>'Crédito'];
        $cboDocumento = ['V'=>'Voleta','F'=>'Factura'];
        $cboFormasPago = ['T'=>'Tarjeta','E'=>'Efectivo'];
        $cboPresentacion = ['0'=>'Seleccione'];
        $cboCliente = ['0'=>'Seleccione'];
        $cboProducto = ['0'=>'Seleccione'];
        return view($this->folderview.'.mant')->with(compact('venta','igv','formData', 'entidad', 'boton', 'listar','cboTipos','ruta','cboDocumento','cboFormasPago','cboPresentacion','cboCliente','cboProducto'));
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
        
        $error = DB::transaction(function() use($request){
            $user = Auth::user();
            $caja = Caja::where('estado','=','A')->where('deleted_at','=',null)->get()[0];
            $venta = new Venta();
            $venta->total = $request->input('total');
            $venta->descuento = 0;//$request->input('descuento');
            $venta->igv = $request->input('igv');//IGV= de configuraciones
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
            $venta->tipo_pago =  $request->input('tipo_venta');
            $venta->forma_pago =  $request->input('forma_pago');
            
            $cantidad = $request->input('cantidad_registros');
            $detalle_caja = new DetalleCaja();
            $detalle_caja->caja_id = $caja->id;
            if($id_cliente != 0){
                $detalle_caja->cliente_id = $id_cliente;
            }
            $detalle_caja->forma_pago = $request->input('forma_pago');
            $detalle_caja->concepto_id = 3;
            $detalle_caja->estado = 'P';
            $detalle_caja->fecha = date('Y-m-d H:i:s');

            $numero_operacion = Libreria::codigo_operacion();
            $venta->numero_operacion = $numero_operacion;
            $venta->codigo_venta = Count(Venta::where('caja_id','=',$caja->id)->get());
            $detalle_caja->ingreso = $venta->total;
            $detalle_caja->numero_operacion = $numero_operacion;//se debe generar automatico
            $detalle_caja->codigo_operacion =  $venta->codigo_venta;

            $venta->save();
            $detalle_caja->save();

            for($i=0;$i<$cantidad; $i++){
                $producto = Producto::find($request->get('producto_id'.$i));
               
                $cant =$request->get('cantidad'.$i);
               
                $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto->id)->where('presentacion_id','=',$request->get('presentacion_id'.$i))->get()[0];
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
                $detalle_venta->save();

                $entradas = Venta::listarentradas($producto->id);//Entrada::where('producto_id','=',$producto->id)->where('stock','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
                
                for ($i=0; $i<count($entradas) ; $i++) {
                    $cant_actual = $entradas[$i]->stock;
                    
                    $entrada1 = Entrada::find($entradas[$i]->id);
                    
                    if($cant > $cant_actual){
                        $entrada1->stock = 0;
                        $entrada1->save();
                        $cant = $cant - $cant_actual;
                        $detalleventa_lote = new DetalleVentaLote();
                        $detalleventa_lote->cantidad = $cant;
                        $detalleventa_lote->entrada_id =$entrada1->id;
                        $detalleventa_lote->detalle_venta_id =$detalle_venta->id;
                        $detalleventa_lote->save();
                    }else{
                        $entrada1->stock -= $cant;
                        $entrada1->save();
                       
                        $detalleventa_lote = new DetalleVentaLote();
                        $detalleventa_lote->cantidad = $cant;
                        $detalleventa_lote->entrada_id =$entrada1->id;
                        $detalleventa_lote->detalle_venta_id =$detalle_venta->id;
                        $detalleventa_lote->save();
                        $cant = 0;
                        break;
                    }
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
    public function edit(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'ventas');
        if ($existe !== true) {
            return $existe;
        }
        // $listar   = Libreria::getParam($request->input('listar'), 'NO');
        // $ventas = Sucursal::find($id);
        // $cboDistritos = [''=>'Seleccione'] + Distrito::pluck('nombre', 'id')->all();
        // $entidad  = 'Sucursal';
        // $formData = array('sucursal.update', $id);
        // $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        // $boton    = 'Modificar';
       
        return view($this->folderview.'.mant')->with(compact('ventas', 'formData', 'entidad', 'boton', 'listar','cboDistritos'));
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

            $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->get();
            for($i=0;$i<count($detalle_ventas);$i++){
                $detalle_ventas[$i]->delete();
            }

            $venta->delete();
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
        $boton    = 'Eliminar';
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
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nombres." ".$tag->apellidos];
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
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->descripcion." "];
        }
        return \Response::json($formatted_tags);
    }

    public function getProducto(Request $request, $producto_id){
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
    public function getProductoPresentacion(Request $request, $producto_id, $presentacion_id){
        $producto_presentacion = ProductoPresentacion::where('producto_id','=',$producto_id)->where('presentacion_id','=',$presentacion_id)->get()[0];
        return response()->json($producto_presentacion);
    }
}
