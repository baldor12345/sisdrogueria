<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;

use App\Vendedor;
use App\Venta;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendedorController extends Controller
{
    protected $folderview      = 'app.vendedor';
    protected $tituloAdmin     = 'Vendedor';
    protected $tituloRegistrar = 'Registrar Vendedor';
    protected $tituloModificar = 'Modificar Vendedor';
    protected $tituloEliminar  = 'Eliminar Vendedor';
    protected $rutas           = array('create' => 'vendedor.create', 
            'edit'   => 'vendedor.edit', 
            'delete' => 'vendedor.eliminar',
            'search' => 'vendedor.buscar',
            'index'  => 'vendedor.index'
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
        $entidad          = 'Vendedor';
        $nombre_iniciales = Libreria::getParam($request->input('nombre_iniciales'));
        $resultado        = Vendedor::listar($nombre_iniciales);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DNI', 'numero' => '1');
        $cabecera[]       = array('valor' => 'INICIALES', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombres y Apellidos', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Celular', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Telefono', 'numero' => '1');
        // $cabecera[]       = array('valor' => 'Direccion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
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
        $entidad          = 'Vendedor';
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
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Vendedor';
        $vendedor        = null;
        // $cboTipoDocumento = array('dni' => 'DNI', 'ruc'=>'RUC');
        $formData       = array('vendedor.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        $ruta             = $this->rutas;
        // $accion = 0;
        return view($this->folderview.'.mant')->with(compact( 'ruta', 'vendedor', 'formData', 'entidad', 'boton', 'listar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        
        $reglas = array(
            'iniciales'       => 'required|max:20',
            'nombres'    => 'required|max:100',
            'apellidos'    => 'required|max:100',
            );
       
       
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $vendedor  = new Vendedor();
            $vendedor->dni = $request->input('dni');
            $vendedor->nombres    = strtoupper($request->input('nombres'));
            $vendedor->apellidos  = strtoupper($request->input('apellidos'));
            $vendedor->iniciales       = $request->input('iniciales');
            // $vendedor->direccion   = strtoupper($request->input('direccion'));
            // $vendedor->dni        = $request->input('dni');
            // $vendedor->telefono    = $request->input('telefono');
            // $vendedor->email       = $request->input('email');
            $vendedor->save();
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
    public function edit($id, Request $request)
    {   
         $existe = Libreria::verificarExistencia($id, 'vendedor');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $vendedor        = Vendedor::find($id);
        $entidad        = 'vendedor';
        $formData       = array('vendedor.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'vendedor', 'formData', 'entidad', 'boton', 'listar'));
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
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'iniciales'       => 'required|max:8',
            'nombres'    => 'required|max:100',
            'apellidos'    => 'required|max:100',
            );
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $vendedor  = Vendedor::find($id);
            $vendedor->dni        = $request->input('dni');
            $vendedor->nombres    = strtoupper($request->input('nombres'));
            $vendedor->apellidos  = strtoupper($request->input('apellidos'));
            $vendedor->iniciales     = $request->input('iniciales');
            $vendedor->save();
            // $vendedor->direccion   = strtoupper($request->input('direccion'));
            // $vendedor->telefono    = $request->input('telefono');
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
        $existe = Libreria::verificarExistencia($id, 'vendedor');
        if ($existe !== true) {
            return $existe;
        }
        $mensaje = "";
        $error = DB::transaction(function() use($id, $mensaje){
            $vendedor = Vendedor::find($id);
            if(!is_null($medico)){
                $ventas = Venta::where('vendedor_id','=',$medico->id)->where('deleted_at','=', null)->get();
                if(count($ventas) <= 0){
                    $vendedor->delete();
                }else{
                    $mensaje = "El médico que intenta eliminar";
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
        $mensaje=null;
        $existe = Libreria::verificarExistencia($id, 'vendedor');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Vendedor::find($id);
        $entidad  = 'vendedor';
     
        $formData = array('route' => array('vendedor.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }

    public function getVendedores(Request $request){
        if($request->ajax()){
            $vendedores = Vendedor::where('deleted_at','=',null)->get();
            $cantidad = count($vendedores);
            $res = array($cantidad, $vendedores);
            return response()->json($res);
        }
    }

}
