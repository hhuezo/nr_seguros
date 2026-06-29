# Contexto del proyecto NR Seguros

Revision base: 2026-06-23.

Este documento sirve como punto de entrada para futuras sesiones de trabajo. Si se pierde el historial, leer este archivo primero y luego abrir los archivos indicados en cada seccion.

Documento complementario:

- `CORRELACION_SISTEMA_BD.md`: mapa sistema-BD por modulo, con rutas, controladores, modelos, vistas, tablas y relaciones practicas.

## Stack

- Laravel 9 con PHP 8.
- Blade, Bootstrap, jQuery, DataTables, Select2 y tema tipo Gentelella/custom.
- Vite existe para `resources/sass/app.scss` y `resources/js/app.js`, pero muchas pantallas cargan assets directamente desde `public/vendors`, `public/build` y `resources/views/welcome.blade.php`.
- Autenticacion con `laravel/ui`.
- Permisos con `spatie/laravel-permission`.
- Excel/importaciones con `maatwebsite/excel` y `phpoffice/phpspreadsheet`.
- PDF con `barryvdh/laravel-dompdf`.
- Alertas con `realrashid/sweet-alert`.

## Estructura general

- `routes/web.php`: concentra rutas web. La mayor parte esta dentro de `Route::middleware(['auth'])->group(...)`.
- `app/Http/Controllers/catalogo`: catalogos, clientes, aseguradoras, productos, planes, negocios/ofertas.
- `app/Http/Controllers/polizas`: polizas declarativas, no declarativas, carteras, renovaciones y validaciones.
- `app/Http/Controllers/suscripcion`: suscripciones y catalogos de suscripcion.
- `app/Http/Controllers/seguridad`: usuarios, roles, permisos y tipos de permisos.
- `app/Models/catalogo`: modelos de catalogos, clientes, aseguradoras, productos, planes, ramos, negocios y cotizaciones.
- `app/Models/polizas`: modelos de polizas declarativas, carteras, historicos y poliza no declarativa generica.
- `app/Imports` y `app/Exports`: importacion/exportacion de carteras, recibos y reportes.
- `resources/views/welcome.blade.php`: layout principal autenticado con sidebar y menu por permisos.
- `resources/views/layouts/app.blade.php`: layout basico usado por auth/login.

## Convenciones importantes

- La base de datos usa muchos nombres con mayuscula inicial: `Id`, `Activo`, `Nombre`, etc.
- Muchos modelos usan `protected $primaryKey = 'Id'` y `public $timestamps = false`.
- No asumir `id`, timestamps ni soft deletes de Laravel por defecto.
- `Activo` suele funcionar como borrado logico; algunas partes antiguas todavia usan `delete()` real.
- Los controladores mezclan resource CRUD con endpoints especificos como `add_*`, `edit_*`, `delete_*`, `*_save`, `*_store`.
- Las vistas usan mayormente `url('...')` en vez de rutas nombradas.
- La UI se arma en Blade con Bootstrap, tabs `bar_tabs`, modales y scripts jQuery dentro de la misma vista.
- Hay textos con encoding danado en archivos existentes. Evitar cambios masivos de encoding si no son parte de la tarea.
- Antes de editar archivos modificados, revisar el diff local: hay cambios existentes no confirmados.

## Estado git al crear este resumen

Habia cambios locales previos. No revertirlos sin autorizacion.

Modificados relevantes:

- `routes/web.php`
- `resources/views/welcome.blade.php`
- `app/Http/Controllers/catalogo/ProductoController.php`
- `app/Http/Controllers/catalogo/PlanController.php`
- `app/Http/Controllers/polizas/PolizaSeguroController.php`
- modelos de `Producto`, `PlanCoberturaDetalle`, `PolizaSeguro`, `PolizaSeguroCertificado`, beneficiarios y cesiones
- vistas de producto, plan y poliza seguro

Nuevos/no rastreados relevantes:

- Catalogos: `EstadoCertificadoController`, `FormaPagoPolizaController`, `ParentescoBeneficiarioController`
- Modelos: `EstadoCertificado`, `FormaPagoPoliza`, `PolizaSeguroCertificadoCobertura`, `PolizaSeguroCertificadoDatoTecnico`
- Migraciones 2026-06-11 a 2026-06-18 para certificados/no declarativas
- `resources/views/polizas/seguro/certificado.blade.php`

