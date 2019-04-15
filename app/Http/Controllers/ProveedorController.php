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
use App\Producto;
use App\Proveedor;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
{
    protected $folderview      = 'app.proveedor';
    protected $tituloAdmin     = 'Proveedores';
    protected $tituloRegistrar = 'Registrar Proveedor';
    protected $tituloModificar = 'Modificar Proveedor';
    protected $tituloEliminar  = 'Eliminar Proveedor';
    protected $rutas           = array('create' => 'proveedor.create', 
            'edit'   => 'proveedor.edit', 
            'delete' => 'proveedor.eliminar',
            'search' => 'proveedor.buscar',
            'index'  => 'proveedor.index',
            'listdistritos' => 'proveedor.listdistritos'
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
        $entidad          = 'Proveedor';
        $name             = Libreria::getParam($request->input('name'));
        $type             = Libreria::getParam($request->input('estado_id'));
        $resultado        = Proveedor::listar($name,$type);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre/Rason Social', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Pesona De Contacto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Telefono', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Celular', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Estado', 'numero' => '1');
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
        $entidad          = 'Proveedor';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboEstado      = array('A'=>'Activo','I'=>'Inactivo');
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboEstado', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Proveedor'; //es personamaestro
        $proveedor        = null;
        $cboEstado          = array('A'=>'Activo','I'=>'Inactivo');
        $cboDistrito       = [''=>'Seleccione'] + Distrito::pluck('nombre', 'id')->all();
        $cboProvincia       = [''=>'Seleccione'] + Provincia::pluck('nombre', 'id')->all();
        $cboDepartamento       = [''=>'Seleccione'] + Departamento::pluck('nombre', 'id')->all();
        $ruta             = $this->rutas;
        $formData       = array('proveedor.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('cboProvincia','cboDepartamento', 'proveedor', 'cboEstado', 'ruta','formData', 'entidad', 'boton', 'cboDistrito', 'listar'));
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
            'nombre'       => 'required|max:200|unique:proveedor,nombre,NULL,id,deleted_at,NULL',
            'persona_contacto'    => 'required|max:100',
            'ruc'    => 'required|max:100',
            'telefono'    => 'required|max:100',
            'celular'    => 'required|max:15',
            'distrito_id'    => 'required',
            'provincia_id'    => 'required',
            'departamento_id'    => 'required',
        );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $proveedor               = new Proveedor();
            $proveedor->nombre     = strtoupper($request->input('nombre'));
            $proveedor->ruc     = strtoupper($request->input('ruc'));
            $proveedor->direccion   = $request->input('direccion');
            $proveedor->persona_contacto = $request->input('persona_contacto'); 
            $proveedor->telefono    = $request->input('telefono');
            $proveedor->celular     = $request->input('celular');
            $proveedor->estado       = $request->input('estado');
            $proveedor->distrito_id  = $request->input('distrito_id');
            $proveedor->provincia_id  = $request->input('provincia_id');
            $proveedor->departamento_id  = $request->input('departamento_id');
            $proveedor->save();
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
        $existe = Libreria::verificarExistencia($id, 'proveedor');
        if ($existe !== true) {
            return $existe;
        }
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $cboDistrito       = [''=>'Seleccione'] + Distrito::pluck('nombre', 'id')->all();
        $cboProvincia       = [''=>'Seleccione'] + Provincia::pluck('nombre', 'id')->all();
        $cboDepartamento       = [''=>'Seleccione'] + Departamento::pluck('nombre', 'id')->all();
        $cboEstado          = array('A'=>'Activo','I'=>'Inactivo');
        $proveedor        = Proveedor::find($id);
        $ruta             = $this->rutas;
        $entidad        = 'Proveedor';
        $formData       = array('proveedor.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('cboProvincia','cboDepartamento','cboEstado', 'proveedor', 'ruta', 'formData', 'entidad', 'boton', 'listar', 'cboDistrito'));
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
        $existe = Libreria::verificarExistencia($id, 'proveedor');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'nombre'       => 'required|max:200|unique:proveedor,nombre,'.$id.',id,deleted_at,NULL',
            'persona_contacto'    => 'required|max:100',
            'telefono'    => 'required|max:100',
            'ruc'    => 'required|max:100',
            'celular'    => 'required|max:15',
            'distrito_id'    => 'required',
            'provincia_id'    => 'required',
            'departamento_id'    => 'required',
        );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $proveedor               = Proveedor::find($id);
            $proveedor->nombre     = strtoupper($request->input('nombre'));
            $proveedor->ruc     = strtoupper($request->input('ruc'));
            $proveedor->direccion   = $request->input('direccion');
            $proveedor->persona_contacto = $request->input('persona_contacto'); 
            $proveedor->telefono    = $request->input('telefono');
            $proveedor->celular     = $request->input('celular');
            $proveedor->estado       = $request->input('estado');
            $proveedor->distrito_id  = $request->input('distrito_id');
            $proveedor->provincia_id  = $request->input('provincia_id');
            $proveedor->departamento_id  = $request->input('departamento_id');
            $proveedor->save();
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
        $existe = Libreria::verificarExistencia($id, 'proveedor');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $proveedor = Proveedor::find($id);
            $proveedor->delete();
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
        $existe = Libreria::verificarExistencia($id, 'proveedor');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $count_producto = Producto::where('proveedor_id', $id)->count();
        $modelo   = Proveedor::find($id);
        $entidad  = 'Proveedor';
        $boton    = 'Eliminar';
        if(($count_producto==0)){
            $formData = array('route' => array('proveedor.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
            return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
        }else{
            return view($this->folderview.'.messageproveedor')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
        }
    }



    public function listdistritos(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Distrito::where('nombre','LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->nombre];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

}
