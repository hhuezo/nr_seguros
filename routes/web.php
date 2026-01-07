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
use App\Http\Controllers\catalogo\TipoCobroController;
use App\Http\Controllers\polizas\DeudaController;
use App\Http\Controllers\polizas\PolizaControlCarteraController;
use App\Http\Controllers\polizas\PolizaSeguroController;
use App\Http\Controllers\seguridad\PermissionController;
use App\Http\Controllers\seguridad\RoleController;
use App\Http\Controllers\suscripcion\SuscripcionController;
use App\Http\Controllers\suscripcion\CompaniaController;
use App\Http\Controllers\suscripcion\EstadoCasoController;
use App\Http\Controllers\suscripcion\FechasFeriadasController;
use App\Http\Controllers\suscripcion\OcupacionController;
use App\Http\Controllers\suscripcion\TipoOrdenMedicaController;
use App\Http\Controllers\suscripcion\TipoImcController;
use App\Http\Controllers\suscripcion\TipoClienteController;
use App\Http\Controllers\suscripcion\TipoCreditoController;

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
    Route::get('get_producto/{id}/{tipo}', [PlanController::class, 'get_producto']);
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

    Route::put('catalogo/numeracion_recibo/{id}', [ConfiguracionReciboController::class, 'numeracion_recibo']);
    Route::get('catalogo/numeracion_recibo', [ConfiguracionReciboController::class, 'form_numeracion_recibo']);

    Route::get('get_municipio/{id}', [ClienteController::class, 'get_municipio']);
    Route::get('get_distrito/{id}', [ClienteController::class, 'get_distrito']);

    Route::get('catalogo/cliente/getMetodoPago', [ClienteController::class, 'getMetodoPago']);
    Route::get('catalogo/cliente/verificarCredenciales', [ClienteController::class, 'verificarCredenciales']);

    Route::resource('catalogo/cliente', ClienteController::class); //el resource va siempre de ultimo o ocurre problema con metodo controller::show()






    //vida



    //reportes declarativas
    Route::get('control_cartera/actualizacion', [PolizaControlCarteraController::class, 'actualizacion']);
    Route::get('control_cartera', [PolizaControlCarteraController::class, 'index']);
    Route::get('control_cartera/{id}/{tipo}/{anio}/{mes}', [PolizaControlCarteraController::class, 'edit']);
    Route::put('control_cartera/{id}', [PolizaControlCarteraController::class, 'update']);

    //suscripciones

    Route::get('get_imc', [SuscripcionController::class, 'get_imc']);
    Route::post('suscripciones/agregar_comentario', [SuscripcionController::class, 'agregar_comentario']);
    Route::post('suscripciones_update', [SuscripcionController::class, 'update']);
    Route::post('suscripciones/comentarios/update/{id}', [SuscripcionController::class, 'comentarios_update']);
    Route::delete('suscripciones/comentarios/delete/{id}', [SuscripcionController::class, 'comentarios_delete']);
    Route::get('suscripciones/getComentarios/{id}', [SuscripcionController::class, 'comentarios_get']);
    Route::post('suscripciones/exportar', [SuscripcionController::class, 'exportar']);
    Route::post('suscripciones/importar', [SuscripcionController::class, 'importar']);
    Route::get('suscripciones/calcular_dias_habiles_json', [SuscripcionController::class, 'calcularDiasHabilesJson'])->name('calcular.dias.habiles.json');
    Route::get('suscripciones/data/{fechaInicio}/{fechaFinal}', [SuscripcionController::class, 'data']);

    Route::resource('suscripciones', SuscripcionController::class);
    Route::resource('companias', CompaniaController::class);
    Route::resource('estadoscasos', EstadoCasoController::class);
    Route::resource('tiposordenesmedicas', TipoOrdenMedicaController::class);
    Route::resource('tiposimc', TipoImcController::class);
    Route::resource('tiposclientes', TipoClienteController::class);

    Route::resource('ocupaciones', OcupacionController::class);
    Route::resource('tipocreditos', TipoCreditoController::class);
    Route::resource('fechasferiadas', FechasFeriadasController::class);


    //no declarativas
    Route::get('poliza/seguro/get_oferta', [PolizaSeguroController::class, 'get_oferta']);
    Route::post('poliza/seguro/save/{id}', [PolizaSeguroController::class, 'save']);
    Route::post('poliza/cobertura/update/{id}', [PolizaSeguroController::class, 'update_cobertura']);
    Route::post('poliza/datos_tecnicos/update/{id}', [PolizaSeguroController::class, 'update_datos_tecnicos']);

    Route::post('poliza/seguro/cobertura_store/{id}', [PolizaSeguroController::class, 'cobertura_store']);
    Route::post('poliza/seguro/dato_tecnico_store/{id}', [PolizaSeguroController::class, 'dato_tecnico_store']);
    Route::delete('poliza/seguro/cobertura_delete/{id}', [PolizaSeguroController::class, 'cobertura_delete']);
    Route::delete('poliza/seguro/dato_tecnico_delete/{id}', [PolizaSeguroController::class, 'dato_tecnico_delete']);


    Route::resource('poliza/seguro', PolizaSeguroController::class);
});
