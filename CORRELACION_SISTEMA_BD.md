# Correlacion sistema - base de datos

Revision: 2026-06-23.

Este documento cruza el sistema Laravel con la base `nrseguros`: modulos funcionales, rutas, controladores, vistas, modelos y tablas. Usarlo cuando haya que ubicar donde tocar codigo y que tablas se afectan.

## Lectura general

El sistema esta dividido en cinco bloques:

- Seguridad y permisos.
- Catalogos maestros.
- Clientes, aseguradoras, negocios y cotizaciones.
- Polizas declarativas: deuda, vida, desempleo, residencia y control de cartera.
- Polizas no declarativas: modulo `poliza/seguro`, certificados y anexos.
- Suscripciones.

La BD tiene 167 tablas. El sistema no depende solo de foreign keys fisicas: muchas relaciones estan implementadas en modelos/controladores aunque la tabla no tenga restriccion FK. Ejemplo claro: `poliza_seguro` guarda `Productos`, `Planes`, `Cliente`, `FormaPago`, etc. como `int(11)` sin FK fisica.

## Seguridad y permisos

Rutas:

- `usuario`
- `rol`
- `permission`
- `permission_type`
- `usuario/rol_link`
- `role/permission_link`

Controladores:

- `app/Http/Controllers/seguridad/UserController.php`
- `app/Http/Controllers/seguridad/RoleController.php`
- `app/Http/Controllers/seguridad/PermissionController.php`
- `app/Http/Controllers/seguridad/PermissionTypeController.php`

Modelos:

- `App\Models\User` -> `users`
- `App\Models\Role` -> `roles`
- `App\Models\Permission` -> `permissions`
- `App\Models\PermissionType` -> `permission_types`

Tablas:

