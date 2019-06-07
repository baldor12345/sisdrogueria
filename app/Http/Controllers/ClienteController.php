<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;

use App\Cliente;
// use App\Curl;
// use App\Personamaestro;
use App\Librerias\Libreria;
// use App\Librerias\Curl;

use App\Libsunat\Sunat;
 use App\Libsunat\cURL;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    protected $folderview      = 'app.cliente';
    protected $tituloAdmin     = 'Clientes';
    protected $tituloRegistrar = 'Registrar Cliente';
    protected $tituloModificar = 'Modificar Cliente';
    protected $tituloEliminar  = 'Eliminar Cliente';
    protected $rutas           = array('create' => 'clientes.create', 
            'edit'   => 'clientes.edit', 
            'delete' => 'clientes.eliminar',
            'search' => 'clientes.buscar',
            'index'  => 'clientes.index',
            'buscarclienteSunat'  => 'clientes.buscarclienteSunat'
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
        $entidad          = 'Cliente';
        $name             = Libreria::getParam($request->input('name'));
        $dni             = Libreria::getParam($request->input('dni'));
        $resultado        = Cliente::listar($name, $dni);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DNI o RUC', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombres y Apellidos / Razon Social', 'numero' => '1');
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
        $entidad          = 'cliente';
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
        $entidad        = 'Cliente';
        $cliente        = null;
        $cboTipoDocumento = array('dni' => 'DNI', 'ruc'=>'RUC');
        $formData       = array('clientes.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        $ruta             = $this->rutas;
        // $accion = 0;
        return view($this->folderview.'.mant')->with(compact( 'ruta', 'cliente', 'formData', 'entidad', 'boton', 'listar','cboTipoDocumento'));
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
        $respuesta = "";
        $num_doc = $request->input('cboTipoDocumento');
        
        if( $num_doc== 'dni'){
            $reglas = array(
                
                'doc'       => 'required|max:20',
                'nombres'    => 'required|max:100',
                'apellidos'    => 'required|max:100',
                );
        }else{
            $reglas = array(
                'doc'       => 'required|max:20',
                'razon_social'    => 'required|max:100',
                );
        }
       
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $cliente  = new Cliente();
            if($request->input('cboTipoDocumento') == 'dni'){
                $cliente->dni  = $request->input('doc');
                $cliente->nombres    = strtoupper($request->input('nombres'));
                $cliente->apellidos  = strtoupper($request->input('apellidos'));
            }else{
                $cliente->ruc  = $request->input('doc');
                $cliente->razon_social   = strtoupper($request->input('razon_social'));
            }
           
            $cliente->direccion   = strtoupper($request->input('direccion'));
            $cliente->telefono    = $request->input('telefono');
            $cliente->celular     = $request->input('celular');
            $cliente->email       = $request->input('email');
            $cliente->save();
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
         $existe = Libreria::verificarExistencia($id, 'cliente');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $cliente        = Cliente::find($id);
        $entidad        = 'Cliente';
        $formData       = array('clientes.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        $cboTipoDocumento = array('dni' => 'DNI', 'ruc'=>'RUC');
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'cliente', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento'));
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
        $reglas = array();
       
            if($request->input('cboTipoDocumento') == 'dni'){
                if($request->input('doc') == '00000000'){
                    $reglas = array(
                    'nombres'    => 'required|max:100',
                    );
                }else{
                    $reglas = array(
                    'nombres'    => 'required|max:100',
                    'apellidos'    => 'required|max:100',
                    );
                }
                
            }else if($request->input('cboTipoDocumento') == 'ruc'){
                $reglas = array(
                'doc'    => 'required|max:200',
                'razon_social'    => 'required|max:200',
                );
            }
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request , $id){
            $cliente  = Cliente::find($id);
            if($request->input('cboTipoDocumento') == 'dni'){
                $cliente->dni        = $request->input('doc');
                $cliente->nombres    = strtoupper($request->input('nombres'));
                $cliente->apellidos  = strtoupper($request->input('apellidos'));
            }else{
                $cliente->ruc        = $request->input('doc');
                $cliente->razon_social  = strtoupper($request->input('razon_social'));
            }
            $cliente->direccion   = strtoupper($request->input('direccion'));
            $cliente->telefono    = $request->input('telefono');
            $cliente->celular     = $request->input('celular');
            $cliente->email       = $request->input('email');
            $cliente->save();
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
        $existe = Libreria::verificarExistencia($id, 'cliente');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $cliente = Cliente::find($id);
            if(!is_null($cliente)){
                $cliente->delete();
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
        $existe = Libreria::verificarExistencia($id, 'cliente');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Cliente::find($id);
        $entidad  = 'Cliente';
     
        $formData = array('route' => array('clientes.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }

    function buscarclienteSunat(Request $request){

        if($request->get('accion')=="consultaDNI"){
            // require("/curl.php");
            $token = 'qusEj_w7aHEpX';
            $cc = new Curl(false,'http://45.58.136.7/facturacion/buscaCliente/BuscaCliente2.php');
            $url = 'http://45.58.136.7/facturacion/buscaCliente/BuscaCliente2.php?token='.$token.'&dni='.$request->get('dni').'&fe=N';
            $Page = $cc->get($url,array());
            $datos = json_decode($Page);
            return response()->json($datos);
            // header('Content-Type: application/json');
            // echo json_encode($datos,JSON_PRETTY_PRINT);
        }else if($request->get('accion')=="consultaRUC"){
            header("Access-Control-Allow-Origin: * ");
            // require ("curl.php");
            // require ("sunat.php");
            $cliente = new Sunat();
            //$ruc = $_GET["ruc"];//print_r($ruc);exit();
            // $ruc="10470718566";
            $ruc=$request->get('ruc');
            header('Content-Type: application/json');
            //$empresa = $cliente->BuscaDatosSunat($ruc);
            //$cliente->BuscaDatosSunat($ruc);
            echo ('Llego a consulta RUC: ');
            // $respuesta = $cliente->BuscaDatosSunat($ruc);
            // echo("res: ".response()->json($respuesta)." ");
            // echo('nroRnd: '.$cliente->ProcesaNumRand());
            echo json_encode( $cliente->BuscaDatosSunat($ruc), JSON_PRETTY_PRINT );
            //  return response()->json($cliente->BuscaDatosSunat($ruc));
        }
      
    }
    public function getCliente(Request $request, $doc, $tipo_doc){
        if($request->ajax()){
            $cliente = Cliente::where(($tipo_doc == 'B'?'dni':'ruc'),'=',$doc)->where('deleted_at','=',null)->get();
            $sidatos = count($cliente) > 0?'OK':'NO';
            $res = array($sidatos, $sidatos=='OK'?$cliente[0]:null);
            return response()->json($res);
        }
    }

}
