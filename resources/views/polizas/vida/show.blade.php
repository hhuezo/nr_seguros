@extends ('welcome')
@section('contenido')
    <div class="x_panel">
        <div id="loading-overlay" style="display: none">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
        </div>


        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Polizas de vida </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('polizas/desempleo/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-arrow-left"></i></button></a>
            </div>
            <div class="clearfix"></div>
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


        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
            <li class="nav-item {{ isset($tab) && $tab == 1 ? 'active in' : '' }}">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                    aria-selected="true">Póliza</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 2 ? 'active in' : '' }}">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Generar cartera</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hoja-tab" data-toggle="tab" href="#hoja" role="tab" aria-controls="hoja"
                    aria-selected="false">Hoja de Cartera {{ $poliza_vida->NumeroPoliza }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos"
                    aria-selected="false">Estado de Pago</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">Ver Aviso</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ isset($tab) && $tab == 1 ? 'active in' : '' }}" id="home" role="tabpanel"
                aria-labelledby="home-tab">


                <!-- Número de Póliza -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Número de Póliza</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->NumeroPoliza }}" readonly>
                </div>

                <!-- Aseguradora -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Aseguradora</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->aseguradora->Nombre }}" readonly>
                </div>

                <!--Productos -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Productos</label>
                    <input type="text" class="form-control"
                        value="{{ $poliza_vida->planes && $poliza_vida->planes->productos ? $poliza_vida->planes->productos->Nombre : '' }}"
                        readonly>
                </div>

                <!-- Plan -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Planes</label>
                    <input type="text" class="form-control"
                        value="{{ $poliza_vida->planes ? $poliza_vida->planes->Nombre : '' }}" readonly>
                </div>

                <!-- Asegurado -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Asegurado</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->cliente->Nombre }}" readonly>
                </div>


                <!-- Nit -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Nit</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->cliente->Nit }}" readonly>
                </div>

                <!-- Ejecutivo -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Ejecutivo</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->ejecutivo->Nombre }}" readonly>
                </div>

                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Tipo cobro</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->tipoCobro->Nombre }}" readonly>
                </div>

                <!-- Vigencia Desde -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Vigencia Desde</label>
                    <input type="date" class="form-control" value="{{ $poliza_vida->VigenciaDesde }}" readonly>
                </div>

                <!-- Vigencia Hasta -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Vigencia Hasta</label>
                    <input type="date" class="form-control" value="{{ $poliza_vida->VigenciaHasta }}" readonly>
                </div>

                <!-- Edad Máxima de Inscripción -->
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label>Edad Máxima de Inscripción</label>
                    <input type="text" class="form-control" value="{{ $poliza_vida->EdadMaximaInscripcion }}"
                        readonly>
                </div>



                <!-- Edad Terminación -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Edad Terminación</label>
                    <input class="form-control" type="number" value="{{ $poliza_vida->EdadTerminacion }}" readonly>
                </div>


                <!-- Tasa Millar Mensual -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Tasa Millar Mensual</label>
                    <input class="form-control"type="number" value="{{ $poliza_vida->Tasa }}" readonly>
                </div>

                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Suma asegurada</label>
                    <input class="form-control" type="number" value="{{ $poliza_vida->SumaAsegurada }}" readonly>
                </div>

                <!-- Tasa Millar Mensual -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Descuento</label>
                    <input class="form-control" type="number" value="{{ $poliza_vida->Descuento }}" readonly>
                </div>

                <!-- Concepto -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Concepto</label>
                    <textarea class="form-control" rows="3" cols="4" readonly>{{ $poliza_vida->Concepto }}</textarea>
                </div>


            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">

                <ul class="nav navbar-right panel_toolbox">
                    <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">
                        Subir Archivo Excel</div>
                </ul>


                @include('polizas.vida.tab2')

            </div>
            <div class="tab-pane fade " id="hoja" role="tabpanel" aria-labelledby="hoja-tab">

                @include('polizas.vida.tab3')
            </div>
            <div class="tab-pane fade " id="pagos" role="tabpanel" aria-labelledby="pagos-tab">

                @include('polizas.vida.tab4')
            </div>
            <div class="tab-pane fade " id="contact" role="tabpanel" aria-labelledby="contact-tab">


            </div>
        </div>

    </div>



    <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel
                        </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('polizas/vida/create_pago') }}/{{ $poliza_vida->Id }}" method="POST"
                    id="form-create-pago" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Axo" id="Axo" class="form-control">
                                    @foreach ($anios as $anio)
                                        <option value="{{ $anio }}"
                                            {{ $anio == $anioSeleccionado ? 'selected' : '' }}>{{ $anio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Mes" id="Mes" class="form-control">
                                    @for ($i = 1; $i < 12; $i++)
                                        @if ($mes == $i)
                                            <option value="{{ $i }}" selected>
                                                {{ $meses[$i] }}
                                            </option>
                                        @else
                                            <option value="{{ $i }}">
                                                {{ $meses[$i] }}
                                            </option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                inicio</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Id" value="{{ $poliza_vida->Id }}"
                                    type="hidden" required>
                                <input class="form-control" type="date" name="FechaInicio" id="FechaInicio"
                                    value="{{ $fechaInicio }}" required min="{{ $poliza_vida->VigenciaDesde }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                final</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="FechaFinal" id="FechaFinal"
                                    value="{{ $fechaFinal }}" type="date" required
                                    max="{{ $poliza_vida->VigenciaHasta }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Archivo" type="file" required>
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubirCartera">Subir Cartera</button>
                    </div>
                </form>

                <div id="loading-indicator" style="text-align: center; display:none">
                    <img src="{{ asset('img/ajax-loader.gif') }}">
                    <br>
                </div>


            </div>
        </div>
    </div>




    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-vida");


            // Interceptar solo el formulario con id form-create-pago
            $('#form-create-pago').on('submit', function(e) {
                // Mostrar el indicador de carga dentro del modal
                $('#loading-indicator').show();

                // Deshabilitar el botón de submit para evitar envíos duplicados
                $('#btnSubirCartera').prop('disabled', true).html('Procesando...');

                // Opcional: Evitar que el modal se cierre mientras se procesa
                $('#modal_pago').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });

        });

        document.addEventListener('DOMContentLoaded', function() {
            // Obtener referencias a los elementos del formulario
            const añoSelect = document.getElementById('Axo');
            const mesSelect = document.getElementById('Mes');
            const fechaInicioInput = document.getElementById('FechaInicio');
            const fechaFinalInput = document.getElementById('FechaFinal');

            // Función para actualizar las fechas
            function actualizarFechas() {
                // Obtener el año y mes seleccionados
                const año = añoSelect.value;
                const mes = mesSelect.value;

                // Validar que ambos campos tengan valores
                if (año && mes) {
                    // Formatear el primer día del mes seleccionado
                    const primerDiaMes = `${año}-${mes.padStart(2, '0')}-01`;
                    fechaInicioInput.value = primerDiaMes;

                    // Calcular el primer día del mes siguiente
                    const fecha = new Date(año, mes - 1, 1); // Mes en JavaScript es 0-indexado
                    fecha.setMonth(fecha.getMonth() + 1); // Sumar un mes
                    const primerDiaMesSiguiente = fecha.toISOString().split('T')[0];
                    fechaFinalInput.value = primerDiaMesSiguiente;
                }
            }

            // Asignar la función al evento onchange de los selectores
            añoSelect.addEventListener('change', actualizarFechas);
            mesSelect.addEventListener('change', actualizarFechas);
        });
    </script>



    {{-- <style>
    @media screen and (min-width: 992px) {
        .modal-lg {
            width: 1100px !important;
        }
    }
</style>

<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Editar Poliza Vida &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VICO - Vida Colectivo Seguros<small></small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">

                </ul>
                <div class="clearfix"></div>
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

            <div class="x_content">
                <br />


                @method('PUT')
                @csrf
                <div class="form-horizontal" style="font-size: 12px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número de Póliza</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ $vida->NumeroPoliza }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Codigo" type="text" value="{{ $vida->Codigo }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Aseguradora" type="text" value="{{ $vida->aseguradoras->Nombre }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Asegurado" type="text" value="{{ $vida->clientes->Nombre }}" readonly>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nit" id="Nit" type="text" value="{{ $vida->Nit }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                    Asegurado</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="" readonly>{{ $vida->GrupoAsegurado }} </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                    Especiales</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="" readonly>{{ $vida->ClausulasEspeciales }} </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                    Adicionales</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4"> {{ $vida->BeneficiosAdicionales }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Concepto</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="Concepto" row="3" col="4"> {{ $vida->Concepto }} </textarea>
                                </div>
                            </div>
                            @if ($vida->TipoCobro == 2)
                            <div class="form-group row">

                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Limite Grupal </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="LimiteGrupo" id="LimiteGrupo" type="number" step="any" value="{{$vida->LimiteGrupo }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Limite Individual </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="LimiteIndividual" id="LimiteIndividual" type="number" step="any" value="{{ $vida->LimiteIndividual }}" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                    Desde</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="VigenciaDesde" type="text" value="{{ \Carbon\Carbon::parse($vida->VigenciaDesde)->format('d/m/Y') }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                    Hasta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="VigenciaHasta" type="text" value="{{ \Carbon\Carbon::parse($vida->VigenciaHasta)->format('d/m/Y') }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    Cartera</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TipoCartera" type="text" value="{{ $vida->tipoCarteras->Nombre ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Ejecutivo" type="text" value="{{ $vida->ejecutivos->Nombre }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estatus</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="EstadoPoliza" type="text" value="{{ $vida->estadoPolizas->Nombre ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                    Cobro</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TipoCobro" type="text" value="{{ $vida->tipoCobros->Nombre }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                </label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    @if ($vida->Mensual == 1)
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Mensual" value="1" checked disabled>
                                        <label class="control-label">Tasa Millar Mensual</label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Anual" value="0" disabled>
                                        <label class="control-label">Tasa ‰ Millar Anual</label>
                                    </div>
                                    @else
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Mensual" value="1" disabled>
                                        <label class="control-label">Tasa Millar Mensual</label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Anual" value="0" checked disabled>
                                        <label class="control-label">Tasa ‰ Millar Anual</label>
                                    </div>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                    %</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Tasa" id="Tasa" type="number" step="any" value="{{ $vida->Tasa }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa Comision
                                    %</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ $vida->TasaComision }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa Descuento
                                    %</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TasaDescuento" id="TasaDescuento" type="number" step="any" value="{{ $vida->TasaDescuento }}">
                                </div>
                            </div>
                        </div>
                        <br>


                        <br><br>

                        <br>
                    </div>
                    <br>
                    &nbsp;
                    <br>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Estado de Pagos</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Generar Pago</a>
                                </li>
                                @if ($vida->TipoCobro == 1)
                                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="usuarios-tab" data-toggle="tab" aria-expanded="false">Usuarios</a>
                                </li>
                                @endif
                            </ul>


                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <div class="x_title">
                                        <br>&nbsp;

                                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                        <h2>Pagos<small></small>
                                        </h2>
                                        <ul class="nav navbar-right panel_toolbox">

                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div>
                                        <br>
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <th><br><i class="fa fa-pencil"></i></th>
                                                <th>Tasa</th>
                                                <th>Descuento</th>
                                                <th>A Pagar</th>
                                                <th>Impresion de Recibo</th>
                                                <th>Envio de Cartera</th>
                                                <th>Envio de Pago</th>
                                                <th>Pago Aplicado</th>
                                            </tr>
                                            @foreach ($detalle as $obj)
                                            <tr>
                                                <td><i class="fa fa-pencil" onclick="modal_edit({{ $obj->Id }})"></i>
                                                </td>
                                                <td>{{ $obj->Tasa }}</td>
                                                <td>{{ $obj->Descuento }}</td>
                                                <td>{{ $obj->APagar }}</td>
                                                <td>{{ $obj->ImpresionRecibo }}</td>
                                                <td>{{ $obj->EnvioCartera }}</td>
                                                <td>{{ $obj->EnvioPago }}</td>
                                                <td>{{ $obj->PagoAplicado }}</td>
                                            </tr>
                                            @endforeach
                                        </table>

                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                    <div class="x_title">

                                        <br>&nbsp;
                                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                        <h2>Pagos<small></small>
                                        </h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <div class="btn btn-info float-right" data-toggle="modal" data-target=".bs-example-modal-lg">Nuevo</div>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                        <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
                                                    </div>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ url('polizas/vida/create_pago') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                                <select name="Axo" class="form-control">
                                                                    @for ($i = date('Y'); $i >= 2022; $i--)
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                                <select name="Mes" class="form-control">
                                                                    @for ($i = 1; $i < 12; $i++) @if (date('m') == $i) <option value="{{ $i }}" selected>{{ $meses[$i] }}
                                                                        </option>
                                                                        @else
                                                                        <option value="{{ $i }}">{{ $meses[$i] }}</option>
                                                                        @endif
                                                                        @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                                inicio</label>
                                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                                <input class="form-control" name="Id" value="{{ $vida->Id }}" type="hidden" required>
                                                                <input class="form-control" name="FechaInicio" type="date" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                                final</label>
                                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                                <input class="form-control" name="FechaFinal" type="date" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                                <input class="form-control" name="Archivo" type="file" required>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Validar</label>
                                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                            <input name="Validar" type="checkbox" checked class="js-switch" />
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Aceptar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($vida->TipoCobro == 1)
                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="usuarios-tab">


                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="Usuarios">
                                        <div class="x_title">
                                            <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                            <h2>Usuarios<small></small>
                                            </h2>
                                            <div class="clearfix" align="right">
                                                <a class="btn btn-primary" onclick="modal_usuario(<?php echo $vida->Id; ?>, <?php echo $vida->Tasa; ?>, document.getElementById('Mensual').checked, document.getElementById('Anual').checked);"><i class="fa fa-plus"></i>&nbsp; Nuevo Usuario </a>
                                            </div>
                                            <br>@php($montocartera = 0)
                                            @php($subtotal = 0)

                                        </div>
                                        <br>

                                        <div>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Numero Usuarios</th>
                                                        <th>Suma Asegurada</th>
                                                        <th>MontoCartera</th>
                                                        <th>Tasa</th>
                                                        <th>Sub Total Asegurado</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($totalUsuario = 0)
                                                    @php($montocartera = 0)
                                                    @php($subtotal = 0)
                                                    @foreach ($usuario_vidas as $obj)
                                                    <tr>
                                                        <td>{{ $obj->NumeroUsuario }}</td>
                                                        <td>{{ $obj->SumaAsegurada }}</td>
                                                        <td>{{ $obj->SubTotalAsegurado }}</td>
                                                        <td>{{ $obj->Tasa }}</td>
                                                        <td>{{ $obj->TotalAsegurado }}</td>
                                                        <td>
                                                            <a onclick="edit_usuario({{$obj->Id}},{{$obj->Tasa}},{{$obj->NumeroUsuario}},{{$obj->SumaAsegurada}},{{$obj->SubTotalAsegurado}},{{$obj->TotalAsegurado}},{{$vida->Mensual}})" data-toggle="modal" class="on-default edit-row">
                                                                <i class="fa fa-pencil fa-lg"></i></a>
                                                            &nbsp;&nbsp;<a onclick="delete_usuario({{$obj->Id}})" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                                        </td>

                                                        @php($totalUsuario += $obj->NumeroUsuario)
                                                        @php($montocartera += $obj->SubTotalAsegurado)
                                                        @php($subtotal += $obj->TotalAsegurado)
                                                    </tr>



                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>Totales</td>
                                                        <td></td>
                                                        <td>${{ $montocartera }}</td>
                                                        <td></td>
                                                        <td>${{ $subtotal }}</td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <br>
                                    </div>
                                    @include('polizas.vida.modal_usuario')

                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>


                <br><br>



            </div>

        </div>
    </div>





</div>


<div class="modal fade " id="modal_editar_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('polizas/vida/edit_pago') }}">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Pago</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        @csrf
                        <input type="hidden" name="Id" id="ModalId" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Saldo a</label>
                                <input type="date" name="SaldoA" id="ModalSaldoA" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Impresión de Recibo</label>
                                <input type="date" name="ImpresionRecibo" id="ModalImpresionRecibo" class="form-control" readonly>
                            </div>
                        </div>




                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Envio cartera</label>
                                <input type="date" name="EnvioCartera" id="ModalEnvioCartera" class="form-control">
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Envio pago</label>
                                <input type="date" name="EnvioPago" id="ModalEnvioPago" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Pago aplicado</label>
                                <input type="date" name="PagoAplicado" id="ModalPagoAplicado" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Comentario</label>
                                <textarea class="form-control" rows="4" name="Comentario" id="ModalComentario"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
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
    $(document).ready(function() {

        $('#ModalSumaAseguradaa').change(function() {
            calculo();
        })
        $('#ModalNumeroUsuarioo').change(function() {
            calculo();
        })
        $('#ModalSubTotall').change(function() {
            calculo();
        })
        $('#ModalTasaUsuarioo').change(function() {
            calculo();
        })
        $('#ModalTotalAseguradaa').change(function() {
            calculo();
        })

        function calculo() {
            if (document.getElementById('ModalTipoTasaa').value == 1) { //mensual
                var tasa = (document.getElementById('ModalTasaUsuarioo').value / 1000);
                var usuarios = document.getElementById('ModalNumeroUsuarioo').value;
                document.getElementById('ModalSubTotall').value = usuarios * document.getElementById(
                    'ModalSumaAseguradaa').value;
                document.getElementById('ModalTotalAseguradoo').value = document.getElementById(
                    'ModalSubTotall').value * tasa;

            } else if (document.getElementById('ModalTipoTasaa').value == 0) { //anual
                var tasa = (document.getElementById('ModalTasaUsuarioo').value / 1000) / 12;
                var usuarios = document.getElementById('ModalNumeroUsuarioo').value;
                document.getElementById('ModalSubTotall').value = usuarios * document.getElementById(
                    'ModalSumaAseguradaa').value;
                document.getElementById('ModalTotalAseguradoo').value = document.getElementById(
                    'ModalSubTotall').value * tasa;
            }

        }

        $("#MontoCartera").change(function() {
            calculoSubTotal();
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoSubTotal() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            if (document.getElementById('Anual').checked == true) {
                var tasaFinal = (tasa / 1000) / 12;
            } else {
                var tasaFinal = tasa / 1000;
            }
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
        }
        $('#ExtPrima').change(function() {
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoPrimaTotal() {
            var sub = document.getElementById('SubTotal').value;
            var extra = document.getElementById('ExtPrima').value;
            var prima = Number(sub) + Number(extra);
            document.getElementById('PrimaTotal').value = Number(prima);
        }
        $("#Descuento").change(function() {
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoPrimaDescontada() {
            var prima = document.getElementById('PrimaTotal').value;
            var descuento = document.getElementById('Descuento').value;
            if (descuento == 0) {
                var total = Number(prima);
            } else {
                var total = Number(prima * (descuento / 100));
            }
            document.getElementById('PrimaDescontada').value = total + Number(document.getElementById(
                'PrimaTotal').value);

        }

        $("#TasaComision").change(function() {
            calculoCCF();
        })

        function calculoCCF() {
            var comision = document.getElementById('TasaComision').value;
            var total = document.getElementById('PrimaDescontada').value;

            var valorDes = total * (comision / 100);
            document.getElementById('ValorDescuento').value = Number(valorDes);
            var IvaSobreComision = Number(valorDes) * 0.13;

            document.getElementById('IvaSobreComision').value = Number(IvaSobreComision);
            if (document.getElementById('Retencion').hasAttribute('readonly')) {
                var Retencion = 0;
            } else {
                var Retencion = valorDes * 0.01;
                document.getElementById('Retencion').value = Retencion;
            }
            var ValorCCF = Number(valorDes) + Number(IvaSobreComision) - Number(Retencion);
            // alert(ValorCCF);
            document.getElementById('ValorCCFE').value = Number(ValorCCF);
            document.getElementById('ValorCCF').value = Number(ValorCCF);
            var PrimaTotal = document.getElementById('PrimaTotal').value;
            var APagar = Number(PrimaTotal) - Number(ValorCCF);
            document.getElementById('APagar').value = APagar;
        }

        $("#habilitar").click(function() {
            //  $("#btn_guardar").click(function() {
            //  document.getElementById('ImpresionRecibo').removeAttribute('readonly');
            document.getElementById('ImpresionRecibo').value = '';
            document.getElementById('EnvioCartera').type = 'date';
            document.getElementById('EnvioPago').type = 'date';
            document.getElementById('PagoAplicado').type = 'date';
            document.getElementById('SaldoA').type = 'date';
            document.getElementById('ValorDescuento').value = 0;
            document.getElementById('IvaSobreComision').value = 0;
            document.getElementById('Retencion').value = 0;
            document.getElementById('ValorCCFE').value = 0;

        })
        $('#SaldoA').change(function() {
            var hoy = new Date().toLocaleDateString();
            //alert(hoy);
            document.getElementById('ImpresionRecibo').value = hoy;
            document.getElementById('ImpresionRecibo').setAttribute("readonly", true);
        })
        $('#EnvioCartera').change(function() {
            var hoy = new Date();
            // alert(hoy);
            if (document.getElementById('ImpresionRecibo').value <= document.getElementById(
                    'EnvioCartera')) {
                alert('debe seleccionar una fecha mayor o igual a la impresion recibo');
            }

        })
        $('#EnvioPago').change(function() {
            var hoy = new Date();
            // alert(hoy);
            if (document.getElementById('EnvioCartera').value <= document.getElementById('EnvioPago')) {
                alert('debe seleccionar una fecha mayor o igual a la envio de cartera');
            }

        })
        $('#PagoAplicado').change(function() {
            var hoy = new Date();
            // alert(hoy);
            if (document.getElementById('EnvioPago').value <= document.getElementById('PagoAplicado')) {
                alert('debe seleccionar una fecha mayor o igual a la envio de pago');
            }

        })


    });

    function edit_usuario(id, tasa, usuarios, suma_asegurada, sub_total, total, tipoTasa) {
        document.getElementById('ModalEditId').value = id;
        document.getElementById('ModalEditTasaUsuario').value = tasa;
        document.getElementById('ModalEditNumeroUsuario').value = usuarios;
        document.getElementById('ModalEditSumaAsegurada').value = suma_asegurada;
        document.getElementById('ModalEditSubTotal').value = sub_total;
        document.getElementById('ModalEditTotalAsegurado').value = total;
        if (tipoTasa == 1) {
            document.getElementById('ModalEditTipoTasa').value = 1;
        } else if (tipoTasa == 0) {
            document.getElementById('ModalEditTipoTasa').value = 0;
        }
        $('#modal-usuario-edit').modal('show');
    }



    function delete_usuario(id) {
        document.getElementById('ModalDeleteId').value = id;
        $('#modal-usuario-delete').modal('show');

    }



    function modal_edit(id) {
        document.getElementById('ModalSaldoA').value = "";
        document.getElementById('ModalImpresionRecibo').value = "";
        document.getElementById('ModalComentario').value = "";
        document.getElementById('ModalEnvioCartera').value = "";
        document.getElementById('ModalEnvioPago').value = "";
        document.getElementById('ModalPagoAplicado').value = "";
        document.getElementById('ModalId').value = id;



        $.get("{{ url('polizas/vida/get_pago') }}" + '/' + id, function(data) {
            console.log(data);
            document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
            document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
            document.getElementById('ModalComentario').value = data.Comentario;
            if (data.EnvioCartera) {
                document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
            } else {
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.EnvioPago) {
                document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
            } else {
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.PagoAplicado) {
                document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            } else {
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
            }



        });
        $('#modal_editar_pago').modal('show');

    }

    function modal_usuario(id, Tasa, Mensual, Anual) {
        // alert(Mensual);
        document.getElementById('ModalId').value = id;
        document.getElementById('ModalTasaUsuario').value = Tasa;
        if (Mensual == true) {
            document.getElementById('ModalTipoTasa').value = 1;
        } else if (Anual == true) {
            document.getElementById('ModalTipoTasa').value = 0;
        }
        $('#modal_usuario').modal('show');


    }

    function modal_usuario_edit(id) {
        $('#modal_usuario_edit').modal('show');
    }
</script> --}}
@endsection
