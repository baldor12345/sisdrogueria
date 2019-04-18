<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movimiento;
use App\Sucursal;
use App\Person;
use App\Persona;
use App\Cliente;
use App\Concepto;
use App\FormaPago;
use App\DetalleCaja;
use App\Caja;
use App\User;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Date\Date;
use DateTime;

class CajaController extends Controller
{

    protected $folderview      = 'app.caja';
    protected $tituloAdmin     = 'Caja';
    protected $tituloRegistrar = 'Apertura Caja';
    protected $tituloModificar = 'Modificar Caja';
    protected $titulo_nuevomovimiento = 'Registrar Nuevo Movimiento';
    protected $tituloCerrarCaja = 'Cerrar Caja';
    protected $titulo_reaperturar = 'Reaperturar Caja';
    protected $titulo_reporte = 'Reportes';
    protected $titulo_transaccion = 'Transacciones Realizadas';
    protected $tituloNuevaTransaccion = 'Registrar Nuevo Gasto';
    protected $tituloEliminar  = 'Eliminar persona';
    protected $rutas           = array('create' => 'caja.create', 
            'edit'              => 'caja.edit', 
            'delete'            => 'caja.eliminar',
            'search'            => 'caja.buscar',
            'index'             => 'caja.index',
            'nuevomovimiento'   => 'caja.nuevomovimiento',
            'guardarnuevomovimiento'   => 'caja.guardarnuevomovimiento',
            'cierrecaja'            => 'caja.cierrecaja',
            'cargarselect'      => 'caja.cargarselect',
            'listpersonas'      =>'caja.listpersonas',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Caja';
        $user           = Auth::user();
        $num_op             = Libreria::getParam($request->input('num_op'));
        $fechaI             = Libreria::getParam($request->input('fechaI'));
        $fechaF             = Libreria::getParam($request->input('fechaF'));
        $resultado        = Caja::listardetallecaja($num_op, $fechaI, $fechaF, $user->sucursal_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro Caja', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro Oper', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Concepto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Ingreso', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Egreso', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Forma Pago', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Entregado a', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comentario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $caja_last = Caja::all()->last();

        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_cerrarCaja = $this->tituloCerrarCaja;
        $titulo_transaccion = $this->titulo_transaccion;
        $titulo_nuevomovimiento = $this->titulo_nuevomovimiento;
        $titulo_reapertura = $this->titulo_reaperturar;
        $titulo_reporte = $this->titulo_reporte;
        $ruta = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar','titulo_cerrarCaja','titulo_nuevomovimiento','titulo_transaccion' ,'ruta','titulo_reapertura','titulo_reporte','caja_last'));
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

        $entidad          = 'Caja';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_nuevomovimiento = $this->titulo_nuevomovimiento;
        $titulo_reapertura = $this->titulo_reaperturar;
        $titulo_reporte = $this->titulo_reporte;
        $ruta             = $this->rutas;
        $listCaja = Caja::listar();

       
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar','titulo_nuevomovimiento', 'ruta','listCaja','titulo_reapertura','titulo_reporte'));
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $caja_last = Caja::All()->last();
        $ingresos = (count($caja_last) != 0)?$caja_last->monto_cierre:0;
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Caja';
        $count_caja = Caja::where('estado','C')->count();
        if(strlen($count_caja) == 1){
            $titulo = "Caja 000".($count_caja+1);
        }
        if(strlen($count_caja) == 2){
            $titulo = "Caja 00".($count_caja+1);
        }
        if(strlen($count_caja) == 3){
            $titulo = "Caja 0".($count_caja+1);
        }
        if(strlen($count_caja) == 4){
            $titulo = "Caja ".($count_caja+1);
        }
        $caja_last = Caja::All()->last();
        $date_caja = Date::parse($caja_last->fecha_horacierre)->format('Y-m-01');

        $fecha_format = date($date_caja);

        $sum_month = date("d-m-Y",strtotime($fecha_format."+ 1 month"));
        //primer dia del mes
        $first_day = Date::parse($sum_month)->format('Y-m-d');
        //ultimo dia del mes 
        $fecha_p = new DateTime($sum_month);
        $fecha_p->modify('last day of this month');
        $last_day = $fecha_p->format('Y-m-d');
        
        $caja_abierta = Caja::where('estado','A')->count();
        $caja        = null;
        $formData       = array('caja.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('caja', 'formData', 'entidad', 'boton', 'listar','ingresos','titulo','caja_abierta','first_day','last_day','caja_last'));
    }

    public function apertura(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $persona_id = $user->persona_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;
        
        $boton        = 'Registrar'; 
        return view($this->folderview.'.apertura')->with(compact('persona_id' , 'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
    }


    //cierre de caja
    public function cierrecaja(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $caja   = null;
        $cboConcepto            =  array(2=>'Cierre de Caja');
        $user = Auth::user();
        $cajero_dat    = Persona::find($user->person_id);
        $caja_dat   = Caja::where('estado', '=' , 'A')->where('deleted_at',null)->get();

        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $ruta = $this->rutas;
        $boton        = 'Registrar';
        return view($this->folderview.'.cierrecaja')->with(compact('ruta', 'cajero_dat', 'user', 'cboConcepto', 'persona_id' , 'caja_dat', 'caja', 'formData', 'entidad', 'boton', 'listar'));
    }



    public function nuevomovimiento(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $caja   = null;
        $numero_operacion   = Libreria::codigo_operacion();
        $cboFormaPago       = [0=>'Seleccione'] + array('C'=>'Contado', 'D'=>'Debito');
        $cboTipo            = [0=>'Seleccione'] + array('I'=>'Ingreso', 'E'=>'Egreso');
        $cboConcepto        = [0=>'Seleccione'];
        $cboPersona         = [0=>'Seleccione'];

        $user           = Auth::user();
        $cajero_dat    = Persona::find($user->person_id);
        $num_caja       = Caja::where('estado','A')->where('user_id',$user->id)->where('sucursal_id',$user->sucursal_id)->where('deleted_at',null)->get();

        $formData       = array('caja.guardarnuevomovimiento','1');
        $ruta = $this->rutas;
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar';
        return view($this->folderview.'.nuevomovimiento')->with(compact('cboPersona','ruta','cboFormaPago', 'cboTipo', 'cboConcepto', 'numero_operacion', 'user' , 'num_caja', 'caja', 'cajero_dat', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function guardarnuevomovimiento(Request $request, $id){
        echo "hola";
        $persona_id = $request->input('persona_id');
        
        $error = DB::transaction(function() use($request, $persona_id){
            $persona = new Persona();
            $persona->empresa_id = Auth::user()->empresa_id;
            $persona->personamaestro_id = $persona_id;
            $persona->type = $request->input('type');
            $persona->secondtype = $request->input('secondtype');
            $persona->save();
        });
        return is_null($error) ? "OK" : $error;

    }

    public function listpersonas(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Persona::where("nombres",'LIKE', '%'.$term.'%')->orwhere('apellidos', '%'.$term.'%')->orwhere('dni', '%'.$term.'%')->orwhere('ruc', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->apellidos.' '.$tag->nombres];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function cargarselect($idselect, Request $request)
    {
        echo $idselect;
        $entidad = $request->get('entidad');
        $t = '';
        $tt = '';

        if($request->get('t') == ''){
            $t = '_';
            $tt = '2';
        }

        $retorno = '<select class="form-control input-sm" id="' . $t . $entidad . '_id" name="';
        $cbo = Concepto::select('id', 'titulo')
            ->where('tipo', '=', $idselect)
            ->get();
        $retorno .= '><option value="" selected="selected">Seleccione</option>';

        foreach ($cbo as $row) {
            $retorno .= '<option value="' . $row['id'] .  '">' . $row['titulo'] . '</option>';
        }
        $retorno .= '</select></div>';

        echo $retorno;
    }




    public function persona(Request $request)
    {
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Persona'; //es personamaestro
        $persona        = null;
        $cboDistrito = array('' => 'Seleccione') + Distrito::pluck('nombre', 'id')->all();
        $formData       = array('caja.guardarpersona');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        $ruta             = $this->rutas;
        $accion = 0;
        return view($this->folderview.'.persona')->with(compact( 'accion' , 'ruta', 'persona', 'formData', 'entidad', 'boton', 'cboDistrito', 'listar'));
    }

    public function guardarpersona(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $documento = $request->input('documento');
        $tamCadena = strlen($documento);
        if($tamCadena == 8){
            $reglas = array(
                'documento'       => 'required|max:8|unique:personamaestro,dni,NULL,id,deleted_at,NULL',
                'nombres'    => 'required|max:100',
                'apellidos'    => 'required|max:100',
                );
        }else{
            $reglas = array(
            'documento'       => 'required|max:11|unique:personamaestro,ruc,NULL,id,deleted_at,NULL',
            'razonsocial'    => 'required|max:100',
            );
        }
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request,$tamCadena){
            $cliente               = new Personamaestro();
            if($tamCadena == 8){
                $cliente->dni        = $request->input('documento');
            }else{
                $cliente->ruc        = $request->input('documento');
            }
            $cliente->nombres    = strtoupper($request->input('nombres'));
            $cliente->apellidos  = strtoupper($request->input('apellidos'));
            $cliente->razonsocial = strtoupper($request->input('razonsocial')); 
            $cliente->direccion   = strtoupper($request->input('direccion'));
            $cliente->telefono    = $request->input('telefono');
            $cliente->celular     = $request->input('celular');
            $cliente->email       = $request->input('email');
            $value =Libreria::getParam($request->input('fechanacimiento'));
            $cliente->fechanacimiento        = $value;
            $cliente->distrito_id  = $request->input('distrito_id');
            $cliente->save();

            $persona = new Persona();
            $persona->empresa_id = Auth::user()->empresa_id;
            $persona->personamaestro_id = $cliente->id;

            $tipocliente = $request->input('cliente');
            $tipoproveedor = $request->input('proveedor');
            $tipotrabajador = $request->input('trabajador');


            if( $tipocliente !==null && $tipoproveedor == null && $tipotrabajador == null ){
                //CLIENTE
                $persona->type  = $tipocliente;
                $persona->secondtype  = null;
                $persona->comision = 0;
            }
            elseif( $tipocliente == null && $tipoproveedor !== null && $tipotrabajador == null ){
                //PROVEEDOR
                $persona->type  = $tipoproveedor;
                $persona->secondtype  = null;
                $persona->comision = 0;
            }
            elseif( $tipocliente == null && $tipoproveedor == null && $tipotrabajador !== null ){
                //TRABAJADOR
                $persona->type  = $tipotrabajador;
                $persona->secondtype  = null;
                $persona->comision = $request->input('comision');
            }
            elseif( $tipocliente !== null && $tipoproveedor == null && $tipotrabajador !== null ){
                // CLIENTE Y TRABAJADOR
                $persona->type  = $tipocliente;
                $persona->secondtype  = $tipotrabajador;
                $persona->comision = $request->input('comision');
            }
            elseif( $tipocliente !== null && $tipoproveedor !== null && $tipotrabajador == null ){
                //CLIENTE Y PROVEEDOR
                $persona->type  = $tipocliente;
                $persona->secondtype  = $tipoproveedor;
                $persona->comision = 0;
            }
            elseif( $tipocliente == null && $tipoproveedor !== null && $tipotrabajador !== null ){
                //TRABAJADOR Y PROVEEDOR
                $persona->type  = $tipotrabajador;
                $persona->secondtype  = $tipoproveedor;
                $persona->comision = $request->input('comision');
            }
            elseif( $tipocliente !== null && $tipoproveedor !== null && $tipotrabajador !== null ){
                //TODOS
                $persona->type  = 'T';
                $persona->secondtype  = null;
                $persona->comision = $request->input('comision');
            }

            $persona->save();

        });
        return is_null($error) ? "OK" : $error;
    }

    public function repetido($id, $listarLuego){
        $existe = Libreria::verificarExistencia($id, 'personamaestro');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }

        $modelo   = Personamaestro::find($id);

        if($modelo->distrito_id != null){
            $distrito = Distrito::find($modelo->distrito_id);
            $provincia = Provincia::find($distrito->provincia_id);
            $departamento = Departamento::find($provincia->departamento_id);
        }


        $entidad  = 'Persona';
        $formData = array('route' => array('caja.guardarrepetido', $id), 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        return view('app.personarepetida')->with(compact('modelo', 'distrito', 'provincia', 'departamento' , 'formData', 'entidad', 'listar'));
    }

    public function guardarrepetido(Request $request){

        $persona_id = $request->input('persona_id');
        
        $error = DB::transaction(function() use($request, $persona_id){
            $persona = new Persona();
            $persona->empresa_id = Auth::user()->empresa_id;
            $persona->personamaestro_id = $persona_id;
            $persona->type = $request->input('type');
            $persona->secondtype = $request->input('secondtype');
            $persona->save();
        });
        return is_null($error) ? "OK" : $error;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * 
     * EDITAR NUEVO
     * 
     * 
     */

    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'fecha_horaApert'        => 'required|max:100',
            'hora_apertura'      => 'required|max:100',
            'monto_iniciado'    => 'required|max:100',
            'titulo'    => 'required|max:200'
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $evaluar_caja = Caja::where('estado','A')->count();
        if($evaluar_caja == 0){
            $error = DB::transaction(function() use($request){
                //apertura una nueva caja
                
                $caja               = new Caja();
                $caja->descripcion        = $request->input('titulo');
                // $caja->descripcion        = $request->input('descripcion');
                $caja->fecha_horaapert        = $request->input('fecha_horaApert').date(" H:i:s");
                $caja->monto_iniciado        = $request->input('monto_iniciado');
                $caja->estado        = 'A';//abierto
                $caja->user_id        = Caja::getIdUser();
                $caja->num_caja        = "0002";
                $user_logueado = User::find($caja->user_id);
                $caja->sucursal_id        = $user_logueado->id;
                $caja->save();
            });
        }else{
            $error = "ERROR";
        }

        

        // $error =  $this->actualizardatosahorros($request);
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

    public function edit($id, Request $request)
    {
        $result = DB::table('caja')->where('id', $id)->first();
        $monto_inicio = round($result->monto_iniciado,1);
        $ingresos = 0;
        $egresos = 0;
        $diferencia = 0;
        $saldo = 0;//Transaccion::getsaldo($id)->get();
        // for($i = 0; $i<count( $saldo ); $i++){
        //     if(($saldo[$i]->concepto_tipo)=="I"){
        //         $ingresos  += $saldo[$i]->monto; 
        //     }else if(($saldo[$i]->concepto_tipo)=="E"){
        //         $egresos += $saldo[$i]->monto;
        //     }
        // }

        $diferencia= $monto_inicio + round($ingresos, 1) - round($egresos, 1);
        $monto_cierre=0;
        $monto_cierre = round(($result->monto_iniciado-$diferencia),1);

        //fecha de apertura de caja
        $fecha_caja = Date::parse($result->fecha_horaapert)->format('Y-m-d');

        
        //ultimo dia del mes 
        $fecha_p = new DateTime($fecha_caja);
        $fecha_p->modify('last day of this month');
        $last_day = $fecha_p->format('Y-m-d');
        $existe = Libreria::verificarExistencia($id, 'caja');
        if ($existe !== true){
            return $existe;
        }

        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $entidad = 'Caja';
        $caja = Caja::find($id);

        $formData       = array('caja.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Cerrar Caja';
        return view($this->folderview.'.cierrecaja')->with(compact( 'formData', 'caja','listar','entidad', 'boton','diferencia','monto_cierre','fecha_caja','last_day'));
    }

    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'caja');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'diferencia_monto'    => 'required|max:200'
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $caja                 = Caja::find($id);
            $caja->descripcion        = $request->get('descripcion');
            $caja->fecha_horacierre        = $request->input('fecha_horaApert').date(" H:i:s");
            $caja->monto_cierre        = $request->get('monto_cierre');
            // $caja->diferencia_monto        = $request->get('diferencia_monto');
            $caja->estado        = 'C';//cierre
            $caja->save();
        });
        
        return is_null($error) ? "OK" : $error;
    }
    public function clienteautocompletar($searching)
    {
        $type = 'C';
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $resultado = DB::table('personamaestro')
            ->where(function($subquery) use($searching)
            {
                $subquery->where(DB::raw('CONCAT(apellidos," ",nombres)'), 'LIKE','%'.strtoupper($searching).'%')->orWhere('razonsocial','LIKE','%'.strtoupper($searching).'%');
            })
            ->where(function($subquery) use($type)
            {
                if (!is_null($type)) {
                   
                    $subquery->where('type', '=', $type)->orwhere('secondtype','=', $type)->orwhere('type','=', 'T');
                   
                }		            		
            })
            ->leftJoin('persona', 'personamaestro.id', '=', 'persona.personamaestro_id')
            ->where('persona.empresa_id', '=', $empresa_id)
            ->whereNull('personamaestro.deleted_at')
            ->orderBy('apellidos', 'ASC')->orderBy('nombres', 'ASC')->orderBy('razonsocial', 'ASC')
            ->take(5);
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->razonsocial != null) {
                $name = $value->razonsocial;
            }else{
                $name = $value->apellidos." ".$value->nombres;
            }
            $data[] = array(
                            'label' => trim($name),
                            'id'    => $value->id,
                            'value' => trim($name),
                        );
        }
        return json_encode($data);
    }

    public function proveedorautocompletar($searching)
    {
        $type = 'P';
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $resultado = DB::table('personamaestro')
            ->where(function($subquery) use($searching)
            {
                $subquery->where(DB::raw('CONCAT(apellidos," ",nombres)'), 'LIKE','%'.strtoupper($searching).'%')->orWhere('razonsocial','LIKE','%'.strtoupper($searching).'%');
            })
            ->where(function($subquery) use($type)
            {
                if (!is_null($type)) {
                   
                    $subquery->where('type', '=', $type)->orwhere('secondtype','=', $type)->orwhere('type','=', 'T');
                   
                }		            		
            })
            ->leftJoin('persona', 'personamaestro.id', '=', 'persona.personamaestro_id')
            ->where('persona.empresa_id', '=', $empresa_id)
            ->whereNull('personamaestro.deleted_at')
            ->orderBy('apellidos', 'ASC')->orderBy('nombres', 'ASC')->orderBy('razonsocial', 'ASC')
            ->take(5);
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->razonsocial != null) {
                $name = $value->razonsocial;
            }else{
                $name = $value->apellidos." ".$value->nombres;
            }
            $data[] = array(
                            'label' => trim($name),
                            'id'    => $value->id,
                            'value' => trim($name),
                        );
        }
        return json_encode($data);
    }

