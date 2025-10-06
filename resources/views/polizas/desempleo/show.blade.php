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
                <h4>Polizas de Desempleo </h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('polizas/desempleo/') }}?idRegistro={{$desempleo->Id}}"><button class="btn btn-info float-right"> <i
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
                <a class="nav-link" id="hoja-tab" data-toggle="tab" href="#hoja" role="tab" aria-controls="hoja"
                    aria-selected="false">Hoja de Cartera {{ $desempleo->NumeroPoliza }}</a>
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
                        <label>Saldos y Montos</label>
                        <input type="text" class="form-control"
                            value="{{ $desempleo->saldos->Abreviatura }} - {{ $desempleo->saldos->Descripcion }}"
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" class="form-control" value="{{ $desempleo->estadoPoliza->Nombre ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Edad Máxima de Inscripción</label>
                        <input type="text" class="form-control" value="{{ $desempleo->EdadMaximaInscripcion }}"
                            readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Edad Terminación</label>
                        <input type="text" class="form-control" value="{{ $desempleo->EdadMaxima }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
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

                <ul class="nav navbar-right panel_toolbox">
                    <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">
                        Subir Archivo Excel</div>
                </ul>

                @include('polizas.desempleo.tab2')


            </div>
            <div class="tab-pane fade " id="hoja" role="tabpanel" aria-labelledby="hoja-tab">
                @include('polizas.desempleo.tab3')

            </div>
            <div class="tab-pane fade " id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
                @include('polizas.desempleo.tab4')

            </div>
            <div class="tab-pane fade " id="contact" role="tabpanel" aria-labelledby="contact-tab">
                @include('polizas.desempleo.tab5')

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
                                    value="{{ $fechaInicio }}" required min="{{ $desempleo->VigenciaDesde }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                final</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="FechaFinal" id="FechaFinal"
                                    value="{{ $fechaFinal }}" type="date" required
                                    max="{{ $desempleo->VigenciaHasta }}">
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

    <script>
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-desempleo");
        });


        document.getElementById('btnSubirCartera').addEventListener('click', function() {
            document.getElementById('loading-overlay').style.display = 'flex';
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
@endsection
