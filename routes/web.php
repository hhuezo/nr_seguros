<?php

use App\Http\Controllers\catalogo\AreaComercialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\UserController;
use App\Http\Controllers\catalogo\ClienteController;
use App\Http\Controllers\catalogo\AseguradoraController;
use App\Http\Controllers\catalogo\AsignacionNecesidadAseguradoraController;
use App\Http\Controllers\catalogo\BomberoController;
use App\Http\Controllers\catalogo\DepartamentoNRController;
use App\Http\Controllers\catalogo\EjecutivoController;
use App\Http\Controllers\catalogo\EstadoPolizaController;
use App\Http\Controllers\catalogo\EstadoVentaController;
use App\Http\Controllers\catalogo\NecesidadProteccionController;
use App\Http\Controllers\catalogo\TipoCarteraController;
use App\Http\Controllers\catalogo\TipoNegocioController;
use App\Http\Controllers\catalogo\TipoPolizaController;
use App\Http\Controllers\catalogo\UbicacionCobroController;
use App\Http\Controllers\catalogo\NegocioController;
use App\Http\Controllers\catalogo\NrCarteraController;
use App\Http\Controllers\catalogo\PerfilController;
use App\Http\Controllers\catalogo\PlanController;
use App\Http\Controllers\catalogo\ProductoController;
use App\Http\Controllers\catalogo\RutaController;
use App\Http\Controllers\catalogo\TipoCobroController;
use App\Http\Controllers\polizas\DeudaController;
use App\Http\Controllers\polizas\VidaController;
use App\Http\Controllers\polizas\ResidenciaController;
use App\Http\Controllers\polizas\ValidacionCarteraController;
use App\Http\Controllers\seguridad\PermissionController;
use App\Http\Controllers\seguridad\RoleController;
use App\Models\catalogo\Aseguradora;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', [HomeController::class, 'index']);

Route::get('/public', [HomeController::class, 'redirectToLogin']);


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/getPrimaGeneral', [HomeController::class, 'getPrimaGeneral']);



//security

Route::post('usuario/rol_link', [UserController::class, 'rol_link']);
Route::post('usuario/rol_unlink', [UserController::class, 'rol_unlink']);
Route::resource('permission', PermissionController::class);
Route::post('role/permission_unlink', [RoleController::class, 'permission_unlink']);
Route::post('role/permission_link', [RoleController::class, 'permission_link']);
Route::resource('rol', RoleController::class);
Route::resource('usuario', UserController::class);


//catalogos
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('catalogo/aseguradora/add_contacto', [AseguradoraController::class, 'add_contacto']);
Route::post('catalogo/aseguradora/delete_contacto', [AseguradoraController::class, 'delete_contacto']);
Route::post('catalogo/aseguradora/edit_contacto', [AseguradoraController::class, 'edit_contacto']);
Route::get('catalogo/aseguradora/addCargo', [AseguradoraController::class, 'addCargo']);
Route::post('catalogo/aseguradora/attach_necesidad_proteccion', [AseguradoraController::class, 'attach_necesidad_proteccion']);
Route::post('catalogo/aseguradora/detach_necesidad_proteccion', [AseguradoraController::class, 'detach_necesidad_proteccion']);
Route::get('catalogo/aseguradora/get_necesidad/{id}', [AseguradoraController::class, 'get_necesidad']);
Route::resource('catalogo/aseguradoras', AseguradoraController::class);
Route::resource('catalogo/ejecutivos', EjecutivoController::class);
Route::resource('catalogo/estado_polizas', EstadoPolizaController::class);
Route::resource('catalogo/estado_venta', EstadoVentaController::class);
Route::resource('catalogo/tipo_cartera', TipoCarteraController::class);
Route::resource('catalogo/nr_cartera', NrCarteraController::class);
Route::resource('catalogo/tipo_negocio', TipoNegocioController::class);
Route::resource('catalogo/tipo_poliza', TipoPolizaController::class);
Route::resource('catalogo/ubicacion_cobro', UbicacionCobroController::class);
Route::resource('catalogo/necesidad_proteccion', NecesidadProteccionController::class);
Route::resource('catalogo/necesidad_aseguradora', AsignacionNecesidadAseguradoraController::class);
Route::resource('catalogo/departamento_nr', DepartamentoNRController::class);

