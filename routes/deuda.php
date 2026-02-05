<?php

use App\Http\Controllers\polizas\DeudaCarteraController;
use App\Http\Controllers\polizas\DeudaCarteraFedeController;
use App\Http\Controllers\polizas\DeudaController;
use App\Http\Controllers\polizas\DeudaRenovacionController;
use App\Http\Controllers\polizas\DeudaTasaDiferenciadaController;
use App\Http\Controllers\polizas\ResidenciaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {



    Route::post('exportar/extraprimados_excluidos/{id}', [DeudaController::class, 'extraprimados_excluidos']);
    Route::post('exportar/nuevos_registros/{id}', [DeudaController::class, 'exportar_nuevos_registros']);
    Route::post('exportar/registros_eliminados/{id}', [DeudaController::class, 'exportar_registros_eliminados']);
    Route::post('exportar/registros_no_validos/{id}', [DeudaController::class, 'registros_no_validos']);
    Route::post('exportar/registros_requisitos/{id}', [DeudaController::class, 'registros_requisitos']);
    Route::post('exportar/registros_requisitos_recibos/{id}', [DeudaController::class, 'registros_requisitos_recibos']);
    Route::post('exportar/registros_edad_maxima/{id}', [DeudaController::class, 'registros_edad_maxima']);
    Route::post('exportar/registros_erroneos/{id}', [DeudaController::class, 'registros_erroneos']);
    Route::post('exportar/registros_responsabilidad_maxima/{id}', [DeudaController::class, 'registros_responsabilidad_maxima']);
    Route::post('exportar/rehabilitados/{id}', [DeudaController::class, 'registros_rehabilitados']);



    Route::get('polizas/deuda/get_historico', [DeudaController::class, 'get_historico']);

    Route::post('polizas/deuda/create_pago', [DeudaCarteraController::class, 'create_pago']);
    Route::post('polizas/deuda/eliminar_pago/{id}', [DeudaCarteraController::class, 'eliminar_pago']);
    Route::post('polizas/deuda/create_pago_recibo', [DeudaCarteraController::class, 'create_pago_recibo']);
    Route::post('polizas/deuda/validar_poliza', [DeudaCarteraController::class, 'validar_poliza']);
    Route::post('polizas/deuda/validar_poliza_recibos', [DeudaCarteraController::class, 'validar_poliza_recibos']);
    Route::get('polizas/deuda/subir_cartera/{id}', [DeudaCarteraController::class, 'subir_cartera']);
    Route::get('polizas/deuda/recibo_complementario/{id}', [DeudaCarteraController::class, 'recibo_complementario']);
    Route::get('polizas/deuda/get_cartera/{id}/{mes}/{axo}', [DeudaCarteraController::class, 'get_cartera']);
    Route::post('polizas/deuda/delete_temp', [DeudaCarteraController::class, 'deleteLineaCredito']);
    Route::post('deuda/cancelar_pago', [DeudaController::class, 'cancelar_pago']);
    Route::post('deuda/reiniciar_carga', [DeudaController::class, 'reiniciar_carga']);
    Route::post('polizas/deuda/eliminar_extraprima', [DeudaController::class, 'eliminar_extraprima']);
    Route::get('polizas/deuda/get_extraprimado/{id}', [DeudaController::class, 'get_extraprimado']);
    Route::post('polizas/deuda/store_extraprimado', [DeudaController::class, 'store_extraprimado']);
    Route::post('polizas/deuda/update_extraprimado', [DeudaController::class, 'update_extraprimado']);
    Route::post('polizas/deuda/store_poliza_primara_carga/{id}', [DeudaCarteraController::class, 'primera_carga'])->name('deuda.primera_carga');
    Route::post('polizas/deuda/store_poliza', [DeudaCarteraController::class, 'store_poliza']);
    Route::post('polizas/deuda/store_poliza_recibo', [DeudaCarteraController::class, 'store_poliza_recibo']);
    Route::post('polizas/deuda/store_requisitos', [DeudaController::class, 'store_requisitos']);
    Route::get('polizas/deuda/renovar/{id}', [DeudaRenovacionController::class, 'renovar']);
    Route::post('polizas/deuda/renovar', [DeudaRenovacionController::class, 'save_renovar']);
    Route::get('poliza/deuda/configuracion_renovar/{id}', [DeudaRenovacionController::class, 'conf_renovar']);
    Route::post('renovacion_poliza', [DeudaRenovacionController::class, 'renovacion_poliza']);
    Route::get('get_fechas_renovacion', [DeudaRenovacionController::class, 'get_fechas_renovacion']);
    Route::post('eliminar_renovacion/{id}', [DeudaRenovacionController::class, 'eliminar_renovacion']);
    Route::get('polizas/deuda/get_requisitos', [DeudaController::class, 'get_requisitos']);





    Route::post('polizas/deuda/borrar_proceso_actual', [DeudaController::class, 'borrar_proceso_actual']);
    Route::post('exportar_excel', [DeudaController::class, 'exportar_excel']);
    Route::post('exportar_excel_fede', [DeudaController::class, 'exportar_excel_fede']);

    Route::post('poliza/deuda/aumentar_techo', [DeudaCarteraController::class, 'aumentar_techo']);
    Route::post('poliza/deuda/add_excluidos', [DeudaCarteraController::class, 'add_excluidos']);
    Route::post('poliza/deuda/add_excluidos_responsabilidad', [DeudaCarteraController::class, 'add_excluidos_responsabilidad']);
    Route::post('poliza/deuda/delete_excluido', [DeudaCarteraController::class, 'delete_excluido']);


    Route::post('polizas/deuda/agregar_pago', [DeudaController::class, 'agregar_pago']);
    Route::get('polizas/deuda/get_pago/{id}', [DeudaController::class, 'get_pago']);
    Route::post('polizas/deuda/edit_pago', [DeudaController::class, 'edit_pago']);
    Route::post('polizas/deuda/anular_pago/{id}', [DeudaController::class, 'anular_pago']);
    Route::post('polizas/deuda/delete_pago/{id}', [DeudaController::class, 'delete_pago']);
    Route::post('polizas/deuda/actualizar', [DeudaController::class, 'actualizar']);



    Route::POST('polizas/deuda/delete_tasa_diferenciada', [DeudaTasaDiferenciadaController::class, 'destroy']);
    Route::get('polizas/deuda/tasa_diferenciada/{id}', [DeudaTasaDiferenciadaController::class, 'show']);
    Route::post('polizas/deuda/tasa_diferenciada', [DeudaTasaDiferenciadaController::class, 'store'])->name('tasa_diferenciada.store');
    Route::put('polizas/deuda/tasa_diferenciada/{id}', [DeudaTasaDiferenciadaController::class, 'update']);
    Route::put('polizas/deuda/tasa_diferenciada_activo/{id}', [DeudaTasaDiferenciadaController::class, 'tasa_diferenciada_activo']);


    Route::post('polizas/deuda/agregar_tipo_cartera/{id}', [DeudaTasaDiferenciadaController::class, 'agregar_tipo_cartera']);
    Route::post('polizas/deuda/delete_tipo_cartera', [DeudaTasaDiferenciadaController::class, 'delete_tipo_cartera']);
    Route::put('polizas/deuda/update_tipo_cartera/{id}', [DeudaTasaDiferenciadaController::class, 'update_tipo_cartera']);



    Route::post('datos_asegurabilidad', [DeudaController::class, 'datos_asegurabilidad']);
    Route::post('polizas/deuda/eliminar_requisito', [DeudaController::class, 'eliminar_requisito']);
    Route::post('polizas/deuda/update_requisito', [DeudaController::class, 'update_requisito']);
    Route::post('polizas/deuda/exportar_historial', [DeudaController::class, 'exportar_historial']);

    Route::post('poliza/deuda/recibo/{id}', [DeudaController::class, 'recibo_pago']);
    Route::get('poliza/deuda/get_recibo/{id}/{exportar}', [DeudaController::class, 'get_recibo']);
    Route::get('poliza/deuda/get_recibo_edit/{id}', [DeudaController::class, 'get_recibo_edit']);
    Route::post('poliza/deuda/get_recibo_edit', [DeudaController::class, 'get_recibo_update']);
    Route::post('polizas/deuda/agregar_comentario', [DeudaController::class, 'agregar_comentario']);
    Route::post('polizas/deuda/eliminar_comentario', [DeudaController::class, 'eliminar_comentario']);



    Route::get('polizas/deuda/get_referencia_creditos/{id}/{tipo_cartera}', [DeudaController::class, 'get_referencia_creditos']);
    Route::get('polizas/deuda/get_creditos/{id}', [DeudaController::class, 'get_creditos']);

    Route::get('polizas/deuda/get_creditos_no_validos/{id}', [DeudaController::class, 'get_creditos_no_validos']);
    Route::get('polizas/deuda/get_creditos_con_requisitos/{id}', [DeudaController::class, 'get_creditos_con_requisitos']);

    Route::get('polizas/deuda/get_creditos_detalle_requisitos/{documento}/{poliza}/{tipo}/{tipo_cartera}', [DeudaController::class, 'get_creditos_detalle_requisitos']);



    Route::post('polizas/deuda/fede/create_pago', [DeudaCarteraFedeController::class, 'create_pago']);
    Route::post('polizas/deuda/fede/create_pago_recibo', [DeudaCarteraFedeController::class, 'create_pago_recibo']);

    Route::post('polizas/deuda/agregar_valido_detalle', [DeudaController::class, 'agregar_valido_detalle']);
    Route::post('polizas/deuda/agregar_valido', [DeudaController::class, 'agregar_valido']);

    Route::post('polizas/deuda/detalle_preliminar/{id}', [DeudaController::class, 'detalle_preliminar']);

    Route::get('deuda/exportar_excel/{id}', [DeudaController::class, 'exportar_excel_cartera']);









    Route::resource('polizas/deuda', DeudaController::class);
});
