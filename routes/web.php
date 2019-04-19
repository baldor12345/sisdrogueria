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

    Route::post('verificarpersona','ClienteController@verificarpersona')->name('verificarpersona');

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

    //=========================================================================================================================

    Route::post('usuario/buscar', 'UsuarioController@buscar')->name('usuario.buscar');
    Route::get('usuario/eliminar/{id}/{listarluego}', 'UsuarioController@eliminar')->name('usuario.eliminar');
    Route::get('usuario/escogerSucursal','UsuarioController@escogerSucursal')->name('usuario.escogerSucursal');
    Route::post('usuario/guardarSucursal','UsuarioController@guardarSucursal')->name('usuario.guardarSucursal');
    Route::resource('usuario', 'UsuarioController', array('except' => array('show')));

    //PROVEEDOR
    Route::post('proveedor/buscar','ProveedorController@buscar')->name('proveedor.buscar');
    Route::get('proveedor/eliminar/{id}/{listarluego}','ProveedorController@eliminar')->name('proveedor.eliminar');
    Route::resource('proveedor', 'ProveedorController', array('except' => array('show')));
    Route::get('proveedor/listdistritos',  'ProveedorController@listdistritos')->name('proveedor.listdistritos');
    Route::get('proveedor/cargarselect/{idselect}', 'ProveedorController@cargarselect')->name('proveedor.cargarselect');


    //CATEGORIAS
    Route::post('categoria/buscar','CategoriaController@buscar')->name('categoria.buscar');
    Route::get('categoria/eliminar/{id}/{listarluego}','CategoriaController@eliminar')->name('categoria.eliminar');
    Route::resource('categoria', 'CategoriaController', array('except' => array('show')));
    
    //PROPIEDADES
    Route::post('propiedades/buscar','PropiedadesController@buscar')->name('propiedades.buscar');
    Route::get('propiedades/eliminar/{id}/{listarluego}','PropiedadesController@eliminar')->name('propiedades.eliminar');
    Route::resource('propiedades', 'PropiedadesController', array('except' => array('show')));

    //UNIDADES
    Route::post('unidad/buscar','UnidadController@buscar')->name('unidad.buscar');
    Route::get('unidad/eliminar/{id}/{listarluego}','UnidadController@eliminar')->name('unidad.eliminar');
    Route::resource('unidad', 'UnidadController', array('except' => array('show')));

    //PRESENTACION
    Route::post('presentacion/buscar','PresentacionController@buscar')->name('presentacion.buscar');
    Route::get('presentacion/eliminar/{id}/{listarluego}','PresentacionController@eliminar')->name('presentacion.eliminar');
    Route::resource('presentacion', 'PresentacionController', array('except' => array('show')));

    //MARCAS
    Route::post('marca/buscar','MarcaController@buscar')->name('marca.buscar');
    Route::get('marca/eliminar/{id}/{listarluego}','MarcaController@eliminar')->name('marca.eliminar');
    Route::resource('marca', 'MarcaController', array('except' => array('show')));

    //PRODUCTO
    Route::post('producto/buscar','ProductoController@buscar')->name('producto.buscar');
    Route::get('producto/eliminar/{id}/{listarluego}','ProductoController@eliminar')->name('producto.eliminar');
    Route::resource('producto', 'ProductoController', array('except' => array('show')));
    Route::get('producto/listmarcas',  'ProductoController@listmarcas')->name('producto.listmarcas');
    Route::get('producto/listunidades',  'ProductoController@listunidades')->name('producto.listunidades');
    Route::get('producto/listcategorias',  'ProductoController@listcategorias')->name('producto.listcategorias');
    Route::get('producto/listproveedores',  'ProductoController@listproveedores')->name('producto.listproveedores');
    Route::get('producto/listsucursales',  'ProductoController@listsucursales')->name('producto.listsucursales');

    //comprobante
    Route::post('comprobante/buscar','ComprobanteController@buscar')->name('comprobante.buscar');
    Route::get('comprobante/eliminar/{id}/{listarluego}','ComprobanteController@eliminar')->name('comprobante.eliminar');
    Route::resource('comprobante', 'ComprobanteController', array('except' => array('show')));
    //formaa pago
    Route::post('forma_pago/buscar','FormaPagoController@buscar')->name('forma_pago.buscar');
    Route::get('forma_pago/eliminar/{id}/{listarluego}','FormaPagoController@eliminar')->name('forma_pago.eliminar');
    Route::resource('forma_pago', 'FormaPagoController', array('except' => array('show')));
    
    /*CAJA*/
    Route::post('caja/buscar', 'CajaController@buscar')->name('caja.buscar');
    Route::resource('caja', 'CajaController', array('except' => array('show')));
    Route::get('caja/nuevomovimiento', 'CajaController@nuevomovimiento')->name('caja.nuevomovimiento');
    Route::post('caja/guardarnuevomovimiento', 'CajaController@guardarnuevomovimiento')->name('caja.guardarnuevomovimiento');
    Route::get('caja/cierrecaja', 'CajaController@cierrecaja')->name('caja.cierrecaja');
    Route::get('caja/cargarselect/{idselect}', 'CajaController@cargarselect')->name('caja.cargarselect');
    Route::get('caja/cargarreapertura/{id}/{listarluego}', 'CajaController@cargarreapertura')->name('caja.cargarreapertura');
    Route::get('caja/guardarreapertura', 'CajaController@guardarreapertura')->name('caja.guardarreapertura');
    Route::get('caja/listpersonas',  'CajaController@listpersonas')->name('caja.listpersonas');
  

    //COMPRA
    Route::post('compra/buscar','CompraController@buscar')->name('compra.buscar');
    Route::get('compra/eliminar/{id}/{listarluego}','CompraController@eliminar')->name('compra.eliminar');
    Route::resource('compra', 'CompraController', array('except' => array('show')));
    Route::get('compra/listproveedores',  'CompraController@listproveedores')->name('compra.listproveedores');
    Route::get('compra/listproductos',  'CompraController@listproductos')->name('compra.listproductos');
    Route::get('compra/verdetalle/{id?}',  'CompraController@verdetalle')->name('compra.verdetalle');
    
    //LOTES Y CADUCIDAD
    Route::post('entrada_salida/buscar','EntradaSalidaController@buscar')->name('entrada_salida.buscar');
    Route::get('entrada_salida/eliminar/{id}/{listarluego}','EntradaSalidaController@eliminar')->name('entrada_salida.eliminar');
    Route::resource('entrada_salida', 'EntradaSalidaController', array('except' => array('show')));
    Route::get('entrada_salida/listproveedores',  'EntradaSalidaController@listproveedores')->name('entrada_salida.listproveedores');
    Route::get('entrada_salida/listproductos',  'EntradaSalidaController@listproductos')->name('entrada_salida.listproductos');
    Route::get('entrada_salida/listproductosalida',  'EntradaSalidaController@listproductosalida')->name('entrada_salida.listproductosalida');
    Route::get('entrada_salida/verdetalle/{id?}',  'EntradaSalidaController@verdetalle')->name('entrada_salida.verdetalle');

    //ENTRADAS Y SALIDAS
    Route::post('lotes_caducidad/buscar','lotescaducidadController@buscar')->name('lotes_caducidad.buscar');
    Route::get('lotes_caducidad/eliminar/{id}/{listarluego}','lotescaducidadController@eliminar')->name('lotes_caducidad.eliminar');
    Route::resource('lotes_caducidad', 'lotescaducidadController', array('except' => array('show')));
    
    //STOCK
    Route::post('stock_producto/buscar','StockController@buscar')->name('stock_producto.buscar');
    Route::get('stock_producto/eliminar/{id}/{listarluego}','StockController@eliminar')->name('stock_producto.eliminar');
    Route::resource('stock_producto', 'StockController', array('except' => array('show')));
    
    
    //VENTA
    Route::post('ventas/buscar','VentasController@buscar')->name('ventas.buscar');
    Route::get('ventas/eliminar/{id}/{listarluego}','VentasController@eliminar')->name('ventas.eliminar');
    Route::resource('ventas', 'VentasController', array('except' => array('show')));
    Route::get('ventas/listclientes',  'VentasController@listclientes')->name('ventas.listclientes');
    Route::get('ventas/listproductos',  'VentasController@listproductos')->name('ventas.listproductos');

    //SUCURSAL
    Route::post('sucursal/buscar','SucursalController@buscar')->name('sucursal.buscar');
    Route::get('sucursal/eliminar/{id}/{listarluego}','SucursalController@eliminar')->name('sucursal.eliminar');
    Route::resource('sucursal', 'SucursalController', array('except' => array('show')));
    //Departamento
    Route::post('departamento/buscar','DepartamentoController@buscar')->name('departamento.buscar');
    Route::get('departamento/eliminar/{id}/{listarluego}','DepartamentoController@eliminar')->name('departamento.eliminar');
    Route::resource('departamento', 'DepartamentoController', array('except' => array('show')));
    //Provincia
    Route::post('provincia/buscar','ProvinciaController@buscar')->name('provincia.buscar');
    Route::get('provincia/eliminar/{id}/{listarluego}','ProvinciaController@eliminar')->name('provincia.eliminar');
    Route::resource('provincia', 'ProvinciaController', array('except' => array('show')));
    //Distrito
    Route::post('distrito/buscar','DistritoController@buscar')->name('distrito.buscar');
    Route::get('distrito/eliminar/{id}/{listarluego}','DistritoController@eliminar')->name('distrito.eliminar');
    Route::resource('distrito', 'DistritoController', array('except' => array('show')));
    //Tipo Persona
    Route::post('tipopersona/buscar','TipopersonaController@buscar')->name('tipopersona.buscar');
    Route::get('tipopersona/eliminar/{id}/{listarluego}','TipopersonaController@eliminar')->name('tipopersona.eliminar');
    Route::resource('tipopersona', 'TipopersonaController', array('except' => array('show')));
    //Trabajador
    Route::post('trabajador/buscar','TrabajadorController@buscar')->name('trabajador.buscar');
    Route::get('trabajador/eliminar/{id}/{listarluego}','TrabajadorController@eliminar')->name('trabajador.eliminar');
    Route::resource('trabajador', 'TrabajadorController', array('except' => array('show')));
    //Trabajador
    Route::post('clientes/buscar','ClienteController@buscar')->name('clientes.buscar');
    Route::get('clientes/eliminar/{id}/{listarluego}','ClienteController@eliminar')->name('clientes.eliminar');
    Route::resource('clientes', 'ClienteController', array('except' => array('show')));

    /*CONCEPTO*/
    Route::post('concepto/buscar', 'ConceptoController@buscar')->name('concepto.buscar');
    Route::get('concepto/eliminar/{id}/{listarluego}', 'ConceptoController@eliminar')->name('concepto.eliminar');
    Route::resource('concepto', 'ConceptoController', array('except' => array('show')));

});
Route::get('entrada/{id?}','EntradaSalidaController@getEntrada');
Route::get('compra/{id?}','CompraController@getProductoPresentacion');
Route::get('ventas/{producto_id?}','VentasController@getProducto');
Route::get('ventas/{producto_id?}/{presentacion_id?}','VentasController@getProductoPresentacion');
Route::get('provincias/{id}','ProvinciaController@getProvincias');
Route::get('distritos/{id}','DistritoController@getDistritos');