Route::post('catalogo/producto/add_cobertura', [ProductoController::class, 'add_cobertura']);
Route::post('catalogo/producto/edit_cobertura', [ProductoController::class, 'edit_cobertura']);
Route::post('catalogo/producto/delete_cobertura', [ProductoController::class, 'delete_cobertura']);
Route::post('catalogo/producto/add_dato_tecnico', [ProductoController::class, 'add_dato_tecnico']);
Route::post('catalogo/producto/edit_dato_tecnico', [ProductoController::class, 'edit_dato_tecnico']);
Route::post('catalogo/producto/delete_dato_tecnico', [ProductoController::class, 'delete_dato_tecnico']);
Route::resource('catalogo/producto', ProductoController::class);


Route::get('catalogo/plan/getCoberturas', [PlanController::class, 'getCoberturas']);
Route::post('catalogo/plan/edit_cobertura_detalle', [PlanController::class, 'edit_cobertura_detalle']);
Route::resource('catalogo/plan', PlanController::class);

Route::get('negocio/getCliente', [NegocioController::class, 'getCliente']);
Route::get('negocio/getProducto', [NegocioController::class, 'getProducto']);
Route::get('negocio/getPlan', [NegocioController::class, 'getPlan']);
Route::get('negocio/elegirCotizacion', [NegocioController::class, 'elegirCotizacion']);

Route::post('catalogo/negocio/add_cotizacion', [NegocioController::class, 'add_cotizacion']);
Route::post('catalogo/negocio/edit_cotizacion', [NegocioController::class, 'edit_cotizacion']);
Route::post('catalogo/negocio/delete_cotizacion', [NegocioController::class, 'delete_cotizacion']);
Route::post('catalogo/negocio/add_informacion_negocio', [NegocioController::class, 'add_informacion_negocio']);
Route::post('catalogo/negocio/edit_informacion_negocio', [NegocioController::class, 'edit_informacion_negocio']);
Route::post('catalogo/negocio/delete_informacion_negocio', [NegocioController::class, 'delete_informacion_negocio']);
Route::post('catalogo/negocio/documento', [NegocioController::class, 'agregar_documento']);
Route::post('catalogo/negocio/documento_eliminar/{id}', [NegocioController::class, 'eliminar_documento']);
Route::post('catalogo/negocio/add_gestion', [NegocioController::class, 'add_gestion']);
Route::post('catalogo/negocio/edit_gestion', [NegocioController::class, 'edit_gestion']);
Route::post('catalogo/negocio/delete_gestion', [NegocioController::class, 'delete_gestion']);



Route::resource('catalogo/negocio', NegocioController::class);
Route::get('get_producto/{id}',[PlanController::class , 'get_producto']);
Route::get('get_plan/{id}',[PlanController::class , 'get_plan']);