## Permisos y menu

Permisos basados en Spatie.

Archivos clave:

- `database/seeders/PermissionTypeSeeder.php`
- `database/seeders/PermissionSeeder.php`
- `app/Models/Permission.php`
- `app/Models/PermissionType.php`
- `app/Models/User.php`
- `resources/views/welcome.blade.php`
- `app/Http/Controllers/seguridad`

Tipos de permisos principales:

- Administracion
- Seguridad
- Configuracion
- catalogos
- suscripciones
- catalogo deuda
- catalogo vida
- cliente
- cotizaciones
- aseguradoras
- poliza residencia
- poliza vida
- poliza deuda
- poliza desempleo
- poliza seguro
- poliza control-cartera

Patron de permisos:

- Menu principal: `<prefijo> menu`, por ejemplo `catalogos menu`, `poliza seguro menu`.
- Acciones por modulo: `<modulo> read`, `<modulo> create`, `<modulo> edit`, `<modulo> delete`.
- El sidebar usa `@can` y `@canany` directamente.
- Ejemplos: `producto read`, `plan read`, `seguro read`, `deuda read`, `suscripcion read`.

Nota: algunos enlaces de catalogos no declarativos en el menu aparecen sin `@can` especifico propio, por ejemplo motivo de cancelacion, estado de certificado, forma de pago poliza, origen poliza, tipo deducible y parentescos.

## Catalogos

Controladores en `app/Http/Controllers/catalogo`:

- Clientes: `ClienteController`
- Aseguradoras: `AseguradoraController`
- Ramos/necesidades: `NecesidadProteccionController`, `AgrupadorRamoController`
- Productos: `ProductoController`
- Planes: `PlanController`
- Negocios/ofertas: `NegocioController`
- Estados y auxiliares: `EstadoPolizaController`, `EstadoCertificadoController`, `EstadoVentaController`, `MotivoCancelacionController`, `FormaPagoPolizaController`, `OrigenPolizaController`, `DeducibleController`, `ParentescoBeneficiarioController`
- Otros: `EjecutivoController`, `DepartamentoNRController`, `TipoCarteraController`, `TipoNegocioController`, `TipoCobroController`, `TipoPolizaController`, `UbicacionCobroController`, `PerfilController`, `ConfiguracionReciboController`

Vistas en `resources/views/catalogo/*`.

Modelos importantes:

- `Cliente`, con contactos, documentos, tarjetas, habitos, retroalimentacion y metodos de pago.
- `Aseguradora`, con contactos, documentos, cargos y necesidades de proteccion.
- `NecesidadProteccion`, ramo del producto/negocio.
- `NecesidadProteccionCampo`, campos dinamicos por ramo.
- `Negocio`, oferta/cotizacion inicial.
- `Cotizacion`, ofertas por plan y marca `Aceptado`.
- `Producto`, configuracion comercial/tecnica del seguro.
- `Plan`, planes por producto.
- `PlanCoberturaDetalle`, pivot logico plan-cobertura.

## Producto, plan y campos dinamicos

Producto:

- Modelo: `app/Models/catalogo/Producto.php`
- Controlador: `app/Http/Controllers/catalogo/ProductoController.php`
- Vistas: `resources/views/catalogo/producto`
- Tabla: `producto`

Campos clave detectados:

- `Nombre`
- `Aseguradora`
- `NecesidadProteccion`
- `Descripcion`
- `PorcentajeComisionNoDeclarativa`
- `PermiteDependientesCertificado`
- `Activo`

Relaciones:

- aseguradora
- ramo/necesidad
- coberturas
- datos tecnicos
- planes
- campos dinamicos de certificado

Plan:

- Modelo: `app/Models/catalogo/Plan.php`
- Controlador: `app/Http/Controllers/catalogo/PlanController.php`
- Vistas: `resources/views/catalogo/plan`
- Tabla: `plan`

`PlanCoberturaDetalle`:

- Modelo: `app/Models/catalogo/PlanCoberturaDetalle.php`
- Tabla: `plan_cobertura_detalle`
- Campos logicos: `Plan`, `Cobertura`, `SumaAsegurada`, `Tasa`, `Prima`, `Activo`
- El modelo no incrementa llave (`public $incrementing = false`); algunas escrituras usan `DB::table()`.

