@extends ('welcome')
@section('contenido')


    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="x_title">
                    <h2>Pólizas / Deuda / Póliza de deuda / {{ $deuda->NumeroPoliza }}
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        @if ($deuda->Configuracion == 0)
                            <a href="" data-target="#modal-finalizar" data-toggle="modal"
                                class="btn btn-success">Finalizar <br> Configuración</a>
                        @else
                            <a href="" data-target="#modal-finalizar" data-toggle="modal"
                                class="btn btn-primary">Apertura <br> Configuración</a>
                        @endif
                    </ul>
                    <div class="clearfix"></div>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                        id="modal-finalizar">

                        <form method="POST" action="{{ url('finalizar_configuracion') }}">
                            @method('POST')
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <input type="hidden" name="deuda" value="{{ $deuda->Id }}">
                                        <h4 class="modal-title">{{ $deuda->Configuracion == 0 ? 'Finalizar' : 'Aperturar' }}
                                            Configuración</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Confirme si desea {{ $deuda->Configuracion == 0 ? 'finalizar' : 'aperturar' }} la
                                            configuración de la poliza</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                @if (session('success'))
                    <script>
                        toastr.success("{{ session('success') }}");
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        toastr.error("{{ session('error') }}");
                    </script>
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ $tab == 1 ? 'active' : '' }}"><a href="#tab_content1"
                                id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                        </li>
                        <li role="presentation" class="{{ $tab == 4 ? 'active' : '' }}"><a href="#tab_content4"
                                id="renovacion-tab" role="tab" data-toggle="tab" aria-expanded="true">Renovación </a>
                        </li>
                        <li role="presentation" class="{{ $tab == 2 ? 'active' : '' }} "><a href="#tab_content2"
                                id="lineas-tab" role="tab" data-toggle="tab" aria-expanded="true">Tasa diferencia</a>
                        </li>
                        <li role="presentation" class="{{ $tab == 3 ? 'active' : '' }}"><a href="#tab_content3"
                                id="asegurabilidad-tab" role="tab" data-toggle="tab" aria-expanded="true">Requisitos
                                Mínimos de Asegurabilidad </a>
                        </li>



                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }}" id="tab_content1"
                            aria-labelledby="home-tab">
                            <form method="POST" action="{{ route('deuda.update', $deuda->Id) }}">
                                @method('PUT')
                                @csrf

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                                    <div class="col-sm-12 row">
                                        <div class="col-sm-4">
                                            <input type="hidden" value="{{ old('Deuda', $deuda->Id) }}" name="Deuda">
                                            <label class="control-label" align="right">Número de Póliza *</label>
                                            <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                                value="{{ old('NumeroPoliza', $deuda->NumeroPoliza) }}" required>
                                        </div>

                                        <div class="col-sm-4">&nbsp;</div>

                                        <div class="col-sm-4" style="display: none !important;">
                                            <label class="control-label" align="right">Código *</label>
                                            <input class="form-control" name="Codigo" type="text"
                                                value="{{ old('Codigo', $deuda->Codigo) }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Aseguradora *</label>
                                        <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Aseguradora', $deuda->Aseguradora) == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label class="control-label">Productos *</label>
                                        <select name="Productos" id="Productos" class="form-control select2"
                                            style="width: 100%">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($productos as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Productos', optional($deuda->planes)->Producto) == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label class="control-label">Planes *</label>
                                        <select name="Planes" id="Planes" class="form-control select2"
                                            style="width: 100%">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($planes as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Planes', $deuda->Plan) == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Asegurado *</label>
                                        <select name="Asegurado" id="Asegurado" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($cliente as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Asegurado', $deuda->Asegurado) == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">DUI / NIT *</label>
                                        <input class="form-control" name="Nit" id="Nit" type="text"
                                            value="{{ old('Nit', $deuda->Nit) }}" readonly>
                                    </div>

                                    <div class="col-sm-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Desde *</label>
                                        <input class="form-control" name="VigenciaDesde" type="date"
                                            value="{{ old('VigenciaDesde', $deuda->VigenciaDesde) }}" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Hasta *</label>
                                        <input class="form-control" name="VigenciaHasta" type="date"
                                            value="{{ old('VigenciaHasta', $deuda->VigenciaHasta) }}" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Estado *</label>
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
                                            @foreach ($estadoPoliza as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ $deuda->EstadoPoliza == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Ejecutivo *</label>
                                        <select name="Ejecutivo" class="form-control select2" style="width: 100%"
                                            required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($ejecutivo as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ old('Ejecutivo', $deuda->Ejecutivo) == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Descuento de Rentabilidad
                                            *</label>
                                        <input class="form-control" name="Descuento" type="number" step="any"
                                            id="Descuento" value="{{ old('Descuento', $deuda->Descuento) }}" required>
                                    </div>

                                    <div class="col-sm-4">&nbsp;</div>
                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Edad Máxima Terminación *</label>
                                        <input type="number" name="EdadMaximaTerminacion" class="form-control" required
                                            value="{{ old('EdadMaximaTerminacion', $deuda->EdadMaximaTerminacion) }}">
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label">Responsabilidad Máxima *</label>
                                        <div class="form-group has-feedback position-relative">
                                            <input type="text" name="ResponsabilidadMaxima"
                                                id="ResponsabilidadMaximaTexto" class="form-control"
                                                style="padding-left: 15%; display: block;" required
                                                value="{{ old('ResponsabilidadMaxima', number_format($deuda->ResponsabilidadMaxima, 2, '.', ',')) }}"
                                                oninput="this.value = this.value.replace(/[^0-9.,]/g, '')"
                                                onblur="formatearCantidad(this)" autocomplete="off">
                                            <span class="fa fa-dollar form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>
                                    </div>



                                    <div class="col-sm-4">&nbsp;</div>
                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Clausulas Especiales</label>
                                        <textarea class="form-control" name="ClausulasEspeciales" rows="3">{{ old('ClausulasEspeciales', $deuda->ClausulasEspeciales) }}</textarea>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Beneficios Adicionales</label>
                                        <textarea class="form-control" name="Beneficios" rows="3">{{ old('Beneficios', $deuda->Beneficios) }}</textarea>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Concepto</label>
                                        <textarea class="form-control" name="Concepto" rows="3">{{ old('Concepto', $deuda->Concepto) }}</textarea>
                                    </div>

                                    <div class="col-sm-4 ocultar" style="display: none !important;">
                                        <br>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                                {{ old('tipoTasa', $deuda->Mensual) == 1 ? 'checked' : '' }}>
                                            <label class="control-label">Tasa Millar Mensual *</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Anual" value="0"
                                                {{ old('tipoTasa', $deuda->Mensual) == 0 ? 'checked' : '' }}>
                                            <label class="control-label">Tasa ‰ Millar Anual *</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">&nbsp;</div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Tasa Millar Mensual *</label>
                                        <input class="form-control" name="Tasa" type="number" id="Tasa"
                                            step="any" value="{{ old('Tasa', $deuda->Tasa) }}" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">% de Comisión *</label>
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                            step="any" value="{{ old('TasaComision', $deuda->TasaComision) }}">
                                    </div>

                                    <div class="col-sm-2"><br>
                                        <label class="control-label" align="right">¿IVA incluído?</label>
                                        <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                                            {{ old('ComisionIva', $deuda->ComisionIva) == 1 ? 'checked' : '' }}>
                                    </div>

                                    <div class="col-sm-2"><br>
                                        <label class="control-label" align="right">Cobro con tarifa
                                            excel&nbsp;</label>
                                        <input name="TarifaExcel" type="checkbox" class="js-switch"
                                            {{ old('TarifaExcel', $deuda->TarifaExcel) == 1 ? 'checked' : '' }}>
                                    </div>

                                    <!-- Póliza Vida -->
                                    <div class="col-sm-6">
                                        <div>
                                            <label class="control-label">Póliza Vida</label>
                                            <select class="form-control" name="PolizaVida">
                                                <option value="" selected>SELECCIONE</option>
                                                @foreach ($polizas_vida as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ old('PolizaVida', $deuda->PolizaVida) == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->NumeroPoliza }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Póliza Desempleo -->
                                    <div class="col-sm-6">
                                        <div>
                                            <label class="control-label">Póliza Desempleo</label>
                                            <select class="form-control" name="PolizaDesempleo">
                                                <option value="" selected>SELECCIONE</option>
                                                @foreach ($polizas_desempleo as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ old('PolizaDesempleo', $deuda->PolizaDesempleo) == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->NumeroPoliza }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">&nbsp;</div>
                                </div>



                                <br>

                                <div class="form-group row" style="text-align: center">
                                    <button type="submit" class="btn btn-success"
                                        {{ $deuda->Configuracion == 1 ? 'disabled' : '' }}>Guardar y
                                        Continuar</button>
                                    <a href="{{ url('polizas/deuda') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>

                            </form>

                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="tab_content2"
                            aria-labelledby="lineas-tab">


                            <div class="x_title">

                                <ul class="nav navbar-right panel_toolbox">
                                    <a @if ($deuda->Configuracion != 1) href="{{ url('polizas/deuda/tasa_diferenciada') }}/{{ $deuda->Id }}" @endif
                                        class="btn btn-primary @if ($deuda->Configuracion == 1) disabled @endif">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </ul>
                                <div class="clearfix"></div>

                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <table width="100%" class="table table-striped">
                                    {{-- <thead>
                                            <tr>
                                                <th>Línea de Crédito</th>
                                                <th>Saldos y Montos</th>
                                                <th>Tasa General</th>

                                                <th>Monto Máximo</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead> --}}
                                    <tbody>

                                        @if ($deuda->deuda_tipos_cartera->count() > 0)
                                            <table class="table table-bordered">
                                                <thead class="table-dark">
                                                    <tr class="warning-row">
                                                        <th style="width: 40%;">Tipo cartera</th>
                                                        <th style="width: 20%;">Tipo cálculo</th>
                                                        <th style="width: 20%;">Monto máximo individual</th>
                                                    </tr>
                                                </thead>
                                                <tbody>



                                                    @foreach ($deuda->deuda_tipos_cartera as $tipo)
                                                        <tr class="tarea warning-row">
                                                            <td>
                                                                <span class="expand-icon">▼</span>
                                                                {{ $tipo->tipo_cartera?->Nombre ?? '' }}
                                                            </td>
                                                            <td>
                                                                @if ($tipo->TipoCalculo == 1)
                                                                    {{ 'Fecha' }}
                                                                @elseif ($tipo->TipoCalculo == 2)
                                                                    {{ 'Edad' }}
                                                                @else
                                                                    {{ '' }}
                                                                @endif
                                                            </td>
                                                            <td class="text-end">
                                                                ${{ $tipo->MontoMaximoIndividual }}
                                                            </td>
                                                        </tr>

                                                        <tr class="subtareas-container">
                                                            <td colspan="4" style="background-color: #f8fafc;">

                                                                @if ($tipo->tasa_diferenciada->count() > 0)
                                                                    <br>
                                                                    <div
                                                                        style="padding-left: 20px !important; padding-right: 20px !important;">
                                                                        <table class="table table-sm table-bordered">
                                                                            <thead class="table-light">
                                                                                <tr class="primary-row">
                                                                                    <th>Linea credito</th>
                                                                                    @if ($tipo->TipoCalculo == 1)
                                                                                        <th>Fecha inicio</th>
                                                                                        <th>Fecha final</th>
                                                                                    @endif

                                                                                    @if ($tipo->TipoCalculo == 2)
                                                                                        <th>Edad inicio</th>
                                                                                        <th>Edad final</th>
                                                                                    @endif
                                                                                    <th>Tasa</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($tipo->tasa_diferenciada as $tasa_diferenciada)
                                                                                    <tr class="primary-row">
                                                                                        <td>
                                                                                            {{ $tasa_diferenciada->linea_credito?->Abreviatura ?? '' }}
                                                                                            -
                                                                                            {{ $tasa_diferenciada->linea_credito?->Descripcion ?? '' }}
                                                                                        </td>
                                                                                        @if ($tipo->TipoCalculo == 1)
                                                                                            <td>
                                                                                                {{ $tasa_diferenciada->FechaDesde ? date('d/m/Y', strtotime($tasa_diferenciada->FechaDesde)) : 'Sin fecha' }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $tasa_diferenciada->FechaHasta ? date('d/m/Y', strtotime($tasa_diferenciada->FechaHasta)) : 'Sin fecha' }}
                                                                                            </td>
                                                                                        @endif

                                                                                        @if ($tipo->TipoCalculo == 2)
                                                                                            <td>{{ $tasa_diferenciada->EdadDesde }}
                                                                                                Años</td>
                                                                                            <td>{{ $tasa_diferenciada->EdadHasta }}
                                                                                                Años</td>
                                                                                        @endif

                                                                                        <td>{{ $tasa_diferenciada->Tasa }}%
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @endif


                                                                <br>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @else
                                            <div class="alert alert-warning">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close"><span aria-hidden="true">×</span>
                                                </button>

                                                <strong>No hay datos</strong>
                                            </div>
                                        @endif

                                    </tbody>
                                </table>


                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 3 ? 'active in' : '' }}" id="tab_content3"
                            aria-labelledby="asegurabilidad-tab">

                            <div class="x_title">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h4>Tabla de requisitos </h4>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                                    <button class="btn btn-info float-right"
                                        {{ $deuda->Configuracion == 1 ? 'disabled' : '' }}
                                        data-target="#modal-add-requisito" data-toggle="modal">
                                        <i class="fa fa-plus"></i>
                                        Nuevo</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>



                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <table class="table table-bordered table-hover" style="font-size: 14px;">
                                    <thead class="thead-dark">
                                        <tr class="text-center">
                                            <th style="vertical-align: middle; width: 50%;">REQUISITOS</th>
                                            @foreach ($columnas as $columna)
                                                <th style="vertical-align: middle;">
                                                    DESDE {{ explode('-', $columna)[0] }} AÑOS <br>
                                                    HASTA {{ explode('-', $columna)[1] }} AÑOS
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tabla as $perfil => $montos)
                                            <tr>
                                                <td class="font-weight-bold align-middle">{{ $perfil }}</td>
                                                @foreach ($columnas as $columna)
                                                    @php
                                                        $monto = $montos[$columna]['monto'] ?? null;
                                                        $requisitoId = $montos[$columna]['id'] ?? null;
                                                        $perfilId = $montos[$columna]['perfilId'] ?? null;
                                                        $edades = explode('-', $columna);
                                                        $edadInicial = $edades[0];
                                                        $edadFinal = $edades[1];
                                                    @endphp
                                                    <td class="align-middle">
                                                        @if ($monto)
                                                            @php
                                                                [$montoInicial, $montoFinal] = explode('-', $monto);
                                                            @endphp
                                                            <div class="mb-2 text-center">
                                                                <span class="text-success font-weight-bold">
                                                                    Desde ${{ number_format($montoInicial, 2) }}
                                                                </span><br>
                                                                <span class="text-success font-weight-bold">
                                                                    Hasta ${{ number_format($montoFinal, 2) }}
                                                                </span>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <button class="btn btn btn-outline-primary"
                                                                    {{ $deuda->Configuracion == 1 ? 'disabled' : '' }}
                                                                    data-toggle="modal"
                                                                    data-target="#modal-requisito-{{ $requisitoId }}">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn btn-outline-danger"
                                                                    {{ $deuda->Configuracion == 1 ? 'disabled' : '' }}
                                                                    data-toggle="modal"
                                                                    data-target="#modal-delete-{{ $requisitoId }}">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                            @include('polizas.deuda.modal_edit_requisito')
                                                            @include('polizas.deuda.modal_delete_requisito')
                                                        @else
                                                            <div class="text-muted text-center">-</div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>






                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 4 ? 'active in' : '' }}" id="tab_content4"
                            aria-labelledby="renovacion-tab">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                @if ($historico_poliza->count() > 0)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tipo Renovacion</th>
                                                <th>Vigencia Desde</th>
                                                <th>Vigencia Hasta</th>
                                                <!-- <th style="width: 30%;">Opciones</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($registroInicial)
                                                <tr>
                                                    <td>Registro inicial</td>
                                                    <td>{{ $registroInicial->VigenciaDesde ? date('d/m/Y', strtotime($registroInicial->VigenciaDesde)) : '' }}
                                                    </td>
                                                    <td>{{ $registroInicial->VigenciaHasta ? date('d/m/Y', strtotime($registroInicial->VigenciaHasta)) : '' }}
                                                    </td>
                                                </tr>
                                            @endif

                                            @foreach ($historico_poliza as $obj)
                                                <tr
                                                    @if ($obj->TipoRenovacion == 1) style="background-color: #e8f5ee;" @endif>
                                                    <td>{{ $obj->TipoRenovacion == 1 ? 'Anual' : 'Parcial' }}</td>
                                                    <td>{{ $obj->VigenciaDesde ? date('d/m/Y', strtotime($obj->VigenciaDesde)) : '' }}
                                                    </td>
                                                    <td>{{ $obj->VigenciaHasta ? date('d/m/Y', strtotime($obj->VigenciaHasta)) : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <br>
                                    <div class="alert alert-danger alert-dismissible " role="alert">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Sin registros</strong>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-add-requisito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('datos_asegurabilidad') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar requisito</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                                <div class="form-group row">
                                    <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                                    <label class="control-label">Perfiles
                                        médicos</label>

                                    <select name="Perfiles" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($perfiles as $obj)
                                            <option value="{{ $obj->Id }}">
                                                {{ $obj->Codigo }} - {{ $obj->Descripcion }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group row">
                                    <dv class="col-md-6">
                                        <label class="control-label">Edad
                                            inicial</label>
                                        <input class="form-control" id="EdadInicial" value="18" type="text"
                                            name="EdadInicial" required min="18">
                                    </dv>
                                    <dv class="col-md-6">
                                        <label class="control-label">Edad
                                            final</label>
                                        <input class="form-control" id="EdadFinal" name="EdadFinal" type="number"
                                            required>
                                    </dv>


                                </div>
                                <div class="form-group row">
                                    <dv class="col-md-6">
                                        <label class="control-label">Monto
                                            inicial</label>
                                        <input class="form-control" id="MontoInicial" step="0.01" type="number"
                                            name="MontoInicial" required>
                                    </dv>
                                    <dv class="col-md-6">
                                        <label class="control-label">Monto
                                            final</label>
                                        <input class="form-control" id="MontoFinal" step="0.01" type="number"
                                            name="MontoFinal" required>
                                    </dv>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer" align="center">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");


            $("#Asegurado").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var parametros = {
                    "Cliente": document.getElementById('Asegurado').value
                };
                $.ajax({
                    type: "GET",
                    url: "{{ url('get_cliente') }}",
                    data: parametros,
                    success: function(data) {
                        console.log(data);
                        document.getElementById('Nit').value = data.Nit;
                    }
                });
            });


            $("#Aseguradora").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var Aseguradora = $(this).val();

                $.get("{{ url('get_producto') }}" + '/' + Aseguradora, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = '<option value=""> Seleccione </option>';
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Productos").html(_select);
                });
            })

            $("#Productos").change(function() {
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var Productos = $(this).val();

                $.get("{{ url('get_plan') }}" + '/' + Productos, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    var _select = '<option value=""> Seleccione </option>';
                    for (var i = 0; i < data.length; i++)
                        _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                        '</option>';
                    $("#Planes").html(_select);
                });
            })


        });

        function formatearCantidad(input) {
            let valor = input.value.trim();

            if (!valor) return;

            // Contar cantidad de puntos y comas
            const puntos = (valor.match(/\./g) || []).length;
            const comas = (valor.match(/,/g) || []).length;

            // Si no hay separador decimal, quitar todas las comas y puntos
            // o si hay varios puntos y comas, alertar error
            if (puntos + comas > 1) {
                // Asumiremos que el separador decimal es el último de los dos caracteres (coma o punto)
                let ultimoSeparadorIndex = Math.max(valor.lastIndexOf('.'), valor.lastIndexOf(','));
                let separadorDecimal = valor.charAt(ultimoSeparadorIndex);

                // Limpiar separadores de miles (todos menos el último separador decimal)
                let parteEntera = valor.slice(0, ultimoSeparadorIndex).replace(/[.,]/g, '');
                let parteDecimal = valor.slice(ultimoSeparadorIndex + 1);

                // Reconstruir valor estándar, cambiando separador decimal a punto
                valor = parteEntera + '.' + parteDecimal;
            } else {
                // Si sólo hay uno o ninguno separador, sustituimos coma por punto para decimal
                valor = valor.replace(',', '.').replace(/,/g, '');
            }

            // Validar que ahora solo hay un punto decimal
            const partes = valor.split('.');
            if (partes.length > 2) {
                toastr.error('Cantidad inválida: múltiples separadores decimales.', 'Error');
                input.value = "";
                return;
            }

            // Validar que sólo haya dígitos en partes
            if (!partes.every(p => /^\d*$/.test(p))) {
                toastr.error('Cantidad inválida: contiene caracteres no numéricos.', 'Error');
                input.value = "";
                return;
            }

            let numero = parseFloat(valor);
            if (isNaN(numero)) {
                toastr.error('Cantidad inválida.', 'Error');
                input.value = "";
                return;
            }

            // Formatear con miles (coma) y punto decimal
            input.value = numero.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    </script>






@endsection
