@extends ('welcome')
@section('contenido')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");
        });
    </script>



    <!-- Agrega este div al final de tu archivo blade -->
    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>


    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-10 col-sm-10 col-xs-12">
                <h3>Subir Carteras de <br> {{ $deuda->NumeroPoliza }} | {{ $deuda->clientes->Nombre }} </h3>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12" align="right">
                <a href="{{ url('polizas/deuda') }}/{{ $deuda->Id }}/edit"><button class="btn btn-info float-right"> <i
                            class="fa fa-undo"></i> Atras</button></a>
            </div>


            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                @if (session('warning'))
                    <div class="alert alert-danger">
                        <strong>{{ session('warning') }}</strong><br>

                        @if (session('errores'))
                            <span>
                                {{ implode(', ', session('errores')) }}
                            </span>
                        @endif
                    </div>
                @endif


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
                            <th>Abreviatura</th>
                            <th>Descripcion</th>
                            <th>Datos Ingresados</th>
                            <th align="center">Carga de <br> archivo de cartera </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deuda_tipo_cartera as $obj)
                            <tr>
                                <td>{{ $obj->tipo_cartera->Nombre }}</td>
                                <td>{{ $obj->Abreviatura }}</td>
                                <td>{{ $obj->Descripcion }}</td>
                                <td align="right">${{ number_format($obj->Total, 2, '.', ',') }}</td>
                                <td align="center">
                                    @if ($deuda->Aseguradora == 3 || $deuda->Aseguradora == 4)
                                        <a data-target="#modal-add-fede-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button> </a>
                                    @else
                                        <a data-target="#modal-add-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button> </a>
                                    @endif
                                    @if ($obj->Total > 0)
                                        <a data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-default"><i class="fa fa-trash fa-lg"></i></button> </a>
                                    @endif
                                </td>
                            </tr>

                            @include('polizas.deuda.add_cartera')
                            @include('polizas.deuda.add_cartera_fede')
                            @include('polizas.deuda.modal_eliminar_cartera')
                        @endforeach
                    </tbody>
                </table>



            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                <form method="POST" action="{{ url('polizas/deuda/validar_poliza') }}">
                    @csrf
                    <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                    <button class="btn btn-primary float-right">Validar póliza</button>
                </form>
            </div>
        </div>
    </div>











    @include('sweetalert::alert')
@endsection
