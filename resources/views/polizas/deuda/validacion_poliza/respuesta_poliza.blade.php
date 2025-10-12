@extends ('welcome')
@section('contenido')
    @include('polizas.deuda.validacion_poliza.js')


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
                                <h2>Resumen de cartera {{ $deuda->NumeroPoliza }} <br>
                                    {{ $deuda->clientes->Nombre }}
                                </h2>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                                <table>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <a href="{{ url('polizas/deuda') }}/{{ $deuda->Id }}/edit"
                                                class="btn btn-info">Pausar Validación</a>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <form method="post" action="{{ url('polizas/deuda/borrar_proceso_actual') }}">
                                                @csrf
                                                <input type="hidden" name="deuda_id" value="{{ $deuda->Id }}">
                                                <button class="btn btn-default">Borrar Proceso Actual</button>
                                            </form>
                                        </td>
                                        <td>

                                            <form method="post" id="miFormulario"
                                                action="{{ url('polizas/deuda/store_poliza') }}">
                                                @csrf
                                                <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                                                <input type="hidden" name="MesActual" value="{{ $mesActual }}">
                                                <input type="hidden" name="AxoActual" value="{{ $axoActual }}">

                                                <input type="hidden" name="Eliminados"
                                                    value="{{ $registros_eliminados->isNotEmpty() ? implode(', ', $registros_eliminados->pluck('NumeroReferencia')->toArray()) : '' }}">


                                                <input type="hidden" name="MesAnterior" value="{{ $mesAnterior }}">
                                                <input type="hidden" name="AxoAnterior" value="{{ $axoAnterior }}">


                                                <button id="btnGuardarCartera" type="submit" class="btn btn-primary"
                                                    {{ $deuda->conteoEdadMaxima() > 0 ? 'disabled' : '' }}>
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
                                        <li role="presentation" class=""><a href="#tab_content7"
                                                id="responsabilidad-tab5" role="tab" data-toggle="tab"
                                                aria-expanded="false">Responsabilidad <br> Máxima </a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content1" id="home-tab"
                                                role="tab" data-toggle="tab" aria-expanded="true">Nuevos <br>
                                                registros</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content5" id="profile-tab5"
                                                role="tab" data-toggle="tab" aria-expanded="false">Registros <br>
                                                Eliminados</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content2" role="tab"
                                                id="profile-tab" data-toggle="tab" aria-expanded="false">Creditos <br> no
                                                válidos</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content3" role="tab"
                                                id="profile-tab2" data-toggle="tab" aria-expanded="false">Registros <br> con
                                                requisitos</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content4" role="tab"
                                                id="profile-tab2" data-toggle="tab" aria-expanded="false">Extraprimados
                                                <br>
                                                excluidos</a>
                                        </li>

                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <br>
                                        <!-- edad maxima -->
                                        <div role="tabpanel" class="tab-pane active" id="tab_content6"
                                            aria-labelledby="edad-tab">
                                            @include('polizas.deuda.validacion_poliza.tab1_edad_maxima')

                                        </div>



                                        <!-- responsabilidad maxima -->
                                        <div role="tabpanel5" class="tab-pane" id="tab_content7"
                                            aria-labelledby="responsabilidad-tab5">
                                            @include('polizas.deuda.validacion_poliza.tab2_responsabilidad_maxima')
                                        </div>


                                        <!-- nuevos registros -->
                                        <div role="tabpanel" class="tab-pane  " id="tab_content1"
                                            aria-labelledby="home-tab">


                                            @include('polizas.deuda.validacion_poliza.tab3_nuevos_registros')

                                        </div>



                                        <!-- registros eliminados -->
                                        <div role="tabpanel5" class="tab-pane" id="tab_content5" aria-labelledby="tab">

                                            @include('polizas.deuda.validacion_poliza.tab4_registros_eliminados')

                                        </div>


                                        <!-- creditos no validos -->
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                            aria-labelledby="profile-tab">

                                            @include('polizas.deuda.validacion_poliza.tab5_registro_no_validos')
                                        </div>


                                        <!--con requisitos-->
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content3"
                                            aria-labelledby="profile-tab">

                                            @include('polizas.deuda.validacion_poliza.tab6_registros_requisitos')


                                        </div>

                                        <!--con extraprimados excluidos-->
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content4"
                                            aria-labelledby="profile-tab">

                                            @include('polizas.deuda.validacion_poliza.tab7_registros_excluidos')

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

    @include('polizas.deuda.validacion_poliza.modales')
@endsection
