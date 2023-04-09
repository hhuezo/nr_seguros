<?php

use App\Http\Controllers\catalogo\AreaComercialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\UserController;
use App\Http\Controllers\catalogo\ClienteController;
use App\Http\Controllers\catalogo\AseguradoraController;
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
use App\Http\Controllers\polizas\DepositoPlazoController;
use App\Http\Controllers\polizas\ResidenciaController;

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
Route::resource('catalogo/aseguradoras', AseguradoraController::class);
Route::resource('catalogo/ejecutivos', EjecutivoController::class);
Route::resource('catalogo/estado_polizas', EstadoPolizaController::class);
Route::resource('catalogo/estado_venta', EstadoVentaController::class);
Route::resource('catalogo/tipo_cartera', TipoCarteraController::class);
Route::resource('catalogo/tipo_negocio', TipoNegocioController::class);
Route::resource('catalogo/tipo_poliza', TipoPolizaController::class);
Route::resource('catalogo/ubicacion_cobro', UbicacionCobroController::class);
Route::resource('catalogo/cliente', ClienteController::class);
Route::resource('catalogo/negocio', NegocioController::class);
Route::resource('catalogo/ruta',RutaController::class);
Route::resource('catalogo/tipo_cobro', TipoCobroController::class);
Route::resource('catalogo/area_comercial',AreaComercialController::class);

Route::get('catalogo/negocios/consultar', [NegocioController::class, 'consultar']);


//pólizas
Route::resource('polizas/residencia', ResidenciaController::class);
Route::resource('polizas/deposito_plazo', DepositoPlazoController::class);