Campos dinamicos:

- Ramo: `NecesidadProteccionCampo`, guardado como JSON en `Negocio.DatosRamo` y `PolizaSeguro.DatosRamo`.
- Certificado/dependiente: `ProductoCertificadoCampo`, guardado en `PolizaSeguroCertificado.DatosJson` y `PolizaSeguroCertificadoDependiente.DatosJson`.
- Validaciones conocidas: correo/email, dui, solo numeros, solo numeros y letras, solo texto, number, date.

## Negocios y cotizaciones

Archivos clave:

- `app/Http/Controllers/catalogo/NegocioController.php`
- `app/Models/catalogo/Negocio.php`
- `app/Models/catalogo/Cotizacion.php`
- `resources/views/catalogo/negocio`

Flujo observado:

- Un negocio representa una oportunidad/oferta.
- Puede tener campos dinamicos de ramo en `DatosRamo`.
- Tiene cotizaciones asociadas por plan.
- Una cotizacion puede marcarse como `Aceptado`.
- La poliza no declarativa puede crearse desde una oferta aceptada.

Endpoints relevantes:

- `negocio/getCliente`
- `negocio/getProducto`
- `negocio/getPlan`
- `negocio/getCamposRamo`
- `negocio/elegirCotizacion`
- `catalogo/negocio/{id}/datos_ramo`
- `catalogo/negocio/add_cotizacion`
- `catalogo/negocio/edit_cotizacion`
- `catalogo/negocio/delete_cotizacion`

## Polizas declarativas

Modulos principales:

- Deuda: `DeudaController`, `DeudaCarteraController`, `DeudaCarteraFedeController`, `DeudaRenovacionController`, `DeudaTasaDiferenciadaController`
- Vida: `VidaController`, `VidaFedeController`, `VidaRenovacionController`, `VidaTasaDiferenciadaController`
- Desempleo: `DesempleoController`, `DesempleoCarteraController`, `DesempleoCarteraComController`, `DesempleoRenovacionController`, `DesempleoTasaDiferenciadaController`
- Residencia: `ResidenciaController`, `ResidenciaRenovacionController`
- Control cartera: `PolizaControlCarteraController` y `app/Http/Controllers/Traits/PolizaControlCarteraTrait.php`
- Validacion cartera: `ValidacionCarteraController`

Vistas:

- `resources/views/polizas/deuda`
- `resources/views/polizas/vida`
- `resources/views/polizas/desempleo`
- `resources/views/polizas/residencia`
- `resources/views/polizas/control_cartera`
- `resources/views/polizas/validacion_cartera`

Patron funcional:

- Manejan carteras mensuales, archivos importados, temporales, preliminares, validaciones, pagos/recibos, historicos, renovaciones, tasas diferenciadas, registros excluidos y reportes.
- Usan mucho `app/Imports` y `app/Exports`.
- Rutas principales usan prefijos `polizas/deuda`, `polizas/vida`, `polizas/desempleo`, `polizas/residencia`, y algunas acciones auxiliares usan `poliza/deuda`, `poliza/vida`, `poliza/desempleo`.

## Polizas no declarativas / seguro generico

Este es el modulo central para polizas no declarativas.

Archivos clave:

- `app/Http/Controllers/polizas/PolizaSeguroController.php`
- `app/Models/polizas/PolizaSeguro.php`
- `app/Models/polizas/PolizaSeguroCertificado.php`
- `resources/views/polizas/seguro/index.blade.php`
- `resources/views/polizas/seguro/create.blade.php`
- `resources/views/polizas/seguro/show.blade.php`
- `resources/views/polizas/seguro/certificado.blade.php`
- `resources/views/polizas/seguro/partials/ramo_campos_form.blade.php`
- `resources/views/polizas/seguro/partials/certificado_campos_form.blade.php`

Modelo principal:

- `PolizaSeguro`
- Tabla `poliza_seguro`
- Relaciones: oferta, forma de pago, estado, motivo/cancelacion, departamento, cliente, producto, plan, ejecutivo, coberturas, datos tecnicos, certificados, beneficiarios y cesiones.

Certificados:

