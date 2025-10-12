<?php

use App\Http\Controllers\catalogo\AseguradoraController;
use App\Http\Controllers\catalogo\AsignacionNecesidadAseguradoraController;
use App\Http\Controllers\catalogo\DepartamentoNRController;
use App\Http\Controllers\catalogo\EjecutivoController;
use App\Http\Controllers\catalogo\EstadoPolizaController;
use App\Http\Controllers\catalogo\EstadoVentaController;
use App\Http\Controllers\catalogo\NecesidadProteccionController;
use App\Http\Controllers\catalogo\NegocioController;
use App\Http\Controllers\catalogo\NrCarteraController;
use App\Http\Controllers\catalogo\PlanController;
use App\Http\Controllers\catalogo\ProductoController;
use App\Http\Controllers\catalogo\TipoCarteraController;
use App\Http\Controllers\catalogo\TipoNegocioController;
use App\Http\Controllers\catalogo\TipoPolizaController;
use App\Http\Controllers\catalogo\UbicacionCobroController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\polizas\DeudaController;

Route::middleware(['web'])->group(function () {

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
    Route::get('get_producto/{id}/{tipo}', [PlanController::class, 'get_producto']);
    Route::get('get_plan/{id}', [PlanController::class, 'get_plan']);
});
