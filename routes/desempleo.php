<?php

use App\Http\Controllers\polizas\DesempleoCarteraController;
use App\Http\Controllers\polizas\DesempleoController;
use App\Http\Controllers\polizas\DesempleoRenovacionController;
use App\Http\Controllers\polizas\DesempleoTasaDiferenciadaController;
use App\Models\polizas\Desempleo;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {


    Route::post('polizas/desempleo/agregar_no_valido/{id}', [DesempleoController::class, 'agregar_no_valido']);
    Route::get('polizas/desempleo/get_no_valido/{id}', [DesempleoController::class, 'get_no_valido']);
    Route::post('polizas/desempleo/store_poliza/{id}', [DesempleoController::class, 'store_poliza']);

    Route::post('polizas/desempleo/borrar_proceso_actual/{id}', [DesempleoController::class, 'borrar_proceso_actual']);
    Route::post('polizas/desempleo/agregar_pago', [DesempleoController::class, 'agregar_pago']);
    Route::get('polizas/desempleo/get_pago/{id}', [DesempleoController::class, 'get_pago']);
    Route::post('poliza/desempleo/recibo/{id}', [DesempleoController::class, 'recibo_pago']);
    Route::post('polizas/desempleo/edit_pago', [DesempleoController::class, 'edit_pago']);
    Route::post('polizas/desempleo/anular_pago/{id}', [DesempleoController::class, 'anular_pago']);
    Route::post('polizas/desempleo/delete_pago/{id}', [DesempleoController::class, 'delete_pago']);
    Route::post('finalizar_configuracion_desempleo', [DesempleoController::class, 'finalizar_configuracion']);

    Route::get('polizas/desempleo/subir_cartera/{id}', [DesempleoCarteraController::class, 'subir_cartera']);
    Route::post('polizas/desempleo/create_pago/{id}', [DesempleoCarteraController::class, 'create_pago']);
    Route::post('polizas/desempleo/eliminar_pago/{id}', [DesempleoCarteraController::class, 'eliminar_pago']);

    Route::post('polizas/desempleo/create_pago_fedecredito/{id}', [DesempleoCarteraController::class, 'create_pago_fedecredito']);
    Route::post('polizas/desempleo/validar_poliza/{id}', [DesempleoCarteraController::class, 'validar_poliza']);

    Route::post('polizas/desempleo/cancelar_pago', [DesempleoCarteraController::class, 'cancelar_pago']);

    Route::post('exportar/desempleo/registros_edad_maxima/{id}', [DesempleoController::class, 'registros_edad_maxima']);
    Route::post('exportar/desempleo/registros_responsabilidad_maxima/{id}', [DesempleoController::class, 'registros_responsabilidad_maxima']);
    Route::post('exportar/desempleo/nuevos_registros/{id}', [DesempleoController::class, 'exportar_nuevos_registros']);
    Route::post('exportar/desempleo/registros_eliminados/{id}', [DesempleoController::class, 'exportar_registros_eliminados']);
    Route::post('exportar/desempleo/registros_rehabilitados/{id}', [DesempleoController::class, 'exportar_registros_rehabilitados']);


    Route::get('poliza/desempleo/get_recibo/{id}/{exportar}', [DesempleoController::class, 'get_recibo']);
    Route::get('poliza/desempleo/get_recibo_edit/{id}', [DesempleoController::class, 'get_recibo_edit']);
    Route::post('poliza/desempleo/get_recibo_edit', [DesempleoController::class, 'get_recibo_update']);
    Route::get('polizas/desempleo/get_cartera/{id}/{mes}/{axo}', [DesempleoController::class, 'get_cartera']);

    Route::post('polizas/desempleo/store_poliza_primara_carga', [DesempleoController::class, 'primera_carga']);
    Route::post('polizas/desempleo/delete_temp', [DesempleoCarteraController::class, 'delete_temp']);


    //tasa diferenciada
    Route::get('polizas/desempleo/tasa_diferenciada/{id}', [DesempleoTasaDiferenciadaController::class, 'tasa_diferenciada']);
    Route::post('polizas/desempleo/agregar_tipo_cartera/{id}', [DesempleoTasaDiferenciadaController::class, 'agregar_tipo_cartera']);
    Route::post('polizas/desempleo/tasa_diferenciada', [DesempleoTasaDiferenciadaController::class, 'store'])->name('tasa_diferenciada_desempleo.store');
    Route::post('polizas/desempleo/delete_tipo_cartera', [DesempleoTasaDiferenciadaController::class, 'delete_tipo_cartera']);
    Route::post('polizas/desempleo/delete_tasa_diferenciada', [DesempleoTasaDiferenciadaController::class, 'destroy']);
    Route::put('polizas/desempleo/update_tipo_cartera/{id}', [DesempleoTasaDiferenciadaController::class, 'update_tipo_cartera']);
    Route::put('polizas/desempleo/tasa_diferenciada/{id}', [DesempleoTasaDiferenciadaController::class, 'update']);

    //renovacion
    Route::get('polizas/desempleo/renovar/{id}', [DesempleoRenovacionController::class, 'renovar']);
    Route::post('polizas/desempleo/renovar', [DesempleoRenovacionController::class, 'save_renovar']);
    Route::post('polizas/desempleo/eliminar_renovacion/{id}', [DesempleoRenovacionController::class, 'eliminar_renovacion']);

    Route::resource('polizas/desempleo', DesempleoController::class);
});