- `PolizaSeguroCertificado`
- Tabla `poliza_seguro_certificados`
- Guarda datos base: numero, asegurado, vigencias, valores, primas, estado, motivo, vendedor, `DatosJson`, observacion, activo.
- Relaciones: poliza, plan, dependientes, coberturas del certificado, datos tecnicos del certificado, beneficiarios, cesiones, estado, motivo, vendedor y usuario modificador.

Tablas/modelos secundarios:

- `PolizaSeguroCertificadoDependiente`: dependientes por certificado, con `DatosJson`.
- `PolizaSeguroCertificadoCobertura`: coberturas/sumas/primas por certificado.
- `PolizaSeguroCertificadoDatoTecnico`: datos tecnicos por certificado.
- `PolizaSeguroBeneficiario`: beneficiarios por poliza y/o certificado.
- `PolizaSeguroCesionBeneficio`: cesion de beneficios por poliza y/o certificado.
- `PolizaSeguroCobertura`: coberturas a nivel poliza.
- `PolizaSeguroDatosTecnicos`: datos tecnicos a nivel poliza.

Flujo no declarativo:

1. Crear poliza desde oferta aceptada o captura.
2. Guardar caratula de poliza.
3. Guardar datos dinamicos del ramo.
4. Gestionar coberturas y datos tecnicos de poliza.
5. Crear certificados.
6. En cada certificado: datos base, sumas aseguradas, detalle del asegurado, dependientes, beneficiarios, cesiones y datos tecnicos.

Reglas importantes:

- `PolizaSeguroController::validarProductoPlan()` valida que producto activo pertenezca al ramo/aseguradora indicados y que plan pertenezca al producto.
- `productoConfigCertificado()` puede derivar el producto desde el plan del certificado; si no, usa producto de poliza.
- `siguienteNumeroCertificado()` calcula consecutivo por poliza.
- Beneficiarios de certificado no pueden superar 100% sumado.
- `certificado_sumas_save()` recalcula totales de suma asegurada y prima desde coberturas del certificado.
- `datos_tecnicos_save()` guarda campos de ramo y valores tecnicos a nivel poliza.
- `certificado_datos_tecnicos_save()` sincroniza valores tecnicos a nivel certificado.
- Si `PermiteDependientesCertificado` no esta activo en producto, no permite dependientes.

Rutas utiles:

- `php artisan route:list --path=poliza/seguro`
- `GET poliza/seguro`
- `GET poliza/seguro/create`
- `POST poliza/seguro`
- `GET poliza/seguro/get_oferta`
- `POST poliza/seguro/save/{id}`
- `GET poliza/seguro/{id}/certificado/create`
- `GET poliza/seguro/certificado/{id}/edit`
- `POST poliza/seguro/certificado_store/{id}`
- `POST poliza/seguro/certificado_update/{id}`
- `POST poliza/seguro/certificado_sumas_save/{id}`
- `POST poliza/seguro/certificado_datos_tecnicos_save/{id}`
- `POST poliza/seguro/dependiente_store/{id}`
- `POST poliza/seguro/certificado_beneficiario_store/{id}`
- `POST poliza/seguro/certificado_cesion_beneficios_store/{id}`

## Suscripciones

Archivos clave:

- `app/Http/Controllers/suscripcion/SuscripcionController.php`
- `resources/views/suscripciones/suscripcion`
- Catalogos: compania, estado caso, fechas feriadas, ocupacion, tipo cliente, tipo credito, tipo IMC, tipo orden medica.
- Import/export: `app/Imports/SuscripcionImport.php`, `app/Exports/suscripcion/SuscripcionesExport.php`.

Rutas principales:

- `suscripciones`
- `suscripciones/importar`
- `suscripciones/exportar`
- `suscripciones/agregar_comentario`
- `suscripciones/calcular_dias_habiles_json`
- `suscripciones/data/{fechaInicio}/{fechaFinal}`

## Migraciones recientes clave

No declarativas/certificados:

