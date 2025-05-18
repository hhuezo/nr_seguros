<?php

use App\Http\Controllers\catalogo\AreaComercialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\seguridad\UserController;
use App\Http\Controllers\catalogo\ClienteController;
use App\Http\Controllers\catalogo\AseguradoraController;
use App\Http\Controllers\catalogo\AsignacionNecesidadAseguradoraController;
use App\Http\Controllers\catalogo\BomberoController;
use App\Http\Controllers\catalogo\ConfiguracionReciboController;
use App\Http\Controllers\catalogo\DepartamentoNRController;
use App\Http\Controllers\catalogo\EjecutivoController;
use App\Http\Controllers\catalogo\EstadoPolizaController;
use App\Http\Controllers\catalogo\EstadoVentaController;
use App\Http\Controllers\catalogo\NecesidadProteccionController;
use App\Http\Controllers\catalogo\TipoCarteraController;
use App\Http\Controllers\catalogo\TipoNegocioController;
use App\Http\Controllers\catalogo\TipoPolizaController;
use App\Http\Controllers\catalogo\UbicacionCobroController;
use App\Http\Controllers\catalogo\NegocioController;
use App\Http\Controllers\catalogo\NrCarteraController;
use App\Http\Controllers\catalogo\PerfilController;
use App\Http\Controllers\catalogo\PlanController;
use App\Http\Controllers\catalogo\ProductoController;
use App\Http\Controllers\catalogo\RutaController;
use App\Http\Controllers\catalogo\TipoCarteraVidaController;
use App\Http\Controllers\catalogo\TipoCobroController;
use App\Http\Controllers\polizas\DesempleoController;
use App\Http\Controllers\polizas\DeudaCarteraController;
use App\Http\Controllers\polizas\DeudaCarteraFedeController;
use App\Http\Controllers\polizas\DeudaController;
use App\Http\Controllers\polizas\DeudaRenovacionController;
use App\Http\Controllers\polizas\DeudaTasaDiferenciadaController;
use App\Http\Controllers\polizas\VidaController;
use App\Http\Controllers\polizas\ResidenciaController;
use App\Http\Controllers\polizas\ValidacionCarteraController;
use App\Http\Controllers\polizas\VidaFedeController;
use App\Http\Controllers\polizas\VidaTasaDiferenciadaController;
use App\Http\Controllers\seguridad\PermissionController;
use App\Http\Controllers\seguridad\RoleController;
use App\Http\Controllers\suscripcion\SuscripcionController;

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

Route::get('/', [HomeController::class, 'redirectToLogin']);


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/getPrimaGeneral', [HomeController::class, 'getPrimaGeneral']);