Route::post('catalogo/cliente/add_contacto', [ClienteController::class, 'add_contacto']);
Route::post('catalogo/cliente/delete_contacto', [ClienteController::class, 'delete_contacto']);
Route::post('catalogo/cliente/edit_contacto', [ClienteController::class, 'edit_contacto']);
Route::post('catalogo/cliente/add_tarjeta', [ClienteController::class, 'add_tarjeta']);
Route::post('catalogo/cliente/delete_tarjeta', [ClienteController::class, 'delete_tarjeta']);
Route::post('catalogo/cliente/edit_tarjeta', [ClienteController::class, 'edit_tarjeta']);
Route::post('catalogo/cliente/add_habito', [ClienteController::class, 'add_habito']);
Route::post('catalogo/cliente/edit_habito', [ClienteController::class, 'edit_habito']);
Route::post('catalogo/cliente/delete_habito', [ClienteController::class, 'delete_habito']);
Route::post('catalogo/cliente/add_retroalimentacion', [ClienteController::class, 'add_retroalimentacion']);
Route::post('catalogo/cliente/delete_retroalimentacion', [ClienteController::class, 'delete_retroalimentacion']);
Route::post('catalogo/cliente/edit_retroalimentacion', [ClienteController::class, 'edit_retroalimentacion']);
Route::post('catalogo/cliente/red_social', [ClienteController::class, 'red_social']);
Route::post('catalogo/cliente/active/{id}', [ClienteController::class, 'active']);
Route::get('catalogo/cliente/addCargo', [ClienteController::class, 'addCargo']);
Route::get('catalogo/cliente/addMotivo', [ClienteController::class, 'addMotivo']);
Route::post('catalogo/cliente/documento', [ClienteController::class, 'agregar_documento']);
Route::post('catalogo/cliente/documento_eliminar/{id}', [ClienteController::class, 'eliminar_documento']);
Route::post('catalogo/aseguradora/documento', [AseguradoraController::class, 'agregar_documento']);
Route::post('catalogo/aseguradora/documento_eliminar/{id}', [AseguradoraController::class, 'eliminar_documento']);
Route::get('catalogo/cliente/addPreferencia', [ClienteController::class, 'addPreferencia']);
Route::get('catalogo/cliente_create', [ClienteController::class, 'cliente_create']);
Route::resource('catalogo/ruta', RutaController::class);
Route::resource('catalogo/perfiles',PerfilController::class);
Route::post('finalizar_configuracion',[DeudaController::class, 'finalizar_configuracion']);    

Route::resource('catalogo/tipo_cobro', TipoCobroController::class);
Route::resource('catalogo/area_comercial', AreaComercialController::class);
Route::resource('catalogo/bombero', BomberoController::class);
Route::get('get_municipio/{id}', [ClienteController::class, 'get_municipio']);
Route::get('get_distrito/{id}', [ClienteController::class, 'get_distrito']);

Route::get('catalogo/cliente/getMetodoPago', [ClienteController::class, 'getMetodoPago']);
Route::get('catalogo/cliente/verificarCredenciales', [ClienteController::class, 'verificarCredenciales']);

Route::resource('catalogo/cliente', ClienteController::class);//el resource va siempre de ultimo o ocurre problema con metodo controller::show()



//pólizas
Route::get('polizas/residencia/get_recibo', [ResidenciaController::class, 'impresion']);
Route::resource('polizas/residencia', ResidenciaController::class);
Route::post('polizas/residencia/create_pago', [ResidenciaController::class, 'create_pago']);
Route::post('polizas/residencia/agregar_pago', [ResidenciaController::class, 'agregar_pago']);
Route::post('polizas/residencia/edit_pago', [ResidenciaController::class, 'edit_pago']);
Route::post('poliza/residencia/recibo/{id}', [ResidenciaController::class, 'recibo_pago']);
Route::get('poliza/residencia/get_recibo/{id}', [ResidenciaController::class, 'get_recibo']);
Route::post('poliza/residencia/active/{id}', [ResidenciaController::class, 'active_edit']);
Route::post('poliza/residencia/desactive/{id}', [ResidenciaController::class, 'desactive_edit']);
Route::get('polizas/residencia/get_recibo', [ResidenciaController::class, 'impresion']);
Route::get('polizas/residencia/get_pago/{id}', [ResidenciaController::class, 'get_pago']);
Route::get('polizas/residencia/{id}/renovar', [ResidenciaController::class, 'renovar']);
Route::get('polizas/residencia/{id}/cancelacion', [ResidenciaController::class, 'cancelacion']);
Route::post('polizas/residencia/renovar/{id}', [ResidenciaController::class, 'renovarPoliza'])->name('residencia.renovarPoliza');
Route::post('polizas/residencia/delete_pago/{id}', [ResidenciaController::class, 'delete_pago']);
Route::post('polizas/residencia/agregar_comentario',[ResidenciaController::class,'agregar_comentario']);
Route::post('polizas/residencia/eliminar_comentario',[ResidenciaController::class,'eliminar_comentario']);