- `2026_04_28_150000_add_permite_dependientes_certificado_to_producto_table.php`
- `2026_04_28_150100_create_producto_certificado_campos_table.php`
- `2026_04_28_160000_add_mostrar_en_reporte_to_producto_certificado_campos_table.php`
- `2026_06_06_090000_add_validacion_campo_to_producto_certificado_campos_table.php`
- `2026_06_06_110000_create_necesidad_proteccion_campos_table.php`
- `2026_06_06_120000_add_requerido_to_necesidad_proteccion_campos_table.php`
- `2026_06_06_130000_add_datos_ramo_to_negocio_table.php`
- `2026_06_08_091000_add_caratula_fields_to_poliza_seguro_table.php`
- `2026_06_09_110000_add_datos_ramo_to_poliza_seguro_table.php`
- `2026_06_11_090000_add_porcentaje_comision_no_declarativa_to_producto_table.php`
- `2026_06_11_100000_add_caratula_and_certificado_base_fields.php`
- `2026_06_11_110000_add_static_fields_to_poliza_seguro_certificados_table.php`
- `2026_06_11_120000_create_estado_certificado_table.php`
- `2026_06_11_121000_add_catalog_fields_to_poliza_seguro_certificados_table.php`
- `2026_06_12_090000_create_forma_pago_polizas_table.php`
- `2026_06_12_100000_modify_tasa_precision_on_plan_cobertura_detalle_table.php`
- `2026_06_12_110000_add_plan_to_poliza_seguro_certificados_table.php`
- `2026_06_12_111000_create_poliza_seguro_certificado_coberturas_table.php`
- `2026_06_13_091654_add_prorrata_fields_to_poliza_seguro_certificado_coberturas_table.php`
- `2026_06_13_110930_add_certificado_to_poliza_seguro_beneficiarios_table.php`
- `2026_06_14_113544_add_certificado_to_poliza_seguro_cesion_beneficios_table.php`
- `2026_06_18_090000_create_poliza_seguro_certificado_datos_tecnicos_table.php`

Permisos:

- `2023_03_26_145013_create_permission_tables.php`
- `2026_02_06_100207_create_permission_types_table.php`
- `2026_02_06_100440_add_permission_type_id_to_permissions_table.php`

## Base de datos real

Revision de BD local: 2026-06-23.

Conexion usada por `.env`:

- Motor: MySQL/MariaDB via `mysql`
- Base: `nrseguros`
- Host/puerto: `localhost:3307`
- No documentar usuario/password en este archivo.

Estado:

- `php artisan migrate:status` muestra todas las migraciones del repositorio como `Ran`.
- La base tiene 167 tablas.
- Hay datos reales, no es una BD vacia de desarrollo.

Conteos observados en tablas principales:

- `cliente`: 724
- `aseguradora`: 11
- `necesidad_proteccion`: 21
- `producto`: 71
- `plan`: 94
- `cobertura`: 272
- `datos_tecnicos`: 443
- `producto_certificado_campos`: 11
- `necesidad_proteccion_campos`: 4
- `negocio`: 8
- `cotizacion`: 16
- `poliza_seguro`: 7
- `poliza_seguro_certificados`: 8
- `poliza_seguro_certificado_dependientes`: 8
- `poliza_seguro_beneficiarios`: 5
- `poliza_seguro_cesion_beneficios`: 1
- `poliza_vida`: 40
- `poliza_vida_cartera`: 351852
- `poliza_desempleo`: 19
- `poliza_desempleo_cartera`: 66267
- `poliza_residencia`: 15
- `poliza_deuda`: 0
- `suscripcion`: 680
- `permissions`: 210
- `roles`: 15
- `users`: 31

### Grupos de tablas reales

Catalogos y maestros:

- `cliente`, `cliente_*`
- `aseguradora`, `aseguradora_*`
- `producto`, `producto_certificado_campos`
- `plan`, `plan_cobertura_detalle`
- `cobertura`, `datos_tecnicos`
- `necesidad_proteccion`, `necesidad_proteccion_campos`, `agrupador_ramo`
- `negocio`, `cotizacion`, `negocio_*`
- auxiliares: `estado_poliza`, `estado_certificado`, `estado_venta`, `forma_pago`, `forma_pago_polizas`, `motivo_cancelacion`, `origen_poliza`, `parentesco`, `tipo_*`, `departamento*`, `municipio`, `distrito`

Seguridad:

- `users`
- `roles`
- `permissions`
- `permission_types`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Polizas declarativas:

