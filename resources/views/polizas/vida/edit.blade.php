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




<style>
    .subtareas-container {
        /* display: none;
                                /* Ocultar subtareas por defecto */
    }

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


<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="x_title">
                <h2>Pólizas / Vida / Póliza de vida / Nueva póliza<small></small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    @if ($vida->Configuracion == 0)
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

                    <form method="POST" action="{{ url('finalizar_configuracion_vida') }}">
                        @method('POST')
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <input type="hidden" name="vida" value="{{ $vida->Id }}">
                                    <h4 class="modal-title">{{ $vida->Configuracion == 0 ? 'Finalizar' : 'Aperturar' }}
                                        Configuración</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Confirme si desea {{ $vida->Configuracion == 0 ? 'finalizar' : 'aperturar' }} la
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

                    <li role="presentation" class="{{ session('tab') == 2 ? 'active' : '' }} "><a href="#tab_content2"
                            id="lineas-tab" role="tab" data-toggle="tab" aria-expanded="true">Tasa diferenciada</a>
                    </li>




                </ul>

                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab') == 1 ? 'active in' : '' }}"
                        id="tab_content1" aria-labelledby="home-tab">
                        <form action="{{ url('polizas/vida/')}}/{{$vida->Id}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <!-- Número de Póliza -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Número de Póliza</label>
                                    <input type="text" class="form-control" name="NumeroPoliza" value="{{ $vida->NumeroPoliza }}" required>
                                </div>

                                <!-- Aseguradora -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Aseguradora</label>
                                    <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($aseguradora as $obj)
                                        <option value="{{ $obj->Id }}" {{ $vida->Aseguradora == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('Aseguradora')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Productos -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Productos</label>
                                    <select name="Productos" id="Productos" class="form-control select2" style="width: 100%" required>
                                        <option value="" disabled selected>Seleccione...</option>
                                        @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ ($vida->planes && $vida->planes->productos && $vida->planes->productos->Id == $obj->Id) ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('Productos')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Planes -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Planes</label>
                                    <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" required>
                                        <option value="" disabled selected>Seleccione...</option>
                                        @foreach ($planes as $obj)
                                        <option value="{{ $obj->Id }}" {{ $vida->planes && $vida->planes->Id == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('Planes')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Asegurado -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Asegurado</label>
                                    <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($cliente as $obj)
                                        <option value="{{ $obj->Id }}" {{ $vida->cliente->Id == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nit -->
                                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Nit</label>
                                    <input type="text" class="form-control" name="Nit" value="{{ trim($vida->cliente->Nit) }}" required>
                                </div>

                                <!-- Ejecutivo -->
                                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Ejecutivo</label>
                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($ejecutivo as $obj)
                                        <option value="{{ $obj->Id }}" {{ $vida->ejecutivo->Id == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo de cobro -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Tipo de Cobro</label>
                                    <select name="TipoCobro" class="form-control" required>
                                        @foreach ($tipoCobro as $tipo)
                                        <option value="{{ $tipo->Id }}" {{ $vida->tipoCobro->Id == $tipo->Id ? 'selected' : '' }}>
                                            {{ $tipo->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Vigencia Desde -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Vigencia Desde</label>
                                    <input type="date" class="form-control" name="VigenciaDesde" value="{{ $vida->VigenciaDesde }}" required>
                                </div>

                                <!-- Vigencia Hasta -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Vigencia Hasta</label>
                                    <input type="date" class="form-control" name="VigenciaHasta" value="{{ $vida->VigenciaHasta }}" required>
                                </div>

                                <!-- Edad Máxima de Inscripción -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Edad Máxima de Inscripción</label>
                                    <input type="number" class="form-control" name="EdadMaximaInscripcion" value="{{ $vida->EdadMaximaInscripcion }}" required>
                                </div>

                                <!-- Edad Terminación -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Edad Terminación</label>
                                    <input type="number" class="form-control" name="EdadTerminacion" value="{{ $vida->EdadTerminacion }}" required>
                                </div>

                                <!-- Tasa Millar Mensual -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Tasa Millar Mensual</label>
                                    <input type="number" class="form-control" name="Tasa" value="{{ $vida->Tasa }}" required>
                                </div>

                                <!-- Suma Asegurada -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Suma Asegurada</label>
                                    <input type="number" class="form-control" name="SumaAsegurada" value="{{ $vida->SumaAsegurada }}" required>
                                </div>

                                <!-- Descuento -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Descuento</label>
                                    <input type="number" class="form-control" name="TasaDescuento" value="{{ $vida->TasaDescuento ?? 0 }}" required>
                                </div>

                                <!-- Concepto -->
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Concepto</label>
                                    <textarea class="form-control" name="Concepto" rows="3">{{ $vida->Concepto }}</textarea>
                                </div>
                            </div>

                            <div class="clearfix my-3"></div>

                            <!-- Botones -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success" {{ $vida->Configuracion == 1 ? 'disabled' : '' }}>Guardar y Continuar</button>
                                <a href="{{ url('polizas/vida') }}" class="btn btn-primary">Cancelar</a>
                            </div>
                        </form>

                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ session('tab') == 2 ? 'active in' : '' }}"
                        id="tab_content2" aria-labelledby="lineas-tab">


                        <div class="x_title">

                            <ul class="nav navbar-right panel_toolbox">
                                <a href="{{ url('polizas/vida/tasa_diferenciada') }}/{{ $vida->Id }}"
                                    class="btn btn-primary"><i class="fa fa-edit"></i></a>
                            </ul>
                            <div class="clearfix"></div>

                        </div>

                        <hr>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <table width="100%" class="table table-striped">

                                    <tbody>

                                        @if ($vida->vida_tipos_cartera->count() > 0)
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr class="warning-row">
                                                    <th style="width: 40%;">Tipo cartera</th>
                                                    <th style="width: 20%;">Tipo cálculo</th>
                                                    <th style="width: 20%;">Monto máximo individual</th>
                                                </tr>
                                            </thead>
                                            <tbody>



                                                @foreach ($vida->vida_tipos_cartera as $tipo)
                                                <tr class="tarea warning-row">
                                                    <td>
                                                        <span class="expand-icon">▼</span>
                                                        {{ $tipo->catalogo_tipo_cartera?->Nombre ?? '' }}
                                                    </td>
                                                    <td>{{ $tipo->TipoCalculo == 1 ? 'Fecha' : ($tipo->TipoCalculo == 2 ? 'Monto' : 'No aplica') }}</td>
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
                                                                        <!-- <th>Linea credito</th> -->
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
                                                                        <!-- <td>
                                                                            {{ $tasa_diferenciada->linea_credito?->Abreviatura ?? '' }}
                                                                            -
                                                                            {{ $tasa_diferenciada->linea_credito?->Descripcion ?? '' }}
                                                                        </td> -->
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
                    </div>
                    {{--<div role="tabpanel" class="tab-pane fade {{ session('tab') == 4 ? 'active in' : '' }}"
                    id="tab_content4" aria-labelledby="renovacion-tab">

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
                                <tr @if($obj->TipoRenovacion == 1) style="background-color: #e8f5ee;" @endif>
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

                </div> --}}
            </div>

        </div>
    </div>
</div>
</div>



<div class="modal fade" id="modal-add-tipo-cartera" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/vida/agregar_tipo_cartera') }}/{{ $vida->Id }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar tipo cartera</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label">Tipo de Cartera</label>
                                <select class="form-control" name="TipoCartera">
                                    @foreach ($tiposCartera as $tipo)
                                    <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label class="control-label">Tipo cálculo</label>
                                <select name="TipoCalculo" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1">Fecha
                                    </option>
                                    <option value="2">Edad
                                    </option>
                                </select>
                            </div>

                            <div class="form-group row">
                                <label class="control-label">Monto máximo individual</label>
                                <input type="number" step="any" min="0.00" class="form-control"
                                    name="MontoMaximoIndividual" required>

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




<div class="modal fade" id="modal-tasa-diferenciada" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/deuda/tasa_diferenciada_store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar tasa diferenciada</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="PolizaDuedaTipoCartera" class="form-control">

                            <div class="form-group row">
                                <label class="control-label">Tipo de Cartera</label>
                                <input type="hidden" name="PolizaDeudaTipoCartera">

                            </div>
                            <div class="form-group row" id="divFechaDesde" style="display: none">
                                <label class="control-label">Fecha inicio</label>
                                <input type="date" name="FechaDesde" class="form-control">
                            </div>

                            <div class="form-group row" id="divFechaHasta" style="display: none">
                                <label class="control-label">Fecha final</label>
                                <input type="date" name="FechaHasta" class="form-control">
                            </div>

                            <div class="form-group row" id="divEdadDesde" style="display: none">
                                <label class="control-label">Edad inicio</label>
                                <input type="number" step="1" name="EdadDesde" class="form-control">
                            </div>

                            <div class="form-group row" id="divEdadHasta" style="display: none">
                                <label class="control-label">Edad final</label>
                                <input type="number" step="1" name="EdadHasta" class="form-control">
                            </div>

                            <div class="form-group row">
                                <label class="control-label">Tasa</label>
                                <input type="number" name="Tasa" step="any" class="form-control" required>
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




@include('sweetalert::alert')


<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
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
        displayOption("ul-poliza", "li-poliza-vida");

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






<script>
    $(document).ready(function() {
        $(".expand-icon").click(function() {
            let row = $(this).closest("tr");
            let subtable = row.next(".subtareas-container");

            // Alternar icono
            $(this).text($(this).text() === "►" ? "▼" : "►");

            // Mostrar u ocultar tabla de subtareas
            subtable.toggle();
        });
    });

    function show_modal_tasa_diferenciada(id, tipo) {

        // limpiando los inputs
        $('input[name="FechaDesde"]').val('');
        $('input[name="FechaHasta"]').val('');
        $('input[name="EdadDesde"]').val('');
        $('input[name="EdadHasta"]').val('');

        // Ocultar todos los divs de fecha y edad
        $('#divFechaDesde, #divFechaHasta, #divEdadDesde, #divEdadHasta').hide();

        // Mostrar los campos según el tipo
        if (tipo == 1) {
            $('#divFechaDesde, #divFechaHasta').show();
        } else if (tipo == 2) {
            $('#divEdadDesde, #divEdadHasta').show();
        }

        document.querySelector('input[name="PolizaDuedaTipoCartera"]').value = id;


    }
</script>




@endsection