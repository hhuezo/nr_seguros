@extends ('welcome')
@section('contenido')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-desempleo");
        });
    </script>

    <!-- Agrega este div al final de tu archivo blade -->
    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>


    <div class="x_panel">

        <div class="x_title">
            <div class="col-md-10 col-sm-10 col-xs-12">
                <h3>Subir Carteras de <br> {{ $desempleo->NumeroPoliza }} | {{ $desempleo->cliente->Nombre ?? '' }} </h3>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12" align="right">
                <a href="{{ url('polizas/desempleo') }}/{{ $desempleo->Id }}?tab=2"><button class="btn btn-info float-right">
                        <i class="fa fa-undo"></i> Atras</button></a>
            </div>


            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Linea credito</th>
                            <th>Abreviatura</th>
                            <th>Datos Ingresados</th>
                            <th align="center">Carga de <br> archivo de cartera </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($desempleo_tipos_cartera as $tipo_cartera)
                            @php
                                $total_temp = $totales_por_cartera[$tipo_cartera->Id] ?? 0;
                            @endphp

                            <tr>
                                <td>{{ $tipo_cartera->saldos_montos->Descripcion ?? '' }}</td>
                                <td>{{ $tipo_cartera->saldos_montos->Abreviatura ?? '' }}</td>
                                <td align="right">${{ number_format($total_temp, 2) }}</td>
                                <td align="center">
                                    @if ($desempleo->Aseguradora == 3 || $desempleo->Aseguradora == 4)
                                        <a data-target="#modal-add-fede-{{ $tipo_cartera->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button>
                                        </a>
                                    @else
                                        <a data-target="#modal-add-{{ $tipo_cartera->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button>
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            @include('polizas.desempleo.modal_subir_cartera')
                        @endforeach

                    </tbody>
                </table>



            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                <form method="POST" action="{{ url('polizas/desempleo/validar_poliza') }}/{{$desempleo->Id}}">
                    @csrf
                    <button class="btn btn-primary float-right">Validar póliza</button>
                </form>
            </div>
        </div>
    </div>

















    @include('sweetalert::alert')
@endsection