- Deuda: `poliza_deuda`, `poliza_deuda_cartera`, `poliza_deuda_temp_cartera`, `poliza_deuda_detalle`, `poliza_deuda_detalle_preliminar`, `poliza_deuda_historica`, `poliza_deuda_historial_recibo`, `poliza_deuda_validados`, `poliza_deuda_creditos_validos`, `poliza_deuda_excluidos`, `poliza_deuda_eliminados`, `poliza_deuda_extra_primado`, `poliza_deuda_tasa_diferenciada`, `poliza_deuda_tipo_cartera`, etc.
- Vida: `poliza_vida`, `poliza_vida_cartera`, `poliza_vida_cartera_temp`, `poliza_vida_detalle`, `poliza_vida_detalle_preliminar`, `poliza_vida_historica`, `poliza_vida_historial_recibo`, `poliza_vida_extra_primado`, `poliza_vida_tasa_diferenciada`, `poliza_vida_tipo_cartera`, etc.
- Desempleo: `poliza_desempleo`, `poliza_desempleo_cartera`, `poliza_desempleo_cartera_temp`, `poliza_desempleo_detalle`, `poliza_desempleo_detalle_preliminar`, `poliza_desempleo_historica`, `poliza_desempleo_historial_recibo`, `poliza_desempleo_tasa_diferenciada`, `poliza_desempleo_tipo_cartera`, etc.
- Residencia: `poliza_residencia`, `poliza_residencia_cartera`, `poliza_residencia_temp_cartera`, `poliza_residencia_detalle`, `poliza_residencia_detalle_preliminar`, `poliza_residencia_historica`, `poliza_residencia_historial_recibo`.
- Control declarativas: `poliza_declarativa_control`, `poliza_declarativa_reproceso`.

Polizas no declarativas:

- `poliza_seguro`
- `poliza_seguro_cobertura`
- `poliza_seguro_datos_tecnicos`
- `poliza_seguro_certificados`
- `poliza_seguro_certificado_coberturas`
- `poliza_seguro_certificado_datos_tecnicos`
- `poliza_seguro_certificado_dependientes`
- `poliza_seguro_beneficiarios`
- `poliza_seguro_cesion_beneficios`

Suscripcion:

- `suscripcion`
- `suscripcion_temp`
- `suscripcion_padecimientos`
- `sus_comentarios`
- `sus_compania`
- `sus_contratante`
- `sus_estado_caso`
- `sus_fechas_feriadas`
- `sus_ocupacion`
- `sus_orden_medica`
- `sus_padecimientos`
- `sus_reproceso`
- `sus_resumen_gestion`
- `sus_tipo_cliente`
- `sus_tipo_credito`
- `sus_tipo_imc`

### Relaciones fisicas importantes

Catalogos:

- `producto.Aseguradora -> aseguradora.Id`
- `producto.NecesidadProteccion -> necesidad_proteccion.Id`
- `producto_certificado_campos.Producto -> producto.Id`
- `plan.Producto -> producto.Id`
- `plan_cobertura_detalle.Plan -> plan.Id`
- `plan_cobertura_detalle.Cobertura -> cobertura.Id`
- `cobertura.Producto -> producto.Id`
- `datos_tecnicos.Producto -> producto.Id`
- `necesidad_proteccion.AgrupadorRamo -> agrupador_ramo.Id`
- `necesidad_proteccion_campos.NecesidadProteccion -> necesidad_proteccion.Id`
- `cotizacion.Negocio -> negocio.Id`
- `cotizacion.Plan -> plan.Id`

Negocio:

- `negocio.Cliente -> cliente.Id`
- `negocio.NecesidadProteccion -> necesidad_proteccion.Id`
- `negocio.Ejecutivo -> ejecutivo.Id`
- `negocio.EstadoVenta -> estado_venta.Id`
- `negocio.TipoCarteraNr -> tipo_cartera_nr.Id`
- `negocio.TipoNegocio -> tipo_negocio.Id`
- `negocio.DepartamentoNr -> departamento_nr.Id`
- `negocio.UsuarioIngreso -> users.id`

Poliza no declarativa:

