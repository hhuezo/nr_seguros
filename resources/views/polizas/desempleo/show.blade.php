@extends ('welcome')
@section('contenido')
    <style>
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loading-overlay img {
            width: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
            height: 50px;
            /* Ajusta el tamaño de la imagen según tus necesidades */
        }
    </style>
    <div class="x_panel">
        <div id="loading-overlay">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
        </div>


        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                 <h4>Pólizas/ Póliza de desempleo / {{ $desempleo->NumeroPoliza }}<small></small>
                </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('polizas/desempleo/') }}?idRegistro={{ $desempleo->Id }}"><button
                        class="btn btn-info float-right"> <i class="fa fa-arrow-left"></i></button></a>
            </div>
            <div class="clearfix"></div>
        </div>


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
                    aria-selected="false">Hoja de Cartera {{ $desempleo->NumeroPoliza }}</a>
            </li>
            <li class="nav-item {{ isset($tab) && $tab == 4 ? 'active in' : '' }}">
                <a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos"
                    aria-selected="false">Estado de Pago</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">Ver Aviso</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="comentarios-tab" data-toggle="tab" href="#comentarios" role="tab"
                    aria-controls="comentarios" aria-selected="false">Comentarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="historico-tab" data-toggle="tab" href="#historico" role="tab"
                    aria-controls="historico" aria-selected="false">Histórico de pagos</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ isset($tab) && $tab == 1 ? 'active in' : '' }}" id="home" role="tabpanel"
                aria-labelledby="home-tab">

                <div class="col-md-6">
                    <!-- Número de Póliza -->
                    <div class="form-group">
                        <label>Número de Póliza</label>
                        <input type="text" class="form-control" value="{{ $desempleo->NumeroPoliza }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Productos</label>
                        <input type="text" class="form-control"
                            value="{{ $desempleo->planes && $desempleo->planes->productos ? $desempleo->planes->productos->Nombre : '' }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Asegurado</label>
                        <input type="text" class="form-control" value="{{ $desempleo->cliente->Nombre }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Ejecutivo</label>
                        <input type="text" class="form-control" value="{{ $desempleo->ejecutivo->Nombre }}" readonly>
                    </div>

                </div>

                <!-- Columna 2 -->
                <div class="col-md-6">
                    <!-- Aseguradora -->
                    <div class="form-group">
                        <label>Aseguradora</label>
                        <input type="text" class="form-control" value="{{ $desempleo->aseguradora->Nombre }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Planes</label>
                        <input type="text" class="form-control"
                            value="{{ $desempleo->planes ? $desempleo->planes->Nombre : '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nit</label>
                        <input type="text" class="form-control" value="{{ $desempleo->cliente->Nit }}" readonly>
                    </div>


                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" class="form-control" value="{{ $desempleo->estadoPoliza->Nombre ?? '' }}"
                            readonly>
                    </div>


                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Vigencia Desde</label>
                        <input type="date" class="form-control" value="{{ $desempleo->VigenciaDesde }}" readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Vigencia Hasta</label>
                        <input type="date" class="form-control" value="{{ $desempleo->VigenciaHasta }}" readonly>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label>Edad Máxima de Inscripción</label>
                        <input type="text" class="form-control" value="{{ $desempleo->EdadMaximaInscripcion }}"
                            readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Edad Terminación</label>
                        <input type="text" class="form-control" value="{{ $desempleo->EdadMaxima }}" readonly>
                    </div>
                </div>

                <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" align="right">Tasa Diferenciada</label>
                    <select name="TasaDiferenciada" id="TasaDiferenciada" class="form-control" disabled>
                        <option value="0" {{ $desempleo->TasaDiferenciada == 0 ? 'selected' : '' }}>NO</option>
                        <option value="1" {{ $desempleo->TasaDiferenciada == 1 ? 'selected' : '' }}>SI</option>
                    </select>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tasa</label>
                        <input type="text" class="form-control" value="{{ $desempleo->Tasa }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Comisión</label>
                        <input type="text" class="form-control" value="{{ $desempleo->Descuento }}" readonly>
                    </div>
                </div>



                <!-- Concepto -->
                <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label" align="right">Concepto</label>
                    <textarea class="form-control" name="Concepto" readonly rows="3" cols="4">{{ $desempleo->Concepto }}</textarea>
                </div>
                <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label" align="right">Beneficios Adicionales </label>
                    <textarea class="form-control" name="Beneficios" readonly rows="3" cols="4">{{ $desempleo->Beneficios }}</textarea>
                </div>

                <!-- Concepto -->
                <div class="item form-group col-sm-12 col-md-4 col-lg-4">
                    <label class="control-label" align="right">Concepto</label>
                    <textarea class="form-control" name="Concepto" readonly rows="3" cols="4">{{ $desempleo->Concepto }}</textarea>
                </div>


                <!-- Tipo de Cálculo -->
                {{-- <div class="form-group">
                    <label>Tipo de Cálculo</label>
                    <input type="text" class="form-control"
                        value="{{ $desempleo->TipoCalculo == 1 ? 'Por cabeza' : 'Por crédito' }}" readonly>
                </div> --}}





            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">


                @include('polizas.desempleo.tab2')


            </div>
            <div class="tab-pane fade " id="hoja" role="tabpanel" aria-labelledby="hoja-tab">
                @include('polizas.desempleo.tab3')

            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 4 ? 'active in' : '' }}" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
                @include('polizas.desempleo.tab4')

            </div>
            <div class="tab-pane fade " id="contact" role="tabpanel" aria-labelledby="contact-tab">
                @include('polizas.desempleo.tab5')

            </div>
            <div class="tab-pane fade " id="comentarios" role="tabpanel" aria-labelledby="comentarios-tab">
                @include('polizas.desempleo.tab6')

            </div>
            <div class="tab-pane fade " id="historico" role="tabpanel" aria-labelledby="historico-tab">
                @include('polizas.desempleo.tab7')

            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-desempleo");
        });

        function mostrar_historial(axo, mes, fechaInicio, fechaFinal, polizaDeuda) {
            $.ajax({
                url: "{{ url('polizas/vida/get_historico') }}",
                type: 'GET',
                data: {
                    Axo: axo,
                    Mes: mes,
                    FechaInicio: encodeURIComponent(fechaInicio), // Codifica las fechas
                    FechaFinal: encodeURIComponent(fechaFinal), // Codifica las fechas
                    PolizaDeuda: polizaDeuda
                },
                success: function(response) {
                    $('#historial_table').html(response);
                    $("#modal_historial").modal('show');
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function add_comment() {
            $("#modal_agregar_comentario").modal('show');
        }


    </script>
@endsection