//security
Route::middleware(['auth'])->group(function () {
    Route::post('usuario/rol_link', [UserController::class, 'rol_link']);
    Route::post('usuario/active/{id}', [UserController::class, 'active']);
    Route::resource('permission', PermissionController::class);
    Route::post('role/permission_link', [RoleController::class, 'permission_link']);
    Route::resource('rol', RoleController::class);
    Route::resource('usuario', UserController::class);


    //catalogos
    Route::get('/home', [HomeController::class, 'index'])->name('home');
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
    Route::get('get_producto/{id}', [PlanController::class, 'get_producto']);
    Route::get('get_plan/{id}', [PlanController::class, 'get_plan']);


    Route::get('catalogo/cliente/validar_cliente', [ClienteController::class, 'validar']);
    Route::post('catalogo/cliente/add_contacto', [ClienteController::class, 'add_contacto']);
    Route::post('catalogo/cliente/delete_contacto', [ClienteController::class, 'delete_contacto']);
    Route::post('catalogo/cliente/edit_contacto', [ClienteController::class, 'edit_contacto']);
    Route::post('catalogo/cliente/add_tarjeta', [ClienteController::class, 'add_tarjeta']);
    Route::post('catalogo/cliente/delete_tarjeta', [ClienteController::class, 'delete_tarjeta']);
    Route::post('catalogo/cliente/edit_tarjeta', [ClienteController::class, 'edit_tarjeta']);
    Route::post('catalogo/cliente/add_habito', [ClienteController::class, 'add_habito']);
    Route::post('catalogo/cliente/edit_habito', [ClienteController::class, 'edit_habito']);
    Route::post('catalogo/cliente/delete_habito', [ClienteController::class, 'delete_habito']);
    Route::post('catalogo/cliente/add_retroalimentacion', [ClienteController::class, 'add_retroalimentacion']);
    Route::post('catalogo/cliente/delete_retroalimentacion', [ClienteController::class, 'delete_retroalimentacion']);
    Route::post('catalogo/cliente/edit_retroalimentacion', [ClienteController::class, 'edit_retroalimentacion']);
    Route::post('catalogo/cliente/red_social', [ClienteController::class, 'red_social']);
    Route::post('catalogo/cliente/active/{id}', [ClienteController::class, 'active']);
    Route::get('catalogo/cliente/addCargo', [ClienteController::class, 'addCargo']);
    Route::get('catalogo/cliente/addMotivo', [ClienteController::class, 'addMotivo']);
    Route::post('catalogo/cliente/documento', [ClienteController::class, 'agregar_documento']);
    Route::post('catalogo/cliente/documento_eliminar/{id}', [ClienteController::class, 'eliminar_documento']);
    Route::post('catalogo/aseguradora/documento', [AseguradoraController::class, 'agregar_documento']);
    Route::post('catalogo/aseguradora/documento_eliminar/{id}', [AseguradoraController::class, 'eliminar_documento']);
    Route::get('catalogo/cliente/addPreferencia', [ClienteController::class, 'addPreferencia']);
    Route::get('catalogo/cliente_create', [ClienteController::class, 'cliente_create']);
    Route::resource('catalogo/ruta', RutaController::class);
    Route::resource('catalogo/perfiles', PerfilController::class);
    Route::post('finalizar_configuracion', [DeudaController::class, 'finalizar_configuracion']);

    Route::resource('catalogo/tipo_cobro', TipoCobroController::class);
    Route::resource('catalogo/area_comercial', AreaComercialController::class);
    Route::resource('catalogo/bombero', BomberoController::class);
    Route::resource('catalogo/configuracion_recibo', ConfiguracionReciboController::class);
    Route::get('get_municipio/{id}', [ClienteController::class, 'get_municipio']);
    Route::get('get_distrito/{id}', [ClienteController::class, 'get_distrito']);

    Route::get('catalogo/cliente/getMetodoPago', [ClienteController::class, 'getMetodoPago']);
    Route::get('catalogo/cliente/verificarCredenciales', [ClienteController::class, 'verificarCredenciales']);

    Route::resource('catalogo/cliente', ClienteController::class); //el resource va siempre de ultimo o ocurre problema con metodo controller::show()



    //pólizas
    Route::get('polizas/residencia/get_recibo', [ResidenciaController::class, 'impresion']);
    Route::resource('polizas/residencia', ResidenciaController::class);
    Route::post('polizas/residencia/create_pago', [ResidenciaController::class, 'create_pago']);
    Route::post('polizas/residencia/agregar_pago', [ResidenciaController::class, 'agregar_pago']);
    Route::post('polizas/residencia/edit_pago', [ResidenciaController::class, 'edit_pago']);
    Route::post('poliza/residencia/recibo/{id}', [ResidenciaController::class, 'recibo_pago']);
    Route::get('poliza/residencia/get_recibo/{id}', [ResidenciaController::class, 'get_recibo']);
    Route::post('poliza/residencia/active/{id}', [ResidenciaController::class, 'active_edit']);
    Route::post('poliza/residencia/desactive/{id}', [ResidenciaController::class, 'desactive_edit']);
    Route::get('polizas/residencia/get_recibo', [ResidenciaController::class, 'impresion']);
    Route::get('polizas/residencia/get_pago/{id}', [ResidenciaController::class, 'get_pago']);
    Route::get('polizas/residencia/{id}/renovar', [ResidenciaController::class, 'renovar']);
    Route::get('polizas/residencia/{id}/cancelacion', [ResidenciaController::class, 'cancelacion']);
    Route::post('polizas/residencia/renovar/{id}', [ResidenciaController::class, 'renovarPoliza'])->name('residencia.renovarPoliza');
    Route::post('polizas/residencia/delete_pago/{id}', [ResidenciaController::class, 'delete_pago']);
    Route::post('polizas/residencia/agregar_comentario', [ResidenciaController::class, 'agregar_comentario']);
    Route::post('polizas/residencia/eliminar_comentario', [ResidenciaController::class, 'eliminar_comentario']);
    Route::post('polizas/residencia/cancelar_pago', [ResidenciaController::class, 'cancelar_pago']);




    Route::get('polizas/deuda/get_referencia_creditos/{id}', [DeudaController::class, 'get_referencia_creditos']);
    Route::get('polizas/deuda/get_creditos/{id}', [DeudaController::class, 'get_creditos']);
    Route::get('polizas/deuda/get_creditos_detalle/{documento}/{poliza}/{tipo}', [DeudaController::class, 'get_creditos_detalle']);


    Route::post('exportar/extraprimados_excluidos/{id}', [DeudaController::class, 'extraprimados_excluidos']);
    Route::post('exportar/nuevos_registros/{id}', [DeudaController::class, 'exportar_nuevos_registros']);
    Route::post('exportar/registros_eliminados/{id}', [DeudaController::class, 'exportar_registros_eliminados']);
    Route::post('exportar/registros_no_validos/{id}', [DeudaController::class, 'registros_no_validos']);
    Route::post('exportar/registros_requisitos/{id}', [DeudaController::class, 'registros_requisitos']);
    Route::post('exportar/registros_requisitos_recibos/{id}', [DeudaController::class, 'registros_requisitos_recibos']);
    Route::post('exportar/registros_edad_maxima/{id}', [DeudaController::class, 'registros_edad_maxima']);
    Route::post('exportar/registros_erroneos/{id}', [DeudaController::class, 'registros_erroneos']);
    Route::post('exportar/registros_responsabilidad_maxima/{id}', [DeudaController::class, 'registros_responsabilidad_maxima']);
    Route::get('polizas/deuda/get_historico', [DeudaController::class, 'get_historico']);
    Route::post('polizas/deuda/agregar_valido', [DeudaController::class, 'agregar_valido']);
    Route::post('polizas/deuda/agregar_validado', [DeudaController::class, 'agregar_validado']);
    Route::post('polizas/deuda/create_pago', [DeudaCarteraController::class, 'create_pago']);
    Route::post('polizas/deuda/create_pago_recibo', [DeudaCarteraController::class, 'create_pago_recibo']);
    Route::post('polizas/deuda/validar_poliza', [DeudaCarteraController::class, 'validar_poliza']);
    Route::post('polizas/deuda/validar_poliza_recibos', [DeudaCarteraController::class, 'validar_poliza_recibos']);
    Route::get('polizas/deuda/subir_cartera/{id}', [DeudaCarteraController::class, 'subir_cartera']);
    Route::get('polizas/deuda/recibo_complementario/{id}', [DeudaCarteraController::class, 'recibo_complementario']);
    Route::post('polizas/deuda/delete_temp', [DeudaCarteraController::class, 'deleteLineaCredito']);
    Route::post('deuda/cancelar_pago', [DeudaController::class, 'cancelar_pago']);
    Route::post('polizas/deuda/eliminar_extraprima', [DeudaController::class, 'eliminar_extraprima']);
    Route::get('polizas/deuda/get_extraprimado/{poliza}/{dui}', [DeudaController::class, 'get_extraprimado']);
    Route::post('polizas/deuda/store_extraprimado', [DeudaController::class, 'store_extraprimado']);
    Route::post('polizas/deuda/update_extraprimado', [DeudaController::class, 'update_extraprimado']);
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
    Route::resource('polizas/deuda', DeudaController::class);

    Route::post('polizas/deuda/fede/create_pago', [DeudaCarteraFedeController::class, 'create_pago']);
    Route::post('polizas/deuda/fede/create_pago_recibo', [DeudaCarteraFedeController::class, 'create_pago_recibo']);



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







    //desempleo store_poliza

    Route::post('polizas/desempleo/agregar_no_valido/{id}', [DesempleoController::class, 'agregar_no_valido']);
    Route::get('polizas/desempleo/get_no_valido/{id}', [DesempleoController::class, 'get_no_valido']);
    Route::post('polizas/desempleo/store_poliza/{id}', [DesempleoController::class, 'store_poliza']);
    Route::post('polizas/desempleo/create_pago/{id}', [DesempleoController::class, 'create_pago']);
    Route::post('polizas/desempleo/borrar_proceso_actual/{id}', [DesempleoController::class, 'borrar_proceso_actual']);
    Route::post('polizas/desempleo/agregar_pago', [DesempleoController::class, 'agregar_pago']);
    Route::get('polizas/desempleo/get_pago/{id}', [DesempleoController::class, 'get_pago']);
    Route::post('poliza/desempleo/recibo/{id}', [DesempleoController::class, 'recibo_pago']);
    Route::post('polizas/desempleo/edit_pago', [DesempleoController::class, 'edit_pago']);
    Route::post('polizas/desempleo/anular_pago/{id}', [DesempleoController::class, 'anular_pago']);
    Route::post('polizas/desempleo/delete_pago/{id}', [DesempleoController::class, 'delete_pago']);
    Route::post('finalizar_configuracion_desempleo', [DesempleoController::class, 'finalizar_configuracion']);

    Route::post('exportar/desempleo/registros_edad_maxima/{id}', [DesempleoController::class, 'registros_edad_maxima']);
    Route::post('exportar/desempleo/registros_responsabilidad_maxima/{id}', [DesempleoController::class, 'registros_responsabilidad_maxima']);
    Route::post('exportar/desempleo/nuevos_registros/{id}', [DesempleoController::class, 'exportar_nuevos_registros']);
    Route::post('exportar/desempleo/registros_eliminados/{id}', [DesempleoController::class, 'exportar_registros_eliminados']);
    Route::post('exportar/desempleo/registros_rehabilitados/{id}', [DesempleoController::class, 'exportar_registros_rehabilitados']);


    Route::get('poliza/desempleo/get_recibo/{id}/{exportar}', [DesempleoController::class, 'get_recibo']);
    Route::get('poliza/desempleo/get_recibo_edit/{id}', [DesempleoController::class, 'get_recibo_edit']);
    Route::post('poliza/desempleo/get_recibo_edit', [DesempleoController::class, 'get_recibo_update']);

    Route::resource('polizas/desempleo', DesempleoController::class);






    //vida

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




    Route::get('get_cliente', [VidaController::class, 'get_cliente']);

    Route::post('polizas/vida/agregar_pago', [VidaController::class, 'agregar_pago']);
    Route::post('polizas/vida/cancelar_pago', [VidaController::class, 'cancelar_pago']);
    Route::post('poliza/vida/recibo/{id}', [VidaController::class, 'recibo_pago']);


    Route::post('poliza/vida/validar_store', [VidaController::class, 'validar_store']);

    Route::resource('polizas/vida', VidaController::class);

    Route::resource('catalogo/tipo_cartera_vida', TipoCarteraVidaController::class);
    Route::post('polizas/vida/edit_pago', [VidaController::class, 'edit_pago']);
    Route::get('polizas/vida/get_pago/{id}', [VidaController::class, 'get_pago']);

    Route::post('polizas/vida/anular_pago/{id}', [VidaController::class, 'anular_pago']);
    Route::post('polizas/vida/delete_pago/{id}', [VidaController::class, 'delete_pago']);

    Route::get('poliza/vida/get_recibo/{id}/{exportar}', [VidaController::class, 'get_recibo']);
    Route::get('poliza/vida/get_recibo_edit/{id}', [VidaController::class, 'get_recibo_edit']);
    Route::post('poliza/vida/get_recibo_edit', [VidaController::class, 'get_recibo_update']);

    Route::post('vida/exportar_excel', [VidaController::class, 'exportar_excel']);
    Route::post('vida/exportar_excel_fede', [VidaController::class, 'exportar_excel_fede']);

    Route::post('exportar/vida/registros_edad_maxima/{id}', [VidaController::class, 'registros_edad_maxima']);
    Route::post('exportar/vida/registros_responsabilidad_maxima/{id}', [VidaController::class, 'registros_responsabilidad_maxima']);
    Route::post('exportar/vida/registros_responsabilidad_terminacion/{id}', [VidaController::class, 'registros_responsabilidad_terminacion']);
    Route::post('exportar/vida/nuevos_registros/{id}', [VidaController::class, 'exportar_nuevos_registros']);


    //suscripciones

    Route::get('get_imc', [SuscripcionController::class, 'get_imc']);
    Route::post('suscripciones/agregar_comentario', [SuscripcionController::class, 'agregar_comentario']);
    Route::post('suscripciones_update', [SuscripcionController::class, 'update']);
    Route::resource('suscripciones', SuscripcionController::class);
});
