<?php

namespace App\Http\Controllers;
use PDF;
use App\GuiaRemision;
use App\Sucursal;
use App\Producto;
use App\Presentacion;
use App\Entrada;
use App\DatosEmpresa;
use App\Caja;
use App\Venta;
use App\Bienes;
use App\ProductoPresentacion;
use Validator;
use DateTime;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GuiaRemisionController extends Controller
{

    protected $folderview      = 'app.guia';
    protected $tituloAdmin     = 'Guias de Remision';
    protected $tituloRegistrar = 'Registrar Guia';
    protected $tituloModificar = 'Modificar Guia';
    protected $tituloEliminar  = 'Eliminar Guia';
    // protected $tituloSerieVenta  = 'Serie venta';
    protected $rutas           = array('create' => 'guias.create', 
            'edit'     => 'guias.edit', 
            'delete'   => 'guias.eliminar',
            'search'   => 'guias.buscar',
            'index'    => 'guias.index',
            
            'listproductos'    => 'guias.listproductos',
            'getProducto'    => 'guias.getProducto',
            'getProductoPresentacion'    => 'guias.getProductoPresentacion',
            'getNumeroGuia'    => 'guias.getNumeroGuia',
          
            'verdetalle_g' => 'guias.verdetalle_g',
            'pdfGuia' => 'guias.pdfGuia',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina   = $request->input('page');
        $filas    = $request->input('filas');
        $entidad  = 'Guia';
        $fechai   = Libreria::getParam($request->input('fechai'));
        $fechaf   = Libreria::getParam($request->input('fechaf'));
        
        $numero_serie = Libreria::getParam($request->input('numero_serie'));
        $numero_doc = Libreria::getParam($request->input('num_doc'));
        
        $resultado        = GuiaRemision::listar($fechai, $fechaf, $numero_serie,$numero_doc);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Serie - N°Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Doc. Identidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombres Apellidos', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Emisión', 'numero' => '1');
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
        $entidad          = 'Guia';
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
        $listado_bienes = null;
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

        return view($this->folderview.'.mant')->with(compact('guia','serie','formData', 'entidad', 'boton', 'listar','ruta','cboPresentacion','cboProducto','fecha_defecto','numero_doc','cboMotivos','cboTiposDocDestino','cboTransportes','listado_bienes','empresa'));
    }
  

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        // $reglas     = array(
        // );
        // $mensajes  = array();
        // $validacion = Validator::make($request->all(), $reglas, $mensajes);
        // if ($validacion->fails()) {
        //     return $validacion->messages()->toJson();
        // }
        $respuesta=array();
        $id_guia=null;
        $valido =true;
        $mensaje_err ="";
            $error = DB::transaction(function() use($request, $id_guia){
                $user = Auth::user();
                $caja = Caja::where('estado','=','A')->where('user_id','=', $user->id)->where('deleted_at','=',null)->get()[0];
                
                $guia = new GuiaRemision();
                $guia->fecha_emision = $request->input('fecha_emision').' '.date('H:i:s');
                $guia->fecha_inicio_traslado = $request->input('fecha_traslado').' '.date('H:i:s');
                $guia->motivo_traslado = $request->input('motivo');
                $guia->modalidad_transporte = $request->input('modalidad_transporte');
                $guia->pesobruto_total = $request->input('pesobruto_total');
                $guia->nombres_destinatario =$request->input('nombres_destinatario');
                $guia->doc_identidad = $request->input('doc_identidad');
                $guia->direccion_partida = $request->input('direccion_partida');
                $guia->direccion_llegada = $request->input('direccion_llegada');
                $guia->numero_placa = $request->input('numero_placa');
                $guia->tipodoc_conductor = $request->input('tipodoc_conductor');
                $guia->numerodoc_conductor =  $request->input('numerodoc_conductor');
                $guia->observaciones   =  $request->input('observaciones');
                $guia->serie  =  $request->input('serie');
                $guia->numero  =  $request->input('numero');
                $guia->sucursal_id  = $user->sucursal_id;
                $guia->save();
                $id_guia = $guia->id;
    
                $cantidad_registros = $request->input('cantidad_registros_prod');
                for($i=0;$i< $cantidad_registros; $i++){
                    $cant_pres = $request->get('cant_pro_pres'.$i.'');
                    $producto_presentacion = ProductoPresentacion::find($request->get('prod_pres_id'.$i));
                    $id_prodPresent = $producto_presentacion->id; 
                    $bien = new Bienes();
                    $bien->cantidad =$cant_pres;
                    $bien->producto_presentacion_id =$producto_presentacion->id;
                    $bien->guiaremision_id = $id_guia;
                    $bien->save();
                }
              
            });
        //  is_null($error) ? "OK" : (is_numeric($error)?"OK":$error);
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
        
        $cboPresentacion = ['0'=>'Seleccione'];
      
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
        $existe = Libreria::verificarExistencia($id, 'guia_remisions');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $bienes = Bienes::where('guiaremision_id','=',$id)->get();
            // $detalle_ventas = Detalle_venta::where('ventas_id','=',$venta->id)->where('deleted_at','=',null)->get();
            foreach ($bienes as $key => $value) {
                $value->delete();
            }
            $guia = GuiaRemision::find($id);
            $guia->delete();
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
        $existe = Libreria::verificarExistencia($id, 'guia_remisions');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Venta::find($id);
        $entidad  = 'Guia';
        $formData = array('route' => array('guias.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
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

    public function verdetalle_g($guia_id){
        $existe = Libreria::verificarExistencia($guia_id, 'guia_remisions');
        if ($existe !== true) {
            return $existe;
        }
        $empresa = DatosEmpresa::all()->first();
        $entidad = 'Guia';
        $guia  = GuiaRemision::find($guia_id);
        $bienes = Bienes::where('guiaremision_id','=',$guia->id)->get();
        $user = Auth::user();
        return view($this->folderview.'.verdetalle')->with(compact('guia','bienes', 'entidad','empresa'));
    }

    public function pdfGuia(Request $request)
    {    
       
        $fecha = date("Y-m-d H:i:s");
        $id = Auth::id();

        $guia  = GuiaRemision::find($request->get('guia_id'));
        $bienes = Bienes::where('guiaremision_id','=',$guia->id)->get();
        $empresa = DatosEmpresa::all()->first();
        
        $titulo = $guia->serie.'-'.$guia->numero;
        $view = \View::make('app.guia.pdfguia')->with(compact('bienes', 'guia','fecha','titulo','empresa'));
        $html_content = $view->render();      
 
        PDF::SetTitle($titulo);
        PDF::AddPage('P', 'A4', 'es');
        PDF::SetTopMargin(0);
        // PDF::SetLeftMargin(20);
        //PDF::SetRightMargin(110);
        PDF::SetDisplayMode('fullpage');
        PDF::writeHTML($html_content, true, false, true, false, '');
        PDF::Output($titulo.'.pdf', 'I');
    }


}
