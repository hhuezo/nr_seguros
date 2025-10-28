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
            <h3>Subir Carteras de <br> {{ $deuda->NumeroPoliza }} | {{ $deuda->clientes->Nombre }} <br><br>
            </h3>
            <h4>Recibos Complementarios</h4>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12" align="right">
            <a href="{{ url('polizas/deuda') }}/{{ $deuda->Id }}/edit"><button class="btn btn-info float-right"> <i
                        class="fa fa-undo"></i> Atras</button></a>
        </div>


        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

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
                        <td align="right">${{ number_format($obj->Total, 2, '.', ',') }}
                        </td>
                        <td align="center">
                            @if($deuda->Aseguradora == 3)
                            <a data-target="#modal-add-fede-{{ $obj->Id }}" data-toggle="modal">
                                <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button> </a>
                            @else
                            <a data-target="#modal-add-{{ $obj->Id }}" data-toggle="modal">
                                <button class="btn btn-default"><i class="fa fa-upload fa-lg"></i></button> </a>
                            @endif
                            @if($obj->Total > 0)
                                <a data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal">
                                <button class="btn btn-default"><i class="fa fa-trash fa-lg"></i></button> </a>
                            @endif

                        </td>


                    </tr>
                    <div class="modal fade bs-example-modal-lg" id="modal-add-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel..</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('polizas/deuda/create_pago_recibo') }}" id="uploadForm{{$obj->Id}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de
                                                Credito..</label>
                                            <input type="hidden" name="PolizaDeudaTipoCartera" value="{{ $obj->Id }}">
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" class="form-control" value="{{$obj->tipo_cartera->Nombre}}" readonly>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                inicio</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Id" value="{{ $deuda->Id }}" type="hidden" required>
                                                <input class="form-control" type="date" name="FechaInicio" value="{{ $fecha_inicial}}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                final</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="FechaFinal" value="{{ $fecha_final}}" type="date" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Archivo" id="Archivo" type="file" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Subir Cartera</button>
                                        <!-- <button type="button" class="btn btn-primary" id="submitButton-{{$obj->Id}}">Subir Cartera</button> -->
                                    </div>
                                </form>




                            </div>
                        </div>
                    </div>

                    <div class="modal fade bs-example-modal-lg" id="modal-add-fede-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel..</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('polizas/deuda/fede/create_pago_recibo') }}" id="uploadForm{{$obj->Id}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de
                                                Credito..</label>
                                            <input type="hidden" name="PolizaDeudaTipoCartera" value="{{ $obj->Id }}">
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" class="form-control" value="{{$obj->tipo_cartera->Nombre}}" readonly>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                inicio</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Id" value="{{ $deuda->Id }}" type="hidden" required>
                                                <input class="form-control" type="date" name="FechaInicio" value="{{ $fecha_inicial}}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                final</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="FechaFinal" value="{{ $fecha_final}}" type="date" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Archivo" id="Archivo" type="file" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Subir Cartera</button>
                                        <!-- <button type="button" class="btn btn-primary" id="submitButton-{{$obj->Id}}">Subir Cartera</button> -->
                                    </div>
                                </form>




                            </div>
                        </div>
                    </div>
                    <div class="modal fade bs-example-modal-lg" id="modal-delete-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel..</h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ url('polizas/deuda/eliminar_pago') }},{{$deuda->Id}}" method="POST" >
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">

                                            <input type="hidden" name="PolizaDeudaTipoCartera" value="{{ $obj->Id }}">


                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Eliminar Cartera</button>
                                        <!-- <button type="button" class="btn btn-primary" id="submitButton-{{$obj->Id}}">Subir Cartera</button> -->
                                    </div>
                                </form>




                            </div>
                        </div>
                    </div>

                    <script>
                        document.getElementById('uploadForm{{$obj->Id}}').addEventListener('submit', function() {
                            document.getElementById('loading-overlay').style.display = 'flex'; // Muestra el overlay de carga
                        });
                    </script>

                    @endforeach
                </tbody>
            </table>



        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
            <form method="POST" action="{{ url('polizas/deuda/validar_poliza_recibos') }}">
                @csrf
                <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                <button class="btn btn-primary float-right">Validar póliza</button>
            </form>
        </div>
    </div>
</div>

@include('sweetalert::alert')
@endsection
