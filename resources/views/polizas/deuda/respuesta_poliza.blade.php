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
                            <h2>Resumen de cartera {{ $deuda->NumeroPoliza }} <br>
                                {{ $deuda->clientes->Nombre }}
                            </h2>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                            <table>
                                <tr>
                                    <td style="vertical-align: top;">
                                        <a href="{{ url('polizas/deuda') }}/{{ $deuda->Id }}/edit" class="btn btn-info">Pausar Validación</a>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <form method="post" action="{{ url('polizas/deuda/borrar_proceso_actual') }}">
                                            @csrf
                                            <input type="hidden" name="deuda_id" value="{{ $deuda->Id }}">
                                            <button class="btn btn-default">Borrar Proceso Actual</button>
                                        </form>
                                    </td>
                                    <td>

                                        <form method="post" id="miFormulario" action="{{ url('polizas/deuda/store_poliza') }}">
                                            @csrf
                                            <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                                            <input type="hidden" name="MesActual" value="{{ $mesActual }}">
                                            <input type="hidden" name="AxoActual" value="{{ $axoActual }}">

                                            <input type="hidden" name="Eliminados" value="{{ $registros_eliminados->isNotEmpty() ? implode(', ', $registros_eliminados->pluck('NumeroReferencia')->toArray()) : '' }}">


                                            <input type="hidden" name="MesAnterior" value="{{ $mesAnterior }}">
                                            <input type="hidden" name="AxoAnterior" value="{{ $axoAnterior }}">

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

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab_content6" id="edad-tab" role="tab" data-toggle="tab" aria-expanded="false">Edad <br> Máxima </a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content7" id="responsabilidad-tab5" role="tab" data-toggle="tab" aria-expanded="false">Responsabilidad <br> Máxima </a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Nuevos <br>
                                            registros</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content5" id="profile-tab5" role="tab" data-toggle="tab" aria-expanded="false">Registros <br>
                                            Eliminados</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Creditos <br> no
                                            válidos</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros <br> con
                                            requisitos</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Extraprimados
                                            <br>
                                            excluidos</a>
                                    </li>
                                    </li>


                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <br>
                                    <!-- edad maxima -->
                                    <div role="tabpanel" class="tab-pane active" id="tab_content6" aria-labelledby="edad-tab">
                                        <br>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <h4>Edad Maxima de Terminación {{ $deuda->EdadMaximaTerminacion }} años
                                            </h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo" style="display:{{ $poliza_edad_maxima->count() > 0 ? 'block' : 'none' }}">

                                            <form action="{{ url('exportar/registros_edad_maxima') }}/{{$deuda->Id}}" method="POST">
                                                @csrf
                                                <button style="text-align: right;" class="btn btn-success">Descargar
                                                    Excel</button>
                                            </form>

                                        </div>
                                        <br><br>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-striped" id="MyTable6">
                                                <thead>
                                                    <tr>
                                                        <th>Número crédito</th>
                                                        <th>DUI</th>
                                                        <th>NIT</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha nacimiento</th>
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
                                                        <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                                                        </td>
                                                        <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                                                        <td>${{ number_format($registro->saldo_total, 2) }}</td>
                                                        <td>
                                                            <input type="checkbox" onchange="excluir({{ $registro->Id }},0,1)" class="js-switch" {{ $registro->Excluido > 0 ? 'checked' : '' }}>
                                                            <input type="hidden" id="id_excluido-{{ $registro->Id }}" value="{{ $registro->Excluido }}">
                                                        </td>
                                                        <!-- <td style="text-align: center;"><button class="btn btn-primary"><i class="fa fa-exchange"></i></button></td> -->
                                                    </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>



                                    </div>



                                    <!-- responsabilidad maxima -->
                                    <div role="tabpanel5" class="tab-pane" id="tab_content7" aria-labelledby="responsabilidad-tab5">
                                        <br>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <h4 id="text_dinero" style="display: block;">Responsabilidad Máxima ${{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }} </h4>
                                            <h4 id="text_dinero_ac" style="display: none;"> </h4>

                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12" align="right" id="btn_expo2" style="display:{{ $poliza_responsabilidad_maxima->count() > 0 ? 'block' : 'none' }}">

                                            <form action="{{ url('exportar/registros_responsabilidad_maxima/') }}/{{$deuda->Id}}" method="POST">
                                                @csrf
                                                <button style="text-align: right;" class="btn btn-success">Descargar
                                                    Excel</button>
                                            </form>
                                        </div>
                                        <br><br>


                                        <table class="table table-striped" id="MyTable7">
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
                                                    <th>Total </th>
                                                    <th>Excluir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($poliza_responsabilidad_maxima as $registro)
                                                <tr>
                                                    <td>{{ $registro->NumeroReferencia }} <br>
                                                    </td>
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
                                                    <td>${{ number_format($registro->saldo_total, 2) }}</td>
                                                    <td style="text-align: center;">
                                                        <input type="checkbox" onchange="excluir_dinero({{ $registro->Id }},0,1)" class="js-switch" {{ $registro->Excluido > 0 ? 'checked' : '' }}>
                                                        <input type="hidden" id="id_excluido_dinero-{{ $registro->Id }}" value="{{ $registro->Excluido }}">
                                                    </td>

                                                </tr>

                                                @endforeach


                                            </tbody>
                                        </table>
                                    </div>


                                    <!-- nuevos registros -->
                                    <div role="tabpanel" class="tab-pane  " id="tab_content1" aria-labelledby="home-tab">
                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST" action="{{ url('exportar/nuevos_registros') }}/{{ $deuda->Id }}">
                                                @csrf
                                                <button class="btn btn-success" {{ $nuevos_registros->count() > 0 ? '' : 'disabled' }}>Descargar Excel</button>
                                            </form>

                                        </div>
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
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($nuevos_registros->where('Edad', '<=', $deuda->EdadMaximaTerminacion) as $registro)
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
                                                        <td>${{ number_format($registro->saldo_total, 2) }}</td>
                                                    </tr>
                                                    @endforeach


                                            </tbody>
                                        </table>



                                    </div>



                                    <!-- registros eliminados -->
                                    <div role="tabpanel5" class="tab-pane" id="tab_content5" aria-labelledby="tab">


                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST" action="{{ url('exportar/registros_eliminados') }}/{{ $deuda->Id }}">
                                                @csrf
                                                <button class="btn btn-success" {{ $registros_eliminados->count() > 0 ? '' : 'disabled' }}>Descargar Excel</button>
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


                                    <!-- creditos no validos -->
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="col-md-6 col-sm-12">

                                        </div>
                                        <div class="col-md-6 col-sm-12" align="right">
                                            <form method="POST" action="{{ url('exportar/registros_no_validos') }}/{{ $deuda->Id }}">
                                                @csrf
                                                <button class="btn btn-success">Descargar Excel</button>
                                            </form>
                                        </div>
                                        <br>
                                        <br>
                                        <div id="creditos_no_validos">


                                        </div>

                                    </div>


                                    <!--con requisitos-->
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">

                                        <br>
                                        <div class="col-md-6 col-sm-12">

                                            <div class="form-group row">
                                                <label class="control-label">Opciones</label>

                                                <select class="form-control" onchange="loadCreditos(2,this.value)">
                                                    <option value="1">Creditos con requisitos</option>
                                                    <option value="2">Creditos válidos</option>
                                                    <option value="3">Creditos rehabilitados</option>
                                                    {{-- <option value="4">Creditos fuera del monto límite</option> --}}
                                                </select>
                                            </div>

                                            <br>

                                            <div style="display: flex; align-items: center;">
                                                <div style="width: 20px; height: 20px; background-color: #eeb458; margin-right: 10px;"></div>
                                                <span>Los créditos rehabilitados se mostrarán de color naranja.</span>
                                            </div>
                                            <br>
                                            <div style="display: flex; align-items: center;">
                                                <div style="width: 20px; height: 20px; background-color: #b12020; margin-right: 10px;"></div>
                                                <span>Los montos resaltados en rojo han pasado el monto limite por linea.</span>
                                            </div>
                                            <br>

                                            <br>

                                        </div>
                                        <div class="col-md-6 col-sm-12" align="right">
                                            <form method="POST" action="{{ url('exportar/registros_requisitos') }}/{{ $deuda->Id }}">
                                                @csrf
                                                <button class="btn btn-success">Descargar Excel</button>
                                            </form>
                                            <br>

                                        </div>
                                        <br>
                                        <br>
                                        <div id="creditos_validos">
                                        </div>


                                    </div>

                                    <!--con extraprimados excluidos-->
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">

                                        <div class="col-md-12 col-sm-12" align="right">
                                            <form method="POST" action="{{ url('exportar/extraprimados_excluidos') }}/{{ $deuda->Id }}">
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




       <div class="modal fade" id="modal_cambio_credito_valido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
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
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Seleccione credito</label>
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



        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="col-md-6">
                            <h4 class="modal-title" id="myModalLabel">Detalle créditos</h4>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body" id="modal-creditos">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
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
        //$('#MyTable4').DataTable();
        $('#MyTable5').DataTable();
        $('#MyTable6').DataTable();
        $('#MyTable7').DataTable();

        //buscar registros no validos
        loadCreditos(1, "");

        //buscar registros con requisitos (1), rehabilitados(2), limite por linea(3)
        loadCreditos(2, 1);
    });

    function excluir(id, subtotal, val) {
        let id_ex = document.getElementById('id_excluido-' + id).value;
        //alert(id_ex);
        if (id_ex == 0) {
            // alert('si');
            $.ajax({
                url: "{{ url('poliza/deuda/add_excluidos') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST'
                , data: {
                    id: id
                    , subtotal: subtotal
                    , val: val,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                }
                , success: function(response) {
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

                }
                , error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        } else {
            // alert('no');
            $.ajax({
                url: "{{ url('poliza/deuda/delete_excluido') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST'
                , data: {
                    id: id
                    , id_ex: id_ex,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                }
                , success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    document.getElementById('id_excluido-' + id).value = response.excluido;
                    document.getElementById("btnGuardarCartera").disabled = true;
                }
                , error: function(xhr, status, error) {
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
                type: 'POST'
                , data: {
                    id: id
                    , subtotal: subtotal
                    , val: val,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                }
                , success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    if (response.excluido > 0) {
                        $("#btn_expo").show();
                        $("#btn_expo2").show();
                        document.getElementById('id_excluido_dinero-' + id).value = response.excluido;
                    }

                }
                , error: function(xhr, status, error) {
                    // Aquí manejas los errores
                    console.error(error);
                }
            });
        } else {
            //alert('no');
            $.ajax({
                url: "{{ url('poliza/deuda/delete_excluido') }}", // Asegúrate de que esta sintaxis se procese correctamente en tu archivo .blade.php
                type: 'POST'
                , data: {
                    id: id
                    , id_ex: id_ex,
                    //tipo_cartera: ' $tipo_cartera',
                    _token: '{{ csrf_token() }}' // Necesario para la protección CSRF de Laravel
                }
                , success: function(response) {
                    // Aquí manejas lo que suceda después de la respuesta exitosa
                    console.log(response);
                    document.getElementById('id_excluido_dinero-' + id).value = response.excluido;
                }
                , error: function(xhr, status, error) {
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


    function agregarValidos() {
        var id = document.getElementById('creditos').value;

        var loadingOverlay = document.getElementById('loading-overlay'); // Cambiado para coincidir con el HTML

        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex'; // Mostrar overlay
        }

        if (id != '') {
            $.ajax({
                url: "{{ url('polizas/deuda/agregar_valido') }}"
                , type: 'POST'
                , data: {
                    id: id
                    , _token: '{{ csrf_token() }}'
                }
                , success: function(response) {
                    console.log(response);
                    $('#modal_cambio_credito_valido').modal('hide');
                    loadCreditos(1, "");
                    loadCreditos(2, "");
                }
                , error: function(xhr, status, error) {
                    console.error(error);
                }
                , complete: function() {
                    if (loadingOverlay) {
                        console.log("Ocultando overlay en complete");
                        loadingOverlay.style.display = 'none'; // Ocultar overlay después de la solicitud
                    }
                }
            });

        } else {
            Swal.fire({
                title: 'Error!'
                , text: 'Debe de seleccionar el credito'
                , icon: 'error'
                , confirmButtonText: 'Aceptar'
            });

            if (loadingOverlay) {
                loadingOverlay.style.display = 'none'; // Ocultar overlay si no se seleccionó un crédito
            }
        }
    }



    function loadCreditos(opcion, buscar) {
        var loadingOverlay = document.getElementById('loading-overlay'); // Cambiado para coincidir con el HTML

        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex'; // Mostrar overlay
        }
        $.ajax({
            url: "{{ url('polizas/deuda/get_creditos') }}/" + '{{ $deuda->Id }}'
            , type: 'GET'
            , data: {
                buscar: buscar
                , opcion: opcion
            }
            , success: function(response) {
                // Aquí manejas la respuesta. Por ejemplo, podrías imprimir la respuesta en la consola:
                if (opcion == 1) {
                    $('#creditos_no_validos').html(response);
                } else {
                    $('#creditos_validos').html(response);
                }

            }
            , error: function(error) {
                // Aquí manejas el error, si ocurre alguno durante la petición
                console.error(error);
            }
            , complete: function() {
                if (loadingOverlay) {
                    console.log("Ocultando overlay en complete");
                    loadingOverlay.style.display = 'none'; // Ocultar overlay después de la solicitud
                }
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


    function get_creditos_detalle(documento,poliza,tipo) {
        console.log(documento)
        $.ajax({
            url: "{{ url('polizas/deuda/get_creditos_detalle') }}/" + documento+"/"+poliza+"/"+tipo
            , type: 'GET'
            , success: function(response) {
                console.log(response);
                $('#modal-creditos').html(response);
            }
            , error: function(error) {
                // Aquí manejas el error, si ocurre alguno durante la petición
                console.error(error);
            }
        });
    }

</script>
@endsection
