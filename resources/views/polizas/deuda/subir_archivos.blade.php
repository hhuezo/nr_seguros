@extends ('welcome')
@section('contenido')
    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    @if (session('success'))
        <script>toastr.success("{{ session('success') }}");</script>
    @endif
    @if (session('error'))
        <script>toastr.error("{{ session('error') }}");</script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");

            // Loading al enviar "Validar póliza"
            $('#form-validar-poliza').on('submit', function() {
                var $btn = $(this).find('button[type="submit"]');
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Validando...');
                document.getElementById('loading-overlay').style.display = 'flex';
            });
        });
    </script>

    <div id="loading-overlay">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>

    <div class="x_panel">
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
                    @php
                        $msgWarning = session('warning');
                        if (session('errores')) {
                            $msgWarning .= ' ' . implode(', ', session('errores'));
                        }
                    @endphp
                    <script>toastr.warning({!! json_encode($msgWarning) !!});</script>
                @endif

                @if ($errors->any())
                    <script>toastr.error({!! json_encode(implode(' ', $errors->all())) !!});</script>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo de Cartera</th>
                            <th>Abreviatura</th>
                            <th>Descripcion</th>
                            <th>Mes / Año</th>
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
                                <td>{{ $obj->Mes ?? ''}} {{$obj->Mes ? '/' :''}}{{ $obj->Axo ?? ''}}</td>
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
                <form id="form-validar-poliza" method="POST" action="{{ url('polizas/deuda/validar_poliza') }}">
                    @csrf
                    <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                    <button type="submit" class="btn btn-primary float-right">Validar póliza</button>
                </form>
            </div>
        </div>
    </div>








@endsection
