<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Producto;
use App\Categoria;
use App\Laboratorio;
use App\Unidad;
use App\Marca;
use App\User;
use App\Proveedor;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
   
    protected $folderview      = 'app.producto';
    protected $tituloAdmin     = 'Producto';
    protected $tituloRegistrar = 'Registrar producto';
    protected $tituloModificar = 'Modificar producto';
    protected $tituloEliminar  = 'Eliminar producto';
    protected $rutas           = array('create' => 'producto.create', 
            'edit'          => 'producto.edit', 
            'delete'        => 'producto.eliminar',
            'search'        => 'producto.buscar',
            'index'         => 'producto.index',
            'listmarcas'    => 'producto.listmarcas',
            'listunidades'    => 'producto.listunidades',
            'listcategorias'     => 'producto.listcategorias',
            'listproveedores'    => 'producto.listproveedores',
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
        $entidad          = 'Producto';
        $descripcion      = Libreria::getParam($request->input('name'));
        $resultado        = Producto::listar($descripcion);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Codigo', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Precio venta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Disponible', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha de Caducidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Marca', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Unidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Categoria', 'numero' => '1');
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
        $entidad          = 'Producto';
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
        $entidad        = 'Producto';
        $producto       = null;
        $cboMarca       = array(0=>'Seleccione Marca...');
        $cboCategoria   = array(0=>'Seleccione Categoria...');
        $cboUnidad      = array(0=>'Seleccione Unidad...');
        $cboLaboratio   = array(0=>'Seleccione Laboratorio...');
        $cboProveedor   = array(0=>'Seleccione Proveedor...');
        $formData       = array('producto.store');
        $ruta             = $this->rutas;
        $formData       = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('producto', 'formData', 'ruta', 'entidad', 'boton', 'listar','cboLaboratio', 'cboProveedor', 'cboMarca','cboCategoria','cboUnidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $reglas = array(
            'codigo' => 'required|max:30|unique:producto,codigo,NULL,id,deleted_at,NULL',
            'nombre' => 'required|max:100|unique:producto,nombre,NULL,id,deleted_at,NULL',
            'cantidad' => 'required|integer',
            'precio_venta'    => 'required',
            'fecha_llegada' => 'required',
            'fecha_caducidad' => 'required',
            'sitio' => 'required',
            'marca_id' => 'required|integer|exists:marca,id,deleted_at,NULL',
            'categoria_id' => 'required|integer|exists:categoria,id,deleted_at,NULL',
            'unidad_id' => 'required|integer|exists:unidad,id,deleted_at,NULL',
            //'proveedor_id' => 'required|integer|exists:proveedor,id,deleted_at,NULL',
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $producto               = new Producto();
            $producto->codigo = strtoupper($request->input('codigo'));
            $producto->nombre = $request->input('nombre');
            $producto->cantidad = $request->input('cantidad');
            $producto->precio_venta = $request->input('precio_venta');
            $producto->fecha_llegada = $request->input('fecha_llegada').date(" H:i:s");
            $producto->fecha_caducidad = $request->input('fecha_caducidad');
            $producto->sitio = $request->input('sitio');
            $producto->marca_id  = $request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $producto->unidad_id = $request->input('unidad_id');
            //$producto->proveedor_id = $request->input('proveedor_id');
            //$user           = Auth::user();
            //$empresa_id     = $user->empresa_id;
            //$producto->user_id = $user->id;
            $producto->descripcion = $request->input('descripcion');
            $producto->save();
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
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = Libreria::getParam($request->input('listar'), 'NO');
        $cboMarca = array('' => 'Seleccione') + Marca::pluck('name', 'id')->all();
        $cboCategoria = array('' => 'Seleccione') + Categoria::pluck('name', 'id')->all();
        $cboUnidad = array('' => 'Seleccione') + Unidad::pluck('name', 'id')->all();
        
        $producto       = Producto::find($id);
        $entidad        = 'Producto';
        $formData       = array('producto.update', $id);
        $formData       = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton          = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('producto', 'formData', 'entidad', 'boton', 'listar', 'cboMarca','cboCategoria','cboUnidad'));
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
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $reglas = array(
            'descripcion'       => 'required|max:50|unique:producto,descripcion,'.$id.',id,deleted_at,NULL',
            'precioventa' => 'required',
            'marca_id' => 'required|integer|exists:marca,id,deleted_at,NULL',
            'categoria_id' => 'required|integer|exists:categoria,id,deleted_at,NULL',
            'unidad_id' => 'required|integer|exists:unidad,id,deleted_at,NULL'
            );
        $validacion = Validator::make($request->all(),$reglas);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $producto                 = Producto::find($id);
            $producto->descripcion = strtoupper($request->input('descripcion'));
            $producto->precioventa = $request->input('precioventa');
            $producto->marca_id = $request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $producto->unidad_id = $request->input('unidad_id');
            $user           = Auth::user();
            $empresa_id     = $user->empresa_id;
            $producto->empresa_id = $empresa_id;
            $producto->save();
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
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $producto = Producto::find($id);
            $producto->delete();
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
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Producto::find($id);
        $entidad  = 'Producto';
        $formData = array('route' => array('producto.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
    //METOS PARA EL COMBO SELECT
    public function listmarcas(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Marca::where('name','LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function listunidades(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Unidad::where("name",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }

    public function listcategorias(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Categoria::where("name",'LIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }
    

    public function listproveedores(Request $request){
        $term = trim($request->q);
        if (empty($term)) {
            return \Response::json([]);
        }
        $tags = Categoria::where("name",'ILIKE', '%'.$term.'%')->limit(5)->get();
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->name];
            //$formatted_tags[] = ['id'=> '', 'text'=>"seleccione socio"];
        }

        return \Response::json($formatted_tags);
    }


}
