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
                                    <td style="vertical-align: top;"> <a href="{{url('polizas/deuda')}}/{{$deuda->Id}}/edit" class="btn btn-default">
                                            Cancelar
                                        </a></td>
                                    <td>
                                    
                                        <form method="post" action="{{ url('polizas/deuda/store_poliza') }}">
                                            @csrf
                                            <input type="hidden" name="Cartera" value="{{ $deuda->Id }}">
                                            <input type="hidden" name="MesActual" value="{{ date('Y-m-d', strtotime($date)) }}">
                                            <input type="hidden" name="MesAnterior" value="{{ date('Y-m-d', strtotime($date_anterior)) }}">
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
                                    <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Nuevos registros</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content5" id="profile-tab5" role="tab" data-toggle="tab" aria-expanded="false">Registros Eliminados</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Creditos no
                                            válidos</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros con
                                            requisitos</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros
                                            válidos</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane active " id="tab_content1" aria-labelledby="home-tab">
                                        <br>
                                        <table class="table table-striped" id="MyTable1">
                                            <thead>
                                                <tr>
                                                    <th>Número crédito</th>
                                                    <th>DUI</th>
                                                    <th>NIT</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha nacimiento</th>
                                                    <th>Edad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($nuevos_registros as $registro)
                                                <tr>
                                                    <td>{{ $registro->NumeroReferencia }}</td>
                                                    <td>{{ $registro->Dui }}</td>
                                                    <td>{{ $registro->Nit }}</td>
                                                    <td>{{ $registro->PrimerNombre }} {{ $registro->SegundoNombre }}
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
                                                    <th>Fecha nacimiento</th>
                                                    <th>Edad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($registros_eliminados as $registro)
                                                <tr>
                                                    <td>{{ $registro->NumeroReferencia }}</td>
                                                    <td>{{ $registro->Dui }}</td>
                                                    <td>{{ $registro->Nit }}</td>
                                                    <td>{{ $registro->PrimerNombre }} {{ $registro->SegundoNombre }}
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
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                                        <table class="table table-striped" id="MyTable3">
                                            <thead>
                                                <tr>
                                                    <th>Número crédito</th>
                                                    <th>DUI</th>
                                                    <th>NIT</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha nacimiento</th>
                                                    <th>Edad</th>
                                                    <th>Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($poliza_cumulos->where('NoValido', '=', 1) as $registro)
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
                                                    <td class="text-right">
                                                        ${{ number_format($registro->total_saldo, 2) }}</td>
                                                </tr>
                                                @endforeach


                                            </tbody>
                                        </table>

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                        <br>
                                        <table class="table table-striped" id="MyTable4">
                                            <thead>
                                                <tr>
                                                    <th>Número crédito</th>
                                                    <th>DUI</th>
                                                    <th>NIT</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha nacimiento</th>
                                                    <th>Edad</th>
                                                    <th>Requisitos</th>
                                                    <th>Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($poliza_cumulos->where('Perfiles', '<>', null) as $registro)
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

                                                        <td>
                                                            @php
                                                            $uniquePerfiles = array_unique($registro->Perfiles);
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
                                                        <th>Edad</th>
                                                        <th>Saldo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($poliza_cumulos->where('Perfiles', '=', null)->where('NoValido', '=', 0) as $registro)
                                                        <tr>
                                                            <td>{{ $registro->NumeroReferencia }}</td>
                                                            <td>{{ $registro->Dui }}</td>
                                                            <td>{{ $registro->Nit }}</td>
                                                            <td>{{ $registro->PrimerNombre }}
                                                                {{ $registro->SegundoNombre }}
                                                                {{ $registro->PrimerApellido }}
                                                                {{ $registro->SegundoApellido }}
                                                                {{ $registro->ApellidoCasada }}</td>
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
    })
</script>
@endsection