# Modulo Ventas - Catalogo Comparativo y Roadmap

Revision base: 2026-07-13.

Este documento define la direccion funcional y tecnica del nuevo modulo `ventas`. Su objetivo es reemplazar gradualmente el flujo actual de `negocios`, sin mezclar la logica nueva con la estructura historica existente.

## Estado actual de implementacion

Actualizado hasta 2026-07-13.

- Fase 1 implementada: catalogo comparativo por ramo.
- Ya existe la tabla `ventas_campo_comparativo`.
- Ya existe el modelo `App\Models\catalogo\VentasCampoComparativo`.
- Ya existe el CRUD administrativo en `catalogo/ventas_campo_comparativo`.
- La pantalla principal muestra plantillas por ramo con conteo de conceptos.
- La administracion de conceptos se hace por subpantalla: `catalogo/ventas_campo_comparativo/ramo/{id}`.
- Ya existe relacion desde `NecesidadProteccion` mediante `camposComparativosVentas()`.
- Ya existe entrada en el menu `Catalogos comerciales` como `Plantillas comparativas`.
- Permisos creados:
  - `ventas-campo-comparativo read`
  - `ventas-campo-comparativo create`
  - `ventas-campo-comparativo edit`
  - `ventas-campo-comparativo delete`

Esta fase quedo orientada a estandarizar conceptos/casillas por ramo, no valores.

Alcance corregido: `ventas_campo_comparativo` solo estandariza conceptos/casillas por ramo. No guarda valores, formatos ni tipos de dato especificos. Esos datos pertenecen a la configuracion de cada plan comercial.

- Fase 2 implementada a nivel administrativo: planes comerciales de ventas.
- Ya existe la tabla `ventas_plan_comercial`.
- Ya existe la tabla `ventas_plan_comercial_valor`.
- Ya existe el CRUD administrativo en `catalogo/ventas_plan_comercial`.
- Ya existe la pantalla de especificaciones por plan: `catalogo/ventas_plan_comercial/{id}/valores`.
- Ya existe entrada en el menu `Catalogos comerciales` como `Planes comerciales`.
- Cada plan comercial referencia aseguradora, ramo, producto y plan tecnico.
- Las especificaciones se guardan como texto por cada concepto de la plantilla del ramo.
- Se valido funcionalmente la administracion de plantillas y planes comerciales.
- No se crearan clausulas comerciales separadas de momento; las condiciones/textos del comparativo quedan como valores de planes comerciales.
- Decision funcional nueva: el formulario de seguimiento propuesto por cliente sera tambien la pantalla base de oferta/venta.
- La oferta comercial debe combinar:
  - busqueda o creacion express de cliente
  - datos comerciales del pipeline
  - planes comerciales ofertados
  - seleccion del plan elegido
  - seguimientos historicos
  - campos para KPIs comerciales
- Se creo una base visual sin logica de persistencia para validar estructura antes de guardar datos.
- `ventas/ofertas` queda como entrada principal del modulo de ofertas.
- `ventas/ofertas/formulario` queda como formulario de captura/edicion de oferta.
- La entrada principal ya esta enlazada en el menu `Ventas > Ofertas`.
- La caratula del formulario ya carga catalogos reales:
  - `Gestor` desde `ejecutivo`
  - `Tipo de cliente` desde `tipo_cartera_nr`
  - `Tipo de seguro / ramo` desde `necesidad_proteccion`
  - `Aseguradora referencial` desde `aseguradora`
  - `Etapa` desde `estado_venta`
- `Estado` y `Motivo perdida` se mantienen estaticos temporalmente.
- En el formulario de oferta, el tab `Planes ofertados` ya permite filtrar planes comerciales por ramo, agregarlos visualmente al comparativo y marcar el plan elegido sin persistencia.
- El tab de KPI se elimina del formulario; los indicadores viviran en un dashboard comercial separado.
- El resumen/listado de ofertas debe vivir separado del formulario.
- La vista `ventas/ofertas` carga el resumen/listado y solo muestra boton para nuevo formulario por ahora.
- La vista `ventas/ofertas/formulario` contiene la caratula visual y planes ofertados.
- Cliente se busca por Select2; al seleccionar cliente se trae detalle para telefono/correo.
- El boton copiar datos de cliente existe como respaldo si la asignacion automatica no completa telefono/correo.
- Planes ofertados ya carga por ramo desde planes comerciales configurados, agrega tarjetas/comparativo visual y permite marcar elegido, sin guardar aun en BD.
- Permisos base del modulo:
  - `ventas menu`
  - `ventas-ofertas read`
  - `ventas-ofertas create`
  - `ventas-ofertas edit`
  - `ventas-ofertas delete`

Estado tecnico actual:

