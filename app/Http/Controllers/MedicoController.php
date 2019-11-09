<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;

use App\Medico;
use App\Venta;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicoController extends Controller
{
    protected $folderview      = 'app.medico';
    protected $tituloAdmin     = 'Medico';
    protected $tituloRegistrar = 'Registrar Medico';
    protected $tituloModificar = 'Modificar Medico';
    protected $tituloEliminar  = 'Eliminar Medico';
    protected $rutas           = array('create' => 'medico.create', 
            'edit'   => 'medico.edit', 
            'delete' => 'medico.eliminar',
            'search' => 'medico.buscar',
            'index'  => 'medico.index'
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
        $entidad          = 'Medico';
        $nombre_codigo = Libreria::getParam($request->input('nombre_codigo'));
        $resultado        = Medico::listar($nombre_codigo);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'CODIGO', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombres y Apellidos', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Celular', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Telefono', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Direccion', 'numero' => '1');
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
        $entidad          = 'Medico';
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
        $entidad        = 'Medico';
        $medico        = null;
        // $cboTipoDocumento = array('dni' => 'DNI', 'ruc'=>'RUC');
        $formData       = array('medico.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        $ruta             = $this->rutas;
        // $accion = 0;
        return view($this->folderview.'.mant')->with(compact( 'ruta', 'medico', 'formData', 'entidad', 'boton', 'listar'));
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
            'codigo'       => 'required|max:20',
            'nombres'    => 'required|max:100',
            'apellidos'    => 'required|max:100',
            );
       
       
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $medico  = new Medico();
            $medico->dni = $request->input('dni');
           
            // $medico->dni        = $request->input('dni');
            $medico->nombres    = strtoupper($request->input('nombres'));
            $medico->apellidos  = strtoupper($request->input('apellidos'));
            $medico->direccion   = strtoupper($request->input('direccion'));
            $medico->telefono    = $request->input('telefono');
            // $medico->email       = $request->input('email');
            $medico->codigo       = $request->input('codigo');
            $medico->save();
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
         $existe = Libreria::verificarExistencia($id, 'medico');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $medico        = Medico::find($id);
        $entidad        = 'Medico';
        $formData       = array('medico.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'medico', 'formData', 'entidad', 'boton', 'listar'));
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
            'codigo'       => 'required|max:8',
            'nombres'    => 'required|max:100',
            'apellidos'    => 'required|max:100',
            );
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $medico  = Medico::find($id);
            $medico->dni        = $request->input('dni');
            $medico->nombres    = strtoupper($request->input('nombres'));
            $medico->apellidos  = strtoupper($request->input('apellidos'));
            $medico->direccion   = strtoupper($request->input('direccion'));
            $medico->telefono    = $request->input('telefono');
            $medico->codigo     = $request->input('codigo');
            $medico->save();
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
        $existe = Libreria::verificarExistencia($id, 'medico');
        if ($existe !== true) {
            return $existe;
        }
        $mensaje = "";
        $error = DB::transaction(function() use($id, $mensaje){
            $medico = Medico::find($id);
            if(!is_null($medico)){
                $ventas = Venta::where('medico_id','=',$medico->id)->where('deleted_at','=', null)->get();
                if(count($ventas) <= 0){
                    $medico->delete();
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
        $existe = Libreria::verificarExistencia($id, 'medico');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Medico::find($id);
        $entidad  = 'Medico';
     
        $formData = array('route' => array('medico.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }
    public function getMedicos(Request $request){
        if($request->ajax()){
            $medicos = Medico::where('deleted_at','=', null)->get();
            $cantidad = count($medicos);
            $res = array($cantidad, $medicos);
            return response()->json($res);
        }
    }
}
