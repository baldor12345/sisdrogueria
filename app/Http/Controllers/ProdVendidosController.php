<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;

use App\Producto;
use App\Venta;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProdVendidosController extends Controller
{
    protected $folderview      = 'app.productoventas';
    protected $tituloAdmin     = 'Productos vendidos';
    // protected $tituloRegistrar = 'Registrar Medico';
    // protected $tituloModificar = 'Modificar Medico';
    // protected $tituloEliminar  = 'Eliminar Medico';
    protected $rutas           = array('create' => 'productoventa.create', 
            'edit'   => 'productoventa.edit', 
            'delete' => 'productoventa.eliminar',
            'search' => 'productoventa.buscar',
            'index'  => 'productoventa.index'
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
        $entidad          = 'ProdVenta';
        $nombre = Libreria::getParam($request->input('nombre'));
        $fechai = Libreria::getParam($request->input('fechai'));
        $fechaf = Libreria::getParam($request->input('fechaf'));
        $resultado  = Venta::listarproductosvendidos($nombre, $fechai, $fechaf);
        $lista      = $resultado->get();
        $cabecera   = array();
        $cabecera[] = array('valor' => '#', 'numero' => '1');
        $cabecera[] = array('valor' => 'NOMBRE PRODUCTO', 'numero' => '1');
        $cabecera[] = array('valor' => 'CANTIDAD VENDIDA', 'numero' => '1');
        // $cabecera[] = array('valor' => 'Operaciones', 'numero' => '2');
        $fecha_defecto = date('Y-m-d');
        
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'ruta','fecha_defecto'));
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
        $entidad          = 'ProdVenta';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $fecha_defecto = date('Y-m-d');
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'ruta','fecha_defecto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
        public function create(Request $request)
        {
           
        }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
    public function store(Request $request)
    {
        
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
    public function edit($id, Request $request)
    {   
        
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
    
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    public function eliminar($id, $listarLuego)
    {
       
    }
}