- `users`
- `roles`
- `permissions`
- `permission_types`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`

Vistas:

- `resources/views/seguridad/user`
- `resources/views/seguridad/role`
- `resources/views/seguridad/permission`
- `resources/views/seguridad/permission_type`

Menu:

- `resources/views/welcome.blade.php`
- Usa `@can` y `@canany`.
- Seeders base: `PermissionSeeder`, `PermissionTypeSeeder`.

## Catalogos base

Rutas principales bajo `catalogo/*`.

Controladores y tablas:

| Modulo | Ruta | Controlador | Modelo principal | Tabla principal | Vistas |
| --- | --- | --- | --- | --- | --- |
| Ejecutivos | `catalogo/ejecutivos` | `EjecutivoController` | `Ejecutivo` | `ejecutivo` | `catalogo/ejecutivo` |
| Estados poliza | `catalogo/estado_polizas` | `EstadoPolizaController` | `EstadoPoliza` | `estado_poliza` | `catalogo/estado_poliza` |
| Estados certificado | `catalogo/estado_certificado` | `EstadoCertificadoController` | `EstadoCertificado` | `estado_certificado` | `catalogo/estado_certificado` |
| Estados venta | `catalogo/estado_venta` | `EstadoVentaController` | `EstadoVenta` | `estado_venta` | `catalogo/estado_venta` |
| Motivos cancelacion | `catalogo/motivo_cancelacion` | `MotivoCancelacionController` | `MotivoCancelacion` | `motivo_cancelacion` | `catalogo/motivo_cancelacion` |
| Forma pago poliza | `catalogo/forma_pago_polizas` | `FormaPagoPolizaController` | `FormaPagoPoliza` | `forma_pago_polizas` | `catalogo/forma_pago_polizas` |
| Origen poliza | `catalogo/origen_poliza` | `OrigenPolizaController` | `OrigenPoliza` | `origen_poliza` | `catalogo/origen_poliza` |
| Parentesco beneficiario | `catalogo/parentesco_beneficiario` | `ParentescoBeneficiarioController` | `Parentesco` | `parentesco` | `catalogo/parentesco_beneficiario` |
| Tipo deducible | `catalogo/tipo_deducible` | `DeducibleController` | `Deducible` | `tipo_deducible` | `catalogo/tipo_deducible` |
| Tipo cartera | `catalogo/tipo_cartera` | `TipoCarteraController` | `TipoCartera` | `tipo_cartera` | `catalogo/tipo_cartera` |
| NR cartera | `catalogo/nr_cartera` | `NrCarteraController` | `NrCartera` | `tipo_cartera_nr` | `catalogo/nr_cartera` |
| Tipo negocio | `catalogo/tipo_negocio` | `TipoNegocioController` | `TipoNegocio` | `tipo_negocio` | `catalogo/tipo_negocio` |
| Tipo cobro | `catalogo/tipo_cobro` | `TipoCobroController` | `TipoCobro` | `tipo_cobro` | `catalogo/tipo_cobro` |
| Tipo poliza/ramo | `catalogo/tipo_poliza` | `TipoPolizaController` | `TipoPoliza` | `tipo_poliza` | `catalogo/tipo_poliza` |
| Ubicacion cobro | `catalogo/ubicacion_cobro` | `UbicacionCobroController` | `UbicacionCobro` | `ubicacion_cobro` | `catalogo/ubicacion_cobro` |
| Area comercial | `catalogo/area_comercial` | `AreaComercialController` | `AreaComercial` | `area_comercial` | `catalogo/area_comercial` |
| Departamento NR | `catalogo/departamento_nr` | `DepartamentoNRController` | `DepartamentoNR` | `departamento_nr` | `catalogo/departamento_nr` |
| Agrupador ramo | `catalogo/agrupador_ramo` | `AgrupadorRamoController` | `AgrupadorRamo` | `agrupador_ramo` | `catalogo/agrupador_ramo` |
| Perfiles | `catalogo/perfiles` | `PerfilController` | `Perfil` | `perfiles` | `catalogo/perfiles` |
| Configuracion recibo | `catalogo/configuracion_recibo` | `ConfiguracionReciboController` | `ConfiguracionRecibo` | `configuracion_recibos` | `catalogo/configuracion_recibo` |

## Clientes

Rutas:

- `catalogo/cliente`
- `catalogo/cliente/validar_cliente`
- `catalogo/cliente/add_contacto`
- `catalogo/cliente/add_tarjeta`
- `catalogo/cliente/add_habito`
- `catalogo/cliente/add_retroalimentacion`
- `catalogo/cliente/documento`
- `get_municipio/{id}`
- `get_distrito/{id}`

Controlador:

- `app/Http/Controllers/catalogo/ClienteController.php`

Vistas:

- `resources/views/catalogo/cliente`
- `resources/views/consulta`

Modelos/tablas:

- `Cliente` -> `cliente`
- `ClienteContactoFrecuente` -> `cliente_contacto_frecuente`
- `ClienteContactoCargo` -> `cliente_contacto_cargo`
- `ClienteDocumento` -> `cliente_documentos`
- `ClienteTarjetaCredito` -> `cliente_tarjeta_credito`
- `ClienteHabitoConsumo` -> `cliente_habito_consumo`
- `ClienteRetroalimentacion` -> `cliente_retroalimentacion`
- Catalogos cliente: `cliente_estado`, `cliente_informarse`, `cliente_metodo_pago`, `cliente_motivo_eleccion`, `cliente_necesidad_proteccion`, `cliente_preferencia_compra`

Relaciones BD confirmadas:

- `cliente.Estado -> cliente_estado.Id`
- `cliente.FormaPago -> forma_pago.Id`
- `cliente.UbicacionCobro -> ubicacion_cobro.Id`
- `cliente.TipoContribuyente -> tipo_contribuyente.Id`
- contactos, habitos, retroalimentacion y tarjetas referencian `cliente.Id`.

## Aseguradoras

Rutas:

- `catalogo/aseguradoras`
- `catalogo/aseguradora/add_contacto`
- `catalogo/aseguradora/documento`
- `catalogo/aseguradora/attach_necesidad_proteccion`
- `catalogo/aseguradora/get_necesidad/{id}`

Controlador:

- `app/Http/Controllers/catalogo/AseguradoraController.php`

Vistas:

- `resources/views/catalogo/aseguradora`

Modelos/tablas:

- `Aseguradora` -> `aseguradora`
- `AseguradoraContacto` -> `aseguradora_contacto`
- `AseguradoraCargo` -> `aseguradora_cargo`
- `AseguradoraDocumento` -> `aseguradora_documentos`
- `AsignacionNecesidadAseguradora` -> `aseguradora_has_necesidad_proteccion`
- `AseguradoraCartera` -> `aseguradora_has_cartera`

Relaciones BD confirmadas:

- `aseguradora.TipoContribuyente -> tipo_contribuyente.Id`
- `aseguradora_contacto.Aseguradora -> aseguradora.Id`
- `aseguradora_contacto.Cargo -> aseguradora_cargo.Id`
- `aseguradora_has_necesidad_proteccion.aseguradora_id -> aseguradora.Id`
- `aseguradora_has_necesidad_proteccion.necesidad_proteccion_id -> necesidad_proteccion.Id`

## Ramos, productos, planes y cotizaciones

Este bloque conecta catalogo comercial con negocios y polizas no declarativas.

Rutas:

- `catalogo/necesidad_proteccion`
- `catalogo/necesidad_proteccion/add_campo`
- `catalogo/producto`
- `catalogo/producto/add_cobertura`
- `catalogo/producto/add_dato_tecnico`
- `catalogo/producto/certificado/*`
- `catalogo/plan`
- `catalogo/plan/getCoberturas`
- `catalogo/plan/edit_cobertura_detalle`
- `get_producto/{id}/{tipo}`
- `get_plan/{id}`

Controladores:

- `NecesidadProteccionController`
- `ProductoController`
- `PlanController`

Vistas:

- `resources/views/catalogo/necesidad_proteccion`
- `resources/views/catalogo/producto`
- `resources/views/catalogo/plan`

Modelos/tablas:

- `NecesidadProteccion` -> `necesidad_proteccion`
- `NecesidadProteccionCampo` -> `necesidad_proteccion_campos`
- `Producto` -> `producto`
- `ProductoCertificadoCampo` -> `producto_certificado_campos`
- `Cobertura` -> `cobertura`
- `DatosTecnicos` -> `datos_tecnicos`
- `Plan` -> `plan`
- `PlanCoberturaDetalle` -> `plan_cobertura_detalle`

Relaciones BD confirmadas:

- `producto.Aseguradora -> aseguradora.Id`
- `producto.NecesidadProteccion -> necesidad_proteccion.Id`
- `producto_certificado_campos.Producto -> producto.Id`
- `cobertura.Producto -> producto.Id`
- `datos_tecnicos.Producto -> producto.Id`
- `plan.Producto -> producto.Id`
- `plan_cobertura_detalle.Plan -> plan.Id`
- `plan_cobertura_detalle.Cobertura -> cobertura.Id`

Uso funcional:

- Ramo (`necesidad_proteccion`) define clasificacion, comisiones y campos dinamicos de ramo.
- Producto define aseguradora, ramo, coberturas, datos tecnicos, campos de certificado y si permite dependientes.
- Plan agrupa coberturas por producto con suma asegurada, tasa y prima.
- Cotizacion y poliza usan plan/producto para inicializar valores.

## Negocios y ofertas

Rutas:

- `catalogo/negocio`
- `negocio/getCliente`
- `negocio/getProducto`
- `negocio/getPlan`
- `negocio/getCamposRamo`
- `negocio/elegirCotizacion`
- `catalogo/negocio/{id}/datos_ramo`
- `catalogo/negocio/add_cotizacion`
- `catalogo/negocio/edit_cotizacion`
- `catalogo/negocio/delete_cotizacion`
- `catalogo/negocio/add_gestion`
- `catalogo/negocio/documento`

Controlador:

- `app/Http/Controllers/catalogo/NegocioController.php`

Vistas:

- `resources/views/catalogo/negocio`

Modelos/tablas:

- `Negocio` -> `negocio`
- `Cotizacion` -> `cotizacion`
- `NegocioDocumento` -> `negocio_documentos`
- `NegocioGestiones` -> `negocio_gestiones`
- Modelos de datos por ramo de negocio: `negocio_accidentes`, `negocio_autos`, `negocio_dinero_valores`, `negocio_equipo_electronico`, `negocio_gastos_medicos`, `negocio_incendio`, `negocio_robo_hurto`, `negocio_vida`, `negocio_vida_deuda`, etc.

Relaciones BD confirmadas:

- `negocio.Cliente -> cliente.Id`
- `negocio.NecesidadProteccion -> necesidad_proteccion.Id`
- `negocio.Ejecutivo -> ejecutivo.Id`
- `negocio.EstadoVenta -> estado_venta.Id`
- `negocio.TipoCarteraNr -> tipo_cartera_nr.Id`
- `negocio.TipoNegocio -> tipo_negocio.Id`
- `negocio.DepartamentoNr -> departamento_nr.Id`
- `negocio.UsuarioIngreso -> users.id`
- `cotizacion.Negocio -> negocio.Id`
- `cotizacion.Plan -> plan.Id`

Uso funcional:

- Negocio es la oferta/oportunidad.
- Cotizacion contiene plan, suma asegurada, prima, datos tecnicos y bandera `Aceptado`.
- `PolizaSeguroController` busca negocios con cotizacion aceptada para crear poliza no declarativa.

## Poliza no declarativa: `poliza/seguro`

Rutas:

- `poliza/seguro`
- `poliza/seguro/get_oferta`
- `poliza/seguro/save/{id}`
- `poliza/seguro/{id}/certificado/create`
- `poliza/seguro/certificado/{id}/edit`
- `poliza/seguro/certificado_store/{id}`
- `poliza/seguro/certificado_update/{id}`
- `poliza/seguro/certificado_sumas_save/{id}`
- `poliza/seguro/certificado_datos_tecnicos_save/{id}`
- `poliza/seguro/dependiente_*`
- `poliza/seguro/beneficiario_*`
- `poliza/seguro/certificado_beneficiario_*`
- `poliza/seguro/cesion_beneficios_*`
- `poliza/seguro/certificado_cesion_beneficios_*`

Controlador:

- `app/Http/Controllers/polizas/PolizaSeguroController.php`

Vistas:

- `resources/views/polizas/seguro/index.blade.php`
- `resources/views/polizas/seguro/create.blade.php`
- `resources/views/polizas/seguro/show.blade.php`
- `resources/views/polizas/seguro/certificado.blade.php`
- `resources/views/polizas/seguro/partials/ramo_campos_form.blade.php`
- `resources/views/polizas/seguro/partials/certificado_campos_form.blade.php`

Modelos/tablas:

- `PolizaSeguro` -> `poliza_seguro`
- `PolizaSeguroCobertura` -> `poliza_seguro_cobertura`
- `PolizaSeguroDatosTecnicos` -> `poliza_seguro_datos_tecnicos`
- `PolizaSeguroCertificado` -> `poliza_seguro_certificados`
- `PolizaSeguroCertificadoCobertura` -> `poliza_seguro_certificado_coberturas`
- `PolizaSeguroCertificadoDatoTecnico` -> `poliza_seguro_certificado_datos_tecnicos`
- `PolizaSeguroCertificadoDependiente` -> `poliza_seguro_certificado_dependientes`
- `PolizaSeguroBeneficiario` -> `poliza_seguro_beneficiarios`
- `PolizaSeguroCesionBeneficio` -> `poliza_seguro_cesion_beneficios`

Relaciones BD confirmadas:

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

Correlacion funcional:

- `poliza_seguro` es la caratula de poliza no declarativa.
- `DatosRamo` viene de `necesidad_proteccion_campos`.
- `Productos` y `Planes` apuntan logicamente a `producto` y `plan`, aunque no hay FK fisica en BD.
- `poliza_seguro_certificados` guarda certificado por poliza.
- `DatosJson` del certificado/dependiente viene de `producto_certificado_campos`.
- Coberturas del certificado nacen del plan (`plan_cobertura_detalle`) y se materializan en `poliza_seguro_certificado_coberturas`.
- Datos tecnicos del certificado nacen de `datos_tecnicos` del producto y se materializan en `poliza_seguro_certificado_datos_tecnicos`.
- Beneficiarios y cesiones pueden existir a nivel poliza o certificado segun `PolizaSeguroCertificadoId`.

Riesgos de este modulo:

- `poliza_seguro` no tiene FKs fisicas para varios catalogos.
- `poliza_seguro_certificados.Vendedor` es `varchar(200)` en BD, pero codigo lo valida/relaciona como ejecutivo id.
- El calculo de totales vive en controlador y JavaScript de vistas.

## Polizas declarativas

Las declarativas siguen un patron comun:

- tabla maestra de poliza
- tabla cartera/temporal por carga mensual
- tabla detalle/recibo por mes y anio
- tablas de historico
- tablas de validacion, excluidos, extraprimados, tasas diferenciadas o tipo cartera segun ramo

### Deuda

Rutas:

- `polizas/deuda`
- `poliza/deuda/*`
- `renovacion_poliza`

Controladores:

- `DeudaController`
- `DeudaCarteraController`
- `DeudaCarteraFedeController`
- `DeudaRenovacionController`
- `DeudaTasaDiferenciadaController`

Vistas:

- `resources/views/polizas/deuda`

Modelos/tablas:

- `Deuda` -> `poliza_deuda`
- `PolizaDeudaCartera` -> `poliza_deuda_cartera`
- `PolizaDeudaTempCartera` -> `poliza_deuda_temp_cartera`
- `DeudaDetalle` -> `poliza_deuda_detalle`
- `DeudaDetallePreliminar` -> `poliza_deuda_detalle_preliminar`
- `DeudaHistorialRecibo` -> `poliza_deuda_historial_recibo`
- `DeudaValidados` -> `poliza_deuda_validados`
- `DeudaCreditosValidos` -> `poliza_deuda_creditos_validos`
- `DeudaExcluidos` -> `poliza_deuda_excluidos`
- `DeudaEliminados` -> `poliza_deuda_eliminados`
- `DeudaRequisitos` -> `poliza_deuda_requisitos`
- `PolizaDeudaExtraPrimados` -> `poliza_deuda_extra_primado`
- `PolizaDeudaExtraPrimadosMensual` -> `poliza_deuda_extra_primado_mensual`
- `PolizaDeudaTasaDiferenciada` -> `poliza_deuda_tasa_diferenciada`
- `PolizaDeudaTipoCartera` -> `poliza_deuda_tipo_cartera`

Relaciones principales:

- `poliza_deuda.Asegurado -> cliente.Id`
- `poliza_deuda.Aseguradora -> aseguradora.Id`
- `poliza_deuda.Ejecutivo -> ejecutivo.Id`
- `poliza_deuda.EstadoPoliza -> estado_poliza.Id`
- `poliza_deuda.Usuario -> users.id`
- `poliza_deuda_cartera.PolizaDeuda -> poliza_deuda.Id`
- `poliza_deuda_detalle.Deuda -> poliza_deuda.Id`

### Vida

Rutas:

- `polizas/vida`
- `poliza/vida/*`
- `vida/exportar_excel*`

Controladores:

- `VidaController`
- `VidaFedeController`
- `VidaRenovacionController`
- `VidaTasaDiferenciadaController`

Vistas:

- `resources/views/polizas/vida`

Modelos/tablas:

- `Vida` -> `poliza_vida`
- `VidaCartera` -> `poliza_vida_cartera`
- `VidaCarteraTemp` -> `poliza_vida_cartera_temp`
- `VidaDetalle` -> `poliza_vida_detalle`
- `VidaDetallePreliminar` -> `poliza_vida_detalle_preliminar`
- `VidaHistorialRecibo` -> `poliza_vida_historial_recibo`
- `PolizaVidaHistorica` -> `poliza_vida_historica`
- `PolizaVidaExtraPrimados` -> `poliza_vida_extra_primado`
- `PolizaVidaExtraPrimadosMensual` -> `poliza_vida_extra_primado_mensual`
- `VidaTasaDiferenciada` -> `poliza_vida_tasa_diferenciada`
- `VidaTipoCartera` -> `poliza_vida_tipo_cartera`
- `VidaCatalogoTipoCartera` -> `poliza_vida_catalogo_tipo_cartera`

Relaciones principales:

- `poliza_vida_tipo_cartera.PolizaVida -> poliza_vida.Id`
- `poliza_vida_tipo_cartera.VidaTipoCartera -> poliza_vida_catalogo_tipo_cartera.Id`
- `poliza_vida_detalle_preliminar.PolizaVidaId -> poliza_vida.Id`
- `poliza_vida_tasa_diferenciada.PolizaVidaTipoCartera -> poliza_vida_tipo_cartera.Id`
- Varias columnas de `poliza_vida` como aseguradora/cliente/estado existen sin FK fisica.

### Desempleo

Rutas:

- `polizas/desempleo`
- `poliza/desempleo/*`

Controladores:

- `DesempleoController`
- `DesempleoCarteraController`
- `DesempleoCarteraComController`
- `DesempleoRenovacionController`
- `DesempleoTasaDiferenciadaController`

Vistas:

- `resources/views/polizas/desempleo`

Modelos/tablas:

- `Desempleo` -> `poliza_desempleo`
- `DesempleoCartera` -> `poliza_desempleo_cartera`
- `DesempleoCarteraTemp` -> `poliza_desempleo_cartera_temp`
- `DesempleoDetalle` -> `poliza_desempleo_detalle`
- `DesempleoDetallePreliminar` -> `poliza_desempleo_detalle_preliminar`
- `DesempleoHistorialRecibo` -> `poliza_desempleo_historial_recibo`
- `PolizaDesempleoHistorica` -> `poliza_desempleo_historica`
- `DesempleoTasaDiferenciada` -> `poliza_desempleo_tasa_diferenciada`
- `DesempleoTipoCartera` -> `poliza_desempleo_tipo_cartera`

Relaciones principales:

- `poliza_desempleo.Asegurado -> cliente.Id`
- `poliza_desempleo.Aseguradora -> aseguradora.Id`
- `poliza_desempleo.Ejecutivo -> ejecutivo.Id`
- `poliza_desempleo.EstadoPoliza -> estado_poliza.Id`
- `poliza_desempleo.Saldos -> saldos_montos.Id`
- `poliza_desempleo.Usuario -> users.id`
- `poliza_desempleo_cartera.PolizaDesempleo -> poliza_desempleo.Id`
- `poliza_desempleo_detalle.Desempleo -> poliza_desempleo.Id`

### Residencia

Rutas:

- `polizas/residencia`

Controladores:

- `ResidenciaController`
- `ResidenciaRenovacionController`

Vistas:

- `resources/views/polizas/residencia`

Modelos/tablas:

- `Residencia` -> `poliza_residencia`
- `PolizaResidenciaCartera` -> `poliza_residencia_cartera`
- `PolizaResidenciaTempCartera` -> `poliza_residencia_temp_cartera`
- `DetalleResidencia` -> `poliza_residencia_detalle`
- `ResidenciaDetallePreliminar` -> `poliza_residencia_detalle_preliminar`
- `ResidenciaHistorialRecibo` -> `poliza_residencia_historial_recibo`
- `PolizaResidenciaHistorica` -> `poliza_residencia_historica`

Relaciones principales:

- `poliza_residencia_detalle_preliminar.PolizaResidenciaId -> poliza_residencia.Id`
- En otras tablas de residencia muchas relaciones son logicas por id sin FK fisica.

### Control declarativas

Rutas:

- `control_cartera`
- `control_cartera/actualizacion`
- `control_cartera/exportar_excel`

Controlador/trait:

- `PolizaControlCarteraController`
- `PolizaControlCarteraTrait`

Vistas:

- `resources/views/polizas/control_cartera`

Modelos/tablas:

- `PolizaDeclarativaControl` -> `poliza_declarativa_control`
- `PolizaDeclarativaReproceso` -> `poliza_declarativa_reproceso`

Relaciones:

- `poliza_declarativa_control` puede apuntar a deuda, vida, desempleo, residencia y reproceso.

## Validacion y consulta transversal

Consulta cliente:

- Rutas: `consulta/cliente`, `consulta/cliente/buscar`
- Controlador: `ConsultaClienteController`
- Cruza carteras de deuda, vida, desempleo y residencia.
- Vista: `resources/views/consulta`

Validacion cartera:

- Ruta: `polizas/validacion_cartera`
- Controlador: `ValidacionCarteraController`
- Usa modelos de carteras mensuales, vida y temporales.
- Vista: `resources/views/polizas/validacion_cartera`

Control primas:

- Ruta: `control-primas`
- Controlador: `ControlPrimasController`
- Modelo/vista: `ViewControlPrimasGeneral` -> `view_control_primas_general`
- Vista: `resources/views/control_primas.blade.php`

## Suscripciones

Rutas:

- `suscripciones`
- `suscripciones/importar`
- `suscripciones/exportar`
- `suscripciones/agregar_comentario`
- `suscripciones/calcular_dias_habiles_json`
- `suscripciones/data/{fechaInicio}/{fechaFinal}`
- Catalogos: `companias`, `estadoscasos`, `fechasferiadas`, `ocupaciones`, `tiposordenesmedicas`, `tiposimc`, `tiposclientes`, `tipocreditos`

Controladores:

- `SuscripcionController`
- `CompaniaController`
- `EstadoCasoController`
- `FechasFeriadasController`
- `OcupacionController`
- `TipoOrdenMedicaController`
- `TipoImcController`
- `TipoClienteController`
- `TipoCreditoController`

Vistas:

- `resources/views/suscripciones/suscripcion`
- `resources/views/suscripciones/compania`
- `resources/views/suscripciones/estado_caso`
- `resources/views/suscripciones/fechas_feriadas`
- `resources/views/suscripciones/ocupacion`
- `resources/views/suscripciones/tipo_orden_medica`
- `resources/views/suscripciones/tipo_imc`
- `resources/views/suscripciones/tipo_cliente`
- `resources/views/suscripciones/tipo_credito`

Modelos/tablas:

- `Suscripcion` -> `suscripcion`
- `SuscripcionTemp` -> `suscripcion_temp`
- `Comentarios` -> `sus_comentarios`
- `Compania` -> `sus_compania`
- `Contratante` -> `sus_contratante`
- `EstadoCaso` -> `sus_estado_caso`
- `FechasFeriadas` -> `sus_fechas_feriadas`
- `Ocupacion` -> `sus_ocupacion`
- `OrdenMedica` -> `sus_orden_medica`
- `Padecimiento` -> `sus_padecimientos`
- `Reproceso` -> `sus_reproceso`
- `ResumenGestion` -> `sus_resumen_gestion`
- `TipoCliente` -> `sus_tipo_cliente`
- `TipoCredito` -> `sus_tipo_credito`
- `TipoImc` -> `sus_tipo_imc`

Relacion fisica confirmada:

- `sus_comentarios.SuscripcionId -> suscripcion.Id`

Uso funcional:

- `suscripcion` registra tarea/caso de suscripcion, polizas deuda/vida, asegurado, sumas, IMC, padecimientos, orden medica, estado, resolucion, reprocesos y tiempos.

## Importaciones y exportaciones

Imports:

- Deuda: `PolizaDeudaTempCarteraImport`, variantes Fede/Complementario.
- Vida: `VidaCarteraTempImport`, variantes Fede/Complementario.
- Desempleo: `DesempleoCarteraTempImport`, variantes Fede/Complementario.
- Residencia: `PolizaResidenciaTempCarteraImport`.
- Suscripcion: `SuscripcionImport`.

Exports:

- Reportes de deuda, vida, desempleo, control cartera, historicos, excluidos, rehabilitados, extraprimados y suscripciones.

Tablas afectadas:

- Temporales: `poliza_*_temp*`.
- Carteras finales: `poliza_*_cartera`.
- Detalles/recibos: `poliza_*_detalle`, `poliza_*_historial_recibo`.

## Reglas practicas para editar

- Si el cambio toca pantalla: empezar por ruta -> controlador -> vista -> modelos -> tabla.
- Si el cambio toca calculo: revisar controlador, vista JS y tabla detalle relacionada.
- Si el cambio toca catalogo: revisar menu/permisos, controlador, modelo y FK real.
- Si el cambio toca poliza no declarativa: revisar producto/plan/ramo antes de tocar certificado.
- Si el cambio toca declarativas: identificar si afecta configuracion de poliza, carga de cartera, validacion, recibo o historico.
- No confiar solo en FKs: muchas relaciones son logicas.
- No asumir nombres Laravel estandar: la mayoria usa `Id`, `Activo`, sin timestamps.
