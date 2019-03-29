<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Sucursal;
use App\Serieventa;
use App\Movimiento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SucursalController extends Controller
{

    protected $folderview      = 'app.sucursal';
    protected $tituloAdmin     = 'Sucursal';
    protected $tituloRegistrar = 'Registrar sucursal';
    protected $tituloModificar = 'Modificar sucursal';
    protected $tituloEliminar  = 'Eliminar sucursal';
    protected $tituloSerieVenta  = 'Serie venta';
    protected $rutas           = array('create' => 'sucursal.create', 
            'edit'     => 'sucursal.edit', 
            'delete'   => 'sucursal.eliminar',
            'search'   => 'sucursal.buscar',
            'index'    => 'sucursal.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Sucursal';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $resultado        = Sucursal::listar($nombre);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Direccion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Telefono', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Serie venta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_serie_venta = $this->tituloSerieVenta;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'titulo_serie_venta', 'ruta'));
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
        $entidad          = 'Sucursal';
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
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Sucursal';
        $sucursal  = null;
        $formData     = array('sucursal.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar'; 
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $serienueva = Sucursal::where('empresa_id', $empresa_id)->count('id');
        $serienueva = $serienueva + 1;
        $serienueva = (string) $serienueva;
        $cant = strlen($serienueva);
        $ceros = 4 - $cant;
        while($ceros != 0){
            $serienueva = "0". $serienueva;
            $ceros = $ceros - 1;
        }
        return view($this->folderview.'.mant')->with(compact('sucursal', 'serienueva','formData', 'entidad', 'boton', 'listar'));
    }

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array('nombre' => 'required|max:50',
                            'direccion' => 'required|max:100',
                            'telefono' => 'required|max:15',
                            'serieventa' => 'required|max:4');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        
        $error = DB::transaction(function() use($request){
            $sucursal       = new Sucursal();
            $sucursal->nombre = strtoupper($request->input('nombre'));
            $sucursal->direccion = strtoupper($request->input('direccion'));
            $sucursal->telefono = $request->input('telefono');
            $user = Auth::user();
            $sucursal->empresa_id = $user->empresa_id;
            $sucursal->save();

            $serie       = new Serieventa();
            $serie->serie = strtoupper($request->input('serieventa'));
            $serie->sucursal_id = $sucursal->id;
            $serie->save();
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
        $existe = Libreria::verificarExistencia($id, 'sucursal');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $sucursal = Sucursal::find($id);
        $serieventa = Serieventa::where('sucursal_id' , '=' , $id)->first();
        $entidad  = 'Sucursal';
        $formData = array('sucursal.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $serienueva = null;
        return view($this->folderview.'.mant')->with(compact('sucursal', 'serienueva',  'serieventa', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'sucursal');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:50',
                            'direccion' => 'required|max:100',
                            'telefono' => 'required|max:15',
                            'serieventa' => 'required|max:4');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $serie = Serieventa::where('sucursal_id', $id)->first();
        $error = DB::transaction(function() use($request, $id, $serie){
            $sucursal       = Sucursal::find($id);
            $sucursal->nombre = strtoupper($request->input('nombre'));
            $sucursal->direccion = strtoupper($request->input('direccion'));
            $sucursal->telefono = $request->input('telefono');
            $sucursal->save();

            $serie->serie = strtoupper($request->input('serieventa'));
            $serie->sucursal_id = $sucursal->id;
            $serie->save();
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
        $existe = Libreria::verificarExistencia($id, 'sucursal');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $sucursal = Sucursal::find($id);
            $sucursal->delete();
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
        $existe = Libreria::verificarExistencia($id, 'sucursal');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Sucursal::find($id);
        $entidad  = 'Sucursal';
        $formData = array('route' => array('sucursal.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}
