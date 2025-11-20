@extends ('welcome')
@section('contenido')

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div id="loading-overlay" style="display: none">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
        </div>


        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                 <h4>Pólizas/ Póliza de vida / {{ $poliza_vida->NumeroPoliza }}<small></small>
                </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('polizas/vida/') }}?idRegistro={{ $poliza_vida->Id }}"><button
                        class="btn btn-info float-right"> <i class="fa fa-arrow-left"></i></button></a>
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
                    aria-selected="true">Datos de <br>Póliza</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 2 ? 'active in' : '' }}">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Generar <br> cartera</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 3 ? 'active in' : '' }}">
                <a class="nav-link" id="extra-prima-tab" data-toggle="tab" href="#extra-prima" role="tab"
                    aria-controls="extra-prima" aria-selected="false">Extra Prima <br> {{ $poliza_vida->NumeroPoliza }}</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 4 ? 'active in' : '' }}">
                <a class="nav-link" id="hoja-tab" data-toggle="tab" href="#hoja" role="tab" aria-controls="hoja"
                    aria-selected="false">Hoja de Cartera <br> {{ $poliza_vida->NumeroPoliza }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos"
                    aria-selected="false">Estado <br> de Pago</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">Ver <br> Aviso</a>
            </li>
            <br><br>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ isset($tab) && $tab == 1 ? 'active in' : '' }}" id="home" role="tabpanel"
                aria-labelledby="home-tab">

                <br><br>

                <div class="col-sm-12" style="padding: 0% !important">
                    <!-- Número de Póliza -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label" align="right">Número de Póliza *</label>
                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                            value="{{ $poliza_vida->NumeroPoliza }}" readonly>
                    </div>

                    <!-- Aseguradora -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label" align="right">Aseguradora *</label>
                        <input class="form-control" type="text" value="{{ $poliza_vida->aseguradora->Nombre ?? '' }}"
                            readonly>
                    </div>

                    <!-- Productos -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label">Productos *</label>
                        <input class="form-control" type="text"
                            value="{{ $poliza_vida->planes->productos->Nombre ?? '' }}" readonly>

                    </div>

                    <!-- Planes -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label">Planes *</label>
                        <input class="form-control" type="text" value="{{ $poliza_vida->planes->Nombre ?? '' }}"
                            readonly>
                    </div>

                    <!-- Asegurado -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label" align="right">Asegurado</label>
                        <input class="form-control" type="text" value="{{ $poliza_vida->cliente->Nombre ?? '' }}"
                            readonly>

                    </div>

                    <!-- Nit -->
                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                        <label class="control-label" align="right">Nit</label>
                        <input class="form-control" name="Nit" id="Nit" type="text"
                            value="{{ $poliza_vida->cliente->Nit ?? '' }}" readonly>
                    </div>
                </div>

                <!-- Ejecutivo -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Ejecutivo</label>
                    <input class="form-control" type="text" value="{{ $poliza_vida->ejecutivo->Nombre ?? '' }}"
                        readonly>
                </div>

                <!-- Tipo cobro -->
                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Tipo cobro</label>
                    <select name="TipoCobro" class="form-control" onchange="showTipoCobro(this.value)" readonly>
                        @foreach ($tipoCobro as $tipo)
                            <option value="{{ $tipo->Id }}"
                                {{ $poliza_vida->TipoCobro == $tipo->Id ? 'selected' : '' }}>
                                {{ $tipo->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($poliza_vida->TipoCobro == 1)
                    <div class="col-sm-12" style="padding: 0% !important;">
                        <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                            <label class="control-label" align="right">Suma minima</label>
                            <input class="form-control" name="SumaMinima" type="number" min="0.00" step="any"
                                value="{{ $poliza_vida->SumaMinima }}">
                        </div>
                        <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                            <label class="control-label" align="right">Suma máxima</label>
                            <input class="form-control" name="SumaMaxima" type="number" min="0.00" step="any"
                                value="{{ $poliza_vida->SumaMaxima }}" readonly>
                        </div>
                    </div>
                @endif

                @if ($poliza_vida->TipoCobro == 2)
                    <div class="col-sm-12" style="padding: 0% !important;" id="div-cobro-creditos">
                        <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                            <label class="control-label" align="right">Tipo de suma</label>
                            <select name="TipoTarifa" class="form-control" onchange="showMultitarifa(this.value)"
                                readonly>
                                <option value="1" {{ $poliza_vida->TipoTarifa == 1 ? 'selected' : '' }}>Suma
                                    uniforme</option>
                                <option value="2" {{ $poliza_vida->TipoTarifa == 2 ? 'selected' : '' }}>
                                    Multicategoria</option>
                            </select>
                        </div>
                        @if ($poliza_vida->TipoTarifa == 1)
                            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                <label class="control-label" align="right">Suma asegurada</label>
                                <input class="form-control" name="SumaAsegurada" type="number" step="any"
                                    value="{{ $poliza_vida->SumaAsegurada }}" min="100" readonly>
                            </div>
                        @endif

                        @if ($poliza_vida->TipoTarifa == 2)
                            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                <label class="control-label" align="right">Multicategoria</label>
                                <input class="form-control" name="Multitarifa" type="text" id="Multitarifa"
                                    value="{{ $poliza_vida->Multitarifa }}" oninput="formatMultitarifa(this)" readonly>
                                <label id="multitarifa-error" class="text-danger" style="display: none;">Formato
                                    inválido: use cantidades separadas por coma.</label>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="col-sm-12" style="padding: 0% !important">



                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" align="right">Opcion</label>
                        <select name="Opcion" id="Opcion" class="form-control" readonly>
                            <option value="0">NO APLICA</option>
                            <option value="1" {{ $poliza_vida->TasaDiferenciada == 1 ? 'selected' : '' }}>
                                TASA
                                DIFERENCIADA</option>
                            <option value="2" {{ $poliza_vida->TarifaExcel == 1 ? 'selected' : '' }}>COBRO
                                CON
                                TARIFA EXCEL</option>
                        </select>

                    </div>

                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" align="right">Tasa Millar Mensual</label>
                        <input class="form-control" name="Tasa" id="Tasa" type="number" step="any"
                            value="{{ $poliza_vida->Tasa }}" readonly>
                    </div>


                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" align="right">Edad máxima de inscripción</label>
                        <input class="form-control" name="EdadMaximaInscripcion" type="number" min="18"
                            max="100" step="any" value="{{ $poliza_vida->EdadMaximaInscripcion }}" readonly>
                    </div>

                    <!-- Edad Terminación -->
                    <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" align="right">Edad Terminación</label>
                        <input class="form-control" name="EdadTerminacion" type="number" step="any" min="18"
                            max="100" value="{{ $poliza_vida->EdadTerminacion }}" readonly>
                    </div>

                </div>


                <div class="col-sm-12" style="padding: 0% !important">
                    <!-- Vigencia Desde -->
                    <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                        <label class="control-label" align="right">Vigencia Desde</label>
                        <input class="form-control" name="VigenciaDesde" type="date"
                            value="{{ $poliza_vida->VigenciaDesde }}" readonly>
                    </div>

                    <!-- Vigencia Hasta -->
                    <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                        <label class="control-label" align="right">Vigencia Hasta</label>
                        <input class="form-control" name="VigenciaHasta" type="date"
                            value="{{ $poliza_vida->VigenciaHasta }}" readonly>
                    </div>

                    <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                        <label class="control-label" align="right">Status </label>
                        <input class="form-control" name="VigenciaHasta" type="text"
                            value="{{ $poliza_vida->estadoPoliza->Nombre ?? '' }}" readonly>
                    </div>
                </div>




                <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label" align="right">Clausulas Especiales </label>
                    <textarea class="form-control" name="ClausulasEspeciales" readonly rows="3" cols="4">{{ $poliza_vida->ClausulasEspeciales }}</textarea>
                </div>
                <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label" align="right">Beneficios Adicionales </label>
                    <textarea class="form-control" name="Beneficios" readonly rows="3" cols="4">{{ $poliza_vida->Beneficios }}</textarea>
                </div>

                <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label" align="right">Concepto</label>
                    <textarea class="form-control" name="Concepto" readonly rows="3" cols="4">{{ $poliza_vida->Concepto }}</textarea>
                </div>

                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">Descuento</label>
                    <input class="form-control" name="TasaDescuento" readonly type="number" step="any"
                        value="{{ $poliza_vida->TasaDescuento }}">
                </div>

                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                    <label class="control-label" align="right">% de Comisión *</label>
                    <input class="form-control" name="TasaComision" readonly id="TasaComision" type="number"
                        step="any" value="{{ $poliza_vida->TasaComision }}" required>
                </div>







            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">

                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{ url('polizas/vida/subir_cartera') }}/{{ $poliza_vida->Id }}">
                        <button type="button" class="btn btn-info float-right">
                            Subir Archivo Excel</button>
                    </a>
                </ul>


                @include('polizas.vida.tab2')

            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 3 ? 'active in' : '' }}" id="extra-prima"
                role="tabpanel" aria-labelledby="extra-prima-tab">
                <br>
                @include('polizas.vida.tab5')
            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 4 ? 'active in' : '' }}" id="hoja" role="tabpanel"
                aria-labelledby="hoja-tab">

                @include('polizas.vida.tab3')
            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 5 ? 'active in' : '' }}" id="pagos" role="tabpanel"
                aria-labelledby="pagos-tab">
                <br>
                @include('polizas.vida.tab4')
            </div>
            <div class="tab-pane fade " id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <br>
                @include('polizas.vida.tab6')
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

            $('#clientes-extra').DataTable();
            $('#clientes').DataTable();


            // Inicialización automática al cargar la vista
            let tipoCobro = "{{ $poliza_vida->TipoCobro }}";
            let tipoTarifa = "{{ $poliza_vida->TipoTarifa }}";

            showTipoCobro(tipoCobro);
            showMultitarifa(tipoTarifa);

        });



        function showTipoCobro(value) {
            let divUsuarios = document.getElementById("div-cobro-usuarios");
            let divCreditos = document.getElementById("div-cobro-creditos");

            if (value == 1) {
                divUsuarios.style.display = "block";
                divCreditos.style.display = "none";
            } else if (value == 2) {
                divUsuarios.style.display = "none";
                divCreditos.style.display = "block";
            } else {
                divUsuarios.style.display = "none";
                divCreditos.style.display = "none";
            }
        }

        function showMultitarifa(value) {
            let divSuma = document.getElementById("div-sumaAsegurada");
            let divMulti = document.getElementById("div-multitarifa");

            if (value == 1) {
                divSuma.style.display = "block";
                divMulti.style.display = "none";
            } else if (value == 2) {
                divSuma.style.display = "none";
                divMulti.style.display = "block";
            } else {
                divSuma.style.display = "none";
                divMulti.style.display = "none";
            }
        }
    </script>

@endsection
