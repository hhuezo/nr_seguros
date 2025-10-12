<?php

use App\Http\Controllers\polizas\DeudaCarteraFedeController;
use App\Http\Controllers\polizas\DeudaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {


    Route::get('/get_referencia_creditos/{id}/{tipo_cartera}', [DeudaController::class, 'get_referencia_creditos']);
    Route::get('/get_creditos/{id}', [DeudaController::class, 'get_creditos']);

    Route::get('/get_creditos_no_validos/{id}', [DeudaController::class, 'get_creditos_no_validos']);
    Route::get('/get_creditos_con_requisitos/{id}', [DeudaController::class, 'get_creditos_con_requisitos']);

    Route::get('/get_creditos_detalle_requisitos/{documento}/{poliza}/{tipo}/{tipo_cartera}', [DeudaController::class, 'get_creditos_detalle_requisitos']);



    Route::post('/fede/create_pago', [DeudaCarteraFedeController::class, 'create_pago']);
    Route::post('/fede/create_pago_recibo', [DeudaCarteraFedeController::class, 'create_pago_recibo']);

    Route::post('/agregar_valido_detalle', [DeudaController::class, 'agregar_valido_detalle']);


});
