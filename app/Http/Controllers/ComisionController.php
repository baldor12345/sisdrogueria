<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Sucursal;
use App\Personamaestro;
use App\Persona;
use App\Serieventa;
use App\Movimiento;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ComisionController extends Controller
{

    protected $folderview      = 'app.comision';
    protected $tituloAdmin     = 'Comisión';
    protected $tituloPagar     = 'Pagar comisión';
    protected $rutas           = array(
            'edit'     => 'comision.edit', 
            'search'   => 'comision.buscar',
            'index'    => 'comision.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Comision';
        $nombre           = Libreria::getParam($request->input('nombre'));

        $type = 'E';

        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $resultado        = Personamaestro::join('persona', 'persona.personamaestro_id', '=', 'personamaestro.id')
                                    ->where(function($subquery) use($nombre)
                                    {
                                        if (!is_null($nombre)) {
                                            $subquery->where(DB::raw('CONCAT(personamaestro.apellidos," ",personamaestro.nombres)'), 'LIKE', '%'.$nombre.'%')->orWhere('personamaestro.razonsocial','LIKE','%'.$nombre.'%');
                                        }
                                    })
                                    ->where(function($subquery) use($type)
                                    {
                                        if (!is_null($type)) {
                                            //$subquery->where('type', '=', $type)->orWhere('secondtype','=','S');
                                            //$IN = " ('P','T')";
                                            if($type == 'C'){
                                                $subquery->where('persona.type', '=', $type)->orwhere('persona.secondtype','=', $type)->orwhere('persona.type','=', 'T');
                                            }else if($type == 'P'){
                                                $subquery->where('persona.type', '=', $type)->orwhere('persona.secondtype','=', $type)->orwhere('persona.type','=', 'T');
                                            }else if($type == 'E'){
                                                $subquery->where('persona.type', '=', $type)->orwhere('persona.secondtype','=', $type)->orwhere('persona.type','=', 'T');
                                            }
                                        }		            		
                                    })
                                    ->where('persona.empresa_id', '=', $empresa_id)
                                    ->where('persona.comision', '=', 1)
                                    ->where('persona.personamaestro_id', '!=', 2)
                                    ->whereNull('personamaestro.deleted_at')
                                    ->orderBy('nombres', 'ASC')->orderBy('apellidos', 'ASC')->orderBy('razonsocial', 'ASC');                   


        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre Completo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comisión Acumulada', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '1');
        
        $titulo_pagar = $this->tituloPagar;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_pagar', 'ruta'));
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
        $entidad          = 'Comision';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    

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
        $existe = Libreria::verificarExistencia($id, 'persona');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $persona  = Persona::find($id);
        $entidad  = 'Comision';
        $formData = array('comision.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Pagar';
        return view($this->folderview.'.mant')->with(compact('comision_acumulada' , 'persona', 'formData', 'entidad', 'boton', 'listar'));
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
        $existe = Libreria::verificarExistencia($id, 'persona');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('comision_acum' => 'required',
                            'montopagar' => 'required');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $persona         = Persona::find($id);
            $persona->comision_acum -=  $request->input('montopagar'); 
            $persona->save();

            $movimiento       = new Movimiento();
            $movimiento->tipomovimiento_id = 1;
            $movimiento->concepto_id    = 5;

            $sucursal_id = 1;

            $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;

            $movimiento->num_caja       = $num_caja;
            $movimiento->total          = $request->input('montopagar');
            $movimiento->subtotal       = $request->input('montopagar');
            $movimiento->estado         = 1;
            $movimiento->persona_id     = $id;
            $user           = Auth::user();
            $movimiento->usuario_id     = $user->id;
            $movimiento->sucursal_id   = $sucursal_id;
            $movimiento->save();
        });
        return is_null($error) ? "OK" : $error;
    }

}
