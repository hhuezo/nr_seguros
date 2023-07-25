<?php

use App\Http\Controllers\catalogo\AreaComercialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\UserController;
use App\Http\Controllers\catalogo\ClienteController;
use App\Http\Controllers\catalogo\AseguradoraController;
use App\Http\Controllers\catalogo\BomberoController;
use App\Http\Controllers\catalogo\EjecutivoController;
use App\Http\Controllers\catalogo\EstadoPolizaController;
use App\Http\Controllers\catalogo\EstadoVentaController;
use App\Http\Controllers\catalogo\TipoCarteraController;
use App\Http\Controllers\catalogo\TipoNegocioController;
use App\Http\Controllers\catalogo\TipoPolizaController;
use App\Http\Controllers\catalogo\UbicacionCobroController;
use App\Http\Controllers\catalogo\NegocioController;
use App\Http\Controllers\catalogo\RutaController;
use App\Http\Controllers\catalogo\TipoCobroController;
use App\Http\Controllers\polizas\DeudaController;
use App\Http\Controllers\polizas\VidaController;
use App\Http\Controllers\polizas\ResidenciaController;
use App\Http\Controllers\polizas\ValidacionCarteraController;


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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
//security
Route::resource('permission', PermissionController::class);
Route::post('permission/unlink', [PermissionController::class, 'unlink']);
Route::post('permission/link', [PermissionController::class, 'link']);
Route::post('role/unlink', [UserController::class, 'unlink']);
Route::post('role/link', [UserController::class, 'link']);
Route::resource('rol', RoleController::class);
Route::resource('usuario', UserController::class);*/


//catalogos
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('catalogo/aseguradora/add_contacto', [AseguradoraController::class,'add_contacto']);
Route::post('catalogo/aseguradora/delete_contacto', [AseguradoraController::class,'delete_contacto']);
Route::post('catalogo/aseguradora/edit_contacto', [AseguradoraController::class,'edit_contacto']);
Route::resource('catalogo/aseguradoras', AseguradoraController::class);
Route::resource('catalogo/ejecutivos', EjecutivoController::class);
Route::resource('catalogo/estado_polizas', EstadoPolizaController::class);
Route::resource('catalogo/estado_venta', EstadoVentaController::class);
Route::resource('catalogo/tipo_cartera', TipoCarteraController::class);
Route::resource('catalogo/tipo_negocio', TipoNegocioController::class);
Route::resource('catalogo/tipo_poliza', TipoPolizaController::class);
Route::resource('catalogo/ubicacion_cobro', UbicacionCobroController::class);

Route::post('catalogo/cliente/add_contacto', [ClienteController::class,'add_contacto']);
Route::post('catalogo/cliente/delete_contacto', [ClienteController::class,'delete_contacto']);
Route::post('catalogo/cliente/edit_contacto', [ClienteController::class,'edit_contacto']);
Route::post('catalogo/cliente/add_tarjeta', [ClienteController::class,'add_tarjeta']);
Route::post('catalogo/cliente/delete_tarjeta', [ClienteController::class,'delete_tarjeta']);
Route::post('catalogo/cliente/edit_tarjeta', [ClienteController::class,'edit_tarjeta']);
Route::post('catalogo/cliente/add_habito', [ClienteController::class,'add_habito']);
Route::post('catalogo/cliente/edit_habito', [ClienteController::class,'edit_habito']);
Route::post('catalogo/cliente/delete_habito', [ClienteController::class,'delete_habito']);
Route::post('catalogo/cliente/add_retroalimentacion', [ClienteController::class,'add_retroalimentacion']);
Route::post('catalogo/cliente/delete_retroalimentacion', [ClienteController::class,'delete_retroalimentacion']);
Route::post('catalogo/cliente/edit_retroalimentacion', [ClienteController::class,'edit_retroalimentacion']);
Route::post('catalogo/cliente/red_social', [ClienteController::class,'red_social']);
Route::post('catalogo/cliente/active/{id}', [ClienteController::class,'active']);
Route::resource('catalogo/cliente', ClienteController::class);
Route::resource('catalogo/cliente', ClienteController::class);
Route::get('catalogo/cliente_create', [ClienteController::class, 'cliente_create']);
Route::get('catalogo/negocio/get_aseguradora',[NegocioController::class,'get_aseguradoras']);
Route::resource('catalogo/negocio', NegocioController::class);
Route::resource('catalogo/ruta',RutaController::class);
Route::get('negocio/getCliente',[NegocioController::class, 'getCliente']);
Route::get('catalogo/negocios/store_aseguradora',[NegocioController::class,'store_aseguradora']);
Route::resource('catalogo/tipo_cobro', TipoCobroController::class);
Route::resource('catalogo/area_comercial',AreaComercialController::class);
Route::resource('catalogo/bombero',BomberoController::class);
Route::get('catalogo/negocios/consultar', [NegocioController::class, 'consultar']);
Route::get('get_municipio/{id}', [ClienteController::class, 'get_municipio']);


