<?php

use App\Http\Controllers\polizas\ResidenciaController;
use App\Http\Controllers\polizas\ResidenciaRenovacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {


    Route::resource('polizas/residencia', ResidenciaController::class);
    Route::post('polizas/residencia/create_pago', [ResidenciaController::class, 'create_pago']);
    Route::post('polizas/residencia/agregar_pago', [ResidenciaController::class, 'agregar_pago']);
    Route::post('polizas/residencia/edit_pago', [ResidenciaController::class, 'edit_pago']);
    Route::post('polizas/residencia/recibo/{id}', [ResidenciaController::class, 'recibo_pago']);
    Route::get('polizas/residencia/get_recibo/{id}', [ResidenciaController::class, 'get_recibo']);
    Route::post('polizas/residencia/active/{id}', [ResidenciaController::class, 'active_edit']);
    Route::post('polizas/residencia/desactive/{id}', [ResidenciaController::class, 'desactive_edit']);
    Route::get('polizas/residencia/get_pago/{id}', [ResidenciaController::class, 'get_pago']);
    // Route::get('polizas/residencia/{id}/renovar', [ResidenciaController::class, 'renovar']);
    Route::get('polizas/residencia/{id}/cancelacion', [ResidenciaController::class, 'cancelacion']);
    // Route::post('polizas/residencia/renovar/{id}', [ResidenciaController::class, 'renovarPoliza'])->name('residencia.renovarPoliza');
    Route::post('polizas/residencia/delete_pago/{id}', [ResidenciaController::class, 'delete_pago']);
    Route::post('polizas/residencia/agregar_comentario', [ResidenciaController::class, 'agregar_comentario']);
    Route::post('polizas/residencia/eliminar_comentario', [ResidenciaController::class, 'eliminar_comentario']);
    Route::post('polizas/residencia/cancelar_pago', [ResidenciaController::class, 'cancelar_pago']);

    Route::post('polizas/residencia/reiniciar_carga', [ResidenciaController::class, 'reiniciar_carga']);

    //renovacion
    Route::get('polizas/residencia/renovar/{id}', [ResidenciaRenovacionController::class, 'renovar']);
    Route::post('polizas/residencia/renovar', [ResidenciaRenovacionController::class, 'save_renovar']);
    Route::post('polizas/residencia/eliminar_renovacion/{id}', [ResidenciaRenovacionController::class, 'eliminar_renovacion']);
});
