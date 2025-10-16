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
<div role="main">
    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>


    <div class="">

        <div class="clearfix"></div>



        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h2>Resumen de cartera</h2>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                            <div class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-primer-ingreso">
                                                Primera carga
                                            </div>

                                    </td>
                                    <td style="vertical-align: top;">
                                        <form method="post"
                                            action="{{ url('polizas/vida/delete_temp') }}/{{ $poliza_vida->Id }}">
                                            @csrf
                                            <button class="btn btn-default">Borrar Proceso Actual</button>
                                        </form>
                                    </td>
                                    <td>

                                        <form method="post"
                                            action="{{ url('polizas/vida/store_poliza') }}/{{ $poliza_vida->Id }}">
                                            @csrf
                                            <input type="hidden" name="MesActual" value="{{ $mesActual }}">
                                            <input type="hidden" name="AxoActual" value="{{ $axoActual }}">

                                            <input type="hidden" name="Eliminados"
                                                value="{{ $registros_eliminados->isNotEmpty() ? implode(', ', $registros_eliminados->pluck('NumeroReferencia')->toArray()) : '' }}">


                                            {{-- <input type="hidden" name="MesAnterior" value="{{ $mesAnterior }}">
                                            <input type="hidden" name="AxoAnterior" value="{{ $axoAnterior }}"> --}}


                                            <button id="btnGuardarCartera" type="submit" class="btn btn-primary"
                                                disabled>
                                                Guardar en cartera
                                            </button>

                                        </form>
                                    </td>
                                </tr>
                            </table>



                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-md-12 col-sm-12 ">

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab_edad" id="edad-tab"
                                            role="tab" data-toggle="tab" aria-expanded="false">Monto máximo
                                            <br>individual</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_inscripcion"
                                            id="inscripcion-tab" role="tab" data-toggle="tab"
                                            aria-expanded="false">Edad <br> Inscripción </a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_maxima"
                                            id="maxima-tab" role="tab" data-toggle="tab"
                                            aria-expanded="false">Edad <br> Máxima </a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_nuevos" id="home-tab"
                                            role="tab" data-toggle="tab" aria-expanded="true">Nuevos <br>
                                            registros</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_eliminados" id="profile-tab5"
                                            role="tab" data-toggle="tab" aria-expanded="false">Registros <br>
                                            Eliminados</a>
                                    </li>

                                    <li role="presentation" class=""><a href="#tab_rehabilitados" role="tab"
                                            id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros
                                            <br />rehabilitados</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_extraprimados" role="tab"
                                            id="profile-tab2" data-toggle="tab" aria-expanded="false">Extraprimados
                                            <br>
                                            excluidos</a>
                                    </li>


                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <br>
                                    <!-- edad maxima -->
                                    <div role="tabpanel" class="tab-pane active" id="tab_edad"
                                        aria-labelledby="edad-tab">


                                        <br>
                                        <div class="col-md-6 col-sm-6 col-xs-12"></div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo"
                                            style="display:">

                                            <form
                                                action="{{ url('exportar/vida/registros_edad_maxima') }}/{{ $poliza_vida->Id }}"
                                                method="POST">
                                                @csrf
                                                <button style="text-align: right;" class="btn btn-success">Descargar
                                                    Excel</button>
                                            </form>

                                        </div>
                                        <br><br>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-striped" id="table2">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad Otorgamiento</th>
                                                        <th>Edad Actual</th>
                                                        <th>Total</th>
                                                        <th>Monto máximo</th>
                                                        <th style="text-align: center;">Excluir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_responsabilidad_maxima as $registro)
                                                    <tr>
                                                        <td>{{ $registro->NumeroReferencia }}</td>
                                                        <td>{{ $registro->Dui }}</td>
                                                        <td>{{ $registro->Nit }}</td>
                                                        <td>{{ $registro->PrimerNombre }}
                                                            {{ $registro->SegundoNombre }}
                                                            {{ $registro->PrimerApellido }}
                                                            {{ $registro->SegundoApellido }}
                                                            {{ $registro->ApellidoCasada }}
                                                        </td>
                                                        <td>{{ $registro->FechaNacimientoDate ? date('d/m/Y', strtotime($registro->FechaNacimientoDate)) : '' }}
                                                        </td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                            Años</td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->Edad : '' }}
                                                            Años</td>
                                                        <td>${{ number_format($registro->SumaAsegurada, 2) }}</td>
                                                        <td>${{ number_format($registro->MontoMaximoIndividual, 2) }}
                                                        </td>
                                                        <td>
                                                            <input type="checkbox"
                                                                onchange="agregarNoValido({{ $registro->Id }})"
                                                                class="js-switch">
                                                        </td>
                                                    </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>



                                    </div>

                                    <!-- edad inscripción -->
                                    <div role="tabpanel" class="tab-pane" id="tab_inscripcion"
                                        aria-labelledby="edad-tab">
                                        <br>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <h4>Edad maxima de inscripción {{ $poliza_vida->EdadMaximaInscripcion }}
                                                años
                                            </h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo"
                                            style="display:show">

                                            <form
                                                action="{{ url('exportar/vida/registros_responsabilidad_maxima') }}/{{ $poliza_vida->Id }}"
                                                method="POST">
                                                @csrf
                                                <button style="text-align: right;" class="btn btn-success">Descargar
                                                    Excel</button>
                                            </form>

                                        </div>
                                        <br><br>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-striped" id="table3">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad Otorgamiento</th>
                                                        <th>Edad Actual</th>
                                                        <th>Total</th>
                                                        <th style="text-align: center;">Excluir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_edad_maxima as $registro)
                                                    <tr>
                                                        <td>{{ $registro->NumeroReferencia }}</td>
                                                        <td>{{ $registro->Dui }}</td>
                                                        <td>{{ $registro->Nit }}</td>
                                                        <td>{{ $registro->PrimerNombre }}
                                                            {{ $registro->SegundoNombre }}
                                                            {{ $registro->PrimerApellido }}
                                                            {{ $registro->SegundoApellido }}
                                                            {{ $registro->ApellidoCasada }}
                                                        </td>
                                                        <td>{{ $registro->FechaNacimientoDate ? date('d/m/Y', strtotime($registro->FechaNacimientoDate)) : '' }}
                                                        </td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                            Años</td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->Edad : '' }}
                                                            Años</td>
                                                        <td>${{ number_format($registro->SumaAsegurada, 2) }}</td>
                                                        <td>
                                                            <input type="checkbox" {{ $registro->NoValido == 1 ? 'checked' : '' }}
                                                                onchange="agregarNoValido({{ $registro->Id }})"
                                                                class="js-switch">

                                                        </td>
                                                    </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- edad inscripción -->
                                    <div role="tabpanel" class="tab-pane" id="tab_maxima"
                                        aria-labelledby="edad-tab">
                                        <br>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <h4>Edad de Terminacion {{ $poliza_vida->EdadTerminacion }}
                                                años
                                            </h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo"
                                            style="display:">

                                            <form
                                                action="{{ url('exportar/vida/registros_responsabilidad_terminacion') }}/{{ $poliza_vida->Id }}"
                                                method="POST">
                                                @csrf
                                                <button style="text-align: right;" class="btn btn-success">Descargar
                                                    Excel</button>
                                            </form>

                                        </div>
                                        <br><br>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-striped" id="table3">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad Otorgamiento</th>
                                                        <th>Edad Actual</th>
                                                        <th>Total</th>
                                                        <th style="text-align: center;">Excluir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_edad_terminacion as $registro)
                                                    <tr>
                                                        <td>{{ $registro->NumeroReferencia }}</td>
                                                        <td>{{ $registro->Dui }}</td>
                                                        <td>{{ $registro->Nit }}</td>
                                                        <td>{{ $registro->PrimerNombre }}
                                                            {{ $registro->SegundoNombre }}
                                                            {{ $registro->PrimerApellido }}
                                                            {{ $registro->SegundoApellido }}
                                                            {{ $registro->ApellidoCasada }}
                                                        </td>
                                                        <td>{{ $registro->FechaNacimientoDate ? date('d/m/Y', strtotime($registro->FechaNacimientoDate)) : '' }}
                                                        </td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                            Años</td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->Edad : '' }}
                                                            Años</td>
                                                        <td>${{ number_format($registro->SumaAsegurada, 2) }}</td>
                                                        <td>
                                                            <input type="checkbox" {{ $registro->NoValido == 1 ? 'checked' : '' }}
                                                                onchange="agregarNoValido({{ $registro->Id }})"
                                                                class="js-switch">

                                                        </td>
                                                    </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <!-- nuevos registros -->
                                    <div role="tabpanel" class="tab-pane  " id="tab_nuevos" aria-labelledby="home-tab">
                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST"
                                                action="{{ url('exportar/vida/nuevos_registros') }}/{{ $poliza_vida->Id }}">
                                                @csrf
                                                <button class="btn btn-success"
                                                    {{ $nuevos_registros->count() > 0 ? '' : 'disabled' }}>Descargar
                                                    Excel</button>
                                            </form>

                                        </div>
                                        <br>
                                        <table class="table table-striped" id="table4">
                                            <thead>
                                                <tr>
                                                    <th>Número crédito</th>
                                                    <th>DUI</th>
                                                    <th>NIT</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha nacimiento</th>
                                                    <th>Edad Otorgamiento</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($nuevos_registros->where('Edad', '<=', $poliza_vida->EdadMaximaInscripcion) as $registro)
                                                    <tr>
                                                        <td>{{ $registro->NumeroReferencia }}</td>
                                                        <td>{{ $registro->Dui }}</td>
                                                        <td>{{ $registro->Nit }}</td>
                                                        <td>{{ $registro->PrimerNombre }}
                                                            {{ $registro->SegundoNombre }}
                                                            {{ $registro->PrimerApellido }}
                                                            {{ $registro->SegundoApellido }}
                                                            {{ $registro->ApellidoCasada }}
                                                        </td>
                                                        <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                        </td>
                                                        <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }} Años</td>
                                                        <td>${{ number_format($registro->SumaAsegurada, 2) }}</td>
                                                    </tr>
                                                    @endforeach


                                            </tbody>
                                        </table>

                                    </div>


                                    <!-- registros eliminados -->
                                    <div role="tabpanel5" class="tab-pane" id="tab_eliminados"
                                        aria-labelledby="tab">


                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST"
                                                action="{{ url('exportar/vida/registros_eliminados') }}/{{ $poliza_vida->Id }}">
                                                @csrf
                                                <button class="btn btn-success"
                                                    {{ $registros_eliminados->count() > 0 ? '' : 'disabled' }}>Descargar
                                                    Excel</button>
                                            </form>
                                        </div>
                                        <br>
                                        <table class="table table-striped" id="table5">
                                            <thead>
                                                <tr>
                                                    <th>Número crédito</th>
                                                    <th>DUI</th>
                                                    <th>NIT</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha Nacimiento</th>
                                                    <th>Fecha Otorgamiento</th>
                                                    <th>Edad Actual</th>
                                                    <th>Edad Desembolso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registros_eliminados as $registro)
                                                <tr>
                                                    <td>{{ $registro->NumeroReferencia }}</td>
                                                    <td>{{ $registro->Dui }}</td>
                                                    <td>{{ $registro->Nit }}</td>
                                                    <td>{{ $registro->PrimerNombre }}
                                                        {{ $registro->SegundoNombre }}
                                                        {{ $registro->PrimerApellido }}
                                                        {{ $registro->SegundoApellido }}
                                                        {{ $registro->ApellidoCasada }}
                                                    </td>
                                                    <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                    </td>
                                                    <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                                                    </td>
                                                    <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                    <td>{{ $registro->Edad ? $registro->Edad : '' }}
                                                        Años</td>
                                                </tr>
                                                @endforeach


                                            </tbody>
                                        </table>



                                    </div>

                                    <!-- registros rehabilitados -->
                                    <div role="tabpanel5" class="tab-pane" id="tab_rehabilitados"
                                        aria-labelledby="tab">


                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST"
                                                action="{{ url('exportar/vida/registros_rehabilitados') }}/{{ $poliza_vida->Id }}">
                                                @csrf
                                                <button class="btn btn-success"
                                                    {{ $registros_rehabilitados->count() > 0 ? '' : 'disabled' }}>Descargar
                                                    Excel</button>
                                            </form>
                                        </div>
                                        <br>
                                        <table class="table table-striped" id="table5">
                                            <thead>
                                                <tr>
                                                    <th>Número crédito</th>
                                                    <th>DUI</th>
                                                    <th>NIT</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha Nacimiento</th>
                                                    <th>Fecha Otorgamiento</th>
                                                    <th>Edad Actual</th>
                                                    <th>Edad Desembolso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registros_rehabilitados as $registro)
                                                <tr>
                                                    <td>{{ $registro->NumeroReferencia }}</td>
                                                    <td>{{ $registro->Dui }}</td>
                                                    <td>{{ $registro->Nit }}</td>
                                                    <td>{{ $registro->PrimerNombre }}
                                                        {{ $registro->SegundoNombre }}
                                                        {{ $registro->PrimerApellido }}
                                                        {{ $registro->SegundoApellido }}
                                                        {{ $registro->ApellidoCasada }}
                                                    </td>
                                                    <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                    </td>
                                                    <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                                                    </td>
                                                    <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                    <td>{{ $registro->Edad ? $registro->Edad : '' }}
                                                        Años</td>
                                                </tr>
                                                @endforeach


                                            </tbody>
                                        </table>
                                    </div>


                                    <!--con extraprimados excluidos-->
                                    <div role="tabpanel" class="tab-pane fade" id="tab_extraprimados" aria-labelledby="profile-tab">

                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST" action="{{ url('exportar/vida/extraprimados_excluidos') }}/{{ $poliza_vida->Id }}">
                                                @csrf
                                                <button class="btn btn-success">Descargar Excel</button>
                                            </form>
                                            <br>
                                        </div>
                                        <br>
                                        <table class="table table-striped" id="datatable">

                                            <thead>
                                                <tr>

                                                    <th>Número Referencia</th>
                                                    <th>Nombre</th>
                                                    <th>DUI</th>
                                                    <th>Fecha Otorgamiento</th>
                                                    <th>Saldo</th>
                                                    <th>Porcentaje EP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($extra_primados->where('Existe', '=', 0) as $extra_primado)
                                                <tr>
                                                    <td>{{ $extra_primado->NumeroReferencia }}</td>
                                                    <td>{{ $extra_primado->Nombre }}</td>
                                                    <td>{{ $extra_primado->Dui }}</td>
                                                    <td>{{ $extra_primado->FechaOtorgamiento }}</td>
                                                    <td>{{ $extra_primado->MontoOtorgamiento }}</td>
                                                    <td> {{ $extra_primado->PorcentajeEP }}%</td>
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
        </div>
        <div class="modal" id="modal-primer-ingreso" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ url('polizas/vida/store_poliza_primara_carga') }}/{{ $poliza_vida->Id }}">
                        @csrf
                        <div class="modal-header">
                            <div class="col-md-6">
                                <h4 class="modal-title" id="myModalLabel">Primer carga</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="Vida" value="{{ $poliza_vida->Id }}">
                            <input type="hidden" name="MesActual" value="{{ $mesActual }}">
                            <input type="hidden" name="AxoActual" value="{{ $axoActual }}">

                            <p class="fs-5 mb-0">
                                ¿Desea realizar el primer carga?
                            </p>
                        </div>

                        <div class="modal-footer text-center">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" id="btnAceptar">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#table1').DataTable({
            paging: false
        });
        $('#table2').DataTable({
            paging: false
        });
        $('#table3').DataTable({
            paging: false
        });
        $('#table4').DataTable({
            //paging: false
        });
        $('#table5').DataTable({
            paging: false
        });

        getNoValido({{$poliza_vida->Id}});
        document.getElementById('btnGuardarCartera').addEventListener('click', function() {
            document.getElementById('loading-overlay').style.display = 'flex';
        });
        document.getElementById('btnAceptar').addEventListener('click', function() {
            document.getElementById('loading-overlay').style.display = 'flex';
        });
    });

    function resumen() {
        document.getElementById('subir_respuesta').style.display = 'none';
        document.getElementById('resumen').style.display = 'block';
    }

    function getNoValido(id) {
        const url = `{{ url('polizas/vida/get_no_valido') }}/${id}`;

        // Hacer la solicitud GET con jQuery
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                // Manejar la respuesta exitosa
                if (response.success) {
                    console.log('Conteo de no válidos:', response.count);

                    // Habilitar o deshabilitar el botón según el conteo
                    if (response.count === 0) {
                        $('#btnGuardarCartera').prop('disabled', false);
                    } else {
                        $('#btnGuardarCartera').prop('disabled', true);
                    }
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
            }
        });
    }

    function agregarNoValido(id) {
        const url = `{{ url('polizas/vida/agregar_no_valido') }}/${id}`;

        // Hacer la solicitud POST con jQuery
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                // Manejar la respuesta exitosa
                if (response.success) {
                    console.log('Registro agregado correctamente:', response.message);
                } else {
                    console.error('Error:', response.message);
                }

                getNoValido({{$poliza_vida->Id}});
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud
                console.error('Error en la solicitud:', error);
            }
        });
    }
</script>
@endsection
