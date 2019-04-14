<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Producto;
use App\DetalleCompra;
use App\User;
use App\Presentacion;
use App\MantenimientoProducto;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class lotescaducidadController extends Controller
{
    protected $folderview      = 'app.lotes_caducidad';
    protected $tituloAdmin     = 'CONSULTA DE LOTES Y CADUCIDAD DEL INVENTARIO';
    protected $tituloRegistrar = 'Registrar lotes_caducidad';
    protected $tituloModificar = 'Modificar lotes_caducidad';
    protected $tituloEliminar  = 'Eliminar lotes_caducidad';
    protected $titulo_ver  = 'Detalle de lotes_caducidad';
    protected $rutas           = array('create' => 'lotes_caducidad.create', 
            'edit'          => 'lotes_caducidad.edit', 
            'delete'        => 'lotes_caducidad.eliminar',
            'search'        => 'lotes_caducidad.buscar',
            'index'         => 'lotes_caducidad.index'
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
        $entidad          = 'LotesCaducidad';
        $numero      = Libreria::getParam($request->input('lote'));
        $fechai      = Libreria::getParam($request->input('fechai'));
        $fechaf      = Libreria::getParam($request->input('fechaf'));
        $resultado        = MantenimientoProducto::listarlotescaducidad($numero, $fechai, $fechaf);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Producto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Lote', 'numero' => '1');
        $cabecera[]       = array('valor' => 'F. Vencimiento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Marca/Laboratorio', 'numero' => '1');
        
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
        $entidad          = 'LotesCaducidad';
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
}
