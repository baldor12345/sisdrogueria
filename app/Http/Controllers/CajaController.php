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
    protected $tituloEliminar  = 'Eliminar persona';
    protected $rutas           = array('create' => 'caja.create', 
            'edit'              => 'caja.edit', 
            'delete'            => 'caja.eliminar',
            'search'            => 'caja.buscar',
            'index'             => 'caja.index',

            'nuevomovimiento'   => 'caja.nuevomovimiento',
            'guardarnuevomovimiento'   => 'caja.guardarnuevomovimiento',

            'cierrecaja'            => 'caja.cierrecaja',
            'guardarcierrecaja'            => 'caja.guardarcierrecaja',

            'cargarselect'      => 'caja.cargarselect',
            'listpersonas'      =>'caja.listpersonas',
            'listclientes'      =>'caja.listclientes',
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
        
        $user           = Auth::user();
        $caja_last      = Caja::where('sucursal_id',$user->sucursal_id)->orderBy('created_at','DSC')->take(1)->get();
        $caja_det_      = DetalleCaja::where('caja_id',$caja_last[0]->id)->where('deleted_at',null)->get();
        
        $ingresos =0;
        $egresos =0;
        foreach ($caja_det_ as $key => $value) {
            if($value->concepto_id != 2){
                $ingresos +=    $value->ingreso;
                $egresos  +=    $value->egreso;
            } 
        }

        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_cerrarCaja = "cerrar caja";
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
            return view($this->folderview.'.list')->with(compact('ingresos','egresos','lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar','titulo_cerrarCaja' ,'ruta','caja_last'));
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
        $titulo_cierrecaja = $this->tituloCerrarCaja;
        $ruta             = $this->rutas;

       
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar','titulo_nuevomovimiento', 'ruta','titulo_cierrecaja'));
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //$articles->where('category_id','=',$categorie->id)->orderby('created_at','DESC')->take(1)->get();

        $user = Auth::user();
        $caja_last = Caja::where('sucursal_id',$user->sucursal_id)->orderBy('created_at','DSC')->take(1)->get();
        $ingresos = (count($caja_last) != 0)?$caja_last[0]->monto_cierre:0;
        $listar         = Libreria::getParam($request->input('listar'), 'NO');
        $entidad        = 'Caja';
        $num_caja       = Libreria::codigo_operacioncaja($user->sucursal_id);
        $cboConcepto            =  array(1=>'Apertura de Caja');

        $fecha_apertura = date('Y-m-d');
        $hora_apertura = date('H:i');
        $cajero_dat    = Persona::find($user->person_id);
        $limit_day = Date::parse($caja_last[0]->fecha_horaapert)->format('Y-m-d');
        
        $caja_abierta = Caja::where('estado','A')->where('sucursal_id',$user->sucursal_id)->count();
        $caja        = null;
        $formData       = array('caja.store');
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('limit_day', 'cajero_dat', 'fecha_apertura', 'hora_apertura', 'cboConcepto', 'num_caja', 'caja', 'formData', 'entidad', 'boton', 'listar','ingresos','titulo','caja_abierta','first_day','last_day','caja_last'));
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
            'monto_ini'    => 'required|max:100',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $user           = Auth::user();
        $evaluar_caja = Caja::where('estado','A')->where('sucursal_id',$user->sucursal_id)->count();
        if($evaluar_caja == 0){
            $error = DB::transaction(function() use($request){
                //apertura una nueva caja
                $fecha_         = $request->input('fecha_horaApert');
                $hora_          = $request->input('hora_apertura');
                $date_apert     = date('Y-m-d H:i:s', strtotime($fecha_." ".$hora_.":00"));
                
                $caja               = new Caja();
                $caja->num_caja        = $request->input('num_caja');
                $caja->fecha_horaapert        = $date_apert;
                $caja->monto_iniciado        = $request->input('monto_ini');
                $caja->descripcion        = $request->input('descripcion');
                $caja->estado        = 'A';
                $user           = Auth::user();
                $caja->user_id = $user->id;
                $caja->sucursal_id = $user->sucursal_id;
                $caja->save();

                $caja_last   =   Caja::where('estado','A')->where('sucursal_id',$user->sucursal_id)->where('deleted_at',null)->get();
                $numero_operacion   = Libreria::codigo_operacion();

                $detalle_caja = new DetalleCaja();
                $detalle_caja->fecha = $request->input('fecha_horaApert').date(" H:i:s");
                $detalle_caja->numero_operacion = $numero_operacion;
                $detalle_caja->concepto_id = $request->input('concepto_id');
                $detalle_caja->ingreso = $request->input('monto_ini');
                $detalle_caja->estado = $request->input('C');
                $detalle_caja->forma_pago = 'C';
                $detalle_caja->comentario = $request->input('descripcion');
                $detalle_caja->caja_id = $caja_last[0]->id;
                $detalle_caja->save();
            });
        }else{
            $error = "ERROR";
        }

        

        // $error =  $this->actualizardatosahorros($request);
        return is_null($error) ? "OK" : $error;
    }


    public function nuevomovimiento(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $caja   = null;
        $numero_operacion   = Libreria::codigo_operacion();
        $cboFormaPago       = [''=>'Seleccione'] + array('CO'=>'Contado', 'CR'=>'Crédito');
        $cboTipo            = [''=>'Seleccione'] + array('I'=>'Ingreso', 'E'=>'Egreso');
        $cboTtipoPersonal   = ['0'=>'Seleccione'] + array('P'=>'Personal', 'C'=>'Cliente');
        $cboConcepto        = [''=>'Seleccione'];
        $cboPersonal         = [''=>'Seleccione personal'];
        $cboCliente         = [''=>'Seleccione cliente'];

        $user           = Auth::user();
        $cajero_dat    = Persona::find($user->person_id);
        $num_caja       = Caja::where('estado','A')->where('user_id',$user->id)->where('sucursal_id',$user->sucursal_id)->where('deleted_at',null)->get();
        $count_caja      = (count($num_caja) !=0)?count($num_caja):0;

        $fecha = (count($num_caja) !=0)?Date::parse($num_caja[0]->fecha_horaapert)->format('Y-m-d'):date('Y-m-d');

        $formData       = array('caja.guardarnuevomovimiento');
        $ruta = $this->rutas;
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton        = 'Registrar';
        return view($this->folderview.'.nuevomovimiento')->with(compact('cboTtipoPersonal', 'fecha', 'count_caja', 'cboPersonal','cboCliente','ruta','cboFormaPago', 'cboTipo', 'cboConcepto', 'numero_operacion', 'user' , 'num_caja', 'caja', 'cajero_dat', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function guardarnuevomovimiento(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'tipo_id'        => 'required|max:100',
            'concepto_id'      => 'required|max:100',
            'total'    => 'required|max:100',
            'forma_pago'    => 'required|max:200'
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }

        $error = DB::transaction(function() use($request){
            $numero_operacion   = Libreria::codigo_operacion();
            $detalle_caja = new DetalleCaja();
            $detalle_caja->fecha = $request->input('fecha').date(" H:i:s");
            $detalle_caja->numero_operacion = $numero_operacion;
            $detalle_caja->concepto_id = $request->input('concepto_id');

            if($request->input('tipo_pers') == '0'){
                $detalle_caja->cliente_id = null;
                $detalle_caja->personal_id = null;
            }
            if($request->input('tipo_pers') == 'P'){
                $detalle_caja->cliente_id = null;
                $detalle_caja->personal_id = $request->input('personal_id');
            }
            if($request->input('tipo_pers') == 'C'){
                $detalle_caja->cliente_id = $request->input('cliente_id');
                $detalle_caja->personal_id = null;
            }
            
            if($request->input('tipo_id') == 'I'){
                $detalle_caja->ingreso = $request->input('total');
                $detalle_caja->egreso = 0;
            }
            if($request->input('tipo_id') == 'E'){
                $detalle_caja->egreso = $request->input('total');
                $detalle_caja->ingreso = 0;
            }
            $detalle_caja->estado = 'C';
            $detalle_caja->forma_pago = $request->input('forma_pago');
            $detalle_caja->comentario = $request->input('comentario');
            $detalle_caja->caja_id = $request->input('caja_id');
            $detalle_caja->save();
        });
        return is_null($error) ? "OK" : $error;

    }

    //cierre de caja
    public function cierrecaja(Request $request)
    {
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $caja   = null;
        $cboConcepto            =  array(2=>'Cierre de Caja');

        $fecha_cierre = date('Y-m-d');
        $hora_cierre = date('H:i');

        $user = Auth::user();
        $cajero_dat    = Persona::find($user->person_id);
        $caja_dat   = Caja::where('estado', '=' , 'A')->where('sucursal_id',$user->sucursal_id)->where('deleted_at',null)->get();
        $count_caja      = (count($caja_dat) !=0)?count($caja_dat):0;

        $caja_last      = Caja::where('sucursal_id',$user->sucursal_id)->orderBy('created_at','DSC')->take(1)->get();
        $caja_det_      = DetalleCaja::where('caja_id',$caja_last[0]->id)->where('deleted_at',null)->get();
        
        $ingresos =0;
        $egresos =0;
        foreach ($caja_det_ as $key => $value) {
            if($value->concepto_id != 2){
                $ingresos +=    $value->ingreso;
                $egresos  +=    $value->egreso;
            } 
        }

        $saldo = $ingresos-$egresos;

        $limit_day = (count($caja_dat)!=0)?Date::parse($caja_dat[0]->fecha_horaapert)->format('Y-m-d'):date('Y-m-d');

        $formData     = array('caja.guardarcierrecaja');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $ruta = $this->rutas;
        $boton        = 'Registrar Cierre';
        return view($this->folderview.'.cierrecaja')->with(compact('saldo', 'limit_day', 'fecha_cierre', 'hora_cierre', 'ruta', 'count_caja', 'cajero_dat', 'user', 'cboConcepto', 'persona_id' , 'caja_dat', 'caja', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function guardarcierrecaja(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }

        $error = DB::transaction(function() use($request){

            $fecha_         = $request->input('fecha');
            $hora_          = $request->input('hora_cierre');
            $date_cierre     = date('Y-m-d H:i:s', strtotime($fecha_." ".$hora_.":00"));

            $caja                   = Caja::find($request->input('caja_id'));
            $caja->fecha_horacierre = $date_cierre;
            $caja->monto_cierre = $request->input('monto_cierre');
            $caja->estado = 'C';
            $caja->descripcion = $request->input('comentario');
            $caja->save();

            $numero_operacion   = Libreria::codigo_operacion();

            $detalle_caja = new DetalleCaja();
            $detalle_caja->fecha = $request->input('fecha').date(" H:i:s");
            $detalle_caja->numero_operacion = $numero_operacion;
            $detalle_caja->concepto_id = $request->input('concepto_id');
            $detalle_caja->ingreso = 0.00;
            $detalle_caja->egreso = $request->input('monto_cierre');
            $detalle_caja->estado = 'C';
            $detalle_caja->forma_pago = 'C';
            $detalle_caja->comentario = $request->input('comentario');
            $detalle_caja->caja_id = $request->input('caja_id');
            $detalle_caja->save();
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
    public function listclientes(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Cliente::where("nombres",'LIKE', '%'.$term.'%')->orwhere('apellidos', '%'.$term.'%')->orwhere('dni', '%'.$term.'%')->orwhere('ruc', '%'.$term.'%')->limit(5)->get();
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
            ->where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->where('id', '!=', 5)
            ->where('id', '!=', 6)
            ->get();
        $retorno .= '><option value="" selected="selected">Seleccione</option>';

        foreach ($cbo as $row) {
            $retorno .= '<option value="' . $row['id'] .  '">' . $row['titulo'] . '</option>';
        }
        $retorno .= '</select></div>';

        echo $retorno;
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
