<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Sucursal;
use App\Distrito;
use App\Venta;
use App\Cliente;
use App\Comprobante;
use App\FormaPago;
use App\Producto;
use App\DetalleCompra;
use App\Presentacion;

use App\Movimiento;
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
            'listproductos'    => 'compra.listproductos',
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
        // $cabecera[]       = array('valor' => 'Telefono', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        // $titulo_serie_venta = $this->tituloSerieVenta;
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
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Ventas';
        $venta  = null;
        $formData     = array('ventas.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $user = Auth::user();
        $ruta  = $this->rutas;
        $cboComprobante = [''=>'Seleccione'] + Comprobante::pluck('nombre', 'id')->all();
        $cboFormaPago = [''=>'Seleccione'] + FormaPago::pluck('nombre', 'id')->all();
        $cboPresentacion = [''=>'Seleccione'] + Presentacion::pluck('nombre', 'id')->all();
        return view($this->folderview.'.mant')->with(compact('venta','formData', 'entidad', 'boton', 'listar','cboComprobante','ruta','cboFormaPago','cboPresentacion'));
    }

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
            // 'nombre' => 'required|max:50',
            //                 'direccion' => 'required|max:100',
            //                 'telefono' => 'required|max:15'
                        );
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        
        $error = DB::transaction(function() use($request){
            $user = Auth::user();
            $caja = Caja::where('estado','=','A')->where('deleted_at','=',null)->get()[0];

            $venta = new Ventas();
            $venta->total = $request->input('total');
            $venta->descuento = $request->input('descuento');
            $venta->igv = 0.18;//IGV= de configuraciones
            $venta->descripcion = $request->input('descripcion');
            $venta->fecha_hora = date('Y-m-d');
            $venta->estado = 'P';//P=Pendiente, C=cancelado
            $venta->user_id = $user->id;
            $venta->caja_id = $caja->id;
            $venta->sucursal_id = $user->sucursal_id;
            $venta->cliente_id =  $request->input('cboCliente');
            $venta->comprobante =  $request->input('cboComprobante');
            $venta->save();

            $cantidad = $request->input('cantidad_registros');

            for($i=0;$i<$cantidad; $i++){
                $producto = Producto::find($request->get('producto_id'.$i));
                $detalle_venta = new Detalle_venta();

                $cant =$request->get('cantidad'.$i);
                $precio_unit = $producto->precio; 
                $descuento = $request->get('descuento'.$i);
                $unidad_id = $request->get('unidad_id'.$i);

                $detalle_venta->producto_id =$producto->id; 
                $detalle_venta->cantidad = $cant;
                $detalle_venta->precio_unitario =$precio_unit;
                $detalle_venta->descuento = $descuento ; 
                $detalle_venta->total = $cantidad*$precio_unit - $descuento;
                $detalle_venta->unidad_id = $unidad_id;
                $detalle_venta->ventas_id = $venta->id;
                $detalle_venta->sucursal_id = $user->sucursal_id;
                $detalle_venta->save();
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
            $venta = Ventas::find($id);

            $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->get();
            for($i=0;$i<count($detalle_venta);$i++){
                $detalle_venta[$i]->delete();
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
        $modelo   = Sucursal::find($id);
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

    public function getProducto(Request $request, $producto_id){
        if($request->ajax()){
            $producto = Producto::find($producto_id);
            $detalle_compras = DetalleCompra::where('producto_id','=',$producto_id)->where('cantidad','>',0)->where('deleted_at','=',null)->orderBy('fecha_caducidad', 'ASC')->get();
            $stock = 0;
            $precio_unidad = $detalle_compras[0]->precio_venta | 0;
            $presentacion_id = $detalle_compras[0]->presentacion_id | 0;

            foreach ($detalle_compras as $key => $value) {
                $stock += $value->cantidad;
            }
            $res = array($producto, $stock, $precio_unidad,$presentacion_id);
            return response()->json($res);
        }
    }
}
