<?php

namespace App\Http\Controllers;
use PDF;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Producto;
use App\DetalleCompra;
use App\User;
use App\Presentacion;
use App\Marca;
use App\MantenimientoProducto;
use App\ProductoPresentacion;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class StockController extends Controller
{
    protected $folderview      = 'app.stock_producto';
    protected $tituloAdmin     = 'CONSULTA DEL STOCK DISPONIBLE';
    protected $tituloRegistrar = 'Registrar stock_producto';
    protected $tituloModificar = 'Modificar stock_producto';
    protected $tituloEliminar  = 'Eliminar stock_producto';
    protected $titulo_ver  = 'Detalle de stock_producto';
    protected $rutas           = array('create' => 'stock_producto.create', 
            'edit'          => 'stock_producto.edit', 
            'delete'        => 'stock_producto.eliminar',
            'search'        => 'stock_producto.buscar',
            'index'         => 'stock_producto.index'
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
        $descripcion      = Libreria::getParam($request->input('descripcion'));
        $presentacion_id      = Libreria::getParam($request->input('presentacion_id'));
        $resultado        = MantenimientoProducto::listarstock_producto($descripcion, $presentacion_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Producto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Presentacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Stock', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Inf Stock', 'numero' => '1');
        
       

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

            $listPresentaciones = array();
            foreach ($lista as $key => $producto) {
                $listPre = ProductoPresentacion::where('producto_id','=',$producto->producto_id)->get();
                $listPresentaciones[$producto->producto_id] = $listPre;
            }

            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta','titulo_ver','listPresentaciones'));
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
        $entidad          = 'StockProducto';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboPresentacion     = [''=>'Todos'] + Presentacion::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboPresentacion','title', 'titulo_registrar', 'ruta'));
    }

    public function reportestockPDF(Request $request)
    {    
        $list        = MantenimientoProducto::listarstock_producto('', null);
        $lista           = $list->get();
        $titulo = "STOCK DISPONIBLE";
        $inicio =0;

        $listPresentaciones = array();
        foreach ($lista as $key => $producto) {
            $listPre = ProductoPresentacion::where('producto_id','=',$producto->producto_id)->get();
            $listPresentaciones[$producto->producto_id] = $listPre;
        }

        $view = \View::make('app.stock_producto.reportestockPDF')->with(compact('lista', 'titulo','inicio', 'listPresentaciones'));
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
