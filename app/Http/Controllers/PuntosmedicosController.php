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
use App\Medico;
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
            'index'         => 'puntos_medicos.index',
            'reportepuntosPDF'         => 'puntos_medicos.reportepuntosPDF',
            'reportepuntosmedicoPDF'         => 'puntos_medicos.reportepuntosmedicoPDF'
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
        // $cod_medico      = Libreria::getParam($request->input('cod_medico'));
        // $nombre_medico      = Libreria::getParam($request->input('nombre_medico'));
        // $tipo_busqueda      = Libreria::getParam($request->input('tip_busqueda'));
        $anio      = Libreria::getParam($request->input('anio'));
        $mes      = Libreria::getParam($request->input('mes'));

        $resultado        = Venta::puntos_acumulados_medico($anio, $mes);
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
        $meses = array(
            '1'=>'Enero',
            '2'=>'Febrero',
            '3'=>'Marzo',
            '4'=>'Abril',
            '5'=>'Mayo',
            '6'=>'Junio',
            '7'=>'Julio',
            '8'=>'Agosto',
            '9'=>'Septiembre',
            '10'=>'Octubre',
            '11'=>'Noviembre',
            '12'=>'Diciembre',
        );
        $anios=null;
        $anio_inicio = 2019;
        $anio_actual = date('Y');
        $mes_actual = date('m');
        for($i = $anio_inicio; $i<= $anio_actual; $i++){
            $anios[$i] = $i;
        }

        $entidad          = 'PuntosMedico';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboPresentacion     = [''=>'Todos'] + Presentacion::pluck('nombre', 'id')->all();
        $fecha_defecto = date('Y-m').'-01';
        $fecha_defecto2 = date('Y-m-d');
        // return view($this->folderview.'.admin')->with(compact('entidad', 'cboPresentacion','title', 'titulo_registrar', 'ruta','fecha_defecto','fecha_defecto2'));
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboPresentacion','title', 'titulo_registrar', 'ruta','anios','meses','anio_actual','mes_actual'));
    }

    public function reportepuntosPDF(Request $request)
    {   
        
        $listapuntos  = Venta::puntos_acumulados_medico($request->get('fei'), $request->get('fef'), 'otros'); 
        // $list = MantenimientoProducto::listarstock_producto('', null);
        $lista = $listapuntos->get();
        $titulo = "PUNTOS ACUMULADOS POR MÉDICO";
        $inicio =0;
        $fecha_inicio = $request->get('fei');
        $fecha_fin = $request->get('fef');
        $fecha = date("Y-m-d H:i:s");
        $user = Auth::user();
        $usuario = $user->persona;
        $sucursal = $user->sucursal;
        $view = \View::make('app.puntos_medicos.reportepuntosPDF')->with(compact('lista', 'titulo','inicio','usuario','fecha_inicio','fecha_fin', 'sucursal','fecha'));
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
    public function reportepuntosmedicoPDF(Request $request)
    {   

        $meses = array(
            '1'=>'Enero',
            '2'=>'Febrero',
            '3'=>'Marzo',
            '4'=>'Abril',
            '5'=>'Mayo',
            '6'=>'Junio',
            '7'=>'Julio',
            '8'=>'Agosto',
            '9'=>'Septiembre',
            '10'=>'Octubre',
            '11'=>'Noviembre',
            '12'=>'Diciembre',
        );

        $medicos = Medico::where('deleted_at','=', null)->get();
        $resultado= array();
        $indice =0;
        foreach ($medicos as $key => $medico) {
           $listapuntos  = Venta::prod_vendidos_medico($medico->id, $request->get('anio'), $request->get('mes')); 
           if(count($listapuntos) > 0){
            $resultado[$indice][0] = $medico;
            $resultado[$indice][1] = $listapuntos;
            $indice ++;
           }
           
        }
        
        // $listapuntos  = Venta::puntos_acumulados_medico($request->get('fei'), $request->get('fef'), 'otros'); 
        // $list = MantenimientoProducto::listarstock_producto('', null);
        // $lista = $listapuntos->get();
        $titulo = "PUNTOS ACUMULADOS POR MÉDICO";
        $inicio =0;
        $anio = $request->get('anio');
        $mes = $request->get('mes');
        $fecha = date("Y-m-d H:i:s");
        $user = Auth::user();
        $usuario = $user->persona;
        $sucursal = $user->sucursal;
        $view = \View::make('app.puntos_medicos.reportepuntosmedicoPDF')->with(compact('resultado', 'titulo','inicio','usuario','anio','mes', 'sucursal','fecha','meses'));
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