- `poliza_seguro_certificados.PolizaSeguroId -> poliza_seguro.Id`
- `poliza_seguro_certificado_coberturas.PolizaSeguroCertificadoId -> poliza_seguro_certificados.Id`
- `poliza_seguro_certificado_datos_tecnicos.PolizaSeguroCertificadoId -> poliza_seguro_certificados.Id`
- `poliza_seguro_certificado_dependientes.PolizaSeguroCertificadoId -> poliza_seguro_certificados.Id`
- `poliza_seguro_beneficiarios.PolizaSeguroId -> poliza_seguro.Id`
- `poliza_seguro_beneficiarios.PolizaSeguroCertificadoId -> poliza_seguro_certificados.Id`
- `poliza_seguro_beneficiarios.Parentesco -> parentesco.Id`
- `poliza_seguro_cesion_beneficios.PolizaSeguroId -> poliza_seguro.Id`
- `poliza_seguro_cesion_beneficios.PolizaSeguroCertificadoId -> poliza_seguro_certificados.Id`
- `poliza_seguro_cobertura.PolizaSeguroId -> poliza_seguro.Id`
- `poliza_seguro_datos_tecnicos.PolizaSeguroId -> poliza_seguro.Id`

Nota tecnica importante: en `poliza_seguro`, varias columnas que el modelo trata como relaciones (`Oferta`, `FormaPago`, `EstadoPoliza`, `Productos`, `Planes`, `Cliente`, etc.) son `int(11)` sin foreign key fisica. Validar por codigo/controlador, no confiar en restricciones de BD.

Declarativas:

- `poliza_deuda` referencia cliente, aseguradora, ejecutivo, estado, usuario, y puede vincularse con `poliza_vida` y `poliza_desempleo`.
- `poliza_deuda_cartera` referencia poliza deuda, detalle, tipo cartera, linea credito y usuario.
- `poliza_vida_tipo_cartera` referencia `poliza_vida` y catalogo tipo cartera.
- `poliza_desempleo` referencia cliente, aseguradora, ejecutivo, estado, saldos y usuario.
- `poliza_desempleo_cartera` referencia poliza desempleo, saldos y usuario.
- `poliza_declarativa_control` puede apuntar a deuda, vida, desempleo, residencia y reproceso.

Seguridad:

- `permissions.permission_type_id -> permission_types.id`
- `role_has_permissions.role_id -> roles.id`
- `role_has_permissions.permission_id -> permissions.id`
- `model_has_roles.role_id -> roles.id`
- `model_has_permissions.permission_id -> permissions.id`

### Columnas clave confirmadas

`producto`:

- `Id`, `Nombre`, `Aseguradora`, `NecesidadProteccion`, `Descripcion`, `PorcentajeComisionNoDeclarativa`, `PermiteDependientesCertificado`, `Activo`.

`producto_certificado_campos`:

- `Producto`, `Etiqueta`, `NombreCampo`, `TipoCampo`, `ValidacionCampo`, `Requerido`, `MostrarEnReporte`, `Orden`, `Placeholder`, `Ayuda`, `OpcionesJson`, `Activo`.

`necesidad_proteccion`:

- Incluye comisiones de no declarativa y bomberos: `PorcentajeComisionNoDeclarativa`, `ComisionBomberos`, `PorcentajeBomberos`.

`plan_cobertura_detalle`:

- Llave primaria compuesta real por `Plan` + `Cobertura`.
- `SumaAsegurada` y `Prima` quedaron como `decimal(65,38)`.
- `Tasa` quedo como `decimal(12,6)`.

`poliza_seguro`:

- Caratula: `Oferta`, `NumeroVigencia`, `FormaPago`, `NumCuotas`, `NumeroPoliza`, `EstadoPoliza`, `Cliente`, `VigenciaDesde`, `VigenciaHasta`, `DiasVigencia`.
- Producto/plan/importes: `Productos`, `Planes`, `SumaAsegurada`, `PrimaNetaAnual`, `PorcentajeComisionNR`.
- Cancelacion: `MotivoCancelacion`, `FechaCancelacion`, `CodCancelacion`.
- Datos de ramo: `DatosRamo`.
- Otros: `OrigenPoliza`, `Departamento`, `EjecutivoCia`, `Deducible`, `ValorDeducible`, `Activo`, `Usuario`.

`poliza_seguro_certificados`:

