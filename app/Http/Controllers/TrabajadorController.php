<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Validator;
use App\Http\Requests;
use App\Distrito;
use App\Provincia;
use App\Departamento;
use App\Persona;
use App\Tipo_persona;
use App\Detalle_persona;
use App\Sucursal;
// use App\Personamaestro;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TrabajadorController extends Controller
{
    protected $folderview      = 'app.trabajador';
    protected $tituloAdmin     = 'Trabajadores';
    protected $tituloRegistrar = 'Registrar Trabajador';
    protected $tituloModificar = 'Modificar Trabajador';
    protected $tituloEliminar  = 'Eliminar Trabajador';
    protected $rutas           = array('create' => 'trabajador.create', 
            'edit'   => 'trabajador.edit', 
            'delete' => 'trabajador.eliminar',
            'search' => 'trabajador.buscar',
            'index'  => 'trabajador.index',
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
        $entidad          = 'Trabajador';
        $nombre             = Libreria::getParam($request->input('nombre'));
        $dni             = Libreria::getParam($request->input('dni'));
        $tipo_personal  = Libreria::getParam($request->input('cboTipo_personal'));
        
        $resultado        = Persona::listar($nombre,$dni);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'DNI', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre Completo', 'numero' => '1');
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
        $entidad          = 'Trabajador';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cbotipo_personas = [0 =>'Todos'] + Tipo_persona::pluck('titulo', 'id')->all();//->where('id','!=',1);
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cbotipo_personas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Trabajador';
        $trabajador        = null;
        $detalle_trabajador = null;
        $cboSucursales = array('' => 'Seleccione') + Sucursal::pluck('nombre', 'id')->all();
        $cboestados = array('A' => 'Activo', 'I'=>'Inactivo');
        $cboTipo_personas = ['' =>'Seleccione'] + Tipo_persona::pluck('titulo', 'id')->all();
        $formData       = array('trabajador.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar';
        $accion = 0;
        $fecha_default = date('Y-m-d');
        return view($this->folderview.'.mant')->with(compact('accion','fecha_default' ,'trabajador', 'formData', 'entidad', 'boton', 'cboestados', 'listar','cboSucursales','detalle_trabajador','cboTipo_personas'));
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
            'dni'       => 'required|max:8',
            'nombres'    => 'required|max:100',
            'apellidos'    => 'required|max:100',
            );
            $mensajes   = array();
            $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $trabajador  = new Persona();
            $trabajador->dni        = $request->input('dni');
            $trabajador->nombres    = strtoupper($request->input('nombres'));
            $trabajador->apellidos  = strtoupper($request->input('apellidos'));
            $trabajador->ruc = strtoupper($request->input('ruc')); 
            $trabajador->direccion   = strtoupper($request->input('direccion'));
            $trabajador->telefono    = $request->input('telefono');
            $trabajador->celular     = $request->input('celular');
            $trabajador->email       = $request->input('email');
            $trabajador->fecha_nacimiento =Libreria::getParam($request->input('fecha_nacimiento'));
            // $trabajador->distrito_id  = $request->input('distrito_id');
            $trabajador->tipo_persona_id  = $request->input('cboTipoPersona');
            $trabajador->estado  = $request->input('cboestado');
            $trabajador->observacion  = $request->input('observacion');
            $trabajador->save();
            
             /*REGISTRAMOS el trabajador EN LA sucursal */
            $detalle_persona = new Detalle_persona();
            $detalle_persona->fecha_ingreso = $request->input('fecha_ingreso');
            $detalle_persona->person_id = $trabajador->id;
            $detalle_persona->sucursal_id = $request->input('cbosucursal');
            $detalle_persona->save();
            
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        // $cboDistrito = array('' => 'Seleccione') + Distrito::pluck('nombre', 'id')->all();
        $trabajador = Persona::find($id);
        $entidad = 'Trabajador';
        $detalle_trabajador = Detalle_persona::where('person_id','=',$id)->where('fecha_salida','=',null)->where('deleted_at','=',null)->get()[0];
        $formData       = array('trabajador.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        $accion = 1;
        $cboSucursales = array('' => 'Seleccione') + Sucursal::pluck('nombre', 'id')->all();
        $cboestados = array('A' => 'Activo', 'I'=>'Inactivo');
        $cboTipo_personas = ['' =>'Seleccione'] + Tipo_persona::pluck('titulo', 'id')->all();
        return view($this->folderview.'.mant')->with(compact( 'accion' , 'trabajador', 'formData', 'entidad', 'boton', 'listar','detalle_trabajador','cboTipo_personas','cboestados','cboSucursales'));
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $documento = $request->input('dni');
        $tamCadena = strlen($documento);
       
        $reglas = array(
            'dni'       => 'required|max:8|unique:person,dni,'.$id.',id,deleted_at,NULL',
            'nombres'    => 'required|max:100',
            'apellidos'    => 'required|max:100',
            );
        
        
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id, $tamCadena){
            $trabajador = Persona::find($id);
            
            $user = Auth::user();
            
            $trabajador->dni        = $request->input('dni');
            $trabajador->nombres    = strtoupper($request->input('nombres'));
            $trabajador->apellidos  = strtoupper($request->input('apellidos'));
            $trabajador->ruc = strtoupper($request->input('ruc')); 
            $trabajador->direccion   = strtoupper($request->input('direccion'));
            $trabajador->telefono    = $request->input('telefono');
            $trabajador->celular     = $request->input('celular');
            $trabajador->email       = $request->input('email');
            $trabajador->fecha_nacimiento =Libreria::getParam($request->input('fecha_nacimiento'));
            // $trabajador->distrito_id  = $request->input('distrito_id');
            $trabajador->tipo_persona_id  = $request->input('cboTipoPersona');
            $trabajador->estado  = $request->input('cboestado');
            $trabajador->observacion  = $request->input('observacion');
            $trabajador->save();
            
             /*REGISTRAMOS el trabajador EN LA sucursal */
            $detalle_persona = new Detalle_persona();
            $detalle_persona->fecha_ingreso = $request->input('fecha_ingreso');
            $detalle_persona->person_id = $trabajador->id;
            $detalle_persona->sucursal_id = $request->input('cbosucursal');
            $detalle_persona->save();
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
           
            $trabajador = Persona::where('id','=',$id)->get()->first();
            if(!is_null($trabajador)){
                $trabajador->delete();
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
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Persona::find($id);
        $entidad  = 'Trabajador';
        $formData = array('route' => array('trabajador.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    
    public function repetido($id, $listarLuego){
        $existe = Libreria::verificarExistencia($id, 'person');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "SI";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }

        $modelo   = Persona::find($id);

        if($modelo->distrito_id != null){
            $distrito = Distrito::find($modelo->distrito_id);
            $provincia = Provincia::find($distrito->provincia_id);
            $departamento = Departamento::find($provincia->departamento_id);
        }


        $entidad  = 'Trabajador';
        $formData = array('route' => array('trabajador.guardarrepetido', $id), 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        return view('app.personarepetida')->with(compact('modelo', 'distrito', 'provincia', 'departamento' , 'formData', 'entidad', 'listar'));
    }

    public function guardarrepetido(Request $request){

        $persona_id = $request->input('persona_id');
        
        $error = DB::transaction(function() use($persona_id){
            $persona = new Persona();
            $persona->empresa_id = Auth::user()->empresa_id;
            $persona->personamaestro_id = $persona_id;
            $persona->comision = 0;
            $persona->type        = 'E';
            $persona->save();
        });
        return is_null($error) ? "OK" : $error;

    }
}
