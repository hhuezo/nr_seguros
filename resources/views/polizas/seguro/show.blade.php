@extends ('welcome')
@section('contenido')
@can('seguro read')
    @php
        $tab = (int) ($tab ?? 1);
        $productoActual = $poliza_seguro->producto;
        $ramoActual = old('Ramo', $productoActual->NecesidadProteccion ?? '');
        $aseguradoraActual = old('Aseguradora', $productoActual->Aseguradora ?? '');
        $productoActualId = old('Productos', $poliza_seguro->Productos);
        $planActualId = old('Planes', $poliza_seguro->Planes);
        $productosCatalogo = $productos->map(function ($producto) {
            return [
                'id' => $producto->Id,
                'nombre' => $producto->Nombre,
                'ramo' => $producto->NecesidadProteccion,
                'aseguradora' => $producto->Aseguradora,
            ];
        })->values();
        $planesCatalogo = $planes->map(function ($plan) {
            return [
                'id' => $plan->Id,
                'nombre' => $plan->Nombre,
                'producto' => $plan->Producto,
            ];
        })->values();
        $aseguradorasCatalogo = $aseguradora->map(function ($item) {
            return [
                'id' => $item->Id,
                'nombre' => $item->Nombre,
            ];
        })->values();
    @endphp

    @if (session('success'))
        <script>toastr.success("{{ session('success') }}");</script>
    @endif
    @if (session('error'))
        <script>toastr.error("{{ session('error') }}");</script>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .poliza-section { border-top: 1px solid #e5e7eb; padding: 16px 0 4px; }
        .poliza-section:first-child { border-top: 0; }
        .poliza-section h5 { color: #334155; font-weight: 700; margin: 0 0 12px; }
        .poliza-field { margin-bottom: 12px; }
        .poliza-field label { color: #475569; font-size: 12px; font-weight: 700; margin-bottom: 5px; }
        .poliza-actions { border-top: 1px solid #e5e7eb; margin-top: 12px; padding-top: 14px; text-align: right; }
        .btn-inline-group { align-items: center; display: inline-flex; gap: 6px; justify-content: center; }
        .btn-inline-group form { margin: 0; }
        .certificado-toggle { width: 30px; }
        .certificado-child-wrapper { padding: 8px 10px 10px; background: #f8fafc; overflow-x: auto; }
        .certificado-child-wrapper h5 { margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #334155; }
        .certificado-child-table { margin-bottom: 0; width: 100%; }
        .certificado-child-table th { background: #eef2f7; color: #475569; font-size: 12px; white-space: nowrap; }
        .certificado-child-table td { font-size: 12px; white-space: nowrap; }
        #tabla-certificados,
        #tabla-certificados_wrapper { font-size: 12px; }
        .certificado-owner-row td { white-space: nowrap; }
        .certificado-search-text { display: none; }
        .modal-dependientes .modal-dialog { width: 94%; max-width: 1180px; }
        .dependiente-edit-row { background: #f8fafc; }
        .dependiente-edit-box { border: 1px solid #e5e7eb; padding: 12px; }
    </style>

    <div class="x_panel">
        <div class="x_title">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <h4>Poliza de seguro #{{ $poliza_seguro->Id }}</h4>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12" align="right">
                <a href="{{ url('poliza/seguro') }}" class="btn btn-info">
                    <i class="fa fa-arrow-left"></i> Atras
                </a>
            </div>
            <div class="clearfix"></div>
        </div>

        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
            <li class="{{ $tab == 1 ? 'active' : '' }}"><a href="#home" data-toggle="tab">Poliza</a></li>
            <li class="{{ $tab == 2 ? 'active' : '' }}"><a href="#cobertura" data-toggle="tab">Cobertura</a></li>
            <li class="{{ $tab == 3 ? 'active' : '' }}"><a href="#datos_tecnicos" data-toggle="tab">Datos tecnicos</a></li>
            <li class="{{ $tab == 4 ? 'active' : '' }}"><a href="#certificados" data-toggle="tab">Certificados</a></li>
            <li class="{{ $tab == 5 ? 'active' : '' }}"><a href="#cesion" data-toggle="tab">Cesion de beneficios</a></li>
            <li class="{{ $tab == 6 ? 'active' : '' }}"><a href="#beneficiarios" data-toggle="tab">Beneficiarios</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }}" id="home">
                <form id="formPolizaSeguroEdit" action="{{ url('poliza/seguro/save') }}/{{ $poliza_seguro->Id }}" method="post">
                    @csrf
                    <input type="hidden" name="Oferta" value="{{ $poliza_seguro->Oferta }}">
                    <input type="hidden" name="Cliente" value="{{ $poliza_seguro->Cliente }}">
                    <div class="poliza-section">
                        <h5>Origen de la poliza</h5>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label># Oferta aceptada</label>
                                <select id="Oferta" class="form-control select2" style="width:100%;" disabled>
                                    <option value="">Sin oferta aceptada...</option>
                                    @foreach ($ofertas as $off)
                                        @php
                                            $cotizacionAceptada = $off->cotizaciones->first();
                                        @endphp
                                        <option value="{{ $off->Id }}" {{ old('Oferta', $poliza_seguro->Oferta) == $off->Id ? 'selected' : '' }}>
                                            #{{ $off->Id }} - {{ $off->clientes->Nombre ?? 'Cliente no definido' }}
                                            @if ($cotizacionAceptada)
                                                / {{ $cotizacionAceptada->planes->productos->Nombre ?? 'Producto N/A' }}
                                                - {{ $cotizacionAceptada->planes->Nombre ?? 'Plan N/A' }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 poliza-field">
                                <label>Nombre cliente *</label>
                                <select id="Cliente" class="form-control select2" style="width:100%;" disabled>
                                    <option value="">Seleccione...</option>
                                    @foreach ($clientes as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('Cliente', $poliza_seguro->Cliente) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-5 poliza-field">
                                <label>Numero documento *</label>
                                <input type="text" name="NumeroDocumento" id="NumeroDocumento" class="form-control" readonly value="{{ old('NumeroDocumento', $poliza_seguro->clientes->Dui ?? $poliza_seguro->clientes->Nit ?? '') }}">
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Numero poliza *</label>
                                <input type="text" name="NumeroPoliza" id="NumeroPoliza" class="form-control" required value="{{ old('NumeroPoliza', $poliza_seguro->NumeroPoliza) }}">
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Ramo *</label>
                                <select name="Ramo" id="Ramo" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($ramos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            data-comision="{{ $obj->PorcentajeComisionNoDeclarativa }}"
                                            {{ $obj->Id == $ramoActual ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Aseguradora *</label>
                                <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradora as $obj)
                                        <option value="{{ $obj->Id }}" {{ $obj->Id == $aseguradoraActual ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Productos *</label>
                                <select name="Productos" id="Productos" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Planes *</label>
                                <select name="Planes" id="Planes" class="form-control select2" style="width:100%;" required>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Departamento NR</label>
                                <select name="DepartamentoNr" id="DepartamentoNr" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($departamento_nr as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('DepartamentoNr', $poliza_seguro->Departamento) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Ejecutivo que atendera</label>
                                <select name="EjecutivoCia" id="EjecutivoCia" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivos as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('EjecutivoCia', $poliza_seguro->EjecutivoCia) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Estado de poliza *</label>
                                <select name="EstadoPoliza" id="EstadoPoliza" class="form-control select2" style="width:100%;" required>
                                    @foreach ($estado_poliza as $estado)
                                        <option value="{{ $estado->Id }}" {{ old('EstadoPoliza', $poliza_seguro->EstadoPoliza) == $estado->Id ? 'selected' : '' }}>{{ $estado->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Tipo de deducible</label>
                                <select name="Deducible" id="Deducible" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipo_deducible as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('Deducible', $poliza_seguro->Deducible) == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-5 poliza-field">
                                <label>Valor deducible</label>
                                <input type="number" step="0.01" name="ValorDeducible" id="ValorDeducible" class="form-control" value="{{ old('ValorDeducible', $poliza_seguro->ValorDeducible !== null ? number_format($poliza_seguro->ValorDeducible, 2, '.', '') : '') }}">
                            </div>
                            <div class="col-md-3 col-sm-6 poliza-field">
                                <label>Forma de pago *</label>
                                <select name="FormaPago" id="FormaPago" class="form-control select2" style="width:100%;" required>
                                    @foreach ($forma_pago as $pago)
                                        <option value="{{ $loop->index }}" {{ old('FormaPago', $poliza_seguro->FormaPago) == $loop->index ? 'selected' : '' }}>{{ $pago == '' ? 'Seleccione...' : $pago }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-6 poliza-field">
                                <label>Cuotas</label>
                                <input type="number" name="NumCuotas" id="NumCuotas" class="form-control" value="{{ old('NumCuotas', $poliza_seguro->NumCuotas) }}">
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Suma asegurada</label>
                                <input type="number" step="0.01" name="SumaAsegurada" id="SumaAsegurada" class="form-control" value="{{ old('SumaAsegurada', $poliza_seguro->SumaAsegurada !== null ? number_format($poliza_seguro->SumaAsegurada, 2, '.', '') : '') }}">
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Prima neta anual</label>
                                <input type="number" step="0.01" name="PrimaNetaAnual" id="PrimaNetaAnual" class="form-control" value="{{ old('PrimaNetaAnual', $poliza_seguro->PrimaNetaAnual !== null ? number_format($poliza_seguro->PrimaNetaAnual, 2, '.', '') : '') }}">
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>% Comision NR</label>
                                <input type="number" step="0.0001" name="PorcentajeComisionNR" id="PorcentajeComisionNR" class="form-control" value="{{ old('PorcentajeComisionNR', $poliza_seguro->PorcentajeComisionNR) }}">
                            </div>
                        </div>
                    </div>

                    <div class="poliza-section">
                        <h5>Vigencia y cancelacion</h5>
                        <div class="row">
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Vigencia Desde *</label>
                                <input class="form-control" id="vigencia_desde" name="VigenciaDesde" type="date" value="{{ $poliza_seguro->VigenciaDesde }}" required>
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Vigencia Hasta *</label>
                                <input class="form-control" id="vigencia_hasta" name="VigenciaHasta" type="date" value="{{ $poliza_seguro->VigenciaHasta }}" required>
                            </div>
                            <div class="col-md-1 col-sm-6 poliza-field">
                                <label>Dias</label>
                                <input type="number" name="DiasVigencia" id="dias_vigencia" class="form-control" readonly value="{{ $poliza_seguro->DiasVigencia }}">
                            </div>
                            <div class="col-md-5 col-sm-6 poliza-field">
                                <label>Motivos de cancelacion</label>
                                <select name="CodCancelacion" id="CodCancelacion" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($motivos_cancelacion as $obj)
                                        <option value="{{ $obj->Id }}" {{ $obj->Id == $poliza_seguro->CodCancelacion ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Fecha Cancelacion</label>
                                <input type="date" name="FechaCancelacion" class="form-control" value="{{ $poliza_seguro->FechaCancelacion }}">
                            </div>
                            <div class="col-md-10 col-sm-6 poliza-field">
                                <label>Motivo Cancelacion</label>
                                <input type="text" name="MotivoCancelacion" id="MotivoCancelacion" class="form-control" value="{{ $poliza_seguro->MotivoCancelacion }}">
                            </div>
                            <div class="col-md-2 col-sm-6 poliza-field">
                                <label>Fecha Envio Anexo</label>
                                <input type="date" name="FechaEnvioAnexo" class="form-control" value="{{ $poliza_seguro->FechaEnvioAnexo }}">
                            </div>
                        </div>
                    </div>

                    <div class="poliza-section">
                        <h5>Administracion y seguimiento</h5>
                        <div class="row">
                            <div class="col-md-6 poliza-field">
                                <label>Observacion Ren.</label>
                                <input type="text" name="Observacion" id="Observacion" class="form-control" value="{{ $poliza_seguro->Observacion }}">
                            </div>
                            <div class="col-md-3 poliza-field">
                                <label>Solicitud Renovacion</label>
                                <input type="date" name="SolicitudRenovacion" class="form-control" value="{{ $poliza_seguro->SolicitudRenovacion }}">
                            </div>
                            <div class="col-md-3 poliza-field">
                                <label>Fecha Vinculacion</label>
                                <input type="date" name="FechaVinculacion" class="form-control" value="{{ $poliza_seguro->FechaVinculacion }}">
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Origen Poliza</label>
                                <select name="OrigenPoliza" id="OrigenPoliza" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($origen_poliza as $obj)
                                        <option value="{{ $obj->Id }}" {{ $obj->Id == $poliza_seguro->OrigenPoliza ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 poliza-field">
                                <label>Fecha Recepcion</label>
                                <input type="date" name="FechaRecepcion" class="form-control" value="{{ $poliza_seguro->FechaRecepcion }}">
                            </div>
                        </div>
                    </div>

                    <div class="poliza-section">
                        <h5>Datos complementarios</h5>
                        <div class="row">
                            <div class="col-md-3 poliza-field">
                                <label>Sustituida por poliza</label>
                                <input type="date" name="SustituidaPoliza" class="form-control" value="{{ $poliza_seguro->SustituidaPoliza }}">
                            </div>
                            <div class="col-md-5 poliza-field">
                                <label>Observacion Siniestro</label>
                                <input type="text" name="ObservacionSiniestro" class="form-control" value="{{ $poliza_seguro->ObservacionSiniestro }}">
                            </div>
                            <div class="col-md-4 poliza-field">
                                <label>Grupo Cliente</label>
                                <input type="text" name="GrupoCliente" class="form-control" value="{{ $poliza_seguro->GrupoCliente }}">
                            </div>
                        </div>
                    </div>

                    <div class="poliza-actions">
                        <a href="{{ url('poliza/seguro') }}" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</a>
                        <button id="btnGuardarPoliza" class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="cobertura">
                <div style="text-align: right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo-cobertura">Agregar</button>
                </div>
                <br>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripcion</th>
                            <th>Tarificacion</th>
                            <th>Descuento</th>
                            <th>IVA</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_seguro->coberturas as $cobertura)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cobertura->Nombre ?? '' }}</td>
                                <td>{{ $cobertura->Tarificacion ? 'Millar' : 'Porcentual' }}</td>
                                <td>{{ $cobertura->Descuento ? 'Si' : 'No' }}</td>
                                <td>{{ $cobertura->Iva ? 'Si' : 'No' }}</td>
                                <td style="text-align:center;">
                                    <button class="btn btn-danger btn-sm" type="button" data-target="#modal-cobertura-delete-{{ $cobertura->Id }}" data-toggle="modal">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @include('polizas.seguro.modal_coberura_delete')
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade {{ $tab == 3 ? 'active in' : '' }}" id="datos_tecnicos">
                <form method="POST" action="{{ url('poliza/seguro/datos_tecnicos_save') }}/{{ $poliza_seguro->Id }}">
                    @csrf
                    <div class="poliza-section">
                        <h5>Datos generales del ramo</h5>
                        @if ($campos_ramo->count() > 0)
                            <div class="row">
                                @include('polizas.seguro.partials.ramo_campos_form')
                            </div>
                        @else
                            <div class="alert alert-info">El ramo de esta poliza no tiene campos generales configurados.</div>
                        @endif
                    </div>

                    <div class="poliza-section">
                        <h5>Datos tecnicos del producto</h5>
                        @if ($poliza_seguro->datosTecnicos->count() > 0)
                            <div class="row">
                                @foreach ($poliza_seguro->datosTecnicos as $dato)
                                    <div class="col-md-6 col-sm-12 poliza-field">
                                        <label>{{ $dato->Nombre }}</label>
                                        <textarea name="DatosTecnicos[{{ $dato->Id }}]" class="form-control" rows="2">{{ old('DatosTecnicos.' . $dato->Id, $dato->Valor) }}</textarea>
                                        @if ($dato->Descripcion)
                                            <small class="text-muted">{{ $dato->Descripcion }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">Este producto no tiene datos tecnicos configurados.</div>
                        @endif
                    </div>

                    @if ($campos_ramo->count() > 0 || $poliza_seguro->datosTecnicos->count() > 0)
                        <div class="poliza-actions">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar datos tecnicos</button>
                        </div>
                    @else
                        <div class="alert alert-info">No hay campos pendientes para complementar.</div>
                    @endif
                </form>
            </div>

            <div class="tab-pane fade {{ $tab == 4 ? 'active in' : '' }}" id="certificados">
                <div style="text-align: right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo-certificado">
                        <i class="fa fa-plus"></i> Agregar certificado
                    </button>
                </div>
                @if ($certificado_campos->count() == 0)
                    <br><div class="alert alert-warning">El producto no tiene campos de certificado configurados.</div>
                @endif
                <br>
                <table id="tabla-certificados" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="certificado-toggle"></th>
                            <th># Certificado</th>
                            @foreach ($certificado_campos as $campo)
                                <th>{{ $campo->Etiqueta }}</th>
                            @endforeach
                            <th>Observacion</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_seguro->certificados as $certificado)
                            @php
                                $datosCertificado = json_decode($certificado->DatosJson ?: '[]', true);
                                $valoresCertificado = collect($datosCertificado)->pluck('Valor', 'CampoId')->all();
                                $textoDependientes = $certificado->dependientes->map(function ($dependiente) {
                                    $datos = json_decode($dependiente->DatosJson ?: '[]', true);
                                    return collect($datos)->pluck('Valor')->implode(' ');
                                })->implode(' ');
                            @endphp
                            <tr class="certificado-owner-row" data-certificado="{{ $certificado->Id }}">
                                <td>
                                    @if ($certificado->dependientes->count() > 0)
                                        <button type="button" class="btn btn-default btn-xs toggle-certificado-dependientes" data-certificado="{{ $certificado->Id }}" title="Ver dependientes">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <span class="certificado-search-text">{{ $textoDependientes }}</span>
                                    @endif
                                </td>
                                <td>{{ $certificado->NumeroCertificado }}</td>
                                @foreach ($certificado_campos as $campo)
                                    <td>{{ $valoresCertificado[$campo->Id] ?? '' }}</td>
                                @endforeach
                                <td>{{ $certificado->Observacion }}</td>
                                <td style="text-align:center;">
                                    <div class="btn-inline-group">
                                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal" data-target="#modal-edit-certificado-{{ $certificado->Id }}">
                                            <i class="fa fa-pencil"></i> Editar
                                        </button>
                                        @if ($permite_dependientes)
                                            <button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#modal-dependiente-{{ $certificado->Id }}">
                                                <i class="fa fa-users"></i> Dependientes
                                            </button>
                                        @endif
                                        <form method="POST" action="{{ url('poliza/seguro/certificado_delete') }}/{{ $certificado->Id }}">
                                            @csrf
                                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Confirme si desea eliminar este certificado.');">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach ($poliza_seguro->certificados as $certificado)
                    @if ($certificado->dependientes->count() > 0)
                        <script type="text/template" id="dependientes-template-{{ $certificado->Id }}">
                            <div class="certificado-child-wrapper">
                                <h5>Dependientes del certificado {{ $certificado->NumeroCertificado }}</h5>
                                <table class="table table-bordered certificado-child-table">
                                    <thead>
                                        <tr>
                                            <th># Certificado</th>
                                            @foreach ($certificado_campos as $campo)
                                                <th>{{ $campo->Etiqueta }}</th>
                                            @endforeach
                                            <th>Observacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($certificado->dependientes as $dependiente)
                                            @php
                                                $datosDependienteVista = json_decode($dependiente->DatosJson ?: '[]', true);
                                                $valoresDependienteVista = collect($datosDependienteVista)->pluck('Valor', 'CampoId')->all();
                                            @endphp
                                            <tr>
                                                <td>{{ $certificado->NumeroCertificado }}</td>
                                                @foreach ($certificado_campos as $campo)
                                                    <td>{{ $valoresDependienteVista[$campo->Id] ?? '' }}</td>
                                                @endforeach
                                                <td>{{ $dependiente->Observacion }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </script>
                    @endif
                @endforeach
            </div>

            <div class="tab-pane fade {{ $tab == 5 ? 'active in' : '' }}" id="cesion">
                <div style="text-align:right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nueva-cesion">
                        <i class="fa fa-plus"></i> Agregar cesion
                    </button>
                </div>
                <br>
                <table id="tabla-cesiones" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Cod. Cesion</th>
                            <th>Beneficiario</th>
                            <th>Fec. Vigencia</th>
                            <th>Fec. Cancelacion</th>
                            <th>Suma Cedida</th>
                            <th>Observaciones</th>
                            <th>Propietario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cesion_beneficios as $cesion)
                            <tr>
                                <td>{{ $cesion->CodigoSesion }}</td>
                                <td>{{ $cesion->Beneficiario }}</td>
                                <td>{{ $cesion->FechaVigencia }}</td>
                                <td>{{ $cesion->FechaCancelacion }}</td>
                                <td>{{ $cesion->SumaCedida !== null ? number_format($cesion->SumaCedida, 2, '.', ',') : '' }}</td>
                                <td>{{ $cesion->Observaciones }}</td>
                                <td>{{ $cesion->Propietario }}</td>
                                <td align="center">
                                    <div class="btn-inline-group">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-edit-cesion-{{ $cesion->Id }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-delete-cesion-{{ $cesion->Id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade {{ $tab == 6 ? 'active in' : '' }}" id="beneficiarios">
                <div style="text-align:right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-nuevo-beneficiario">
                        <i class="fa fa-plus"></i> Agregar beneficiario
                    </button>
                </div>
                <br>
                <table id="tabla-beneficiarios" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Parentesco</th>
                            <th>Fecha nacimiento</th>
                            <th>Porcentaje</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_seguro->beneficiarios as $beneficiario)
                            <tr>
                                <td>{{ $beneficiario->Nombre }}</td>
                                <td>{{ $beneficiario->parentesco->Nombre ?? '' }}</td>
                                <td>{{ $beneficiario->FechaNacimiento }}</td>
                                <td>{{ number_format($beneficiario->Porcentaje, 2) }}%</td>
                                <td style="text-align:center;">
                                    <form method="POST" action="{{ url('poliza/seguro/beneficiario_delete') }}/{{ $beneficiario->Id }}">
                                        @csrf
                                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Confirme si desea eliminar este beneficiario.');">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th>{{ number_format($total_beneficiarios, 2) }}%</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-nuevo-beneficiario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('poliza/seguro/beneficiario_store') }}/{{ $poliza_seguro->Id }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nuevo beneficiario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 poliza-field">
                                <label>Nombre</label>
                                <input type="text" name="Nombre" class="form-control" value="{{ old('Nombre') }}" required>
                            </div>
                            <div class="col-md-3 poliza-field">
                                <label>Parentesco</label>
                                <select name="Parentesco" class="form-control select2" style="width:100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach ($parentescos as $parentesco)
                                        <option value="{{ $parentesco->Id }}" {{ old('Parentesco') == $parentesco->Id ? 'selected' : '' }}>{{ $parentesco->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 poliza-field">
                                <label>Fecha nacimiento</label>
                                <input type="date" name="FechaNacimiento" class="form-control" value="{{ old('FechaNacimiento') }}">
                            </div>
                            <div class="col-md-2 poliza-field">
                                <label>Porcentaje</label>
                                <input type="number" name="Porcentaje" class="form-control" step="0.01" min="0.01" max="100" value="{{ old('Porcentaje') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-nueva-cesion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('poliza/seguro/cesion_beneficios_store') }}/{{ $poliza_seguro->Id }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h4 class="modal-title">Nueva cesion de beneficios</h4></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 poliza-field">
                                <label>Cod. Cesion *</label>
                                <input type="text" name="CodigoSesion" class="form-control" value="{{ old('CodigoSesion') }}" required>
                            </div>
                            <div class="col-md-8 poliza-field">
                                <label>Beneficiario *</label>
                                <input type="text" name="Beneficiario" class="form-control" value="{{ old('Beneficiario') }}" required>
                            </div>
                            <div class="col-md-4 poliza-field">
                                <label>Fec. Vigencia</label>
                                <input type="date" name="FechaVigencia" class="form-control" value="{{ old('FechaVigencia') }}">
                            </div>
                            <div class="col-md-4 poliza-field">
                                <label>Fec. Cancelacion</label>
                                <input type="date" name="FechaCancelacion" class="form-control" value="{{ old('FechaCancelacion') }}">
                            </div>
                            <div class="col-md-4 poliza-field">
                                <label>Suma cedida</label>
                                <input type="number" name="SumaCedida" class="form-control" step="0.01" min="0" value="{{ old('SumaCedida') }}">
                            </div>
                            <div class="col-md-6 poliza-field">
                                <label>Propietario</label>
                                <input type="text" name="Propietario" class="form-control" value="{{ old('Propietario') }}">
                            </div>
                            <div class="col-md-6 poliza-field">
                                <label>Observaciones</label>
                                <textarea name="Observaciones" class="form-control" rows="2">{{ old('Observaciones') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach ($cesion_beneficios as $cesion)
        <div class="modal fade" id="modal-edit-cesion-{{ $cesion->Id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('poliza/seguro/cesion_beneficios_update') }}/{{ $cesion->Id }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Editar cesion de beneficios</h4></div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 poliza-field">
                                    <label>Cod. Cesion *</label>
                                    <input type="text" name="CodigoSesion" class="form-control" value="{{ $cesion->CodigoSesion }}" required>
                                </div>
                                <div class="col-md-8 poliza-field">
                                    <label>Beneficiario *</label>
                                    <input type="text" name="Beneficiario" class="form-control" value="{{ $cesion->Beneficiario }}" required>
                                </div>
                                <div class="col-md-4 poliza-field">
                                    <label>Fec. Vigencia</label>
                                    <input type="date" name="FechaVigencia" class="form-control" value="{{ $cesion->FechaVigencia }}">
                                </div>
                                <div class="col-md-4 poliza-field">
                                    <label>Fec. Cancelacion</label>
                                    <input type="date" name="FechaCancelacion" class="form-control" value="{{ $cesion->FechaCancelacion }}">
                                </div>
                                <div class="col-md-4 poliza-field">
                                    <label>Suma cedida</label>
                                    <input type="number" name="SumaCedida" class="form-control" step="0.01" min="0" value="{{ $cesion->SumaCedida }}">
                                </div>
                                <div class="col-md-6 poliza-field">
                                    <label>Propietario</label>
                                    <input type="text" name="Propietario" class="form-control" value="{{ $cesion->Propietario }}">
                                </div>
                                <div class="col-md-6 poliza-field">
                                    <label>Observaciones</label>
                                    <textarea name="Observaciones" class="form-control" rows="2">{{ $cesion->Observaciones }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-delete-cesion-{{ $cesion->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ url('poliza/seguro/cesion_beneficios_delete') }}/{{ $cesion->Id }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header"><h4 class="modal-title">Eliminar cesion de beneficios</h4></div>
                        <div class="modal-body">
                            <p>Confirme si desea eliminar la cesion:</p>
                            <strong>{{ $cesion->CodigoSesion }} - {{ $cesion->Beneficiario }}</strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modal-nuevo-certificado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('poliza/seguro/certificado_store') }}/{{ $poliza_seguro->Id }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h4 class="modal-title">Nuevo certificado #{{ $siguiente_certificado }}</h4></div>
                    <div class="modal-body">
                        <div class="row">
                            @php $campoValores = []; @endphp
                            @include('polizas.seguro.partials.certificado_campos_form')
                            <div class="col-md-12">
                                <label>Observacion</label>
                                <textarea name="Observacion" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach ($poliza_seguro->certificados as $certificado)
        @php
            $datosCertificadoEdit = json_decode($certificado->DatosJson ?: '[]', true);
            $campoValores = collect($datosCertificadoEdit)->pluck('Valor', 'CampoId')->all();
        @endphp
        <div class="modal fade" id="modal-edit-certificado-{{ $certificado->Id }}" tabindex="-1" role="dialog"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('poliza/seguro/certificado_update') }}/{{ $certificado->Id }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Editar certificado #{{ $certificado->NumeroCertificado }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @include('polizas.seguro.partials.certificado_campos_form')
                                <div class="col-md-12">
                                    <label>Observacion</label>
                                    <textarea name="Observacion" class="form-control" rows="2">{{ $certificado->Observacion }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade modal-dependientes" id="modal-dependiente-{{ $certificado->Id }}" tabindex="-1" role="dialog"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Dependientes del certificado {{ $certificado->NumeroCertificado }}</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ url('poliza/seguro/dependiente_store') }}/{{ $certificado->Id }}">
                            @csrf
                            <div class="row">
                                @php $campoValores = []; @endphp
                                @include('polizas.seguro.partials.certificado_campos_form')
                                <div class="col-md-12">
                                    <label>Observacion</label>
                                    <textarea name="Observacion" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div style="text-align:right; margin-top:12px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Guardar dependiente
                                </button>
                            </div>
                        </form>

                        <hr>
                        <h5>Dependientes registrados</h5>
                        <table id="dependientes-table-{{ $certificado->Id }}" class="table table-striped table-bordered datatable-dependientes" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Datos</th>
                                    <th>Observacion</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($certificado->dependientes as $dependiente)
                                    @php
                                        $datosDependiente = json_decode($dependiente->DatosJson ?: '[]', true);
                                        $campoValores = collect($datosDependiente)->pluck('Valor', 'CampoId')->all();
                                    @endphp
                                    <tr>
                                        <td>{{ $dependiente->NumeroDependiente }}</td>
                                        <td>
                                            @foreach (array_slice($datosDependiente, 0, 4) as $dato)
                                                <strong>{{ $dato['Etiqueta'] ?? '' }}:</strong> {{ $dato['Valor'] ?? '' }}<br>
                                            @endforeach
                                        </td>
                                        <td>{{ $dependiente->Observacion }}</td>
                                        <td style="text-align:center;">
                                            <div class="btn-inline-group">
                                                <button type="button" class="btn btn-warning btn-sm" onclick="toggleEditDependiente({{ $dependiente->Id }})">
                                                    <i class="fa fa-pencil"></i> Editar
                                                </button>
                                                <form method="POST" action="{{ url('poliza/seguro/dependiente_delete') }}/{{ $dependiente->Id }}">
                                                    @csrf
                                                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Confirme si desea eliminar este dependiente.');">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @foreach ($certificado->dependientes as $dependiente)
                            @php
                                $datosDependiente = json_decode($dependiente->DatosJson ?: '[]', true);
                                $campoValores = collect($datosDependiente)->pluck('Valor', 'CampoId')->all();
                            @endphp
                            <div id="dependiente-edit-row-{{ $dependiente->Id }}" class="dependiente-edit-row" style="display:none; margin-top:12px;">
                                <div class="dependiente-edit-box">
                                    <form method="POST" action="{{ url('poliza/seguro/dependiente_update') }}/{{ $dependiente->Id }}">
                                        @csrf
                                        <h5>Editar dependiente #{{ $dependiente->NumeroDependiente }}</h5>
                                        <div class="row">
                                            @include('polizas.seguro.partials.certificado_campos_form')
                                            <div class="col-md-12">
                                                <label>Observacion</label>
                                                <textarea name="Observacion" class="form-control" rows="2">{{ $dependiente->Observacion }}</textarea>
                                            </div>
                                        </div>
                                        <div class="btn-inline-group" style="margin-top:12px;">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Guardar cambios
                                            </button>
                                            <button type="button" class="btn btn-default" onclick="toggleEditDependiente({{ $dependiente->Id }})">
                                                Cerrar edicion
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modal-nuevo-cobertura" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('poliza/seguro/cobertura_store') }}/{{ $poliza_seguro->Id }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h4 class="modal-title">Nueva cobertura</h4></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Nombre</label>
                                <input type="text" name="Nombre" value="{{ old('Nombre') }}" class="form-control" onblur="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="col-sm-6">
                                <label>Tarificacion</label>
                                <select name="Tarificacion" class="form-control" required>
                                    <option value="0">Porcentual</option>
                                    <option value="1">Millar</option>
                                </select>
                            </div>
                            <div class="col-sm-6" style="margin-top:12px;">
                                <label>Descuento</label>
                                <select name="Descuento" class="form-control" required>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                            <div class="col-sm-6" style="margin-top:12px;">
                                <label>IVA</label>
                                <select name="Iva" class="form-control" required>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const desdeInput = document.getElementById('vigencia_desde');
        const hastaInput = document.getElementById('vigencia_hasta');
        const diasInput = document.getElementById('dias_vigencia');
        const productosCatalogo = @json($productosCatalogo);
        const planesCatalogo = @json($planesCatalogo);
        const aseguradorasCatalogo = @json($aseguradorasCatalogo);
        const aseguradoraActualId = "{{ $aseguradoraActual }}";
        const productoActualId = "{{ $productoActualId }}";
        const planActualId = "{{ $planActualId }}";

        function reiniciarSelect(selector, texto) {
            const select = $(selector);
            select.empty();
            select.append(new Option(texto, '', true, false));
            return select;
        }

        function cargarComisionRamo() {
            const option = $('#Ramo option:selected');
            const comision = option.data('comision');
            $('#PorcentajeComisionNR').val(comision || '');
        }

        function poblarAseguradoras(aseguradoraSeleccionada = '') {
            const ramo = $('#Ramo').val();
            const select = reiniciarSelect('#Aseguradora', 'Seleccione...');
            const aseguradorasPermitidas = [];

            productosCatalogo
                .filter(function(producto) {
                    return ramo && producto.ramo == ramo;
                })
                .forEach(function(producto) {
                    const aseguradoraId = String(producto.aseguradora);
                    if (aseguradoraId && aseguradorasPermitidas.indexOf(aseguradoraId) === -1) {
                        aseguradorasPermitidas.push(aseguradoraId);
                    }
                });

            aseguradorasCatalogo
                .filter(function(aseguradora) {
                    return aseguradorasPermitidas.indexOf(String(aseguradora.id)) !== -1;
                })
                .forEach(function(aseguradora) {
                    select.append(new Option(aseguradora.nombre, aseguradora.id, false, aseguradora.id == aseguradoraSeleccionada));
                });

            if (aseguradoraSeleccionada && aseguradorasPermitidas.indexOf(String(aseguradoraSeleccionada)) !== -1) {
                select.val(aseguradoraSeleccionada);
            } else {
                select.val('');
            }

            select.prop('disabled', !ramo).trigger('change.select2');
        }

        function poblarProductos(productoSeleccionado = '', planSeleccionado = '') {
            const ramo = $('#Ramo').val();
            const aseguradora = $('#Aseguradora').val();
            const select = reiniciarSelect('#Productos', 'Seleccione...');

            reiniciarSelect('#Planes', 'Seleccione...').trigger('change.select2');

            if (!ramo || !aseguradora) {
                select.val('').trigger('change.select2');
                return;
            }

            productosCatalogo
                .filter(function(producto) {
                    return producto.ramo == ramo && producto.aseguradora == aseguradora;
                })
                .forEach(function(producto) {
                    select.append(new Option(producto.nombre, producto.id, false, producto.id == productoSeleccionado));
                });

            select.val(productoSeleccionado || '').trigger('change.select2');
            poblarPlanes(productoSeleccionado || $('#Productos').val(), planSeleccionado);
        }

        function poblarPlanes(productoId = '', planSeleccionado = '') {
            const select = reiniciarSelect('#Planes', 'Seleccione...');

            planesCatalogo
                .filter(function(plan) {
                    return productoId && plan.producto == productoId;
                })
                .forEach(function(plan) {
                    select.append(new Option(plan.nombre, plan.id, false, plan.id == planSeleccionado));
                });

            select.val(planSeleccionado || '').trigger('change.select2');
        }

        function calcularDias() {
            const desde = new Date(desdeInput.value);
            const hasta = new Date(hastaInput.value);

            if (!isNaN(desde.getTime()) && !isNaN(hasta.getTime())) {
                const diferencia = Math.ceil((hasta - desde) / (1000 * 60 * 60 * 24));
                diasInput.value = diferencia >= 0 ? diferencia : 0;
            } else {
                diasInput.value = '';
            }
        }

        desdeInput.addEventListener('change', calcularDias);
        hastaInput.addEventListener('change', calcularDias);

        $('#Ramo').on('change', function() {
            cargarComisionRamo();
            poblarAseguradoras();
            poblarProductos();
        });

        $('#Aseguradora').on('change', function() {
            poblarProductos();
        });

        $('#Productos').on('change', function() {
            poblarPlanes($(this).val(), '');
        });

        $('#Cliente').on('change', function() {
            const cliente = $('#Cliente').val();

            if (!cliente) {
                $('#NumeroDocumento').val('');
                return;
            }

            $.ajax({
                type: 'get',
                url: "{{ url('get_cliente') }}",
                data: { Cliente: cliente },
                success: function(data) {
                    $('#NumeroDocumento').val(data.Nit || data.Dui || '');
                },
                error: function() {
                    toastr.error('Error al obtener los datos del cliente.');
                }
            });
        });

        $('#formPolizaSeguroEdit').on('submit', function() {
            $('#btnGuardarPoliza').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        });

        function toggleEditDependiente(id) {
            $('#dependiente-edit-row-' + id).slideToggle(150);
        }

        $(document).on('input', '.campo-validacion-solo-numeros', function() {
            this.value = this.value.replace(/\D+/g, '');
        });

        $(document).on('input', '.campo-validacion-solo-numeros-letras', function() {
            this.value = this.value.replace(/[^A-Za-z0-9]/g, '');
        });

        $(document).on('input', '.campo-validacion-solo-texto', function() {
            this.value = this.value.replace(/[^\p{L}\s\.,#\-\/()&@'":;]/gu, '');
        });

        $(function() {
            if ($.fn.inputmask) {
                $('[data-inputmask]').inputmask();
            }

            const tablaCertificados = $('#tabla-certificados').DataTable({
                pageLength: 25,
                scrollX: true,
                autoWidth: false,
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: [0, -1] }
                ],
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Sin registros',
                    emptyTable: 'Sin certificados registrados.',
                    zeroRecords: 'No se encontraron certificados',
                    paginate: {
                        first: 'Primero',
                        last: 'Ultimo',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });

            $('#tabla-certificados tbody').on('click', '.toggle-certificado-dependientes', function() {
                const button = $(this);
                const tr = button.closest('tr');
                const row = tablaCertificados.row(tr);
                const certificadoId = button.data('certificado');
                const template = $('#dependientes-template-' + certificadoId).html();

                if (!template) {
                    return;
                }

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    button.find('i').removeClass('fa-minus').addClass('fa-plus');
                } else {
                    row.child(template).show();
                    tr.addClass('shown');
                    button.find('i').removeClass('fa-plus').addClass('fa-minus');
                }

                tablaCertificados.columns.adjust();
            });

            if (!$.fn.DataTable.isDataTable('#tabla-cesiones')) {
                $('#tabla-cesiones').DataTable({
                    pageLength: 10,
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Sin registros',
                        zeroRecords: 'No se encontraron cesiones',
                        paginate: {
                            first: 'Primero',
                            last: 'Ultimo',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            }

            if (!$.fn.DataTable.isDataTable('#tabla-beneficiarios')) {
                $('#tabla-beneficiarios').DataTable({
                    pageLength: 10,

                    // Scroll horizontal
                    scrollX: true,

                    // Scroll vertical con alto mínimo/visible de 400px
                    scrollY: '400px',
                    scrollCollapse: true,

                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                        infoEmpty: 'Sin registros',
                        zeroRecords: 'No se encontraron beneficiarios',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            }

            $('.datatable-dependientes').DataTable({
                pageLength: 5,
                lengthMenu: [[5, 10, 25, -1], [5, 10, 25, 'Todos']],
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Sin registros',
                    zeroRecords: 'No se encontraron dependientes',
                    paginate: {
                        first: 'Primero',
                        last: 'Ultimo',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });

            $('.modal-dependientes').on('shown.bs.modal', function() {
                $('[data-mask]').inputmask();
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });

            $('a[data-toggle="tab"][href="#certificados"]').on('shown.bs.tab', function() {
                tablaCertificados.columns.adjust();
            });
        });

        poblarAseguradoras(aseguradoraActualId);
        poblarProductos(productoActualId, planActualId);
        calcularDias();
    </script>
@else
    <p class="text-center text-danger">No tiene permiso para ver.</p>
@endcan
@endsection
