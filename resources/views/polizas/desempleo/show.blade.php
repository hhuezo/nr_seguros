@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Polizas de Desempleo </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('polizas/desempleo/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-arrow-left"></i></button></a>
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
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                    aria-controls="contact" aria-selected="false">Contact</a>
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

                    <!-- Asegurado -->
                    <div class="form-group">
                        <label>Asegurado</label>
                        <input type="text" class="form-control" value="{{ $desempleo->cliente->Nombre }}" readonly>
                    </div>

                    <!-- Nit -->
                    <div class="form-group">
                        <label>Nit</label>
                        <input type="text" class="form-control" value="{{ $desempleo->cliente->Nit }}" readonly>
                    </div>

                    <!-- Vigencia Desde -->
                    <div class="form-group">
                        <label>Vigencia Desde</label>
                        <input type="date" class="form-control" value="{{ $desempleo->VigenciaDesde }}" readonly>
                    </div>

                    <!-- Edad Máxima de Inscripción -->
                    <div class="form-group">
                        <label>Edad Máxima de Inscripción</label>
                        <input type="text" class="form-control" value="{{ $desempleo->EdadMaximaInscripcion }}" readonly>
                    </div>

                    <!-- Tasa -->
                    <div class="form-group">
                        <label>Tasa</label>
                        <input type="text" class="form-control" value="{{ $desempleo->Tasa }}" readonly>
                    </div>
                </div>

                <!-- Columna 2 -->
                <div class="col-md-6">
                    <!-- Aseguradora -->
                    <div class="form-group">
                        <label>Aseguradora</label>
                        <input type="text" class="form-control" value="{{ $desempleo->aseguradora->Nombre }}" readonly>
                    </div>

                    <!-- Ejecutivo -->
                    <div class="form-group">
                        <label>Ejecutivo</label>
                        <input type="text" class="form-control" value="{{ $desempleo->ejecutivo->Nombre }}" readonly>
                    </div>


                    <!-- Saldos y montos -->
                    <div class="form-group">
                        <label>Saldos y Montos</label>
                        <input type="text" class="form-control" value="{{ $desempleo->saldos->Abreviatura }} - {{ $desempleo->saldos->Descripcion }}" readonly>
                    </div>


                    <!-- Vigencia Hasta -->
                    <div class="form-group">
                        <label>Vigencia Hasta</label>
                        <input type="date" class="form-control" value="{{ $desempleo->VigenciaHasta }}" readonly>
                    </div>

                    <!-- Edad Máxima -->
                    <div class="form-group">
                        <label>Edad Máxima</label>
                        <input type="text" class="form-control" value="{{ $desempleo->EdadMaxima }}" readonly>
                    </div>

                    <!-- Tipo de Cálculo -->
                    <div class="form-group">
                        <label>Tipo de Cálculo</label>
                        <input type="text" class="form-control"
                            value="{{ $desempleo->TipoCalculo == 1 ? 'Por cabeza' : 'Por crédito' }}" readonly>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade {{ isset($tab) && $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
                aria-labelledby="profile-tab">

                <ul class="nav navbar-right panel_toolbox">
                    <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">
                        Subir Archivo Excel</div>
                </ul>

                @include('polizas.desempleo.tab2')


            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1
                labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer
                twee. Qui photo
                booth letterpress, commodo enim craft beer mlkshk
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
                <form action="{{ url('polizas/desempleo/create_pago') }}/{{ $desempleo->Id }}" method="POST"
                    enctype="multipart/form-data">
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
                                <input class="form-control" name="Id" value="{{ $desempleo->Id }}" type="hidden"
                                    required>
                                <input class="form-control" type="date" name="FechaInicio" id="FechaInicio"
                                    value="{{ $fechaInicio }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                final</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="FechaFinal" id="FechaFinal"
                                    value="{{ $fechaFinal }}" type="date" required>
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
                        <button type="submit" class="btn btn-primary">Subir Cartera</button>
                    </div>
                </form>

                <div id="loading-indicator" style="text-align: center; display:none">
                    <img src="{{ asset('img/ajax-loader.gif') }}">
                    <br>
                </div>


            </div>
        </div>
    </div>

    <script>
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
@endsection
