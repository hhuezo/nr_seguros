<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\UserController;
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

Route::get('/', [HomeController::class,'index']);

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