- Base: `PolizaSeguroId`, `Plan`, `NumeroCertificado`, `CertificadoAseguradora`, `CodAsegurado`, `Asegurado`, `VigenciaDesde`, `VigenciaHasta`, `FechaInclusion`, `DiasVigencia`.
- Montos: `ValorAsegurado`, `PrimaTotal`, `PorcentajeDescuentoRentabilidad`, `ValorDescuento`, `PrimaNeta`, `PrimaExenta`, `GastosEmision`, `GastosFraccionamiento`, `GastosBomberos`, `OtrosGastos`, `Impuestos`, `TotalCertificado`.
- Estado/cancelacion: `Estado`, `EstadoCertificado`, `MotivoCancelacion`, `MotivoExclusion`, `FechaExclusion`.
- Otros: `Deducible`, `Participacion`, `PorcentajeDepreciacion`, `PrimaMinima`, `UsuarioModifica`, `FechaModificacion`, `Vendedor`, `DatosJson`, `Observacion`, `Activo`.

Nota tecnica: `Vendedor` en BD es `varchar(200)`, aunque el controlador valida como `integer|exists:ejecutivo,Id` y el modelo lo relaciona con `Ejecutivo`. Tener cuidado si se modifica esa parte.

`poliza_seguro_certificado_coberturas`:

- `PolizaSeguroCertificadoId`, `Cobertura`, `Nombre`, `SumaAsegurada`, `PorcentajeSuma`, `Tasa`, `DiasProrrata`, `PrimaAnual`, `Prima`, `PorcentajeDeducible`, `Deducible`, `Activo`.

`suscripcion`:

- Gestiona flujo de tareas, compania, contratante, polizas deuda/vida, asegurado, ocupacion, DUI, edad/genero, sumas aseguradas, IMC, padecimientos, orden medica, estado, fechas de gestion, reproceso y total de dias.

## Comandos utiles

- `php artisan route:list --path=catalogo`
- `php artisan route:list --path=poliza`
- `php artisan route:list --path=poliza/seguro`
- `php artisan route:list --path=suscripciones`
- `php artisan migrate:status`
- `php artisan test`
- `npm run build`

Nota: en este entorno no estaba disponible `rg`; usar `Get-ChildItem`, `Select-String` o instalar ripgrep si se quiere buscar mas rapido.

## Donde empezar segun la tarea

Poliza no declarativa:

1. `app/Http/Controllers/polizas/PolizaSeguroController.php`
2. `app/Models/polizas/PolizaSeguro.php`
3. `app/Models/polizas/PolizaSeguroCertificado.php`
4. `resources/views/polizas/seguro/show.blade.php`
5. `resources/views/polizas/seguro/certificado.blade.php`
6. Si toca producto/plan, abrir `ProductoController`, `PlanController`, `Producto`, `Plan`, `PlanCoberturaDetalle`.

Catalogos:

1. `routes/web.php`
2. Controlador en `app/Http/Controllers/catalogo`
3. Modelo en `app/Models/catalogo`
4. Vista en `resources/views/catalogo/{modulo}`
5. Menu/permisos en `resources/views/welcome.blade.php` y seeders de permisos si necesita acceso.

Permisos:

1. `database/seeders/PermissionSeeder.php`
2. `database/seeders/PermissionTypeSeeder.php`
3. `resources/views/welcome.blade.php`
4. `app/Http/Controllers/seguridad/RoleController.php`
5. `app/Http/Controllers/seguridad/UserController.php`

Polizas declarativas:

1. Identificar ramo: deuda, vida, desempleo o residencia.
2. Abrir controlador principal y controlador de cartera/renovacion/tasa si aplica.
3. Abrir modelos con mismo prefijo en `app/Models/polizas`.
4. Abrir vistas en `resources/views/polizas/{ramo}`.
5. Revisar `app/Imports` y `app/Exports` si toca cargas, recibos o reportes.

Suscripciones:

1. `app/Http/Controllers/suscripcion/SuscripcionController.php`
2. `resources/views/suscripciones/suscripcion`
3. Catalogos de suscripcion si el cambio toca estados, tipos o fechas feriadas.

## Riesgos de mantenimiento

- Controladores grandes concentran logica de negocio, validacion y persistencia.
- Vistas Blade tienen bastante JavaScript embebido.
- La base no sigue todas las convenciones Laravel modernas.
- Hay mezcla de borrado logico y borrado real.
- Hay cambios locales existentes; no asumir que el working tree esta limpio.
- Las pantallas de poliza no declarativa y certificados tienen muchas dependencias cruzadas entre producto, plan, ramo, certificados y tablas detalle.