    public function empleadoautocompletar($searching)
    {
        $type = 'E';
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $resultado = DB::table('personamaestro')
            ->where(function($subquery) use($searching)
            {
                $subquery->where(DB::raw('CONCAT(apellidos," ",nombres)'), 'LIKE','%'.strtoupper($searching).'%')->orWhere('razonsocial','LIKE','%'.strtoupper($searching).'%');
            })
            ->where(function($subquery) use($type)
            {
                if (!is_null($type)) {
                   
                    $subquery->where('type', '=', $type)->orwhere('secondtype','=', $type)->orwhere('type','=', 'T');
                   
                }		            		
            })
            ->leftJoin('persona', 'personamaestro.id', '=', 'persona.personamaestro_id')
            ->where('persona.empresa_id', '=', $empresa_id)
            ->whereNull('personamaestro.deleted_at')
            ->orderBy('apellidos', 'ASC')->orderBy('nombres', 'ASC')->orderBy('razonsocial', 'ASC')
            ->take(5);
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->razonsocial != null) {
                $name = $value->razonsocial;
            }else{
                $name = $value->apellidos." ".$value->nombres;
            }
            $data[] = array(
                            'label' => trim($name),
                            'id'    => $value->id,
                            'value' => trim($name),
                        );
        }
        return json_encode($data);
    }

    public function generarConcepto(Request $request)
    {
        //QUITAR APERTURA Y CIERRE DE CAJA
        $tipoconcepto_id  = $request->input('tipoconcepto_id');   
        $conceptos = Concepto::where('tipo', '=' , $tipoconcepto_id)
                                ->where('id','!=',1)
                                ->where('id','!=',2)
                                ->orderBy('id','ASC')->get();
        $html = "";
        foreach($conceptos as $key => $value){
            $html = $html . '<option value="'. $value->id .'">'. $value->concepto .'</option>';
        }
        return $html;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('motivo' => 'required|max:300');
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request, $id){
            $movimiento = Movimiento::find($id);
            $movimiento->estado = 0;
            $movimiento->comentario_anulado  = strtoupper($request->input('motivo'));  
            $movimiento->save();

            if($movimiento->venta_id != null){
                $movimientoventa = Movimiento::find($movimiento->venta_id);
                $movimientoventa->estado = 0;
                $movimientoventa->comentario_anulado  = strtoupper($request->input('motivo'));  
                $movimientoventa->save();
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
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Movimiento::find($id);
        $entidad  = 'Caja';
        $formData = array('route' => array('caja.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        $mensaje  = '<blockquote><p class="text-danger">¿Está seguro de anular el registro?</p></blockquote>';
        return view('app.caja.confirmarAnular')->with(compact( 'mensaje' ,'modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}
