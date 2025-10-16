<?php

use App\Http\Controllers\catalogo\TipoCarteraVidaController;
use App\Http\Controllers\polizas\ResidenciaController;
use App\Http\Controllers\polizas\VidaController;
use App\Http\Controllers\polizas\VidaFedeController;
use App\Http\Controllers\polizas\VidaTasaDiferenciadaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {





    Route::post('polizas/vida/create_pago/{id}', [VidaController::class, 'create_pago']);
    Route::get('polizas/vida/subir_cartera/{id}', [VidaController::class, 'subir_cartera']);
    Route::post('polizas/vida/validar_poliza/{id}', [VidaController::class, 'validar_poliza']);
    Route::post('polizas/vida/delete_temp/{id}', [VidaController::class, 'delete_temp']);
    Route::post('polizas/vida/agregar_no_valido/{id}', [VidaController::class, 'agregar_no_valido']);
    Route::get('polizas/vida/get_no_valido/{id}', [VidaController::class, 'get_no_valido']);
    Route::post('polizas/vida/store_poliza/{id}', [VidaController::class, 'store_poliza']);
    Route::get('polizas/vida/tasas/{id}', [VidaController::class, 'tasas']);
    Route::post('finalizar_configuracion_vida', [VidaController::class, 'finalizar_configuracion']);


    Route::POST('polizas/vida/delete_tasa_diferenciada', [VidaTasaDiferenciadaController::class, 'destroy']);
    Route::get('polizas/vida/tasa_diferenciada/{id}', [VidaTasaDiferenciadaController::class, 'show']);
    Route::post('polizas/vida/tasa_diferenciada', [VidaTasaDiferenciadaController::class, 'store'])->name('tasa_diferenciada_vida.store');
    Route::put('polizas/vida/tasa_diferenciada/{id}', [VidaTasaDiferenciadaController::class, 'update']);

    Route::post('polizas/vida/fede/create_pago', [VidaFedeController::class, 'create_pago']);

    Route::post('polizas/vida/agregar_tipo_cartera/{id}', [VidaTasaDiferenciadaController::class, 'agregar_tipo_cartera']);
    Route::post('polizas/vida/delete_tipo_cartera', [VidaTasaDiferenciadaController::class, 'delete_tipo_cartera']);
    Route::put('polizas/vida/update_tipo_cartera/{id}', [VidaTasaDiferenciadaController::class, 'update_tipo_cartera']);

    Route::post('polizas/vida/update_extraprimado', [VidaController::class, 'update_extraprimado']);
    Route::post('polizas/vida/store_extraprimado', [VidaController::class, 'store_extraprimado']);
    Route::get('polizas/vida/get_extraprimado/{poliza}/{dui}', [VidaController::class, 'get_extraprimado']);
    Route::post('exportar/vida/extraprimados_excluidos/{id}', [VidaController::class, 'extraprimados_excluidos']);
    Route::post('polizas/vida/eliminar_extraprimado/{id}', [VidaController::class, 'delete_extraprimado']);
    Route::get('polizas/vida/get_cartera/{id}/{mes}/{axo}', [VidaController::class, 'get_cartera']); //




    Route::get('get_cliente', [VidaController::class, 'get_cliente']);

    Route::post('polizas/vida/agregar_pago', [VidaController::class, 'agregar_pago']);
    Route::post('polizas/vida/cancelar_pago', [VidaController::class, 'cancelar_pago']);
    Route::post('poliza/vida/recibo/{id}', [VidaController::class, 'recibo_pago']);


    Route::post('poliza/vida/validar_store', [VidaController::class, 'validar_store']);
    Route::get('poliza/vida/validar_edit/{id}', [VidaController::class, 'validar_edit']);

    Route::resource('polizas/vida', VidaController::class);

    Route::resource('catalogo/tipo_cartera_vida', TipoCarteraVidaController::class);
    Route::post('polizas/vida/edit_pago', [VidaController::class, 'edit_pago']);
    Route::get('polizas/vida/get_pago/{id}', [VidaController::class, 'get_pago']);

    Route::post('polizas/vida/anular_pago/{id}', [VidaController::class, 'anular_pago']);
    Route::post('polizas/vida/delete_pago/{id}', [VidaController::class, 'delete_pago']);

    Route::get('poliza/vida/get_recibo/{id}/{exportar}', [VidaController::class, 'get_recibo']);
    Route::get('poliza/vida/get_recibo_edit/{id}', [VidaController::class, 'get_recibo_edit']);
    Route::post('poliza/vida/get_recibo_edit', [VidaController::class, 'get_recibo_update']);
    Route::post('polizas/vida/store_poliza_primara_carga/{id}',[VidaController::class, 'primera_carga']);


    Route::post('vida/exportar_excel', [VidaController::class, 'exportar_excel']);
    Route::post('vida/exportar_excel_fede', [VidaController::class, 'exportar_excel_fede']);

    Route::post('exportar/vida/registros_edad_maxima/{id}', [VidaController::class, 'registros_edad_maxima']);
    Route::post('exportar/vida/registros_responsabilidad_maxima/{id}', [VidaController::class, 'registros_responsabilidad_maxima']);
    Route::post('exportar/vida/registros_responsabilidad_terminacion/{id}', [VidaController::class, 'registros_responsabilidad_terminacion']);
    Route::post('exportar/vida/nuevos_registros/{id}', [VidaController::class, 'exportar_nuevos_registros']);
});
