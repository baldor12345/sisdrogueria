<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CajaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication routes...
// Route::get('auth/login', 'Auth\AuthController@getLogin');
// Route::post('auth/login', ['as' =>'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
// Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);
 
// Registration routes...
// Route::get('auth/register', 'Auth\AuthController@getRegister');
// Route::post('auth/register', ['as' => 'auth/register', 'uses' => 'Auth\AuthController@postRegister']);

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/', function(){
    return redirect('/dashboard');
});

Route::group(['middleware' => 'guest'], function() {    
    //Password reset routes
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password','Auth\ResetPasswordController@showPasswordReset');
    //Register routes
    Route::get('registro','Auth\RegisterController@showRegistrationForm');
    Route::post('registro', 'Auth\RegisterController@register');
    Route::post('registrovalidator', 'Auth\RegisterController@validator');
});

Route::group(['middleware' => 'auth'], function () {

//AGREGAR SUCURSAL

    Route::get('/dashboard', function(){
        return View::make('dashboard.home');
    });

    Route::get('venta/clienteautocompletar/{searching}', 'VentaController@clienteautocompletar')->name('venta.clienteautocompletar');
    Route::get('venta/servicioautocompletar/{searching}', 'VentaController@servicioautocompletar')->name('venta.servicioautocompletar');
    Route::get('venta/productoautocompletar/{searching}', 'VentaController@productoautocompletar')->name('venta.productoautocompletar');
    Route::post('venta/guardarventa', 'VentaController@guardarventa')->name('venta.guardarventa');
    Route::post('venta/guardardetalle', 'VentaController@guardardetalle')->name('venta.guardardetalle');
    Route::post('venta/serieventa', 'VentaController@serieventa')->name('venta.serieventa');
    Route::post('venta/permisoRegistrar', 'VentaController@permisoRegistrar')->name('venta.permisoRegistrar');
    Route::resource('venta', 'VentaController', array('except' => array('show')));

    Route::get('caja/clienteautocompletar/{searching}', 'CajaController@clienteautocompletar')->name('caja.clienteautocompletar');
    Route::get('caja/proveedorautocompletar/{searching}', 'CajaController@proveedorautocompletar')->name('caja.proveedorautocompletar');
    Route::get('caja/empleadoautocompletar/{searching}', 'CajaController@empleadoautocompletar')->name('caja.empleadoautocompletar');
    Route::get('caja/generarConcepto','CajaController@generarConcepto')->name('caja.generarConcepto');
    Route::post('caja/buscar','CajaController@buscar')->name('caja.buscar');
    Route::get('caja/eliminar/{id}/{listarluego}','CajaController@eliminar')->name('caja.eliminar');
    Route::get('caja/apertura', 'CajaController@apertura')->name('caja.apertura');
    Route::get('caja/cierre', 'CajaController@cierre')->name('caja.cierre');
    Route::get('caja/persona', 'CajaController@persona')->name('caja.persona');
    Route::post('caja/guardarpersona', 'CajaController@guardarpersona')->name('caja.guardarpersona');
    Route::get('caja/repetido/{id}/{listarluego}','CajaController@repetido')->name('caja.repetido');
    Route::post('caja/guardarrepetido','CajaController@guardarrepetido')->name('caja.guardarrepetido');
    Route::get('caja/aperturaycierre', 'CajaController@aperturaycierre')->name('caja.aperturaycierre');
    Route::resource('caja', 'CajaController', array('except' => array('show')));

    Route::post('verificarpersona','ClienteController@verificarpersona')->name('verificarpersona');

    Route::post('cliente/buscar','ClienteController@buscar')->name('cliente.buscar');
    Route::get('cliente/eliminar/{id}/{listarluego}','ClienteController@eliminar')->name('cliente.eliminar');
    Route::get('cliente/repetido/{id}/{listarluego}','ClienteController@repetido')->name('cliente.repetido');
    Route::post('cliente/guardarrepetido','ClienteController@guardarrepetido')->name('cliente.guardarrepetido');
    Route::resource('cliente', 'ClienteController', array('except' => array('show')));

    Route::post('proveedor/buscar','ProveedorController@buscar')->name('proveedor.buscar');
    Route::get('proveedor/eliminar/{id}/{listarluego}','ProveedorController@eliminar')->name('proveedor.eliminar');
    Route::get('proveedor/repetido/{id}/{listarluego}','ProveedorController@repetido')->name('proveedor.repetido');
    Route::post('proveedor/guardarrepetido','ProveedorController@guardarrepetido')->name('proveedor.guardarrepetido');
    Route::resource('proveedor', 'ProveedorController', array('except' => array('show')));

    Route::post('trabajador/buscar','TrabajadorController@buscar')->name('trabajador.buscar');
    Route::get('trabajador/eliminar/{id}/{listarluego}','TrabajadorController@eliminar')->name('trabajador.eliminar');
    Route::get('trabajador/repetido/{id}/{listarluego}','TrabajadorController@repetido')->name('trabajador.repetido');
    Route::post('trabajador/guardarrepetido','TrabajadorController@guardarrepetido')->name('trabajador.guardarrepetido');
    Route::resource('trabajador', 'TrabajadorController', array('except' => array('show')));

    Route::post('comision/buscar','ComisionController@buscar')->name('comision.buscar');
    Route::get('comision/eliminar/{id}/{listarluego}','ComisionController@eliminar')->name('comision.eliminar');
    Route::resource('comision', 'ComisionController', array('except' => array('show')));

    Route::post('sucursal/buscar','SucursalController@buscar')->name('sucursal.buscar');
    Route::get('sucursal/eliminar/{id}/{listarluego}','SucursalController@eliminar')->name('sucursal.eliminar');
    Route::resource('sucursal', 'SucursalController', array('except' => array('show')));

    Route::post('concepto/buscar','ConceptoController@buscar')->name('concepto.buscar');
    Route::get('concepto/eliminar/{id}/{listarluego}','ConceptoController@eliminar')->name('concepto.eliminar');
    Route::resource('concepto', 'ConceptoController', array('except' => array('show')));

    Route::post('categoria/buscar','CategoriaController@buscar')->name('categoria.buscar');
    Route::get('categoria/eliminar/{id}/{listarluego}','CategoriaController@eliminar')->name('categoria.eliminar');
    Route::resource('categoria', 'CategoriaController', array('except' => array('show')));

    Route::post('unidad/buscar','UnidadController@buscar')->name('unidad.buscar');
    Route::get('unidad/eliminar/{id}/{listarluego}','UnidadController@eliminar')->name('unidad.eliminar');
    Route::resource('unidad', 'UnidadController', array('except' => array('show')));

    Route::post('producto/buscar','ProductoController@buscar')->name('producto.buscar');
    Route::get('producto/eliminar/{id}/{listarluego}','ProductoController@eliminar')->name('producto.eliminar');
    Route::resource('producto', 'ProductoController', array('except' => array('show')));

    Route::post('marca/buscar','MarcaController@buscar')->name('marca.buscar');
    Route::get('marca/eliminar/{id}/{listarluego}','MarcaController@eliminar')->name('marca.eliminar');
    Route::resource('marca', 'MarcaController', array('except' => array('show')));

    Route::post('servicio/buscar','ServicioController@buscar')->name('servicio.buscar');
    Route::get('servicio/eliminar/{id}/{listarluego}','ServicioController@eliminar')->name('servicio.eliminar');
    Route::resource('servicio', 'ServicioController', array('except' => array('show')));

    Route::post('categoriaopcionmenu/buscar', 'CategoriaopcionmenuController@buscar')->name('categoriaopcionmenu.buscar');
    Route::get('categoriaopcionmenu/eliminar/{id}/{listarluego}', 'CategoriaopcionmenuController@eliminar')->name('categoriaopcionmenu.eliminar');
    Route::resource('categoriaopcionmenu', 'CategoriaopcionmenuController', array('except' => array('show')));

    Route::post('opcionmenu/buscar', 'OpcionmenuController@buscar')->name('opcionmenu.buscar');
    Route::get('opcionmenu/eliminar/{id}/{listarluego}', 'OpcionmenuController@eliminar')->name('opcionmenu.eliminar');
    Route::resource('opcionmenu', 'OpcionmenuController', array('except' => array('show')));

    Route::post('tipousuario/buscar', 'TipousuarioController@buscar')->name('tipousuario.buscar');
    Route::get('tipousuario/obtenerpermisos/{listar}/{id}', 'TipousuarioController@obtenerpermisos')->name('tipousuario.obtenerpermisos');
    Route::post('tipousuario/guardarpermisos/{id}', 'TipousuarioController@guardarpermisos')->name('tipousuario.guardarpermisos');
    Route::get('tipousuario/obteneroperaciones/{listar}/{id}', 'TipousuarioController@obteneroperaciones')->name('tipousuario.obteneroperaciones');
    Route::post('tipousuario/guardaroperaciones/{id}', 'TipousuarioController@guardaroperaciones')->name('tipousuario.guardaroperaciones');
    Route::get('tipousuario/eliminar/{id}/{listarluego}', 'TipousuarioController@eliminar')->name('tipousuario.eliminar');
    Route::resource('tipousuario', 'TipousuarioController', array('except' => array('show')));
    Route::get('tipousuario/pdf', 'TipousuarioController@pdf')->name('tipousuario.pdf');

    Route::post('workertype/buscar', 'WorkertypeController@buscar')->name('workertype.buscar');
    Route::get('workertype/eliminar/{id}/{listarluego}', 'WorkertypeController@eliminar')->name('workertype.eliminar');
    Route::resource('workertype', 'WorkertypeController', array('except' => array('show')));

    Route::post('employee/buscar', 'EmployeeController@buscar')->name('employee.buscar');
    Route::get('employee/eliminar/{id}/{listarluego}', 'EmployeeController@eliminar')->name('employee.eliminar');
    Route::resource('employee', 'EmployeeController', array('except' => array('show')));

    Route::post('usuario/buscar', 'UsuarioController@buscar')->name('usuario.buscar');
    Route::get('usuario/eliminar/{id}/{listarluego}', 'UsuarioController@eliminar')->name('usuario.eliminar');
    Route::get('usuario/escogerSucursal','UsuarioController@escogerSucursal')->name('usuario.escogerSucursal');
    Route::post('usuario/guardarSucursal','UsuarioController@guardarSucursal')->name('usuario.guardarSucursal');
    Route::resource('usuario', 'UsuarioController', array('except' => array('show')));
});

Route::get('provincia/cboprovincia/{id?}', array('as' => 'provincia.cboprovincia', 'uses' => 'ProvinciaController@cboprovincia'));
Route::get('distrito/cbodistrito/{id?}', array('as' => 'distrito.cbodistrito', 'uses' => 'DistritoController@cbodistrito'));

/*Route::get('provincias/{id}', function($id)
{
	$departamento_id = $id;

	$provincias = Departamento::find($departamento_id)->provincias;

    return Response::json($provincias);
});
*/

Route::get('provincias/{id}','ProvinciaController@getProvincias');
Route::get('distritos/{id}','DistritoController@getDistritos');