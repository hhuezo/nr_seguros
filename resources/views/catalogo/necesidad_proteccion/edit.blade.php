@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <style>
        .custom-section-card {
            background: #ffffff;
            border: 1px solid #e4e7ed;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .custom-section-card .card-header-title {
            font-size: 15px;
            font-weight: 700;
            color: #2A3F54;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #1ABB9C;
            display: inline-block;
        }
        .form-group label {
            font-weight: 600;
            color: #4a5c6d;
            font-size: 12.5px;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 4px !important;
            box-shadow: none !important;
            border: 1px solid #ccc;
            height: 38px;
        }
        .form-control:focus {
            border-color: #1ABB9C !important;
        }
        /* Contenedor interactivo para el Switch */
        .switch-container-box {
            background: #f8f9fa;
            border: 1px solid #e4e7ed;
            padding: 12px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 48px;
        }
        .switch-container-box.active {
            background: #e8f8f5;
            border-color: #a3e4d7;
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-req { background-color: #e6f7f4; color: #1abc9c; }
        .badge-opt { background-color: #f2f4f4; color: #7f8c8d; }
        .table-modern th {
            background-color: #f4f6f7 !important;
            color: #34495e;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        .code-block {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            color: #c0392b;
            border: 1px solid #eaeded;
        }
    </style>

    <div class="x_panel" style="border-radius: 8px;">
        <div class="x_title" style="margin-bottom: 20px;">
            <h2><i class="fa fa-sliders text-primary"></i> Configuración General del Ramo</h2>
            <ul class="nav navbar-right panel_toolbox">
                <a href="{{ url('catalogo/necesidad_proteccion') }}?idRegistro={{ $necesidad_proteccion->Id }}"
                   class="btn btn-default btn-sm" style="border-radius: 4px; font-weight: 600;">
                    <i class="fa fa-chevron-left"></i> Volver al Catálogo
                </a>
            </ul>
            <div class="clearfix"></div>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="border-radius: 6px;">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                <i class="fa fa-exclamation-circle"></i> <strong>Por favor, valida los campos:</strong>
                <ul style="margin-top: 5px; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div role="tabpanel">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist" style="border-bottom: 1px solid #cbd5e1;">
                <li role="presentation" class="{{ session('tab1') == 1 || !session('tab1') ? 'active' : '' }}">
                    <a href="#ramo" id="ramo-tab" role="tab" data-toggle="tab" style="font-weight: 600;"><i class="fa fa-file-text-o"></i> Propiedades del Ramo</a>
                </li>
                <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}">
                    <a href="#campos" id="campos-tab" role="tab" data-toggle="tab" style="font-weight: 600;"><i class="fa fa-database"></i> Estructura Dinámica</a>
                </li>
            </ul>

            <div id="myTabContent" class="tab-content" style="padding-top: 20px;">

                <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 || !session('tab1') ? 'active in' : '' }}" id="ramo">
                    <form method="POST" action="{{ route('necesidad_proteccion.update', $necesidad_proteccion->Id) }}">
                        @method('PUT')
                        @csrf

                        <div class="custom-section-card">
                            <h3 class="card-header-title"><i class="fa fa-id-card-o"></i> Identificación del Ramo</h3>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Nombre Oficial del Ramo <span class="text-danger">*</span></label>
                                    <input class="form-control input-lg" name="Nombre" type="text" value="{{ $necesidad_proteccion->Nombre }}" required style="font-size: 16px; font-weight: bold; letter-spacing: 0.5px; border-left: 3px solid #1ABB9C;"
                                           oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                </div>
                            </div>

                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-6 col-sm-12 form-group">
                                    <label>Agrupador Comercial</label>
                                    <select name="AgrupadorRamo" class="form-control select2" style="width: 100%">
                                        <option value="">Seleccione una categoría...</option>
                                        @foreach ($agrupadores_ramo as $agrupador)
                                            <option value="{{ $agrupador->Id }}" {{ $necesidad_proteccion->AgrupadorRamo == $agrupador->Id ? 'selected' : '' }}>
                                                {{ $agrupador->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12 form-group">
                                    <label>Módulo del Sistema (Tipo de Póliza)</label>
                                    <select name="TipoPoliza" class="form-control select2" style="width: 100%">
                                        <option value="">Seleccione el módulo destino...</option>
                                        @foreach ($tipos_poliza as $tipo)
                                            <option value="{{ $tipo->Id }}" {{ $necesidad_proteccion->TipoPoliza == $tipo->Id ? 'selected' : '' }}>
                                                {{ $tipo->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="custom-section-card">
                            <h3 class="card-header-title"><i class="fa fa-money"></i> Parámetros de Comisión y Retenciones</h3>

                            <div class="row">
                                <div class="col-md-6 col-sm-12 form-group">
                                    <label>% Comisión NR (No Declarativas)</label>
                                    <div class="input-group">
                                        <input class="form-control text-right" name="PorcentajeComisionNoDeclarativa" type="number" min="0" max="100"
                                               step="0.0001" value="{{ $necesidad_proteccion->PorcentajeComisionNoDeclarativa }}" placeholder="0.0000">
                                        <span class="input-group-addon" style="background: #f2f4f4; font-weight: bold;">%</span>
                                    </div>
                                    <p class="text-muted" style="font-size:11px; margin-top:4px;">Tasa aplicada a negocios estándar no declarativos.</p>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="col-xs-6 form-group">
                                            <label>¿Retiene Bomberos?</label>
                                            <div class="switch-container-box" id="box-bomberos-status">
                                                <span style="font-weight: bold; font-size: 13px;" id="lbl-bomberos">No</span>
                                                <input type="hidden" name="ComisionBomberos" value="0">
                                                <label class="switch" style="margin-bottom: 0; margin-top: 3px;">
                                                    <input type="checkbox" name="ComisionBomberos" value="1" class="js-bomberos-switch"
                                                        {{ (int) ($necesidad_proteccion->ComisionBomberos ?? 0) === 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 form-group" id="div-porcentaje-bomberos">
                                            <label>% Retención Bomberos</label>
                                            <div class="input-group">
                                                <input class="form-control js-bomberos-input text-right" name="PorcentajeBomberos" type="number" min="0" max="100"
                                                       step="0.0001" value="{{ $necesidad_proteccion->PorcentajeBomberos }}" placeholder="0.0000">
                                                <span class="input-group-addon" style="background: #f2f4f4; font-weight: bold;">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center" style="margin-top: 30px; padding-bottom: 10px;">
                            @can('ramo edit')
                                <button class="btn btn-success" type="submit" style="padding: 8px 24px; font-weight: bold; font-size: 13px; border-radius: 4px;">
                                    <i class="fa fa-save"></i> Actualizar Ramo
                                </button>
                            @endcan
                            <a href="{{ url('catalogo/necesidad_proteccion') }}" class="btn btn-default" style="padding: 8px 24px; font-weight: bold; font-size: 13px; border-radius: 4px; margin-left: 10px;">
                                <i class="fa fa-ban"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="campos">
                    <div class="row" style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                        <div class="col-md-8 col-xs-12">
                            <h4 style="margin: 0; color: #34495e; font-weight: 600;">Estructura de Captura Adicional</h4>
                            <p class="text-muted" style="margin: 4px 0 0 0; font-size: 12px;">Los campos definidos aquí se renderizarán de forma dinámica en los formularios de cotización o emisión vinculados a este ramo.</p>
                        </div>
                        <div class="col-md-4 col-xs-12 text-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-campo-ramo" style="border-radius: 4px; font-weight: 600; padding: 6px 14px;">
                                <i class="fa fa-plus-circle"></i> Agregar Campo Nuevo
                            </button>
                        </div>
                    </div>

                    @if ($campos->count() > 0)
                        <div class="table-responsive" style="border: 1px solid #e4e7ed; border-radius: 6px;">
                            <table class="table table-hover table-modern" style="margin-bottom: 0;">
                                <thead>
                                <tr>
                                    <th style="padding: 12px;">Etiqueta Visual</th>
                                    <th style="padding: 12px;">Identificador Técnico</th>
                                    <th style="padding: 12px;">Tipo de Dato</th>
                                    <th style="padding: 12px;">Validación</th>
                                    <th style="padding: 12px;" class="text-center">Obligatorio</th>
                                    <th style="padding: 12px;">Texto de Ayuda (Placeholder)</th>
                                    <th style="padding: 12px;" class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($campos as $obj)
                                    <tr style="transition: background 0.2s;">
                                        <td style="padding: 12px; font-weight: 600; vertical-align: middle; color: #2c3e50;">{{ $obj->Etiqueta }}</td>
                                        <td style="padding: 12px; vertical-align: middle;"><span class="code-block">{{ $obj->NombreCampo }}</span></td>
                                        <td style="padding: 12px; vertical-align: middle;"><span class="label label-info" style="font-size:10px; border-radius:3px; font-weight:normal;">{{ $obj->TipoCampo }}</span></td>
                                        <td style="padding: 12px; vertical-align: middle; color: #7f8c8d;">{{ $obj->ValidacionCampo == 'ninguna' ? 'Ninguna' : strtoupper($obj->ValidacionCampo) }}</td>
                                        <td style="padding: 12px; vertical-align: middle;" class="text-center">
                                            @if((int) ($obj->Requerido ?? 1) === 1)
                                                <span class="badge-status badge-req">Sí</span>
                                            @else
                                                <span class="badge-status badge-opt">No</span>
                                            @endif
                                        </td>
                                        <td style="padding: 12px; vertical-align: middle;" class="text-muted"><em>{{ $obj->Placeholder ?? 'Ninguno' }}</em></td>
                                        <td style="padding: 12px; vertical-align: middle;" class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-default btn-sm" style="border-radius: 4px; padding: 4px 8px; margin-right:4px;"
                                                        onclick='modal_edit_campo(@json($obj->Id), @json($obj->Etiqueta), @json($obj->NombreCampo), @json($obj->TipoCampo), @json($obj->ValidacionCampo), @json((string)($obj->Requerido ?? 1)), @json($obj->Placeholder ?? ""))'
                                                        data-target="#modal-edit-campo" data-toggle="modal" title="Editar">
                                                    <i class="fa fa-pencil text-primary"></i>
                                                </button>
                                                <button class="btn btn-default btn-sm" style="border-radius: 4px; padding: 4px 8px;"
                                                        onclick="modal_delete_campo({{ $obj->Id }})"
                                                        data-target="#modal-delete-campo" data-toggle="modal" title="Eliminar">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center" style="background: #fdfefe; border: 2px dashed #d6dbdf; border-radius: 8px; padding: 40px 20px; margin-top: 15px;">
                            <i class="fa fa-cubes text-muted" style="font-size: 36px; margin-bottom: 10px;"></i>
                            <h5 style="color: #7f8c8d; font-weight: bold; margin-bottom: 5px;">Sin campos configurados</h5>
                            <p class="text-muted" style="font-size: 12px;">Aún no has agregado propiedades dinámicas a este ramo de seguros.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade bs-modal-nuevo-campo-ramo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/necesidad_proteccion/add_campo') }}">
                    @csrf
                    <div class="modal-content" style="border-radius: 6px;">
                        <div class="modal-header" style="background: #2A3F54; color: white; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                            <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1;"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" style="font-weight: 600;"><i class="fa fa-plus-circle"></i> Registrar Propiedad Adicional</h4>
                        </div>
                        <div class="modal-body" style="padding: 25px;">
                            <input type="hidden" name="NecesidadProteccion" value="{{ $necesidad_proteccion->Id }}">
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Etiqueta en Formulario (Label)</label>
                                    <input type="text" name="Etiqueta" class="form-control" placeholder="Ej: Año del Vehículo" required>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Clave Técnica (Sin espacios/acentos)</label>
                                    <input type="text" name="NombreCampo" class="form-control" placeholder="Ej: anio_vehiculo" required>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-6 form-group">
                                    <label>Tipo de Input HTML</label>
                                    <select name="TipoCampo" class="form-control">
                                        <option value="text">Texto Estándar</option>
                                        <option value="number">Numérico Puro</option>
                                        <option value="date">Selector de Fecha</option>
                                        <option value="textarea">Área de Texto Amplia</option>
                                        <option value="email">Formato de Email</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Regla de Validación Requerida</label>
                                    <select name="ValidacionCampo" class="form-control">
                                        <option value="ninguna">Sin validación especial</option>
                                        <option value="dui">Documento DUI (El Salvador)</option>
                                        <option value="solo_numeros">Solo valores numéricos</option>
                                        <option value="solo_numeros_letras">Alfa-numérico (Letras y Números)</option>
                                        <option value="solo_texto">Solo alfabeto estándar</option>
                                        <option value="correo">E-mail sintácticamente válido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-6 form-group">
                                    <label>¿Es Campo Obligatorio?</label>
                                    <select name="Requerido" class="form-control">
                                        <option value="1">Sí, bloquear envío si está vacío</option>
                                        <option value="0">No, permitir valor opcional</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Texto Instructivo (Placeholder)</label>
                                    <input type="text" name="Placeholder" class="form-control" placeholder="Ej: Ingrese 4 dígitos de año">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f4f6f7;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Descartar</button>
                            <button type="submit" class="btn btn-primary" style="font-weight: bold;"><i class="fa fa-save"></i> Guardar Configuración</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-campo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/necesidad_proteccion/edit_campo') }}">
                    @csrf
                    <div class="modal-content" style="border-radius: 6px;">
                        <div class="modal-header" style="background: #34495e; color: white; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                            <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1;"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" style="font-weight: 600;"><i class="fa fa-edit"></i> Editar Propiedad Dinámica</h4>
                        </div>
                        <div class="modal-body" style="padding: 25px;">
                            <input type="hidden" name="Id" id="CampoRamoId">
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Etiqueta en Formulario</label>
                                    <input type="text" name="Etiqueta" id="CampoRamoEtiqueta" class="form-control" required>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Clave Técnica</label>
                                    <input type="text" name="NombreCampo" id="CampoRamoNombreCampo" class="form-control" required>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-6 form-group">
                                    <label>Tipo de Input</label>
                                    <select name="TipoCampo" id="CampoRamoTipoCampo" class="form-control">
                                        <option value="text">Texto Estándar</option>
                                        <option value="number">Numérico Puro</option>
                                        <option value="date">Selector de Fecha</option>
                                        <option value="textarea">Área de Texto Amplia</option>
                                        <option value="email">Formato de Email</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Regla de Validación</label>
                                    <select name="ValidacionCampo" id="CampoRamoValidacionCampo" class="form-control">
                                        <option value="ninguna">Sin validación especial</option>
                                        <option value="dui">Documento DUI (El Salvador)</option>
                                        <option value="solo_numeros">Solo valores numéricos</option>
                                        <option value="solo_numeros_letras">Alfa-numérico</option>
                                        <option value="solo_texto">Solo alfabeto estándar</option>
                                        <option value="correo">E-mail sintácticamente válido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:10px;">
                                <div class="col-sm-6 form-group">
                                    <label>¿Es Requerido?</label>
                                    <select name="Requerido" id="CampoRamoRequerido" class="form-control">
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Placeholder</label>
                                    <input type="text" name="Placeholder" id="CampoRamoPlaceholder" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="background: #f4f6f7;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" style="font-weight: bold;"><i class="fa fa-save"></i> Guardar Cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-delete-campo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('catalogo/necesidad_proteccion/delete_campo') }}">
                    @csrf
                    <div class="modal-content" style="border-radius: 6px;">
                        <div class="modal-header" style="background-color: #e74c3c; color: white; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                            <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1;"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" style="font-weight: 600;"><i class="fa fa-exclamation-triangle"></i> Eliminar Estructura</h4>
                        </div>
                        <div class="modal-body text-center" style="padding: 30px 20px;">
                            <input type="hidden" name="Id" id="CampoRamoDeleteId">
                            <h4 style="color: #2c3e50; font-weight: bold; margin-bottom: 10px;">¿Confirmas esta eliminación?</h4>
                            <p class="text-muted">El campo dejará de mostrarse inmediatamente en todas las plantillas dinámicas activas.</p>
                        </div>
                        <div class="modal-footer" style="background: #fdfefe;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger" style="font-weight: bold;">Proceder a Eliminar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-catalogo", "li-necesidad_proteccion");

            function toggleComisionInput() {
                var isBomberos = $('.js-bomberos-switch').is(':checked');
                var $input = $('.js-bomberos-input');
                var $lbl = $('#lbl-bomberos');
                var $box = $('#box-bomberos-status');

                if (isBomberos) {
                    $input.prop('disabled', false);
                    $lbl.text('Sí, Aplica').css('color', '#1abc9c');
                    $box.addClass('active');
                    $('#div-porcentaje-bomberos').css('opacity', '1');
                } else {
                    $input.val('');
                    $input.prop('disabled', true);
                    $lbl.text('No Aplica').css('color', '#7f8c8d');
                    $box.removeClass('active');
                    $('#div-porcentaje-bomberos').css('opacity', '0.4');
                }
            }

            toggleComisionInput();
            $(document).on('change', '.js-bomberos-switch', toggleComisionInput);
        });

        function modal_edit_campo(id, etiqueta, nombreCampo, tipoCampo, validacionCampo, requerido, placeholder) {
            $('#CampoRamoId').val(id);
            $('#CampoRamoEtiqueta').val(etiqueta);
            $('#CampoRamoNombreCampo').val(nombreCampo);
            $('#CampoRamoTipoCampo').val(tipoCampo);
            $('#CampoRamoValidacionCampo').val(validacionCampo || 'ninguna');
            $('#CampoRamoRequerido').val(requerido || '1');
            $('#CampoRamoPlaceholder').val(placeholder || '');
        }

        function modal_delete_campo(id) {
            $('#CampoRamoDeleteId').val(id);
        }
    </script>
@endsection
