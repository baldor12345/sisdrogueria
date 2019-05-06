<?php

namespace App\Http\Controllers;

use Validator;
use App\DatosEmpresa;
use App\Departamento;
use App\Provincia;
use App\Distrito;
use App\Http\Requests;

use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DatosEmpresaController extends Controller
{
    protected $folderview      = 'app.datosempresa';
    protected $tituloAdmin     = 'Datos de Empresa';
    // protected $tituloRegistrar = 'Registrar empresa';
     protected $tituloModificar = 'Modificar empresa';
    // protected $tituloEliminar  = 'Eliminar empresa';
    protected $rutas           = array('create' => 'datosempresa.create', 
            'edit'   => 'datosempresa.edit', 
            'delete' => 'datosempresa.eliminar',
            'search' => 'datosempresa.buscar',
            'index'  => 'datosempresa.index',
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
        $entidad          = 'DatosEmpresa';
        $empresa = DatosEmpresa::find(1);
        $titulo_modificar = $this->tituloModificar;
        $ruta = $this->rutas;
        
        return view($this->folderview.'.list')->with(compact('entidad','empresa','ruta','titulo_modificar'));
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $entidad          = 'DatosEmpresa';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad' ,'title', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Concepto';
        $concepto        = null;
        $formData       = array('concepto.store');
        $cboTipo        = [''=>'Seleccione']+ array('I'=>'Ingresos','E'=>'Egresos');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar';
        return view($this->folderview.'.mant')->with(compact('concepto', 'cboTipo','formData', 'entidad', 'boton', 'listar'));
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
           
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
           
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
        $existe = Libreria::verificarExistencia($id, 'datos_empresa');
        if ($existe !== true) {
            return $existe;
        }
        $empresa = DatosEmpresa::find($id);
        $listar = Libreria::getParam($request->input('listar'), 'NO');

        $departamento = Departamento::find($empresa->departamento_id);
        $provincia = Provincia::find($empresa->provincia_id);
        $distrito = Distrito::find($empresa->distrito_id);

        $cboDistritos = [$distrito->id=>$distrito->nombre.''];
        $cboDepartamentos = [$departamento->id=>$departamento->nombre.''];
        $cboProvincias = [$provincia->id=>$provincia->nombre.''];
        $titulo_modificar = $this->tituloModificar;
        $entidad = 'DatosEmpresa';
        $formData = array('datosempresa.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('formData', 'entidad', 'boton', 'listar','empresa','departamento','distrito','provincia','cboDepartamentos','cboDistritos', 'cboProvincias','titulo_modificar'));
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
        $existe = Libreria::verificarExistencia($id, 'datos_empresa');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'ruc'       => 'required|max:20',
            'razon_social' => 'required'
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $empresa = DatosEmpresa::find($id);
            $empresa->ruc =  $request->input('ruc');
            $empresa->razon_social =  $request->input('razon_social');
            $empresa->direccion =  $request->input('direccion');
            $empresa->telefono =  $request->input('telefono');
            $empresa->email =  $request->input('email');
            $empresa->departamento_id =  $request->input('departamento_id');
            $empresa->provincia_id =  $request->input('provincia_id');
            $empresa->distrito_id =  $request->input('distrito_id');
            $empresa->save();
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $existe = Libreria::verificarExistencia($id, 'concepto');
    //     if ($existe !== true) {
    //         return $existe;
    //     }
    //     $error = DB::transaction(function() use($id){
    //         $empresa = DatosEmpresa::find($id);
    //         $empresa->delete();
    //     });
    //     return is_null($error) ? "OK" : $error;
    // }

    /**
     * Función para confirmar la eliminación de un registrlo
     * @param  integer $id          id del registro a intentar eliminar
     * @param  string $listarLuego consultar si luego de eliminar se listará
     * @return html              se retorna html, con la ventana de confirmar eliminar
     */
    // public function eliminar($id, $listarLuego)
    // {
    //     $existe = Libreria::verificarExistencia($id, 'datos_empresa');
    //     if ($existe !== true) {
    //         return $existe;
    //     }
    //     $listar = "NO";
    //     if (!is_null(Libreria::obtenerParametro($listarLuego))) {
    //         $listar = $listarLuego;
    //     }
      

    //     $modelo   = Concepto::find($id);
    //     $entidad  = 'DatosEmpresa';
    //     $boton    = 'Eliminar';
    //     if(($count_acciones==0) && ($count_transaccion == 0)){
    //         $formData = array('route' => array('concepto.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
    //         return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    //     }else{
    //         return view($this->folderview.'.messageconcepto')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    //     }
        
    // }
}
