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

            <div class="row" id="resumen">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h2>Resumen de cartera {{ $desempleo->NumeroPoliza }} <br>
                                    {{ $desempleo->cliente->Nombre ?? '' }}
                                </h2>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                                <table>
                                    <tr>
                                        {{-- <td style="vertical-align: top;">
                                            <div class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-primer-carga">
                                                Primera carga
                                            </div>

                                        </td> --}}
                                        <td style="vertical-align: top;">
                                            <form method="post"
                                                action="{{ url('polizas/desempleo/borrar_proceso_actual') }}/{{ $desempleo->Id }}">
                                                @csrf
                                                <button class="btn btn-default">Borrar Proceso Actual</button>
                                            </form>
                                        </td>
                                        <td>

                                            <form method="post"
                                                action="{{ url('polizas/desempleo/store_poliza_recibo') }}/{{ $desempleo->Id }}">
                                                @csrf
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

                                        @isset($poliza_cumulos)
                                            <li role="presentation" class=""><a href="#tab_cumulos" role="tab"
                                                    id="cumulos-tab" data-toggle="tab" aria-expanded="false">Listado de
                                                <br />cúmulos</a>
                                            </li>
                                        @endisset


                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <br>


                                        @isset($poliza_cumulos)
                                            <div role="tabpanel" class="tab-pane active" id="tab_cumulos"
                                                aria-labelledby="cumulos-tab">
                                                <br>
                                                <div class="col-md-6 col-sm-12" align="left">
                                                    <h4>Listado de cúmulos</h4>
                                                </div>
                                                <div class="clearfix"></div>
                                                <br>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <table class="table table-striped table-hover align-middle"
                                                        id="table6">
                                                        <thead class="table-primary text-center">
                                                            <tr>
                                                                <th>Número crédito</th>
                                                                <th>DUI</th>
                                                                <th>Nombre</th>
                                                                <th>Fecha nacimiento</th>
                                                                <th>Edad actual</th>
                                                                <th>Edad otorgamiento</th>
                                                                <th>Fecha otorgamiento</th>
                                                                <th>Mes</th>
                                                                <th>Año</th>
                                                                <th>Cúmulo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($poliza_cumulos as $registro)
                                                                <tr>
                                                                    <td>
                                                                        @php
                                                                            $referencias = array_filter(
                                                                                explode(
                                                                                    ',',
                                                                                    $registro->ConcatenatedNumeroReferencia ??
                                                                                        '',
                                                                                ),
                                                                            );
                                                                        @endphp
                                                                        @if (count($referencias) > 0)
                                                                            {{ implode(', ', $referencias) }}
                                                                        @else
                                                                            {{ $registro->NumeroReferencia ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $registro->Dui ?? '-' }}</td>
                                                                    <td>
                                                                        {{ trim("{$registro->PrimerNombre} {$registro->SegundoNombre} {$registro->PrimerApellido} {$registro->SegundoApellido} {$registro->ApellidoCasada}") }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $registro->FechaNacimiento ?? '-' }}</td>
                                                                    <td class="text-center text-nowrap">
                                                                        {{ $registro->Edad ? "{$registro->Edad} años" : '-' }}
                                                                    </td>
                                                                    <td class="text-center text-nowrap">
                                                                        {{ $registro->EdadDesembloso ? "{$registro->EdadDesembloso} años" : '-' }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $registro->FechaOtorgamiento ? date('d/m/Y', strtotime($registro->FechaOtorgamiento)) : '-' }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ $meses[$registro->Mes] ?? $registro->Mes }}</td>
                                                                    <td class="text-center">{{ $registro->Axo ?? '-' }}
                                                                    </td>
                                                                    <td class="text-right text-nowrap">
                                                                        ${{ number_format($registro->SaldoCumulo ?? 0, 2, '.', ',') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endisset
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <div class="modal" id="modal-primer-carga" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('polizas/desempleo/store_poliza_primara_carga') }}">
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
                        <input type="hidden" name="Desempleo" value="{{ $desempleo->Id }}">


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


    <script type="text/javascript">
        $(document).ready(function() {

            @isset($poliza_cumulos)
                $('#table6').DataTable({
                    paging: false
                });
            @endisset

            getNoValido({{ $desempleo->Id }});
            document.getElementById('btnGuardarCartera').addEventListener('click', function() {
                document.getElementById('loading-overlay').style.display = 'flex';
            });
        });

        function resumen() {
            document.getElementById('subir_respuesta').style.display = 'none';
            document.getElementById('resumen').style.display = 'block';
        }

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
