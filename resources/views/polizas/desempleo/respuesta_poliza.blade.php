@extends ('welcome')
@section('contenido')
    <div role="main">
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
                                            <form method="post"
                                                action="{{ url('polizas/desempleo/borrar_proceso_actual') }}/{{ $desempleo->Id }}">
                                                @csrf
                                                <button class="btn btn-default">Borrar Proceso Actual</button>
                                            </form>
                                        </td>
                                        <td>

                                            <form method="post"
                                                action="{{ url('polizas/desempleo/store_poliza') }}/{{ $desempleo->Id }}">
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
                                                role="tab" data-toggle="tab" aria-expanded="false">Edad <br> Máxima </a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_inscripcion"
                                                id="inscripcion-tab" role="tab" data-toggle="tab"
                                                aria-expanded="false">Edad <br> Inscripción </a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_nuevos" id="home-tab"
                                                role="tab" data-toggle="tab" aria-expanded="true">Nuevos <br>
                                                registros</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_eliminados" id="profile-tab5"
                                                role="tab" data-toggle="tab" aria-expanded="false">Registros <br>
                                                Eliminados</a>
                                        </li>

                                        <li role="presentation" class=""><a href="#tab_content4" role="tab"
                                                id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros
                                                <br />rehabilitados</a>
                                        </li>


                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <br>
                                        <!-- edad maxima -->
                                        <div role="tabpanel" class="tab-pane active" id="tab_edad"
                                            aria-labelledby="edad-tab">
                                            <br>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <h4>Edad Maxima {{ $desempleo->EdadMaxima }} años
                                                </h4>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo"
                                                style="display:">

                                                <form
                                                    action="{{ url('exportar/registros_edad_maxima') }}/{{ $desempleo->Id }}"
                                                    method="POST">
                                                    @csrf
                                                    <button style="text-align: right;" class="btn btn-success">Descargar
                                                        Excel</button>
                                                </form>

                                            </div>
                                            <br><br>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-striped">
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
                                                        @foreach ($poliza_edad_maxima->where('EdadDesembloso', '>', $desempleo->EdadMaxima) as $registro)
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
                                                                <td>${{ number_format($registro->MontoOtorgado, 2) }}</td>
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
                                                <h4>Edad maxima de inscripción {{ $desempleo->EdadMaximaInscripcion }} años
                                                </h4>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo"
                                                style="display:">

                                                <form
                                                    action="{{ url('exportar/registros_edad_maxima') }}/{{ $desempleo->Id }}"
                                                    method="POST">
                                                    @csrf
                                                    <button style="text-align: right;" class="btn btn-success">Descargar
                                                        Excel</button>
                                                </form>

                                            </div>
                                            <br><br>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <table class="table table-striped">
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
                                                        @foreach ($poliza_edad_maxima->where('EdadDesembloso', '<=', $desempleo->EdadMaxima) as $registro)
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
                                                                <td>${{ number_format($registro->MontoOtorgado, 2) }}</td>
                                                                <td>
                                                                    <input type="checkbox" {{$registro->NoValido == 1 ? 'checked':''}}
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
                                        <div role="tabpanel" class="tab-pane  " id="tab_nuevos"
                                            aria-labelledby="home-tab">
                                            <div class="col-md-12 col-sm-12" align="right">
                                                <form method="POST"
                                                    action="{{ url('exportar/nuevos_registros') }}/{{ $desempleo->Id }}">
                                                    @csrf
                                                    <button class="btn btn-success"
                                                        {{ $nuevos_registros->count() > 0 ? '' : 'disabled' }}>Descargar
                                                        Excel</button>
                                                </form>

                                            </div>
                                            <br>
                                            <table class="table table-striped" id="MyTable">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad Actual</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($nuevos_registros->where('Edad', '<=', $desempleo->EdadMaximaInscripcion) as $registro)
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
                                                                <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                                <td>${{ number_format($registro->MontoOtorgado, 2) }}</td>
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
                                                        action="{{ url('exportar/registros_eliminados') }}/{{ $desempleo->Id }}">
                                                        @csrf
                                                        <button class="btn btn-success"
                                                            {{ $registros_eliminados->count() > 0 ? '' : 'disabled' }}>Descargar
                                                            Excel</button>
                                                    </form>
                                                </div>
                                                <br>
                                                <table class="table table-striped" id="MyTable2">
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


                                        </div>






                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#MyTable').DataTable();

                getNoValido({{ $desempleo->Id }});
            });

            function getNoValido(id) {
                const url = `{{ url('polizas/desempleo/get_no_valido') }}/${id}`;

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
                const url = `{{ url('polizas/desempleo/agregar_no_valido') }}/${id}`;

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

                        getNoValido({{ $desempleo->Id }});
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores de la solicitud
                        console.error('Error en la solicitud:', error);
                    }
                });
            }
        </script>
    @endsection
