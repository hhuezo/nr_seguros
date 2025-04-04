@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="x_title">
                <h2>Pólizas / Deuda / Póliza de deuda / Nueva póliza<small></small>
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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{ session('tab') == 1 ? 'active' : '' }}"><a href="#tab_content1"
                            id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                    </li>
                    <li role="presentation" class="{{ session('tab') == 4 ? 'active' : '' }}"><a href="#tab_content4"
                            id="renovacion-tab" role="tab" data-toggle="tab" aria-expanded="true">Renovación </a>
                    </li>
                    <li role="presentation" class="{{ session('tab') == 2 ? 'active' : '' }} "><a href="#tab_content2"
                            id="lineas-tab" role="tab" data-toggle="tab" aria-expanded="true">Tasa diferencia</a>
                    </li>
                    <li role="presentation" class="{{ session('tab') == 3 ? 'active' : '' }}"><a href="#tab_content3"
                            id="asegurabilidad-tab" role="tab" data-toggle="tab" aria-expanded="true">Requisitos
                            Mínimos de Asegurabilidad </a>
                    </li>



                </ul>

                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab') == 1 ? 'active in' : '' }}"
                        id="tab_content1" aria-labelledby="home-tab">
                        <form action="{{ url('polizas/deuda/renovar') }}" method="POST">
                            @csrf

                            <div class="x_content" style="font-size: 12px;">
                                <div class="col-sm-12 row">
                                    <div class="col-sm-4">
                                        <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                                        <label class="control-label" align="right">Número de Póliza *</label>
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                            value="{{ $deuda->NumeroPoliza }}" readonly>
                                    </div>

                                    <div class="col-sm-4">&nbsp;</div>

                                    <div class="col-sm-4" style="display: none !important;">
                                        <label class="control-label" align="right">Código *</label>
                                        <input class="form-control" name="Codigo" type="text"
                                            value="{{ $deuda->Codigo }}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" align="right">Aseguradora *</label>
                                    <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="">Seleccione...</option>
                                        @foreach ($aseguradora as $obj)
                                        @if ($obj->Id == $deuda->Aseguradora)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Productos *</label>
                                    <select name="Productos" id="Productos" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $deuda->planes && $obj->Id == $deuda->planes->Producto ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Planes *</label>
                                    <select name="Planes" id="Planes" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($planes as $obj)
                                        @if ($obj->Id == $deuda->Plan)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" align="right">Asegurado *</label>
                                    <select name="Asegurado" id="Asegurado" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="">Seleccione...</option>
                                        @foreach ($cliente as $obj)
                                        @if ($obj->Id == $deuda->Asegurado)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">DUI / NIT *</label>
                                    <input class="form-control" name="Nit" id="Nit" type="text"
                                        value="{{ $deuda->Nit }}" readonly>
                                </div>

                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Desde *</label>
                                    <input class="form-control" name="VigenciaDesde" type="date"
                                        value="{{ $deuda->VigenciaDesde }}" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Hasta *</label>
                                    <input class="form-control" name="VigenciaHasta" type="date"
                                        value="{{ $deuda->VigenciaHasta }}" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Estado *</label>
                                    <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
                                        @foreach ($estadoPoliza as $obj)
                                        @if ($obj->Id == 4)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Ejecutivo *</label>
                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%"
                                        required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($ejecutivo as $obj)
                                        @if ($obj->Id == $deuda->Ejecutivo)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Descuento de Rentabilidad *</label>
                                    <input class="form-control" name="Descuento" type="number" step="any"
                                        id="Descuento" value="{{ $deuda->Descuento }}" required>
                                </div>
                                <div class="col-sm-4">
                                    &nbsp;
                                </div>
                                <div class="col-md-12">
                                    &nbsp;
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Edad Máxima Terminación *</label>
                                    <input type="number" name="EdadMaximaTerminacion" class="form-control" required
                                        value="{{ $deuda->EdadMaximaTerminacion }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Responsabilidad Máxima *</label>
                                    <div class=" form-group has-feedback">
                                        <input type="number" step="any" name="ResponsabilidadMaxima"
                                            id="ResponsabilidadMaxima" style="padding-left: 15%;display: none;"
                                            value="{{ $deuda->ResponsabilidadMaxima }}" class="form-control" required
                                            onblur="ResponsabilidadMax(this.value)">
                                        <input type="text" step="any"
                                            style="padding-left: 15%; display: block;" id="ResponsabilidadMaximaTexto"
                                            value="{{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }}"
                                            class="form-control" required
                                            onfocus="ResponsabilidadMaxTexto(this.value)">
                                        <span class="fa fa-dollar form-control-feedback left"
                                            aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    &nbsp;
                                </div>
                                <div class="col-md-12">
                                    &nbsp;
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label " align="right">Clausulas Especiales </label>
                                    <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4">{{ $deuda->ClausulasEspeciales }} </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Beneficios Adicionales</label>
                                    <textarea class="form-control" name="Beneficios" row="3" col="4">{{ $deuda->Beneficios }} </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Concepto</label>
                                    <textarea class="form-control" name="Concepto" row="3" col="4">{{ $deuda->Concepto }}</textarea>
                                </div>
                                <div class="col-sm-4 ocultar" style="display: none !important;">
                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                            {{ $deuda->Mensual == 1 ? 'checked' : '' }}>
                                        <label class="control-label">Tasa Millar Mensual *</label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Anual" value="0"
                                            {{ $deuda->Mensual == 0 ? 'checked' : '' }}>
                                        <label class="control-label">Tasa ‰ Millar Anual *</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Tasa Millar Mensual *</label>
                                    <input class="form-control" name="Tasa" type="number" id="Tasa"
                                        step="any" value="{{ $deuda->Tasa }}" required>
                                </div>
                                <div class="col-sm-4" align="center">
                                    <br>
                                    <label class="control-label" align="center">Vida</label>
                                    <input id="Vida" type="checkbox" class="js-switch"
                                        {{ $deuda->Vida != '' ? 'checked' : '' }} />
                                </div>
                                <div class="col-sm-4" align="center">
                                    <br>
                                    <label class="control-label" align="center">Desempleo</label>
                                    <input id="Desempleo" type="checkbox" class="js-switch"
                                        {{ $deuda->Desempleo != '' ? 'checked' : '' }} />
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" align="right">% de Comisión *</label>
                                    <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                        step="any" value="{{ $deuda->TasaComision }}">
                                </div>
                                <div class="col-sm-2"><br>
                                    <label class="control-label" align="right">¿IVA incluído?</label>
                                    <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                                        {{ $deuda->ComisionIva == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="col-sm-4">
                                    <div id="poliza_vida"
                                        style="display: {{ $deuda->Vida != '' ? 'block' : 'none' }};">
                                        <label class="control-label">Numero de Poliza Vida *</label>
                                        <input name="Vida" type="text" class="form-control"
                                            value="{{ $deuda->Vida }}" />
                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <div id="poliza_desempleo"
                                        style="display:  {{ $deuda->Desempleo != '' ? 'block' : 'none' }};">
                                        <label class="control-label">Número de Póliza Desempleo *</label>
                                        <input name="Desempleo" type="text" class="form-control"
                                            value="{{ $deuda->Desempleo }}" />
                                    </div>

                                </div>

                            </div>


                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>

                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success"
                                        {{ $deuda->Configuracion == 1 ? 'disabled' : '' }}>Guardar y Continuar</button>
                                    <a href="{{ url('polizas/deuda') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ session('tab') == 2 ? 'active in' : '' }}"
                        id="tab_content2" aria-labelledby="lineas-tab">
                        <div class="col-md-12 text-right">
                            <a href="" data-target="#modal-credito-create" data-toggle="modal"
                                class="btn btn-primary">Nuevo</a>
                        </div>



                        <br>
                        <hr>
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
                                    {{-- <tbody>
                                        @foreach ($creditos as $obj)
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
                                        @endforeach
                                    </tbody> --}}
                                </table>



                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ session('tab') == 3 ? 'active in' : '' }}"
                        id="tab_content3" aria-labelledby="asegurabilidad-tab">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form action="{{ url('datos_asegurabilidad') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Tabla de requisitos </h5>
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
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                    align="right">Perfiles médicos</label>
                                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <select name="Perfiles" id="Perfiles" class="form-control"
                                                        required>
                                                        <option value="">Seleccione...</option>
                                                        @foreach ($perfiles as $obj)
                                                        <option value="{{ $obj->Id }}">
                                                            {{ $obj->Descripcion }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                    align="right">Edad
                                                    inicial</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="EdadInicial" value="18"
                                                        type="text" name="EdadInicial" required min="18">
                                                </div>
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                    align="right">Edad
                                                    final</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="EdadFinal" name="EdadFinal"
                                                        type="number" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                    align="right">Monto
                                                    inicial</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="MontoInicial" step="0.01"
                                                        type="number" name="MontoInicial" required>
                                                </div>
                                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                    align="right">Monto
                                                    final</label>
                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <input class="form-control" id="MontoFinal" step="0.01"
                                                        type="number" name="MontoFinal" required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br>
                                <br>
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success"
                                        {{ $deuda->Configuracion == 1 ? 'disabled' : '' }}>Guardar y Continuar</button>
                                    <a href="{{ url('polizas/deuda') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>REQUISITOS</th>
                                        @foreach ($columnas as $columna)
                                        <th>DESDE {{ explode('-', $columna)[0] }} AÑOS HASTA
                                            {{ explode('-', $columna)[1] }} AÑOS
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tabla as $perfil => $montos)
                                    <tr>
                                        <td>{{ $perfil }}</td>
                                        @foreach ($columnas as $columna)
                                        @php
                                        // Obtener el monto correspondiente al rango de edad
                                        $monto = $montos[$columna]['monto'] ?? null;
                                        $requisitoId = $montos[$columna]['id'] ?? null; // Obtener el id del requisito
                                        $perfilId = $montos[$columna]['perfilId'] ?? null; // Obtener el id del requisito
                                        $edades = explode('-', $columna);
                                        $edadInicial = $edades[0];
                                        $edadFinal = $edades[1];
                                        @endphp
                                        <td>
                                            @if ($monto)
                                            @php
                                            [$montoInicial, $montoFinal] = explode('-', $monto);
                                            @endphp
                                            Desde ${{ number_format($montoInicial, 2) }} HASTA
                                            ${{ number_format($montoFinal, 2) }}

                                            <div class="item form-group offset-md-3">
                                                <br>
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary"
                                                        data-target="#modal-requisito-{{ $requisitoId }}"
                                                        data-toggle="modal">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </div>

                                                <div class="col-md-6" style="text-align: right;">
                                                    <button class="btn btn-danger"
                                                        data-target="#modal-delete-{{ $requisitoId }}"
                                                        data-toggle="modal">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>


                                            </div>
                                            @include('polizas.deuda.modal_edit_requisito')
                                            @include('polizas.deuda.modal_delete_requisito')
                                            @else
                                            -
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>




                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ session('tab') == 4 ? 'active in' : '' }}"
                        id="tab_content4" aria-labelledby="renovacion-tab">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h5 id="exampleModalLabel">Renovación de Poliza </h5>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Desde</label>
                                    <input class="form-control" type="date" value="{{ $deuda->VigenciaDesde }}"
                                        readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Hasta</label>
                                    <input class="form-control" type="date" value="{{ $deuda->VigenciaHasta }}"
                                        readonly>
                                </div>
                            </div>

                            <form action="{{url('renovacion_poliza')}}" method="POST">
                                @csrf

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <br>
                                    <input type="hidden" name="Deuda" id="Deuda" value="{{$deuda->Id}}">
                                    <input type="hidden" name="id_historica" value="{{$historico_poliza->where('TipoRenovacion',1)->first()->Id ?? ''}}">
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Tipo renovación *</label>
                                        <select name="TipoRenovacion" id="TipoRenovacion" class="form-control" style="width: 100%">
                                            <option value="1">Anual</option>
                                            <option value="2">Parcial</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Desde de Renovación *</label>
                                        <input class="form-control" name="VigenciaDesde" id="VigenciaDesdeRenovacion" type="date"
                                            value="{{ old('VigenciaDesde', $fechaDesdeRenovacion) }}" required readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Hasta de Renovación *</label>
                                        <input class="form-control" name="VigenciaHasta" id="VigenciaHastaRenovacion" type="date"
                                            value="{{ old('VigenciaHasta', $fechaHastaRenovacion) }}" required readonly>
                                    </div>


                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br><br>
                                    <div class="form-group" align="center">
                                        <button type="submit" class="btn btn-success">Guardar y Continuar</button>
                                        <a href="{{ url('polizas/deuda') }}"><button type="button"
                                                class="btn btn-primary">Cancelar</button></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo Renovacion</th>
                                        <th>Vigencia Desde</th>
                                        <th>Vigencia Hasta</th>
                                        <!-- <th style="width: 30%;">Opciones</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historico_poliza as $obj)
                                    <tr>
                                        <td>{{ $obj->TipoRenovacion == 1 ? 'Anual':'Parcial' }}</td>
                                        <td>{{ $obj->VigenciaDesde ? date('d/m/Y', strtotime($obj->VigenciaDesde)) : ''}}</td>
                                        <td>{{ $obj->VigenciaHasta ? date('d/m/Y', strtotime($obj->VigenciaHasta)) : ''}}</td>
                                        <!-- <td style="vertical-align: middle; text-align: center;"> <a href="{{ url('catalogo/configuracion_recibo') }}/{{ $obj->Id }}/edit"
                                                class="on-default edit-row">
                                                <i class="fa fa-pencil fa-lg"></i></a></td> -->
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-credito-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/deuda/agregar_credito') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Nueva linea de credito</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label ">Línea de Crédito</label>
                            <input class="form-control" type="hidden" name="Deuda" value="{{$deuda->Id}}">
                            <select name="TipoCartera" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach ($tipoCartera as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Saldos y Montos</label>
                            <select name="Saldos" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach ($saldos as $obj)
                                <option value="{{ $obj->Id }}">
                                    {{ $obj->Abreviatura }} -
                                    {{ $obj->Descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Monto Máximo</label>
                            <input class="form-control" type="number" min="1.00" step="any"
                                name="MontoMaximoIndividual">
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






@include('sweetalert::alert')


<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    function noGuardardo() {
        Swal.fire('Debe guardar los datos inicial de la poliza');
    }

    function formatearCantidad(cantidad) {
        let numero = Number(cantidad);
        return numero.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function ResponsabilidadMax(id) {
        document.getElementById('ResponsabilidadMaximaTexto').value = formatearCantidad(id);
        $("#ResponsabilidadMaxima").hide();
        $("#ResponsabilidadMaximaTexto").show();
    }

    function ResponsabilidadMaxTexto(id) {
        // document.getElementById('ResponsabilidadMaxima').value = document.getElementById('ResponsabilidadMaximaTexto');
        $("#ResponsabilidadMaxima").show();
        $("#ResponsabilidadMaximaTexto").hide();
    }

    function MontoMaxIndividual(id) {
        document.getElementById('MontoMaximoIndividualTexto').value = formatearCantidad(id);
        $("#MontoMaximoIndividual").hide();
        $("#MontoMaximoIndividualTexto").show();
    }

    function MontoMaxIndividualTexto(id) {
        // document.getElementById('MontoMaximoIndividual').value = document.getElementById('MontoMaximoIndividualTexto');
        $("#MontoMaximoIndividual").show();
        $("#MontoMaximoIndividualTexto").hide();
    }

    function add_rango() {
        $("#modal_rango").modal('show');
    }

    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }

    function modal_requisitos() {
        $('#modal_requisitos').modal('show');
    }

    function modal_creditos() {
        $('#modal_creditos').modal('show');
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {

        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-deuda");

        $("#TipoRenovacion").change(function() {
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');

            var tipo = document.getElementById('TipoRenovacion').value;
            var parametros = {
                "TipoRenovacion": tipo,
                "Deuda": document.getElementById('Deuda').value
            };
            $.ajax({
                type: "get",
                url: "{{ url('get_fechas_renovacion') }}",
                data: parametros,
                success: function(data) {
                    console.log("Respuesta del servidor:", data);

                    // Validar que los valores sean correctos antes de asignarlos
                    if (data.data.VigenciaDesde && data.data.VigenciaHasta) {
                        document.getElementById('VigenciaDesdeRenovacion').value = data.data.VigenciaDesde;
                        document.getElementById('VigenciaHastaRenovacion').value = data.data.VigenciaHasta;
                    } else {
                        console.error("Error: Fechas inválidas recibidas del servidor.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX:", error);
                }
            });
            if (tipo == 1) {
                document.getElementById('VigenciaDesdeRenovacion').setAttribute('readonly', true);
                document.getElementById('VigenciaHastaRenovacion').setAttribute('readonly', true);
            } else {
                document.getElementById('VigenciaDesdeRenovacion').removeAttribute('readonly');
                document.getElementById('VigenciaHastaRenovacion').removeAttribute('readonly');
            }
        });

        $("#fechas").change(function() {
            if (document.getElementById('fechas').checked == true) {
                $('#fecha_otorgamiento').show();
                $("#FechaDesde").prop('required', true);
                $("#FechaHasta").prop('required', true);
                $("#TasaFecha").prop('required', true);


            } else {
                $('#fecha_otorgamiento').hide();
                $("#FechaDesde").removeAttr('required');
                $("#FechaHasta").removeAttr('required');
                $("#TasaFecha").removeAttr('required');
            }
        })

        $("#montos").change(function() {
            if (document.getElementById('montos').checked == true) {
                $('#monto_otorgamiento').show();
                $("#MontoDesde").prop('required', true);
                $("#MontoHasta").prop('required', true);
                $("#TasaMonto").prop('required', true);

            } else {
                $('#monto_otorgamiento').hide();
                $("#MontoDesde").removeAttr('required');
                $("#MontoHasta").removeAttr('required');
                $("#TasaMonto").removeAttr('required');
            }
        })
        $("#edad").change(function() {
            if (document.getElementById('edad').checked == true) {
                $('#edad_otorgamiento').show();
                $("#EdadDesde").prop('required', true);
                $("#EdadHasta").prop('required', true);
                $("#TasaEdad").prop('required', true);

            } else {
                $('#edad_otorgamiento').hide();
                $("#EdadDesde").removeAttr('required');
                $("#EdadHasta").removeAttr('required');
                $("#TasaEdad").removeAttr('required');
            }
        })

        $("#montos").change(function() {
            if (document.getElementById('montos').checked == true) {
                $('#monto_otorgamiento').show();

            } else {
                $('#monto_otorgamiento').hide();
            }
        })


        $("#Asegurado").change(function() {
            // alert(document.getElementById('Asegurado').value);
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            var parametros = {
                "Cliente": document.getElementById('Asegurado').value
            };
            $.ajax({
                type: "get",
                //ruta para obtener el horario del doctor
                url: "{{ url('get_cliente') }}",
                data: parametros,
                success: function(data) {
                    console.log(data);
                    document.getElementById('Nit').value = data.Nit;
                    if (data.TipoContribuyente == 1) {
                        document.getElementById('Retencion').setAttribute("readonly", true);
                        document.getElementById('Retencion').value = 0;

                    }


                }
            });
        });
        $("#EdadInicial2").prop("disabled", true);
        $("#EdadFinal2").prop("disabled", true);
        $("#MontoInicial2").prop("disabled", true);
        $("#MontoFinal2").prop("disabled", true);

        $("#EdadInicial3").prop("disabled", true);
        $("#EdadFinal3").prop("disabled", true);
        $("#MontoInicial3").prop("disabled", true);
        $("#MontoFinal3").prop("disabled", true);
        $("#Vida").change(function() {
            if (document.getElementById('Vida').checked == true) {
                $('#poliza_vida').show();
            } else {
                $('#poliza_vida').hide();
            }
        })

        $("#Desempleo").change(function() {
            if (document.getElementById('Desempleo').checked == true) {
                $('#poliza_desempleo').show();
            } else {
                $('#poliza_desempleo').hide();
            }
        })




        $("#Aseguradora").change(function() {
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            // var para la Departamento
            var Aseguradora = $(this).val();

            //funcionpara las distritos
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
            // var para la Departamento
            var Productos = $(this).val();

            //funcionpara las distritos
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

    $("#Activar1").change(function() {
        if (document.getElementById('Activar1').checked == true && document.getElementById('EdadFinal').value ==
            "" && document.getElementById('MontoFinal').value == "") {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar los datos anteriores',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            });
            document.getElementById('Activar1').checked = false;
        } else {
            document.getElementById('EdadInicial2').value = parseInt(document.getElementById('EdadFinal')
                .value) + 1;
            document.getElementById('MontoInicial2').value = parseFloat(document.getElementById('MontoFinal')
                .value) + 0.01;
            if (document.getElementById('Activar1').checked == true) {
                $("#EdadInicial2").prop("disabled", false);
                $("#EdadFinal2").prop("disabled", false);
                $("#MontoInicial2").prop("disabled", false);
                $("#MontoFinal2").prop("disabled", false);
            } else {
                $("#EdadInicial2").prop("disabled", true);
                $("#EdadFinal2").prop("disabled", true);
                $("#MontoInicial2").prop("disabled", true);
                $("#MontoFinal2").prop("disabled", true);

                document.getElementById('EdadInicial2').value = "";
                document.getElementById('EdadFinal2').value = "";
                document.getElementById('MontoInicial2').value = "";
                document.getElementById('MontoFinal2').value = "";

                document.getElementById('EdadInicial3').value = "";
                document.getElementById('EdadFinal3').value = "";
                document.getElementById('MontoInicial3').value = "";
                document.getElementById('MontoFinal3').value = "";
            }
        }

    });

    $("#Activar2").change(function() {
        if (document.getElementById('Activar2').checked == true && document.getElementById(
                'EdadFinal2').value == "" &&
            document.getElementById('MontoFinal2').value == "") {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar los datos anteriores',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            });
            document.getElementById('Activar2').checked = false;
        } else {
            document.getElementById('EdadInicial3').value = parseInt(document.getElementById('EdadFinal2')
                .value) + 1;
            document.getElementById('MontoInicial3').value = parseFloat(document.getElementById('MontoFinal2')
                .value) + 0.01;
            if (document.getElementById('Activar2').checked == true) {
                $("#EdadInicial3").prop("disabled", false);
                $("#EdadFinal3").prop("disabled", false);
                $("#MontoInicial3").prop("disabled", false);
                $("#MontoFinal3").prop("disabled", false);
            }
        }
    });

    $("#btn_modal_guardar").click(function() {
        validar();
    });

    function validar() {
        if (document.getElementById('Requisito').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo requisitos es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('EdadFinal').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo edad final es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('MontoInicial').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo monto inicial es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('MontoFinal').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo monto final es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('Activar1').checked == true &&
            (document.getElementById('EdadInicial2').value.trim() == "" || document.getElementById('EdadFinal2').value
                .trim() == "" ||
                document.getElementById('MontoInicial2').value.trim() == "" || document.getElementById('MontoFinal2')
                .value.trim() == "")

        ) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('Activar2').checked == true &&
            (document.getElementById('EdadInicial3').value.trim() == "" || document.getElementById('EdadFinal3').value
                .trim() == "" ||
                document.getElementById('MontoInicial3').value.trim() == "" || document.getElementById('MontoFinal3')
                .value.trim() == "")

        ) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else {
            guardar();
        }
    }

    function guardar() {
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Requisito": document.getElementById('Requisito').value,
            "EdadInicial": document.getElementById('EdadInicial').value,
            "EdadFinal": document.getElementById('EdadFinal').value,
            "MontoInicial": document.getElementById('MontoInicial').value,
            "MontoFinal": document.getElementById('MontoFinal').value,
            "EdadInicial2": document.getElementById('EdadInicial2').value,
            "EdadFinal2": document.getElementById('EdadFinal2').value,
            "MontoInicial2": document.getElementById('MontoInicial2').value,
            "MontoFinal2": document.getElementById('MontoFinal2').value,
            "EdadInicial3": document.getElementById('EdadInicial3').value,
            "EdadFinal3": document.getElementById('EdadFinal3').value,
            "MontoInicial3": document.getElementById('MontoInicial3').value,
            "MontoFinal3": document.getElementById('MontoFinal3').value
        };
        $.ajax({
            type: "post",
            url: "{{ url('polizas/deuda/store_requisitos') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                if (document.getElementById('DataRequisitos').value == "") {
                    document.getElementById('DataRequisitos').value = data;
                } else {
                    document.getElementById('DataRequisitos').value = document.getElementById(
                        'DataRequisitos').value + "," + data;
                }
                $('#modal_requisitos').modal('hide');
                get_requisitos();
            }
        });
    }

    function get_requisitos() {
        var parametros = {
            "Requisitos": document.getElementById('DataRequisitos').value,
        };
        $.ajax({
            type: "get",
            url: "{{ url('polizas/deuda/get_requisitos') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#divRequisitos').html(data);
            }
        });
    }
</script>


@endsection
