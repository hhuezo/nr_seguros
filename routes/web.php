<?php

use App\Http\Controllers\catalogo\AseguradoraController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\UserController;
use App\Http\Controllers\catalogo\ClienteController;
use App\Http\Controllers\catalogo\EjecutivoController;
use App\Http\Controllers\catalogo\EstadoVentaController;
use App\Http\Controllers\catalogo\TipoCarteraController;
use App\Http\Controllers\catalogo\TipoNegocioController;
use App\Http\Controllers\catalogo\TipoPolizaController;
use App\Http\Controllers\catalogo\UbicacionCobroController;

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

//security
Route::resource('permission', PermissionController::class);
Route::post('permission/unlink', [PermissionController::class, 'unlink']);
Route::post('permission/link', [PermissionController::class, 'link']);
Route::post('role/unlink', [UserController::class, 'unlink']);
Route::post('role/link', [UserController::class, 'link']);
Route::resource('rol', RoleController::class);
Route::resource('usuario', UserController::class);


//catalogos
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('catalogo/aseguradoras', AseguradoraController::class);
Route::resource('catalogo/ejecutivos', EjecutivoController::class);
Route::resource('catalogo/estado_ventas', EstadoVentaController::class);
Route::resource('catalogo/tipo_carteras', TipoCarteraController::class);
Route::resource('catalogo/tipo_negocios', TipoNegocioController::class);
Route::resource('catalogo/tipo_polizas', TipoPolizaController::class);
Route::resource('catalogo/ubicacion_cobros', UbicacionCobroController::class);
Route::resource('catalogo/cliente', ClienteController::class);