- Controlador ofertas: `app/Http/Controllers/ventas/VentasOfertaController.php`.
- Vistas ofertas: `resources/views/ventas/ofertas/index.blade.php` y `resources/views/ventas/ofertas/formulario.blade.php`.
- Catalogos comerciales:
  - `app/Http/Controllers/catalogo/VentasCampoComparativoController.php`
  - `app/Http/Controllers/catalogo/VentasPlanComercialController.php`
  - `resources/views/catalogo/ventas_campo_comparativo`
  - `resources/views/catalogo/ventas_plan_comercial`
- Rutas verificadas:
  - `ventas/ofertas`
  - `ventas/ofertas/formulario`
  - `ventas/ofertas/clientes`
  - `ventas/ofertas/clientes/{id}`
  - `catalogo/ventas_campo_comparativo`
  - `catalogo/ventas_plan_comercial`
  - `catalogo/ventas_plan_comercial/{id}/valores`

## Objetivo del modulo

El modulo `ventas` debe permitir:

- crear oportunidades comerciales por cliente o prospecto
- comparar planes comerciales por ramo
- reutilizar configuraciones estandarizadas por aseguradora / producto / plan
- agregar clausulas especiales o ajustes puntuales por cliente
- congelar un snapshot de la oferta en el momento en que se presenta
- aceptar una opcion y luego usarla como base para crear una poliza no declarativa

## Diagnostico del estado actual

Hoy `negocio` y `cotizacion` sirven para un flujo comercial basico, pero no alcanzan para comparativos como los del Excel comercial.

Limitaciones detectadas:

- `cotizacion` solo guarda `Plan`, `SumaAsegurada`, `PrimaNetaAnual`, `Observaciones` y `DatosTecnicos`
- la estructura actual no modela bien matrices comparativas por ramo
- hay tablas viejas por ramo (`negocio_gastos_medicos`, `negocio_auto`, etc.) que mezclan datos de captura con logica historica
- `producto` y `plan` son catalogos tecnicos base, no una capa comercial suficientemente flexible

Conclusion: el nuevo modulo no debe crecer encima de `negocio` como extension directa. Debe nacer como capa separada y convivir temporalmente con el flujo viejo.

## Hallazgos del Excel comercial

Archivo analizado: `COMPARATIVOS.xlsx`.

Patrones observados:

- hay hojas de ofertas puntuales por cliente
- hay al menos una plantilla reusable de `Gastos Medicos`
- hay al menos una plantilla reusable de `Auto`
- la estructura es matricial:
  - filas = criterios comparativos
  - columnas = opciones comerciales por aseguradora / producto / plan
- los criterios cambian por ramo

Ejemplos:

- `Gastos Medicos`: suma asegurada, cobertura geografica, deducibles, coaseguro, cuarto hospitalario, maternidad, telemedicina, consultas, etc.
- `Auto`: valor asegurado, RC bienes, RC personas, RC complementaria, deducible, prima total, forma de pago, etc.

Conclusion: no es un simple CRUD de planes. Se necesita un motor de comparativos por ramo.

## Decisiones de arquitectura acordadas

### 1. La plantilla comparativa nace del ramo

El ramo (`necesidad_proteccion`) define los criterios maestros que se van a comparar.

Ejemplo:

- ramo `Gastos Medicos` define los criterios que aparecen en la matriz comparativa de gastos medicos
- ramo `Auto` define sus propios criterios

Esto puede reutilizar la idea de `NecesidadProteccionCampo`, pero conviene una estructura separada para no mezclar:

- campos de captura del ramo
- campos comparativos de ventas

### 2. Los planes llenan esos criterios

Cada opcion comercial debe almacenar los valores de los criterios del ramo.

Ejemplo:

- producto `NR SALUD`
- varios planes comerciales
- mismos criterios base
- distintos valores por plan

Importante: estos valores no deben ir como columnas fijas en `plan`. Deben vivir en una tabla detalle por criterio.

### 3. La oferta debe guardar snapshot

Al crear una oferta:

- se toma la configuracion vigente del plan comercial
- se copian criterios comparativos y clausulas
- se permiten ajustes especificos para ese cliente
- la oferta queda congelada historicamente

Esto evita que un cambio futuro en catalogos altere una propuesta vieja.

### 4. Debe existir puente hacia poliza no declarativa

Cuando una opcion de oferta quede aceptada, debe poder alimentar la creacion de una poliza no declarativa, de forma similar al flujo actual con `negocio`.

## Propuesta de estructura funcional

### Catalogo comparativo por ramo

#### `ventas_campo_comparativo`

Define las filas maestras de la matriz comparativa.

Campos sugeridos:

- `Id`
- `NecesidadProteccion`
- `Etiqueta`
- `NombreInterno`
- `Orden`
- `Activo`

