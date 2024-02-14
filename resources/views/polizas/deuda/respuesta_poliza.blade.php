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


    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Reducido de 60px a 40px */
            height: 24px;
            /* Reducido de 34px a 24px */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            /* Reducido de 26px a 18px */
            width: 18px;
            /* Reducido de 26px a 18px */
            left: 3px;
            /* Ajustado de 4px a 3px para centrar */
            bottom: 3px;
            /* Ajustado de 4px a 3px para centrar */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(16px);
            /* Ajustado acorde al nuevo tamaño */
            -ms-transform: translateX(16px);
            /* Ajustado acorde al nuevo tamaño */
            transform: translateX(16px);
            /* Ajustado acorde al nuevo tamaño */
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 24px;
            /* Ajustado para mantener la proporción */
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">



    <!-- Agrega este script en tu archivo blade -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loadingOverlay = document.getElementById('loading-overlay');

            // Muestra el overlay de carga cuando se hace clic en el botón
            document.querySelector('button').addEventListener('click', function() {
                loadingOverlay.style.display = 'flex'; // Cambia a 'flex' para usar flexbox
            });

            // Oculta el overlay de carga después de que la página se haya cargado completamente
            window.addEventListener('load', function() {
                loadingOverlay.style.display = 'none';
            });
        });
    </script>
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
                                        <td style="vertical-align: top;"> <a
                                                href="{{ url('polizas/deuda') }}/{{ $deuda->Id }}/edit"
                                                class="btn btn-default">
                                                Cancelar <br>
                                                <!-- (borrar los datos de la cartera temporal) -->
                                            </a></td>
                                        <td>

                                            <form method="post" action="{{ url('polizas/deuda/store_poliza') }}">
                                                @csrf
                                                <input type="hidden" name="Cartera" value="{{ $deuda->Id }}">
                                                <input type="hidden" name="MesActual"
                                                    value="{{ date('Y-m-d', strtotime($date)) }}">
                                                <input type="hidden" name="MesAnterior"
                                                    value="{{ date('Y-m-d', strtotime($date_anterior)) }}">
                                                <button class="btn btn-primary">
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
                                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab"
                                                role="tab" data-toggle="tab" aria-expanded="true">Nuevos registros</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content5" id="profile-tab5"
                                                role="tab" data-toggle="tab" aria-expanded="false">Registros
                                                Eliminados</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content2" role="tab"
                                                id="profile-tab" data-toggle="tab" aria-expanded="false">Creditos no
                                                válidos</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content3" role="tab"
                                                id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros con
                                                requisitos</a>
                                        </li>
                                        {{-- <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros
                                            válidos</a>
                                    </li> --}}
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane active " id="tab_content1"
                                            aria-labelledby="home-tab">
                                            <br>
                                            <table class="table table-striped" id="MyTable1">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad Actual</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($nuevos_registros as $registro)
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
                                                        </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>



                                        </div>

                                        <div role="tabpanel5" class="tab-pane" id="tab_content5" aria-labelledby="tab">
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
                                                        <th>Saldo</th>
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
                                                                {{ $registro->ApellidoCasada }} </td>
                                                            <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                            </td>
                                                            <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                                                            </td>
                                                            <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                            <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                                Años</td>
                                                            <td>${{ number_format($registro->total_saldo, 2) }}</td>
                                                        </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>



                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                            aria-labelledby="profile-tab">
                                            <div align="right">
                                                <a href="{{ url('exportar/poliza_cumulo') }}"
                                                    class="btn btn-success">Descargar Excel</a>
                                            </div>
                                            <br>
                                            <table class="table table-striped" id="MyTable3">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Fecha otorgamiento</th>
                                                        <th>Edad desembolso</th>
                                                        <th>Edad actual</th>
                                                        <th>Saldo</th>
                                                        <th>Agregar a válidos</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_cumulos->where('NoValido', '=', 1) as $registro)
                                                        <tr>
                                                            <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
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
                                                            <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                                Años</td>
                                                            <td class="text-right">
                                                                ${{ number_format($registro->total_saldo, 2) }}
                                                            </td>
                                                            <td align="center">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        onchange="agregarValidos({{ $registro->Id }})">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>

                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content3"
                                            aria-labelledby="profile-tab">
                                            <br>
                                            <table class="table table-striped" id="MyTable4">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad actual</th>
                                                        <th>Edad otorgamiento</th>
                                                        <th>Fecha otorgamiento</th>
                                                        <th>Requisitos</th>
                                                        <th>Saldo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_cumulos->where('Perfiles', '<>', null) as $registro)
                                                        <tr>
                                                            <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
                                                            <td>{{ $registro->Dui }}</td>
                                                            <td>{{ $registro->Nit }}</td>
                                                            <td>{{ $registro->PrimerNombre }}
                                                                {{ $registro->SegundoNombre }}
                                                                {{ $registro->PrimerApellido }}
                                                                {{ $registro->SegundoApellido }}
                                                                {{ $registro->ApellidoCasada }} </td>
                                                            <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                            </td>
                                                            <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                            <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                                Años</td>
                                                            <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $perfilesArreglo = explode(',', $registro->Perfiles);
                                                                    $uniquePerfiles = array_unique($perfilesArreglo);
                                                                @endphp

                                                                @foreach ($uniquePerfiles as $key => $perfil)
                                                                    {{ $perfil }}{{ $loop->last ? '' : ', ' }}
                                                                @endforeach
                                                            </td>
                                                            <td class="text-right">
                                                                ${{ number_format($registro->total_saldo, 2) }}</td>

                                                        </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>

                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content4"
                                            aria-labelledby="profile-tab">


                                            <br>
                                            <table class="table table-striped" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
                                                        <th>Edad Actual</th>
                                                        <th>Saldo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_cumulos->where('Perfiles', '=', null)->where('NoValido', '=', 0) as $registro)
                                                        <tr>
                                                            <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
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

                                                            <td class="text-right">
                                                                ${{ number_format($registro->total_saldo, 2) }}</td>

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

            <!-- Agrega este div al final de tu archivo blade -->
            <div id="loading-overlay">
                <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
            </div>

        </div>
    </div>
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // alert(document.getElementById('ComisionIva').value);
            $('#MyTable1').DataTable();
            $('#MyTable2').DataTable();
            $('#MyTable3').DataTable();
            $('#MyTable4').DataTable();
            $('#MyTable5').DataTable();
        });

        function agregarValidos(id) {
            $.ajax({
                url: '{{ url('polizas/deuda/agregar_valido') }}', // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                },
                success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        }
    </script>
@endsection
