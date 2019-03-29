<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movimiento;
use App\Sucursal;
use App\Concepto;
use App\Persona;
use App\Distrito;
use App\Provincia;
use App\Departamento;
use App\Personamaestro;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{

    protected $folderview      = 'app.caja';
    protected $tituloAdmin     = 'Caja';
    protected $tituloRegistrar = 'Registrar Movimiento de Caja';
    protected $tituloEliminar  = 'Anular Moviminto de Caja';
    protected $tituloApertura  = 'Apertura de Caja';
    protected $tituloCierre    = 'Cierre de Caja';
    protected $tituloPersona   = 'Registrar Nueva Persona';
    protected $rutas           = array('create' => 'caja.create', 
            'persona'  => 'caja.persona',
            'guardarpersona'  => 'caja.guardarpersona',
            'delete'   => 'caja.eliminar',
            'search'   => 'caja.buscar',
            'index'    => 'caja.index',
            'apertura' => 'caja.apertura',
            'cierre'   => 'caja.cierre',
            'repetido' => 'caja.repetido',
            'aperturaycierre' => 'caja.aperturaycierre',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscar(Request $request)
    {
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $sucursal = Sucursal::find($sucursal_id);
        $empresa_id = $sucursal->empresa_id;

        //cantidad de aperturas
        $aperturas = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->count();
        //cantidad de cierres
        $cierres = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->count();
                
        $aperturaycierre = null;

        if($aperturas == $cierres){ // habilitar apertura de caja
            $aperturaycierre = 0;
        }else if($aperturas != $cierres){ //habilitar cierre de caja
            $aperturaycierre = 1;
        }

        //max apertura
        $maxapertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');
        //max cierre
        $maxcierre = Movimiento::where('concepto_id', 2)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->max('num_caja');

        $montoapertura = 0.00;
        $ingresos_efectivo = 0.00;
        $ingresos_visa = 0.00;
        $ingresos_master = 0.00;
        $ingresos_total = 0.00;
        $egresos = 0.00;
        $saldo = 0.00;

        if (!is_null($maxapertura) && !is_null($maxcierre)) { // Ya existe una apertura y un cierre
            $apertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->where('num_caja',$maxapertura)->first();
            $montoapertura = $apertura->total;
            if($aperturaycierre == 0){ //apertura y cierre iguales ---- mostrar desde apertura a cierre
                /*

                SELECT SUM(montoefectivo)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                
                //ingresos efectivo
                $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montoefectivo');

                round($ingresos_efectivo,2);

                /*

                SELECT SUM(montovisa)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta visa

                $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montovisa');

                round($ingresos_visa,2);

                /*

                SELECT SUM(montomaster)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta mastercard

                $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montomaster');

                round($ingresos_master,2);

                //ingresos total

                $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('total');
                round($ingresos_total,2);

                /*

                SELECT SUM(total)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 1 // EGRESO

                */
                //egresos
                $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('num_caja','<', $maxcierre)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 1) //egreso
                                            ->sum('total');
                round($egresos,2);

                //saldo
                $saldo = round($ingresos_total - $egresos, 2);

            }else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                /*

                SELECT SUM(montoefectivo)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                
                //ingresos efectivo
                $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montoefectivo');
                round($ingresos_efectivo,2);
                /*

                SELECT SUM(montovisa)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta visa


                $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montovisa');
                round($ingresos_visa,2);

                /*

                SELECT SUM(montomaster)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta mastercard

                $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',2)  
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montomaster');
                round($ingresos_master,2);

                //ingresos total

                $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('total');
                round($ingresos_total,2);

                /*

                SELECT SUM(total)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 1 // EGRESO

                */
                //egresos
                $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 1) //egreso
                                            ->sum('total');
                round($egresos,2);
                //saldo
                $saldo = round($ingresos_total - $egresos, 2);
            }
            $saldo += $montoapertura;
        }else if(!is_null($maxapertura) && is_null($maxcierre)) { //existe apertura pero no existe cierre
            $apertura = Movimiento::where('concepto_id', 1)
                ->where('sucursal_id', "=", $sucursal_id)
                ->where('estado', "=", 1)
                ->where('num_caja',$maxapertura)->first();
            $montoapertura = $apertura->total;
            if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura hasta ultimo movimiento
                /*

                SELECT SUM(montoefectivo)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                
                //ingresos efectivo
                $ingresos_efectivo = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montoefectivo');
                round($ingresos_efectivo,2);
                /*

                SELECT SUM(montovisa)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta visa

                $ingresos_visa = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montovisa');
                round($ingresos_visa,2);

                /*

                SELECT SUM(montomaster)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.concepto_id = con.id
                WHERE mov.num_caja >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 0 // INGRESO

                */
                //ingresos tarjeta mastercard

                $ingresos_master = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',2)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('montomaster');
                round($ingresos_master,2);

                //ingresos total

                $ingresos_total = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 0) //ingreso
                                            ->sum('total');
                round($ingresos_total,2);

                /*

                SELECT SUM(total)
                FROM movimiento as mov
                INNER JOIN concepto as con 
                ON mov.num_caja = con.id
                WHERE mov.serie_numero >= 5 
                and mov.sucursal_id = 1
                and con.tipo = 1 // EGRESO

                */
                //egresos
                $egresos = Movimiento::where('num_caja','>', $maxapertura)
                                            ->where('tipomovimiento_id',1)
                                            ->where('estado', "=", 1)
                                            ->where('sucursal_id', "=", $sucursal_id)
                                            ->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
                                            ->where('concepto.tipo', "=", 1) //egreso
                                            ->sum('total');
                round($egresos,2);
                //saldo
                $saldo = round($ingresos_total - $egresos, 2);
            }
            $saldo += $montoapertura;
        }

        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Caja';
        $folio            = Libreria::getParam($request->input('folio'));
        $fechainicio      = Libreria::getParam($request->input('fechainicio'));
        $fechafin         = Libreria::getParam($request->input('fechafin'));
        $resultado        = Movimiento::listar($fechainicio,$fechafin,$folio, $sucursal_id, $aperturaycierre, $maxapertura, $maxcierre, 1);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Concepto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Persona', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Trabajador', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Ingresos', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Egresos', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Pago', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comentario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '1');
        
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_registrar = $this->tituloRegistrar;
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
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
            return view($this->folderview.'.list')->with(compact('montoapertura', 'lista', 'ingresos_efectivo', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo',  'aperturas' , 'cierres' , 'ruta', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'aperturaycierre', 'titulo_eliminar', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('montoapertura', 'lista', 'ingresos_efectivo', 'ingresos_visa', 'ingresos_master' , 'ingresos_total', 'egresos' , 'saldo', 'aperturas' , 'cierres' , 'ruta', 'aperturaycierre', 'titulo_registrar', 'titulo_apertura', 'titulo_cierre', 'entidad'));
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
        $titulo_apertura  = $this->tituloApertura;
        $titulo_cierre    = $this->tituloCierre;
        $ruta             = $this->rutas;
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $cboSucursal      = Sucursal::where('empresa_id', '=', $empresa_id)->pluck('nombre', 'id')->all();
        return view($this->folderview.'.admin')->with(compact('entidad', 'cboSucursal' , 'title', 'titulo_registrar', 'titulo_apertura' , 'titulo_cierre' , 'ruta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $titulo_persona   = $this->tituloPersona;
        $ruta             = $this->rutas;
        $listar       = Libreria::getParam($request->input('listar'), 'NO');
        $entidad      = 'Caja';
        $movimiento   = null;
        $formData     = array('caja.store');
        $formData     = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $sucursal_id  = $request->input('sucursal_id');
        $user = Auth::user();
        $empresa_id = $user->empresa_id;
        $num_caja   = Movimiento::where('sucursal_id', '=' , $sucursal_id)->max('num_caja') + 1;
        $anonimo = Persona::where('empresa_id', '=', $empresa_id)
                          ->where('personamaestro_id','=',2)->first();
        $boton        = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('anonimo','titulo_persona','ruta','num_caja' , 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
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


    public function cierre(Request $request)
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
        return view($this->folderview.'.cierre')->with(compact('persona_id' , 'num_caja', 'movimiento', 'formData', 'entidad', 'boton', 'listar'));
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
        if($request->input('concepto_id') == 1){
            $reglas     = array('num_caja' => 'required|numeric',
                                'fecha'      => 'required',
                                'concepto_id'   => 'required',
                                'persona_id' => 'required',
                                'monto'      => 'required|numeric',
                            );
        }else{
            $reglas     = array('num_caja' => 'required|numeric',
                                'fecha'      => 'required',
                                'concepto_id'   => 'required',
                                'persona_id' => 'required',
                                'total'      => 'required|numeric',
                            );
        }
        $mensajes   = array();
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $movimiento       = new Movimiento();
            $movimiento->tipomovimiento_id = 1;
            $movimiento->concepto_id    = $request->input('concepto_id');
            $movimiento->num_caja   = $request->input('num_caja');
            if($request->input('concepto_id') == 1){
                $movimiento->total          = $request->input('monto');
                $movimiento->subtotal          = $request->input('monto');
            }else{
                $movimiento->total          = $request->input('total');
                $movimiento->subtotal          = $request->input('total');
            }
            $movimiento->estado         = 1;
            $movimiento->persona_id     = $request->input('persona_id');
            $user           = Auth::user();
            $movimiento->usuario_id     = $user->id;
            $movimiento->sucursal_id   = $request->input('sucursal');
            $movimiento->comentario     = strtoupper($request->input('comentario'));
            $movimiento->save();
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
