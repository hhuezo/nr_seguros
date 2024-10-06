@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="x_panel">
    <style>
        .ocultar {
            display: none;
        }
    </style>
    <style>
    #loading-overlay-modal {
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

    #loading-overlay-modal img {
        width: 50px;
        /* Ajusta el tamaño de la imagen según tus necesidades */
        height: 50px;
        /* Ajusta el tamaño de la imagen según tus necesidades */
    }
</style>

    @php
    $tab = request()->has('tab') ? request('tab') : 1;
    @endphp
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Pólizas / Deuda / Póliza de deuda / {{ $deuda->NumeroPoliza }}<small></small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">

                    <a href="{{ url('polizas/deuda') }}" class="btn btn-info">Atras</a>
                </ul>
                <div class="clearfix"></div>
            </div>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="x_content">
                <br />
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="{{ $tab == 1 ? 'active' : '' }}"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de la
                                    Poliza</a>
                            </li>
                            <li role="presentation" class="{{ $tab == 2 ? 'active' : '' }}"><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Generar
                                    Cartera</a>
                            </li>
                            <li role="presentation" class="{{ $tab == 7 ? 'active' : '' }}"><a href="#tab_content7" role="tab" id="extra-prima-tab" data-toggle="tab" aria-expanded="false">Extra
                                    Prima {{ $deuda->NumeroPoliza }}</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="creditos-tab" data-toggle="tab" aria-expanded="false">Hoja de Cartera
                                    {{ $deuda->NumeroPoliza }}</a>
                            </li>
                            <li role="presentation" class="{{ $tab == 4 ? 'active' : '' }}"><a href="#tab_content4" role="tab" id="pagos-tab" data-toggle="tab" aria-expanded="false">Estados de Pago</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab_content5" role="tab" id="avisos-tab" data-toggle="tab" aria-expanded="false">Ver Avisos</a>
                            </li>
                            <li role="presentation" class=""><a href="#tab_content6" role="tab" id="comentarios-tab" data-toggle="tab" aria-expanded="false">Comentarios</a>
                            </li>
                        </ul>

                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 1 ? ' active in' : '' }}" id="tab_content1" aria-labelledby="home-tab">
                                <div style="background-color: lightslategray;">
                                    @include('polizas.deuda.tab1')
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? ' active in' : '' }}" id="tab_content2" aria-labelledby="profile-tab">
                                @include('polizas.deuda.tab2')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 7 ? ' active in' : '' }}" id="tab_content7" aria-labelledby="extra-prima-tab">
                                @include('polizas.deuda.tab7')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 3 ? ' active in' : '' }}" id="tab_content3" aria-labelledby="creditos-tab">
                                @include('polizas.deuda.tab3')
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 4 ? ' active in' : '' }}" id="tab_content4" aria-labelledby="pagos-tab">
                                @include('polizas.deuda.tab4')
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="avisos-tab">
                                @include('polizas.deuda.tab5')
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="comentarios-tab">
                                @include('polizas.deuda.tab6')
                            </div>


                        </div>


                    </div>

                </div>


                <br><br>
                <div class="x_title">
                    <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                    <div class="clearfix"></div>
                </div>



                <br>

            </div>
        </div>
    </div>
  
</div>

<div id="loading-overlay-modal" >
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>


@include('sweetalert::alert')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-deuda");

        // alert(document.getElementById('ComisionIva').value);
        $('#comentarios').DataTable();
        $('#avisos').DataTable();
        $('#clientes').DataTable();
        $('#clientes-extra').DataTable();
        //    $('#cobros').DataTable();

        $('#cobros').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
        });
        $("#tblCobros").DataTable({
            "paging": true,
            "ordering": false,
            "info": true,
        });

        $("#Vida").change(function() {
            if (document.getElementById('Vida').checked == true) {
                $('#poliza_vida').show();
            } else {
                $('#poliza_vida').hide();
            }
        })

        $("#Desempleo").change(function() {
            if (document.getElementById('Desempleo').checked == true) {
                $('#poliza_desempleo').show();
            } else {
                $('#poliza_desempleo').hide();
            }
        })



    });

    function formatearCantidad(cantidad) {
        let numero = Number(cantidad);
        return numero.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function ResponsabilidadMax(id) {
        document.getElementById('ResponsabilidadMaximaTexto').value = formatearCantidad(id);
        $("#ResponsabilidadMaxima").hide();
        $("#ResponsabilidadMaximaTexto").show();
    }

    function ResponsabilidadMaxTexto(id) {
        // document.getElementById('ResponsabilidadMaxima').value = document.getElementById('ResponsabilidadMaximaTexto');
        $("#ResponsabilidadMaxima").show();
        $("#ResponsabilidadMaximaTexto").hide();
    }


    function formatearNumero(numero) {
        // Verificar si el número es válido
        if (isNaN(numero)) {
            console.error("El valor ingresado no es un número válido");
            return null;
        }

        // Formatear el número con separador de miles, punto como separador decimal y dos decimales
        var numeroFormateado = numero.toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        return numeroFormateado;
    }

    function add_comment() {

        $("#modal_agregar_comentario").modal('show');
    }


    // function show_MontoCartera() {
    //     var montoCartera = parseFloat(document.getElementById("MontoCartera").value);

    //     var numeroFormateado = formatearNumero(montoCartera);
    //     document.getElementById('MontoCarteraView').value = numeroFormateado;

    //     $("#MontoCarteraView").show();
    //     $("#MontoCartera").hide();

    // }

    function aplicarpago() {

        document.getElementById('MontoCartera').type = "submit";



        document.getElementById('boton_pago').type = "submit";
    }



    function modal_edit(id) {

        // document.getElementById('ModalSaldoA').value = "";
        // document.getElementById('ModalImpresionRecibo').value = "";
        document.getElementById('ModalComentario').value = "";
        document.getElementById('ModalEnvioCartera').value = "";
        document.getElementById('ModalEnvioPago').value = "";
        document.getElementById('ModalPagoAplicado').value = "";
        document.getElementById('ModalId').value = id;



        $.get("{{ url('polizas/deuda/get_pago') }}" + '/' + id, function(data) {


            console.log(data);
            if (data.SaldoA != null) {
                document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
            }

            if (data.ImpresionRecibo != null) {
                document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
                $("#ModalEnvioCartera").removeAttr("readonly");
            }



            document.getElementById('ModalComentario').value = data.Comentario;
            if (data.EnvioCartera) {
                document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
                $("#ModalEnvioCartera").prop("readonly", true);
            } else {
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }


            if (data.EnvioPago) {
                document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
                $("#ModalEnvioPago").prop("readonly", true);
            } else {
                //  $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.PagoAplicado) {
                document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);

                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }
            // // else {
            //     $("#ModalEnvioCartera").prop("readonly", true);
            //     $("#ModalEnvioPago").prop("readonly", true);
            // }



        });
        $('#modal_editar_pago').modal('show');

    }
</script>
@endsection