Route::resource('polizas/vida', VidaController::class);
Route::post('polizas/vida/create_pago', [VidaController::class, 'create_pago']);
Route::post('polizas/vida/edit_pago', [VidaController::class, 'edit_pago']);
Route::get('polizas/vida/get_pago/{id}', [VidaController::class, 'get_pago']);
Route::get('get_cliente', [VidaController::class, 'get_cliente']);
Route::get('polizas/vida/{id}/renovar', [VidaController::class, 'renovar']);
Route::post('polizas/vida/renovar/{id}', [VidaController::class, 'renovarPoliza'])->name('vida.renovarPoliza');

Route::post('poliza/vida/usuario_edit', [VidaController::class, 'editarUsuario']);
Route::post('poliza/vida/usuario_delete', [VidaController::class, 'eliminarUsuario']);


Route::get('poliza/vida/usuario_create', [VidaController::class, 'agregarUsuario']);
Route::get('poliza/vida/usuario/{id}', [VidaController::class, 'getUsuario']);

Route::post('polizas/deuda/create_pago', [DeudaController::class, 'create_pago']);
Route::post('deuda/cancelar_pago',[DeudaController::class,'cancelar_pago']);
Route::post('polizas/deuda/eliminar_extraprima',[DeudaController::class,'eliminar_extraprima']);
Route::get('polizas/deuda/get_extraprimado/{poliza}/{dui}', [DeudaController::class, 'get_extraprimado']);
Route::post('polizas/deuda/store_extraprimado', [DeudaController::class, 'store_extraprimado']);
Route::post('polizas/deuda/update_extraprimado', [DeudaController::class, 'update_extraprimado']);
Route::post('polizas/deuda/store_poliza', [DeudaController::class, 'store_poliza']);
Route::post('polizas/deuda/store_requisitos', [DeudaController::class, 'store_requisitos']);
Route::get('polizas/deuda/get_requisitos', [DeudaController::class, 'get_requisitos']);
Route::resource('polizas/deuda', DeudaController::class);
Route::get('exportar/poliza_cumulo',[DeudaController::class,'exportar']);

Route::post('polizas/deuda/agregar_pago', [DeudaController::class, 'agregar_pago']);
Route::get('polizas/deuda/get_pago/{id}', [DeudaController::class, 'get_pago']);
Route::post('polizas/deuda/edit_pago', [DeudaController::class, 'edit_pago']);
Route::post('polizas/deuda/delete_pago/{id}', [DeudaController::class, 'delete_pago']);
Route::post('polizas/deuda/actualizar',[DeudaController::class ,'actualizar']);     
Route::post('agregar_credito',[DeudaController::class, 'agregar_credito']);
Route::post('eliminar_credito/{id}',[DeudaController::class,'eliminar_credito']);
Route::post('datos_asegurabilidad',[DeudaController::class,'datos_asegurabilidad']);
Route::post('eliminar/requisito',[DeudaController::class,'eliminar_requisito']);

Route::post('poliza/deuda/recibo/{id}', [DeudaController::class, 'recibo_pago']);
Route::get('poliza/deuda/get_recibo/{id}', [DeudaController::class, 'get_recibo']);
Route::post('polizas/deuda/agregar_comentario',[DeudaController::class,'agregar_comentario']);
Route::post('polizas/deuda/eliminar_comentario',[DeudaController::class,'eliminar_comentario']);


//validación de cartera
Route::resource('polizas/validacion_cartera', ValidacionCarteraController::class);
