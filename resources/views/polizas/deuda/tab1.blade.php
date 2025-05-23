<style>
    .table-simulated {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        border: 1px solid #dee2e6;
        border-radius: 5px;
        overflow: hidden;
    }

    .table-header {
        display: contents;
        background-color: #343a40 !important;
        color: rgb(122, 122, 122);
        font-weight: bold;
    }

    .table-header div {
        padding: 10px;
        border-bottom: 2px solid #dee2e6;
        text-align: center;
    }

    .table-row {
        display: contents;
        border-bottom: 1px solid #dee2e6;
    }

    .table-row div {
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
        text-align: center;
    }

    .table-row:nth-child(even) {
        background-color: #f8f9fa;
    }
</style>


<style>
    .expand-icon {
        cursor: pointer;
        margin-right: 8px;
    }

    .warning-row {
        border-left: 5px solid #ffc107;
        /* Naranja (warning) */
    }

    .primary-row {
        border-left: 5px solid #007bff;
        /* Azul (primary) */
    }
</style>


<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Datos de Póliza <small></small></h2>
                <div class="nav navbar-right panel_toolbox">


                    <a href="" data-target="#modal-techo" data-toggle="modal" class="btn btn-default">Aumentar
                        Techo</a>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                        id="modal-techo">

                        <form method="POST" action="{{ url('poliza/deuda/aumentar_techo') }}">
                            @method('POST')
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                                        <h4 class="modal-title">Aumentar Techo de Poliza</h4>
                                    </div>
                                    <div class="modal-body">

                                        <label class="control-label">Responsabilidad Máxima *</label>
                                        <div class=" form-group has-feedback">
                                            <input type="number" step="any" name="ResponsabilidadMaxima"
                                                id="ResponsabilidadMaxima" style="padding-left: 15%;display: none;"
                                                value="{{ $deuda->ResponsabilidadMaxima }}" class="form-control"
                                                required onblur="ResponsabilidadMax(this.value)">
                                            <input type="text" step="any"
                                                style="padding-left: 15%; display: block;"
                                                id="ResponsabilidadMaximaTexto"
                                                value="{{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }}"
                                                class="form-control" required
                                                onfocus="ResponsabilidadMaxTexto(this.value)">
                                            <span class="fa fa-dollar form-control-feedback left"
                                                aria-hidden="true"></span>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- <ul class="nav navbar-right panel_toolbox">
                    <li style="margin-left: 55px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul> -->
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="x_content" style="font-size: 12px;">
                    <div class="col-sm-12 row">
                        <div class="col-sm-4">
                            <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                            <label class="control-label" align="right">Número de Póliza</label>
                            <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                value="{{ $deuda->NumeroPoliza }}" readonly>
                        </div>

                        <div class="col-sm-4">&nbsp;</div>

                        <div class="col-sm-4" style="display: none !important;">
                            <label class="control-label" align="right">Código</label>
                            <input class="form-control" name="Codigo" type="text" value="{{ $deuda->Codigo }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <label class="control-label" align="right">Aseguradora</label>
                        <input type="text" name="Aseguradora" id="Aseguradora" class="form-control"
                            value="{{ $deuda->aseguradoras->Nombre }}" readonly>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Productos</label>
                        <input type="text" name="Productos" id="Productos" class="form-control"
                            value="{{ $deuda->planes && $deuda->planes->productos ? $deuda->planes->productos->Nombre : '' }}"
                            readonly>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Planes</label>
                        <input type="text" name="Planes" id="Planes" class="form-control"
                            value="{{ $deuda->planes ? $deuda->planes->Nombre : '' }}" readonly>
                    </div>
                    <div class="col-sm-8">
                        <label class="control-label" align="right">Asegurado</label>
                        <input type="text" name="Asegurado" id="Asegurado" class="form-control"
                            value="{{ $deuda->clientes->Nombre }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Nit</label>
                        <input class="form-control" name="Nit" id="Nit" type="text"
                            value="{{ $deuda->Nit }}" readonly>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Vigencia Desde</label>
                        <input class="form-control" name="VigenciaDesde" id="VigenciaDesde" type="date"
                            value="{{ $deuda->VigenciaDesde }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Vigencia Hasta</label>
                        <input class="form-control" name="VigenciaHasta" id="VigenciaHasta" type="date"
                            value="{{ $deuda->VigenciaHasta }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Estatus</label>
                        <input type="text" name="EstadoPoliza" class="form-control"
                            value="{{ $deuda->estadoPolizas->Nombre }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Ejecutivo</label>
                        <input type="text" name="Ejecutivo" class="form-control"
                            value="{{ $deuda->ejecutivos->Nombre }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Descuento de Rentabilidad %</label>
                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento"
                            value="{{ $deuda->Descuento }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Edad Máxima de Terminación</label>
                        <input type="text" class="form-control" value="{{ $deuda->EdadMaximaTerminacion }}"
                            readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Responsabilidad Máxima *</label>
                        <div class=" form-group has-feedback">
                            <input type="text" step="any" style="padding-left: 15%; display: block;" readonly
                                id="ResponsabilidadMaximaTexto"
                                value="{{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }}"
                                class="form-control" required>
                            <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label " align="right">Clausulas Especiales</label>
                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" readonly>{{ $deuda->ClausulasEspeciales }} </textarea>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Beneficios Adicionales</label>
                        <textarea class="form-control" name="Beneficios" row="3" col="4" readonly>{{ $deuda->Beneficios }} </textarea>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Concepto</label>
                        <textarea class="form-control" name="Concepto" row="3" col="4" readonly>{{ $deuda->Concepto }}</textarea>
                    </div>
                    <div class="col-sm-4 ocultar" style="display: none !important;">
                        <br>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                {{ $deuda->Mensual == 1 ? 'checked' : '' }}>
                            <label class="control-label">Tasa Millar Mensual</label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="radio" name="tipoTasa" id="Anual" value="0"
                                {{ $deuda->Mensual == 0 ? 'checked' : '' }}>
                            <label class="control-label">Tasa ‰ Millar Anual</label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Tasa Millar Mensual</label>
                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any"
                            value="{{ $deuda->Tasa }}" readonly>
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label" align="right">% de Comisión </label>
                        <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                            step="any" value="{{ $deuda->TasaComision }}" readonly>
                    </div>
                    <div class="col-sm-2"><br>
                        <label class="control-label" align="right">¿IVA incluido?</label>
                        <input type="checkbox" readonly class="js-switch"
                            {{ $deuda->ComisionIva == 1 ? 'checked' : '' }}>
                        <input type="hidden" name="ComisionIva" id="ComisionIva"
                            value="{{ $deuda->ComisionIva }}">
                    </div>
                    <div class="col-sm-2"><br>
                        <label class="control-label" align="right">Cobro con tarifa excel&nbsp;</label>
                        <input name="TarifaExcel" type="checkbox" class="js-switch" {{ $deuda->TarifaExcel == 1 ? 'checked' : '' }}>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-6">
                        <div>
                            <label class="control-label">Póliza Vida</label>
                            <select class="form-control" name="PolizaVida">
                                <option value="" selected>SELECCIONE</option>
                                @foreach ($polizas_vida as $obj)
                                    <option value="{{ $obj->Id }}"
                                        {{ $deuda->PolizaVida == $obj->Id ? 'selected' : '' }}>{{ $obj->Codigo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>


                    <div class="col-sm-6">
                        <div>
                            <label class="control-label">Póliza Desempleo</label>
                            <select class="form-control" name="PolizaDesempleo">
                                <option value="" selected>SELECCIONE</option>
                                @foreach ($polizas_desempleo as $obj)
                                    <option value="{{ $obj->Id }}"
                                        {{ $deuda->PolizaDesempleo == $obj->Id ? 'selected' : '' }}>
                                        {{ $obj->NumeroPoliza }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Tasa Diferenciada <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="margin-left: 55px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <br>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Línea de Crédito</th>
                                    <th>Saldos y Montos</th>
                                    <th>Tasa General</th>

                                    <th>Monto Máximo</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
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
                                                            {{ 'No aplica' }}
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



                                {{-- @foreach ($creditos as $obj)
                                <tr>
                                    <td>{{ $obj->TipoCartera == null ? '' : $obj->tipoCarteras->Nombre }}
                                    </td>
                                    <td>{{ $obj->Saldos == null ? '' : $obj->saldos->Abreviatura }}</td>
                                    <td>{{ $deuda->Tasa }}</td>

                                    <td>{{ isset($obj->MontoMaximoIndividual) ? '$' . number_format($obj->MontoMaximoIndividual, 2, '.', ',') : '' }}
                                    </td>
                                    <td>

                                        <a href=""
                                            data-target="#modal-creditos-show-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-eye fa-lg"></i></a>

                                        &nbsp;&nbsp;
                                        <a
                                            href="{{ url('polizas/deuda/tasa_diferenciada') }}/{{ $obj->Id }}"><i
                                                class="fa fa-edit fa-lg"></i></a>


                                        &nbsp;&nbsp;
                                        <a href="" data-target="#modal-delete-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade modal-slide-in-right" aria-hidden="true"
                                    role="dialog" tabindex="-1" id="modal-delete-{{ $obj->Id }}">

                                    <form method="POST"
                                        action="{{ url('eliminar_credito', $obj->Id) }}">
                                        @method('POST')
                                        @csrf
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close"
                                                        data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title">Eliminar Registro</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirme si desea Eliminar el Registro</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Cerrar</button>
                                                    <button type="submit"
                                                        class="btn btn-primary">Confirmar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>


                                @include('polizas.deuda.tasa_diferenciada_modal_show')
                                @endforeach --}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Tabla de Asegurabilidad <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li style="margin-left: 55px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-striped table-bordered">
                        @for ($i = 0; $i < count($data); $i++)
                            <tr style="width: 25%;">
                                @for ($j = 0; $j < count($data[0]); $j++)
                                    <td> {{ $data[$i][$j] }}</td>
                                @endfor
                            </tr>

                        @endfor

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
