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

<div id="loading-overlay">
    <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
</div>

<div role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{-- <h2>Resumen de cartera {{ $nombre_cartera }} <br> {{ $deuda->NumeroPoliza }} &nbsp; --}}
                            <h2>Resumen de cartera {{ $poliza_vida->NumeroPoliza }} <br>
                                {{ $poliza_vida->cliente->Nombre }}
                            </h2>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                        <a href="{{ url('polizas/vida') }}/{{ $poliza_vida->Id }}/edit" class="btn btn-info">Pausar Validación</a>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <form method="post" action="{{ url('polizas/deuda/borrar_proceso_actual') }}">
                                            @csrf
                                            <input type="hidden" name="vida_id" value="{{ $poliza_vida->Id }}">
                                            <button class="btn btn-default">Borrar Proceso Actual</button>
                                        </form>
                                    </td>
                                    <td>

                                        <form method="post" id="miFormulario" action="{{ url('polizas/vida/store_poliza_recibo') }}/{{ $poliza_vida->Id }}">
                                            @csrf
                                            <input type="hidden" name="Vida" value="{{ $poliza_vida->Id }}">

                                            {{-- <button id="btnGuardarCartera" type="submit" class="btn btn-primary" {{ $conteo_excluidos > 0 ? 'disabled' : '' }}> --}}
                                            <button id="btnGuardarCartera" type="submit" class="btn btn-primary">
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

                            <br>
                            <div class="col-md-6 col-sm-12" align="left">
                               <h4>Listado de Cúmulos</h4>
                            </div>
                            <div class="col-md-6 col-sm-12" align="right">
                                <form method="POST" action="{{ url('exportar/vida/registros_requisitos_recibos') }}/{{ $poliza_vida->Id }}">
                                    @csrf
                                    <button class="btn btn-success">Descargar Excel</button>
                                </form>
                                <br>
                            </div>
                            <br>
                            <br>
                            <div id="creditos_validos">
                                <table class="table table-striped table-hover align-middle" id="MyTable4">
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
                                            {{-- <th>Requisitos</th> --}}
                                            <th>Cúmulo</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($poliza_cumulos as $registro)
                                        <tr>
                                            <td>
                                                @php
                                                    $referencias = array_filter(explode(',', $registro->ConcatenatedNumeroReferencia ?? ''));
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
                                            <td class="text-center">{{ $registro->FechaNacimiento ?? '-' }}</td>
                                            <td class="text-center text-nowrap">
                                                {{ $registro->Edad ? "{$registro->Edad} años" : '-' }}
                                            </td>
                                            <td class="text-center text-nowrap">
                                                {{ $registro->EdadDesembloso ? "{$registro->EdadDesembloso} años" : '-' }}
                                            </td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($registro->FechaOtorgamiento)) ?? '-' }}</td>
                                            <td class="text-center">{{ $meses[$registro->Mes] ?? $registro->Mes }}</td>
                                            <td class="text-center">{{ $registro->Axo ?? '-' }}</td>
                                            {{-- <td style="width: 25%; white-space: normal; word-wrap: break-word;">
                                                @php
                                                    $perfiles = array_unique(array_filter(explode(',', $registro->Perfiles ?? '')));
                                                @endphp
                                                {{ implode(', ', $perfiles) ?: '-' }}
                                            </td> --}}
                                            <td class="text-right text-nowrap">
                                                ${{ number_format($registro->SumaCumulo ?? 0, 2, '.', ',') }}
                                            </td>
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

<script type="text/javascript">
    $(document).ready(function() {
        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-deuda");

        // alert(document.getElementById('ComisionIva').value);
        $('#MyTable1').DataTable();
        $('#MyTable2').DataTable();
        $('#MyTable3').DataTable();
        $('#MyTable4').DataTable();
        $('#MyTable5').DataTable();
        $('#MyTable6').DataTable();
        $('#MyTable7').DataTable();


    });


    function excluir(id, subtotal, val) {
        let id_ex = document.getElementById('id_excluido-' + id).value;
        //alert(id_ex);
        if (id_ex == 0) {
            // alert('si');
            $.ajax({
                url: "{{ url('poliza/deuda/add_excluidos') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST',
                data: {
                    id: id,
                    subtotal: subtotal,
                    val: val,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                },
                success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    if (response.excluido > 0) {
                        $("#btn_expo").show();
                        $("#btn_expo2").show();
                        document.getElementById('id_excluido-' + id).value = response.excluido;
                        //btnGuardarCartera


                    }


                    if (response.conteo_excluidos) {
                        document.getElementById("btnGuardarCartera").disabled = true;
                    } else {
                        document.getElementById("btnGuardarCartera").disabled = false;
                    }

                },
                error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        } else {
            // alert('no');
            $.ajax({
                url: "{{ url('poliza/deuda/delete_excluido') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST',
                data: {
                    id: id,
                    id_ex: id_ex,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                },
                success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    document.getElementById('id_excluido-' + id).value = response.excluido;
                    document.getElementById("btnGuardarCartera").disabled = true;
                },
                error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        }


    }

    function excluir_dinero(id, subtotal, val) {
        let id_ex = document.getElementById('id_excluido_dinero-' + id).value;
        //alert(id_ex);
        if (id_ex == 0) {
            //alert('si');
            $.ajax({
                url: "{{ url('poliza/deuda/add_excluidos') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST',
                data: {
                    id: id,
                    subtotal: subtotal,
                    val: val,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                },
                success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    if (response.excluido > 0) {
                        $("#btn_expo").show();
                        $("#btn_expo2").show();
                        document.getElementById('id_excluido_dinero-' + id).value = response.excluido;
                    }

                },
                error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        } else {
            //alert('no');
            $.ajax({
                url: "{{ url('poliza/deuda/delete_excluido') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST',
                data: {
                    id: id,
                    id_ex: id_ex,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                },
                success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    document.getElementById('id_excluido_dinero-' + id).value = response.excluido;
                },
                error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        }


    }


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


    function agregarValidos(id) {


        var loadingOverlay = document.getElementById('loading-overlay'); // Cambiado para coincidir con el HTML

        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex'; // Mostrar overlay
        }

      //  if (id != '') {
            $.ajax({
                url: "{{ url('polizas/deuda/agregar_valido') }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                },
                complete: function() {
                    if (loadingOverlay) {
                        console.log("Ocultando overlay en complete");
                        loadingOverlay.style.display = 'none'; // Ocultar overlay después de la solicitud
                    }
                }
            });

     /*   } else {
            Swal.fire({
                title: 'Error!',
                text: 'Debe de seleccionar el credito',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }*/

            if (loadingOverlay) {
                loadingOverlay.style.display = 'none'; // Ocultar overlay si no se seleccionó un crédito
            }
    }


</script>
@endsection
