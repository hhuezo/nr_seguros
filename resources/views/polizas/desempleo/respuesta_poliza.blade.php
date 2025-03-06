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
                                            <a href="{{ url('polizas/desempleo') }}/{{ $desempleo->Id }}"
                                                class="btn btn-info">Pausar Validación</a>
                                        </td>
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
                                        <li role="presentation" class="active"><a href="#tab_content6" id="edad-tab"
                                                role="tab" data-toggle="tab" aria-expanded="false">Edad <br> Máxima </a>
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
                                        <div role="tabpanel" class="tab-pane active" id="tab_content6"
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
                                                                <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                                                                    Años</td>
                                                                <td>{{ $registro->EdadDesembloso ? $registro->Edad : '' }}
                                                                    Años</td>
                                                                <td>${{ number_format($registro->MontoOtorgado, 2) }}</td>
                                                                <td>
                                                                    <input type="checkbox"
                                                                        onchange="excluir({{ $registro->Id }},0,1)"
                                                                        class="js-switch">
                                                                    <input type="hidden"
                                                                        id="id_excluido-{{ $registro->Id }}"
                                                                        value="{{ $registro->Excluido }}">
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




            {{-- <div class="modal fade" id="modal_cambio_credito_valido" tabindex="-1" role="dialog"
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



            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="col-md-6">
                                <h4 class="modal-title" id="myModalLabel">Detalle créditos</h4>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span>
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
            </div> --}}








        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#MyTable').DataTable();
        });
    </script>
@endsection
