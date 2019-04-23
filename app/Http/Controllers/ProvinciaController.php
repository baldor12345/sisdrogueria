<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Departamento;
use App\Provincia;
use App\Distrito;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class ProvinciaController extends Controller
{
    protected $folderview      = 'app.provincia';
    protected $tituloAdmin     = 'Provincia';
    protected $tituloRegistrar = 'Registrar provincia';
    protected $tituloModificar = 'Modificar provincia';
    protected $tituloEliminar  = 'Eliminar provincia';
    protected $tituloSerieVenta  = 'Serie venta';
    protected $rutas           = array('create' => 'provincia.create', 
            'edit'     => 'provincia.edit', 
            'delete'   => 'provincia.eliminar',
            'search'   => 'provincia.buscar',
            'index'    => 'provincia.index',
            'getProvinciasDep'    => 'provincia.getProvinciasDep',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Provincia';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $resultado        = Provincia::listar($nombre);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Departamento', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        // $titulo_serie_venta = $this->tituloSerieVenta;
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
        $entidad          = 'Provincia';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboDepartamentos = [''=>'Todos'] + Departamento::pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboDepartamentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Provincia';
        $provincia  = null;
        $formData     = array('provincia.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar';
        $cboDepartamentos = [''=>'Seleccione'] + Departamento::pluck('nombre', 'id')->all();
        
        
        return view($this->folderview.'.mant')->with(compact('provincia','formData', 'entidad', 'boton', 'listar','cboDepartamentos'));
    }

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array('nombre' => 'required|max:50');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        
        $error = DB::transaction(function() use($request){
            $provincia       = new Provincia();
            $provincia->nombre = strtoupper($request->input('nombre'));
            $provincia->departamento_id = $request->input('departamento_id');
            $provincia->save();

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
    public function edit(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'provincia');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $provincia = Provincia::find($id);
        $cboDepartamentos = [''=>'Seleccione'] + Departamento::pluck('nombre', 'id')->all();
        $entidad  = 'Provincia';
        $formData = array('provincia.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';

        return view($this->folderview.'.mant')->with(compact('provincia', 'formData', 'entidad', 'boton', 'listar','cboDepartamentos'));
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
        $existe = Libreria::verificarExistencia($id, 'provincia');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:50');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
       
        $error = DB::transaction(function() use($request, $id, $serie){
            $provincia       = Provincia::find($id);
            $provincia->nombre = strtoupper($request->input('nombre'));
            $provincia->provincia_id = strtoupper($request->input('cboDepartamento'));
          
            $provincia->save();

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
        $existe = Libreria::verificarExistencia($id, 'provincia');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $provincia = Provincia::find($id);
            $provincia->delete();
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
        $mensaje = null;
        $existe = Libreria::verificarExistencia($id, 'provincia');
        if ($existe !== true) {
            return $existe;
        }
        $distritos = count(Distrito::where('provincia_id', '=', $id)->where('deleted_at','=',null)->get());
        if($distritos > 0){
            $mensaje = "No se puede eliminar, existen registros en la tabla distritos relacionados con esta provincia";
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Provincia::find($id);
        $entidad  = 'Provincia';
        $formData = array('route' => array('provincia.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar','mensaje'));
    }

    public function cboprovincia($departamento_id = null)
    {
        $existe = Libreria::verificarExistencia($departamento_id, 'departamento');
        if ($existe !== true) {
            return $existe;
        }
       
        $departamento = Departamento::find($departamento_id);
        $provincias = $departamento->provincias;
        if (count($provincias)>0) {
            $cadena = '';
            foreach ($provincias as $key => $value) {
                $cadena .= '<option value="'.$value->id.'">'.$value->nombre.'</option>';
            }
            return $cadena;
        } else {
            return '';
        }
    }

    // public function getProvincias(Request $request, $id){
    //     if($request->ajax()){
    //         $provincias = Provincia::provincias($id);
    //         return response()->json($provincias);
    //     }
    // }
    public function getProvinciasDep(Request $request, $departamento_id){
        if($request->ajax()){
            
            $provincias = Provincia::where('departamento_id','=',$departamento_id)->get();
            $cantidad = count($provincias);
            $res = array($cantidad, $provincias);
            return response()->json($res);
        }
    }

}

