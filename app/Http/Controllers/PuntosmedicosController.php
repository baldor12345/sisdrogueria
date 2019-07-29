<?php

namespace App\Http\Controllers;
use PDF;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Producto;

use App\User;
use App\Presentacion;
use App\Venta;
use App\Sucursal;
use App\MantenimientoProducto;
use App\ProductoPresentacion;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class PuntosmedicosController extends Controller
{
    protected $folderview      = 'app.puntos_medicos';
    protected $tituloAdmin     = 'Puntos acumulados por Médico';
    protected $tituloRegistrar = 'Registrar stock_producto';
    protected $tituloModificar = 'Modificar stock_producto';
    protected $tituloEliminar  = 'Eliminar stock_producto';
    protected $titulo_ver  = 'Detalle de stock_producto';
    protected $rutas           = array('create' => 'stock_producto.create', 
            'edit'          => 'puntos_medicos.edit', 
            'delete'        => 'puntos_medicos.eliminar',
            'search'        => 'puntos_medicos.buscar',
            'index'         => 'puntos_medicos.index'
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
        $entidad          = 'StockProducto';
        $cod_medico      = Libreria::getParam($request->input('cod_medico'));
        $nombre_medico      = Libreria::getParam($request->input('nombre_medico'));
        $tipo_busqueda      = Libreria::getParam($request->input('tip_busqueda'));
        $fechai      = Libreria::getParam($request->input('fei'));
        $fechaf      = Libreria::getParam($request->input('fef'));

        $resultado        = Venta::puntos_acumulados_medico($fechai, $fechaf, 'otros');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Código', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Apellidos y Nombres', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Prod. Vendido', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Puntos Acumulados', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Total Puntos', 'numero' => '1');
        
        

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
        $entidad          = 'PuntosMedico';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboPresentacion     = [''=>'Todos'] + Presentacion::pluck('nombre', 'id')->all();
        $fecha_defecto = date('Y-m-d');
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboPresentacion','title', 'titulo_registrar', 'ruta','fecha_defecto'));
    }

    public function reportepuntosPDF(Request $request)
    {   
        $listapuntos  = Venta::puntos_acumulados_medico($request->get('fei'), $request->get('fef'), 'otros'); 
        // $list = MantenimientoProducto::listarstock_producto('', null);
        $lista = $listapuntos->get();
        $titulo = "PUNTOS ACUMULADOS";
        $inicio =0;
        $fecha_inicio = $request->get('fei');
        $fecha_fin = $request->get('fef');
        // $fecha = date("Y-m-d H:i:s");
        // $id = Auth::id();
        $user = Auth::user();
        
        $usuario = $user->persona;
        $sucursal = $user->sucursal;
        // $usuario = DB::table('person')->join('user', 'user.person_id', '=', 'person.id')->select('nombres as nombres', 'apellidos as apellidos', 'dni as dni', 'ruc as ruc', 'direccion as direccion')->where('user.id',$id)->get();
        // $sucursal = DB::table('user')->join('sucursal', 'user.sucursal_id', '=', 'sucursal.id')->select('nombre as nombre', 'telefono as telefono', 'direccion as direccion')->where('user.id',$id)->get();
        // $listPresentaciones = array();

        // foreach ($lista as $key => $producto) {
        //     $listPre = ProductoPresentacion::where('producto_id','=',$producto->producto_id)->get();
        //     $listPresentaciones[$producto->producto_id] = $listPre;
        // }

        $view = \View::make('app.puntos_medico.reportepuntosPDF')->with(compact('lista', 'titulo','inicio','usuario','fecha_inicio','fecha_fin', 'sucursal'));
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