Notas:

- esto es por ramo
- define que se compara y en que orden

#### `ventas_grupo_comparativo` (opcional)

Sirve para agrupar visualmente criterios dentro del comparativo.

Ejemplo:

- Coberturas generales
- Deducibles y participaciones
- Beneficios adicionales
- Forma de pago

Si se usa, `ventas_campo_comparativo` deberia tener un FK a este grupo.

### Catalogo de opciones comerciales

#### `ventas_plan_comercial`

Representa la opcion comercial reusable que luego aparece en comparativos.

Campos sugeridos:

- `Id`
- `Aseguradora`
- `NecesidadProteccion`
- `Producto`
- `Plan`
- `NombreComercial`
- `VersionComercial`
- `ObservacionesInternas`
- `Activo`

Notas:

- referencia al `producto` y `plan` tecnico cuando aplique
- permite multiples variantes comerciales sin ensuciar `plan`

#### `ventas_plan_comercial_valor`

Guarda el valor de cada criterio comparativo para cada plan comercial.

Campos sugeridos:

- `Id`
- `PlanComercial`
- `CampoComparativo`
- `ValorTexto`
- `ValorNumero`
- `ValorBooleano`
- `Activo`

Notas:

- solo uno de los valores deberia ser relevante segun `TipoDato`
- evita crear decenas de columnas fijas en `plan`

### Clausulas y textos estandarizados

#### `ventas_clausula`

Catalogo de clausulas o textos estandarizados.

Campos sugeridos:

- `Id`
- `NecesidadProteccion`
- `Tipo` (`estandar`, `especial`, `beneficio`, `exclusion`, `nota`)
- `Titulo`
- `Texto`
- `Orden`
- `Activo`

#### `ventas_plan_comercial_clausula`

Relaciona clausulas estandar a un plan comercial.

Campos sugeridos:

- `Id`
- `PlanComercial`
- `Clausula`
- `Orden`
- `Activo`

## Propuesta de estructura transaccional

### `ventas_oportunidad`

Cabecera comercial del caso.

Campos sugeridos:

- `Id`
- `Cliente`
- `Ejecutivo`
- `NecesidadProteccion`
- `Estado`
- `FechaVenta`
- `InicioVigencia`
- `PeriodoPago`
- `NumCuotas`
- `Observaciones`
- `DatosCaso`
- `Activo`

Notas:

- sustituye gradualmente el uso de `negocio`
- `DatosCaso` puede guardar informacion puntual del ramo o del cliente

### `ventas_oferta`

Version de oferta dentro de una oportunidad.

Campos sugeridos:

- `Id`
- `Oportunidad`
- `Version`
- `Estado`
- `Observaciones`
- `FechaGeneracion`
- `UsuarioIngreso`
- `Activo`

### `ventas_oferta_opcion`

Cada columna/oferta puntual presentada al cliente.

Campos sugeridos:

- `Id`
- `Oferta`
- `PlanComercial`
- `NombreMostrado`
- `SumaAsegurada`
- `PrimaNetaAnual`
- `Aceptado`
- `Orden`
- `Observaciones`
- `Activo`

Notas:

- aqui ya vive el snapshot base de la opcion
- puede almacenar campos economicos ajustados para ese cliente

### `ventas_oferta_opcion_valor`

Snapshot de los criterios comparativos de la opcion ofrecida.

Campos sugeridos:

- `Id`
- `OfertaOpcion`
- `CampoComparativo`
- `EtiquetaSnapshot`
- `TipoDatoSnapshot`
- `ValorTexto`
- `ValorNumero`
- `ValorBooleano`

Notas:

- si el catalogo cambia despues, la oferta no se altera

### `ventas_oferta_clausula`

Snapshot de clausulas y textos especiales de la oferta.

Campos sugeridos:

- `Id`
- `OfertaOpcion`
- `Clausula`
- `TituloSnapshot`
- `TextoSnapshot`
- `EsTextoLibre`
- `Orden`
- `Activo`

## Flujo objetivo

1. Se crea una `oportunidad`
2. Se selecciona el `ramo`
3. El sistema carga los `campos comparativos` definidos para ese ramo
4. El usuario agrega opciones comerciales desde `planes comerciales`
5. El sistema trae automaticamente:
   - valores comparativos
   - clausulas estandar
6. El usuario ajusta lo necesario para ese cliente
7. Se guarda la `oferta` con snapshot
8. Se genera comparativo visual y posteriormente PDF
9. Una opcion puede marcarse como `aceptada`
10. La opcion aceptada puede alimentar la creacion de una poliza no declarativa

## Relacion con modulo actual `negocios`

Estrategia recomendada:

