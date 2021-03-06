<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\User;
use App\Usertype;
use App\Sucursal;
use App\Persona;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    protected $folderview      = 'app.usuario';
    protected $tituloAdmin     = 'Usuario';
    protected $tituloRegistrar = 'Registrar usuario';
    protected $tituloModificar = 'Modificar usuario';
    protected $tituloEliminar  = 'Eliminar usuario';
    protected $rutas           = array('create' => 'usuario.create', 
            'guardarSucursal' => 'usuario.guardarSucursal',
            'escogerSucursal' => 'usuario.escogerSucursal',
            'edit'   => 'usuario.edit', 
            'delete' => 'usuario.eliminar',
            'search' => 'usuario.buscar',
            'index'  => 'usuario.index',
            'listpersonas'  => 'usuario.listpersonas',
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
        $entidad          = 'Usuario';
        $name             = Libreria::getParam($request->input('nombre'));
        $resultado        = User::listar($name);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Login', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo de usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Personal', 'numero' => '1');
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
        $entidad          = 'Usuario';
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
        $entidad        = 'Usuario';
        $usuario        = null;
        $ruta             = $this->rutas;
        $cboTipousuario = array('' => 'Seleccione') + Usertype::pluck('name', 'id')->all();
        $cboSucursales = array('' => 'Seleccione') + Sucursal::pluck('nombre', 'id')->all();
        $cboPersona = array('' => 'Seleccione');
        $formData       = array('usuario.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('usuario','ruta', 'formData', 'entidad', 'boton', 'listar', 'cboTipousuario','cboPersona','cboSucursales'));
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
            'login'       => 'required|max:20',
            'password'    => 'required|max:20',
            'usertype_id' => 'required|integer|exists:usertype,id,deleted_at,NULL',
            'persona'   => 'required',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $usuario               = new User();
            $usuario->login        = $request->input('login');
            $usuario->password     = Hash::make($request->input('password'));
            $usuario->usertype_id  = $request->input('usertype_id');
            $usuario->person_id    = $request->input('persona');
            $usuario->sucursal_id  = $request->input('cbsucursal');
            // $empresa_id = $user->empresa_id;
            // $usuario->empresa_id = $empresa_id;
            $usuario->save();
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
        $existe = Libreria::verificarExistencia($id, 'user');
        if ($existe !== true) {
            return $existe;
        }
        $ruta             = $this->rutas;
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $cboTipousuario = array('' => 'Seleccione') + Usertype::pluck('name', 'id')->all();
        $cboSucursales = array('' => 'Seleccione') + Sucursal::pluck('nombre', 'id')->all();
        $usuario        = User::find($id);
        $entidad        = 'Usuario';
        $cboPersona = array( $usuario->id =>  $usuario->persona->nombres.''. $usuario->persona->apellidos);
        $formData       = array('usuario.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('usuario','ruta', 'formData', 'entidad', 'boton', 'listar', 'cboTipousuario','cboPersona','cboSucursales'));
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
        $existe = Libreria::verificarExistencia($id, 'user');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'login'       => 'required|max:20|unique:user,login,'.$id.',id,deleted_at,NULL',
            'usertype_id' => 'required|integer|exists:usertype,id,deleted_at,NULL'
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $usuario = User::find($id);
            $usuario->login = $request->input('login');
            if ($request->input('password') != null && $request->input('password') != '') {
                $usuario->password = Hash::make($request->input('password'));
            }
            $usuario->usertype_id = $request->input('usertype_id');
            $usuario->sucursal_id  = $request->input('cbsucursal');
            $usuario->save();
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
        $existe = Libreria::verificarExistencia($id, 'user');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $usuario = User::find($id);
            $usuario->delete();
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
        $existe = Libreria::verificarExistencia($id, 'user');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = User::find($id);
        $entidad  = 'Usuario';
        $formData = array('route' => array('usuario.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function escogerSucursal()
    {
        $entidad          = 'Usuario';
        $title            = 'Escoger Sucursal';
        $ruta             = $this->rutas;
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $cboSucursal      = Sucursal::where('empresa_id', '=', $empresa_id)->pluck('nombre', 'id')->all();
        return view($this->folderview.'.escogerSucursal')->with(compact('cboSucursal','entidad', 'title', 'ruta'));
    }

    public function guardarSucursal(Request $request)
    {
        $error = DB::transaction(function() use($request){
            $usuario = Auth::user();
            $usuario->sucursal_id = $request->input('sucursal_id');
            $usuario->fecha_sucursal = new \Datetime();
            $usuario->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    
    public function listpersonas(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Persona::listarpersonas($term);
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nombres." ".$tag->apellidos];
        }
        return \Response::json($formatted_tags);
    }
}