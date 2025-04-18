@extends ('welcome')
@section('contenido')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");
        });
    </script>

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

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Ajustar el ancho según sea necesario */
            height: 20px;
            /* Ajustar la altura según sea necesario */
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
            border-radius: 10px;
            /* Ajustar el radio de borde para que sea más pequeño */
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            /* Ajustar la altura según sea necesario */
            width: 16px;
            /* Ajustar el ancho según sea necesario */
            left: 2px;
            /* Ajustar la posición según sea necesario */
            bottom: 2px;
            /* Ajustar la posición según sea necesario */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
            /* Hacer el selector redondo */
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 20px;
            /* Ajustar el radio de borde para que sea más pequeño */
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>


    <!-- Agrega este div al final de tu archivo blade -->
    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>


    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-10 col-sm-10 col-xs-12">
                <h3>Subir Carteras de <br> {{ $poliza_vida->NumeroPoliza }} | {{ $poliza_vida->cliente->Nombre ?? '' }}
                </h3>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12" align="right">
                <a href="{{ url('polizas/deuda') }}/{{ $poliza_vida->Id }}/edit"><button class="btn btn-info float-right"> <i
                            class="fa fa-undo"></i> Atras</button></a>
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
                            <th>Tipo de Cartera</th>
                            <th>Tipo cálculo</th>
                            <th>Monto máximo individual</th>
                            <th>Datos Ingresados</th>
                            <th align="center">Carga de <br> archivo de cartera </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($poliza_vida_tipo_cartera as $obj)
                            <tr>
                                <td>{{ $obj->catalogo_tipo_cartera->Nombre ?? '' }}</td>
                                <td>{{ [1 => 'FECHA', 2 => 'MONTO'][$obj->TipoCalculo] ?? 'NO APLICA' }}</td>
                                <td align="right">${{ number_format($obj->MontoMaximoIndividual, 2, '.', ',') }}</td>
                                <td align="right">${{ number_format($obj->Total, 2, '.', ',') }}</td>
                                <td align="center">
                                    @if ($poliza_vida->Aseguradora == 3)
                                        <a data-target="#modal-add-fede-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button> </a>
                                    @else
                                        <a data-target="#modal-add-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button> </a>
                                    @endif
                                </td>
                            </tr>

                            @include('polizas.vida.add_cartera')
                            @include('polizas.vida.add_cartera_fede')
                        @endforeach
                    </tbody>
                </table>



            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                <form method="POST" action="{{ url('polizas/vida/validar_poliza') }}/{{ $poliza_vida->Id }}">
                    @csrf
                    <button class="btn btn-primary float-right">Validar póliza</button>
                </form>
            </div>
        </div>
    </div>

















    @include('sweetalert::alert')
@endsection