//pólizas
Route::get('polizas/residencia/get_recibo',[ResidenciaController::class,'impresion']);
Route::resource('polizas/residencia', ResidenciaController::class);
Route::post('polizas/residencia/create_pago', [ResidenciaController::class,'create_pago']);
Route::post('polizas/residencia/agregar_pago', [ResidenciaController::class,'agregar_pago']);
Route::post('polizas/residencia/edit_pago', [ResidenciaController::class,'edit_pago']);
Route::get('polizas/residencia/get_recibo',[ResidenciaController::class,'impresion']);
Route::get('polizas/residencia/get_pago/{id}', [ResidenciaController::class,'get_pago']);
Route::get('polizas/residencia/{id}/renovar',[ResidenciaController::class, 'renovar']);
Route::get('polizas/residencia/{id}/cancelacion',[ResidenciaController::class, 'cancelacion']);
Route::post('polizas/residencia/renovar/{id}',[ResidenciaController::class, 'renovarPoliza'])->name('residencia.renovarPoliza');
Route::post('polizas/residencia/delete_pago/{id}', [ResidenciaController::class, 'delete_pago']);

Route::resource('polizas/vida', VidaController::class);
Route::post('polizas/vida/create_pago', [VidaController::class,'create_pago']);
Route::post('polizas/vida/edit_pago', [VidaController::class,'edit_pago']);
Route::get('polizas/vida/get_pago/{id}', [VidaController::class,'get_pago']);
Route::get('get_cliente', [VidaController::class, 'get_cliente']);
Route::get('polizas/vida/{id}/renovar',[VidaController::class, 'renovar']);
Route::post('polizas/vida/renovar/{id}',[VidaController::class, 'renovarPoliza'])->name('vida.renovarPoliza');

Route::post('poliza/vida/usuario_edit',[VidaController::class, 'editarUsuario']);
Route::post('poliza/vida/usuario_delete',[VidaController::class,'eliminarUsuario']);


Route::get('poliza/vida/usuario_create',[VidaController::class, 'agregarUsuario']);
Route::get('poliza/vida/usuario/{id}',[VidaController::class, 'getUsuario']);

Route::post('polizas/deuda/store_requisitos',[DeudaController::class, 'store_requisitos']);
Route::get('polizas/deuda/get_requisitos',[DeudaController::class, 'get_requisitos']);
Route::resource('polizas/deuda',DeudaController::class);
Route::post('polizas/deuda/create_pago', [DeudaController::class,'create_pago']);
Route::post('polizas/deuda/agregar_pago', [DeudaController::class,'agregar_pago']);
Route::get('polizas/deuda/get_pago/{id}', [DeudaController::class,'get_pago']);
Route::post('polizas/deuda/edit_pago', [DeudaController::class,'edit_pago']);
Route::post('polizas/deuda/delete_pago/{id}', [DeudaController::class, 'delete_pago']);



//validación de cartera
Route::resource('polizas/validacion_cartera', ValidacionCarteraController::class);