- fase 1: `ventas` convive con `negocios`
- fase 2: nuevas ofertas se crean en `ventas`
- fase 3: la conversion a poliza no declarativa usa `ventas` como fuente principal
- fase 4: `negocios` queda solo como legado de consulta
- fase 5: se evalua retiro funcional del flujo viejo

No se recomienda migrar todo de golpe.

## Roadmap propuesto

### Fase 0 - Analisis y definicion

- cerrar este documento con criterios funcionales
- validar los primeros ramos a soportar
- confirmar si la prima se captura o se calcula
- confirmar comportamiento de clausulas especiales

### Fase 1 - Catalogo comparativo por ramo

- definir tabla de campos comparativos
- definir agrupaciones visuales si hacen falta
- armar CRUD de criterios por ramo

### Fase 2 - Catalogo de planes comerciales

- definir tabla de planes comerciales
- relacionarla con `aseguradora`, `ramo`, `producto` y `plan`
- armar mantenimiento CRUD
- cargar valores comparativos por criterio

### Fase 3 - Oferta / pipeline unificado

- crear base visual del formulario combinado [hecho]
- definir tablas finales para oportunidad/oferta
- permitir buscar cliente existente [hecho visual/lectura]
- permitir crear cliente express con datos basicos
- capturar datos de pipeline:
  - gestor
  - fecha ingreso
  - inicio vigencia
  - canal origen
  - tipo cliente
  - ramo/tipo seguro
  - aseguradora referencial
  - etapa
  - estado
  - cotizacion enviada
  - prima neta
  - postventa
  - encuesta
  - motivo perdida
- mostrar resumen de ofertas/ventas en tabla [pendiente persistencia]
- mantener el resumen de ofertas/ventas en una vista separada del formulario [hecho estructura]
- permitir agregar planes comerciales ofertados [hecho visual/lectura]
- permitir marcar plan seleccionado [hecho visual/lectura]
- preparar insumos para KPI comercial, pero no mostrar dashboard dentro del formulario [decision tomada]

### Fase 4 - Seguimientos

- crear historial de seguimientos por oferta
- evitar depender de un unico campo `Comentarios`
- cada seguimiento debe guardar fecha, usuario, descripcion y proxima accion si aplica

### Fase 5 - Comparativo visual

- pantalla de comparativo por oferta usando planes comerciales seleccionados
- columnas dinamicas por opcion
- filas dinamicas por criterio del ramo
- soporte de ajuste por cliente

### Fase 6 - Snapshot de oferta

- copiar especificaciones de cada plan comercial al momento de agregarlo a la oferta
- permitir ajustes especificos por cliente
- congelar valores para no depender del catalogo vivo

### Fase 7 - PDF de propuesta

- usar snapshot de oferta
- respetar plantilla visual aprobada por negocio

### Fase 8 - Aceptacion y puente a poliza

- marcar opcion aceptada
- exponer endpoint/fuente para prellenar `poliza/seguro/create`
- iniciar sustitucion gradual de `negocios`

## Riesgos a controlar

- no meter los criterios comparativos como columnas fijas en `plan`
- no mezclar snapshot de oferta con catalogo vivo
- no acoplar el modulo nuevo a tablas historicas de `negocio_*`
- no asumir que todos los ramos usan la misma matriz

## Primer alcance recomendado

Arrancar con:

1. `Gastos Medicos`
2. luego `Auto`

Razon:

- el Excel ya tiene una plantilla clara de gastos medicos
- es el caso mas rico en comparativos y mejor valida el modelo
- si soporta gastos medicos, el motor ya va a quedar bastante maduro

## Decisiones pendientes

- si `ventas_campo_comparativo` reutiliza `NecesidadProteccionCampo` o se crea tabla separada
- si las primas se capturan manualmente o se calculan desde una logica posterior
- si una oferta puede tener multiples versiones o si inicialmente bastara una oferta activa por oportunidad
- si una oferta aceptada puede seguir editandose o debe cerrarse
- si la propuesta PDF saldra desde HTML/Blade como en otros modulos
- si habra importacion masiva inicial desde Excel para planes comerciales
- si `Plan presentado` del Excel se migra como texto historico o se sustituye completamente por opciones de planes comerciales
- si `Motivo perdida` sera catalogo cerrado, texto libre o combinacion de ambos

## Siguiente paso inmediato

Siguiente bloque recomendado:

- Definir y crear tablas transaccionales reales de ofertas/ventas.
- Mantener catalogos comerciales separados de transacciones.
- Guardar caratula de oferta con cliente, ejecutivo, ramo, aseguradora referencial, etapa, estado y datos de pipeline.
- Guardar planes ofertados como snapshot, no como referencia viva al catalogo.
- Guardar plan elegido.
- Agregar seguimientos historicos en tab separado.
- Crear cliente express solo cuando el cliente no exista.
