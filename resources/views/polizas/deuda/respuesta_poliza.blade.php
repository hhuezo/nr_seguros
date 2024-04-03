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

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

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
                loadingOverlay.style.display = 'hide';
            });


            var form = document.getElementById('miFormulario');

            form.addEventListener('submit', function() {
                loadingOverlay.style.display = 'flex'; // Muestra el overlay de carga
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var loadingOverlay = document.getElementById('loading-overlay');
           
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
                                <h2>Resumen de cartera {{ $nombre_cartera }} <br> {{ $deuda->NumeroPoliza }} &nbsp;
                                    {{ $deuda->clientes->Nombre }} </h2>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                                <table>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <form method="post" action="{{ url('regresar_edit') }}">
                                                @csrf
                                                <input type="hidden" name="deuda_id" value="{{ $deuda->Id }}">
                                                <button class="btn btn-default">Cancelar</button>
                                            </form>
                                        </td>
                                        <td>

                                            <form method="post" id="miFormulario"
                                                action="{{ url('polizas/deuda/store_poliza') }}">
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
                                                                {{ $registro->ApellidoCasada }}
                                                            </td>
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
                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" id="buscar_no_valido" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="button" id="btn_no_valido"
                                                            class="btn btn-primary">Buscar</button>
                                                        <!-- Nuevo botón Borrar -->
                                                        <button type="button" id="btn_limpiarn_no_valido"
                                                            class="btn btn-secondary">Limpiar</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12" align="right">
                                                <a href="{{ url('exportar/poliza_cumulo') }}"
                                                    class="btn btn-success">Descargar Excel</a>
                                            </div>
                                            <br>
                                            <br>
                                            <div id="creditos_no_validos">

                                                <!-- <table class="table table-striped" id="MyTable3">
                                                        <thead>
                                                            <tr>
                                                                <th>Número crédito</th>
                                                                <th>DUI</th>
                                                                <th>NIT</th>
                                                                <th>Nombre</th>
                                                                <th>Fecha nacimiento</th>
                                                                <th>Fecha otorgamiento</th>
                                                                <th>Edad actual</th>
                                                                <th>Edad desembolso</th>
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
                                                                    <button class="btn btn-primary" onclick="get_creditos({{ $registro->Id }})">
                                                                        <i class="fa fa-exchange" data-target="#modal_cambio_credito_valido" data-toggle="modal"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
    @endforeach


                                                        </tbody>
                                                    </table> -->
                                            </div>

                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content3"
                                            aria-labelledby="profile-tab">


                                            <div class="col-md-6 col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" id="buscar_valido" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button type="button" id="btn_valido"
                                                            class="btn btn-primary">Buscar</button>
                                                        <button type="button" id="btn_limpiarn_valido"
                                                            class="btn btn-secondary">Limpiar</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <br>
                                            <div id="creditos_validos">
                                            </div>
                                            <!-- <table class="table table-striped" id="MyTable4">
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
                                                                    {{ $registro->ApellidoCasada }}
                                                                </td>
                                                                <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                                </td>
                                                                <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                                <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                                    Años</td>
                                                                <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $perfilesArreglo = explode(
                                                                            ',',
                                                                            $registro->Perfiles,
                                                                        );
                                                                        $uniquePerfiles = array_unique(
                                                                            $perfilesArreglo,
                                                                        );
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
                                                </table> -->

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




            <div class="modal fade" id="modal_cambio_credito_valido" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h5 class="modal-title" id="exampleModalLabel">Excluir crédito no válido</h5>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Seleccione credito</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <select id="creditos" class="form-control">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="modal-footer" align="center">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                            <button type="button" onclick="agregarValidos()" class="btn btn-primary">Aceptar</button>
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

    <script type="text/javascript">
        $(document).ready(function() {
            // alert(document.getElementById('ComisionIva').value);
            $('#MyTable1').DataTable();
            $('#MyTable2').DataTable();
            $('#MyTable3').DataTable();
            $('#MyTable4').DataTable();
            $('#MyTable5').DataTable();
            loadCreditos(1, "");
            loadCreditos(2, "");
        });

        function get_creditos(id) {
            $.ajax({
                url: "{{ url('polizas/deuda/get_referencia_creditos') }}/" + id,
                type: 'GET',
                success: function(response) {
                    // Aquí manejas la respuesta. Por ejemplo, podrías imprimir la respuesta en la consola:
                    console.log(response);
                    var _select = '<option value=""> Seleccione ... </option>';
                    for (var i = 0; i < response.length; i++)
                        _select += '<option value="' + response[i].Id + '"  >' + response[i].NumeroReferencia +
                        '</option>';
                    $("#creditos").html(_select);
                },
                error: function(error) {
                    // Aquí manejas el error, si ocurre alguno durante la petición
                    console.error(error);
                }
            });
        }

        function agregarValidos() {
            var id = document.getElementById('creditos').value;
            var buscar = document.getElementById('buscar_no_valido').value;
            console.log(id, ' ', buscar, ' ', '{{ $tipo_cartera }}');
            if (id != '') {
                $.ajax({
                    url: "{{ url('polizas/deuda/agregar_valido') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                    type: 'POST',
                    data: {
                        id: id,
                        tipo_cartera: '{{ $tipo_cartera }}',
                        _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                    },
                    success: function(response) {
                        // Aquí manejas lo que suceda después de la respuesta exitosa
                        console.log(response);
                        $('#modal_cambio_credito_valido').modal('hide');
                        loadCreditos(1, buscar);
                        loadCreditos(2, buscar);
                        Swal.fire({
                            title: 'Exito!',
                            text: 'Se agrego el credito con exito',
                            icon: 'exito',
                            confirmButtonText: 'Aceptar'
                        });

                    },
                    error: function(xhr, status, error) {
                        // Aquí manejas los errores
                        console.error(error);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Debe de seleccionar el credito',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }

        }


        function loadCreditos(opcion, buscar) {
            $.ajax({
                url: "{{ url('polizas/deuda/get_creditos') }}/" + '{{ $deuda->Id }}',
                type: 'GET',
                data: {
                    buscar: buscar,
                    opcion: opcion,
                    tipo_cartera: '{{ $tipo_cartera }}',
                },
                success: function(response) {
                    // Aquí manejas la respuesta. Por ejemplo, podrías imprimir la respuesta en la consola:
                    if (opcion == 1) {
                        $('#creditos_no_validos').html(response);
                    } else {
                        $('#creditos_validos').html(response);
                    }

                },
                error: function(error) {
                    // Aquí manejas el error, si ocurre alguno durante la petición
                    console.error(error);
                }
            });
        }

        $('#btn_valido').on('click', function() {
            var buscar = document.getElementById('buscar_valido').value;

            loadCreditos(2, buscar);
            console.log("hola", buscar);
        });

        $('#btn_limpiarn_valido').on('click', function() {
            document.getElementById('buscar_valido').value = "";
            var buscar = "";

            loadCreditos(2, buscar);
            console.log("hola", buscar);
        });

        $('#btn_no_valido').on('click', function() {
            var buscar = document.getElementById('buscar_no_valido').value;

            loadCreditos(1, buscar);
            console.log(buscar);
        });

        $('#btn_limpiarn_no_valido').on('click', function() {
            document.getElementById('buscar_no_valido').value = "";
            var buscar = "";

            loadCreditos(1, buscar);
            console.log(buscar);
        });
    </script>
@endsection
