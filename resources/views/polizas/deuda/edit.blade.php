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

        @php
            $tab = request()->has('tab') ? request('tab') : 1;
        @endphp
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Poliza de Deuda &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VIDE - Deuda<small></small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">

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
                                <li role="presentation"
                                    class="{{ $tab == 1 ? 'active' : '' }}"><a
                                        href="#tab_content1" id="home-tab" role="tab" data-toggle="tab"
                                        aria-expanded="true">Datos de la Poliza</a>
                                </li>
                                <li role="presentation" class="{{ $tab == 2 ? 'active' : '' }}"><a href="#tab_content2" role="tab"
                                        id="profile-tab" data-toggle="tab" aria-expanded="false">Generar Cartera</a>
                                </li>
                                <li role="presentation"
                                    class="{{ $tab == 7 ? 'active' : '' }}"><a
                                        href="#tab_content7" role="tab" id="extra-prima-tab" data-toggle="tab"
                                        aria-expanded="false">Extra Prima {{ $deuda->NumeroPoliza }}</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content3" role="tab"
                                        id="creditos-tab" data-toggle="tab" aria-expanded="false">Hoja de Cartera
                                        {{ $deuda->NumeroPoliza }}</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content4" role="tab" id="pagos-tab"
                                        data-toggle="tab" aria-expanded="false">Estados de Pago</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content5" role="tab"
                                        id="avisos-tab" data-toggle="tab" aria-expanded="false">Ver Avisos</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content6" role="tab"
                                        id="comentarios-tab" data-toggle="tab" aria-expanded="false">Comentarios</a>
                                </li>
                            </ul>

                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel"
                                    class="tab-pane fade {{ $tab == 1 ? ' active in' : '' }}"
                                    id="tab_content1" aria-labelledby="home-tab">
                                    <div style="background-color: lightslategray;">
                                        @include('polizas.deuda.tab1')
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? ' active in' : '' }}" id="tab_content2" aria-labelledby="profile-tab">
                                    @include('polizas.deuda.tab2')
                                </div>
                                <div role="tabpanel"
                                    class="tab-pane fade {{ $tab == 7 ? ' active in' : '' }}"
                                    id="tab_content7" aria-labelledby="extra-prima-tab">
                                    @include('polizas.deuda.tab7')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="creditos-tab">
                                    @include('polizas.deuda.tab3')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="pagos-tab">
                                    @include('polizas.deuda.tab4')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="avisos-tab">
                                    @include('polizas.deuda.tab5')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content6"
                                    aria-labelledby="comentarios-tab">
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


    @include('sweetalert::alert')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
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
            $("#tblCobros").DataTable();

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

        function formatearNumero(numero) {
            // Verificar si el número es válido
            if (isNaN(numero)) {
                console.error("El valor ingresado no es un número válido");
                return null;
            }

            // Formatear el número con separador de miles y punto como separador decimal
            var numeroFormateado = numero.toLocaleString('en-US', {
                style: 'decimal',
                maximumFractionDigits: 2
            });

            return numeroFormateado;
        }

        function add_comment() {

            $("#modal_agregar_comentario").modal('show');
        }


        function show_MontoCartera() {
            var montoCartera = parseFloat(document.getElementById("MontoCartera").value);

            var numeroFormateado = formatearNumero(montoCartera);
            document.getElementById('MontoCarteraView').value = numeroFormateado;

            $("#MontoCarteraView").show();
            $("#MontoCartera").hide();

        }

        function aplicarpago() {
            document.getElementById('boton_pago').type = "submit";
        }
        $(document).ready(function() {

            $("#MontoCarteraView").on('focus', function() {
                $("#MontoCarteraView").hide();
                $("#MontoCartera").show();
            })



            $('#PrimaDescontada2').val($('#PrimaCalculada2').val() - $('#DescuentoRentabilidad2').val());

            $('#Validar').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#FormArchivo').prop('target', '_blank');
                } else {
                    $('#FormArchivo').removeAttr('target')
                }
            });

            $("#btn_confirmar_recibo").click(function() {
                window.location.reload();

            })

            calculoPrimaCalculada();
            calculoPrimaTotal();
            calculoDescuento();
            calculoSubTotal();
            calculoCCF();

            $('#MontoCartera').change(function() {

                calculoPrimaCalculada();
                calculoPrimaTotal();
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();



            })
            $("#PrimaCalculada").change(function() {
                //  calculoPrimaCalculada();
                calculoPrimaTotal();
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            })


            function calculoPrimaCalculada() {
                var monto = document.getElementById('MontoCartera').value;
                var desde = new Date(document.getElementById('VigenciaDesde').value);
                var hasta = new Date(document.getElementById('VigenciaHasta').value);
                var hoy = new Date();
                console.log(hoy);

                var millisBetween = hasta.getTime() - desde.getTime();


                var dias_axo = (millisBetween / (1000 * 3600 * 24));
                console.log(desde, hasta);
                console.log("dias del año: " + dias_axo)


                var inicio = new Date(document.getElementById('FechaInicio').value);
                var final = new Date(document.getElementById('FechaFinal').value);
                inicio.setHours(0, 0, 0, 0);
                final.setHours(0, 0, 0, 0);
                console.log("inicio" + inicio)
                console.log("final" + final)

                var millisBetween = final.getTime() - inicio.getTime();
                var dias_mes = Math.round(millisBetween / (1000 * 3600 * 24));



                console.log('tasa Final:', tasaFinal);
                var tasaFinal = document.getElementById('Tasa').value;
                var sub = parseFloat(monto) * parseFloat(tasaFinal);

                var sub = parseFloat(monto) * parseFloat(tasaFinal);

                document.getElementById('PruebaDecimales').value = sub;
                document.getElementById('PrimaCalculada').value = sub.toLocaleString('sv-SV', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).replace(',', '.').replace(/[^\d,.-]/g, '');
                document.getElementById('tasaFinal').value = tasaFinal


            }

            $("#ExtPrima").change(function() {
                calculoPrimaTotal();
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            })

            function calculoPrimaTotal() {
                var sub = document.getElementById('PrimaCalculada').value;
                var extra = document.getElementById('ExtPrima').value;
                var prima = Number(sub) + Number(extra);
                document.getElementById('PrimaTotal').value = Number(prima);
            }
            $("#PrimaTotal").change(function() {
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            })
            $("#TasaDescuento").change(function() {
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            })

            function calculoDescuento() {
                var tasa = document.getElementById('TasaDescuento').value;
                var primaTotal = document.getElementById('PrimaTotal').value;
                if (tasa < 0) {
                    document.getElementById('Descuento').value = (tasa * primaTotal).toFixed(2);
                } else {
                    document.getElementById('Descuento').value = ((tasa / 100) * primaTotal).toFixed(2);
                }
                document.getElementById('PrimaDescontada').value = (primaTotal - document.getElementById(
                    'Descuento').value).toFixed(2);
                //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
                if (document.getElementById('Bomberos').value == 0) {
                    document.getElementById('ImpuestoBomberos').value = 0;
                } else {
                    document.getElementById('ImpuestoBomberos').value = (document.getElementById('MontoCartera')
                        .value * ((document.getElementById('Bomberos').value / 100) / 12) / 1000);
                }

            }
            $('#GastosEmision').change(function() {
                calculoSubTotal();
                calculoCCF();
            })
            $('#Otros').change(function() {
                calculoSubTotal();
                calculoCCF();
            })

            function calculoSubTotal() {
                var bomberos = document.getElementById('ImpuestoBomberos').value;
                var primaDescontada = document.getElementById('PrimaDescontada').value;
                var gastos = document.getElementById('GastosEmision').value;
                var otros = document.getElementById('Otros').value;
                document.getElementById('SubTotal').value = Number(bomberos) + Number(primaDescontada) + Number(
                    gastos) + Number(otros);
                document.getElementById('Iva').value = (document.getElementById('SubTotal').value * 0.13).toFixed(
                    2);
            }

            $('#TasaComision').change(function() {
                calculoCCF();
                document.getElementById('APagar').style.backgroundColor = 'yellow';
            })
            $('#ValorCCFE').change(function() {
                var ccfe = document.getElementById('ValorCCFE').value
                document.getElementById('ValorCCF').value = Number(ccfe);
                var PrimaTotal = document.getElementById('SubTotal').value;
                var iva = document.getElementById('Iva').value;
                var APagar = Number(PrimaTotal) - Number(ccfe) + Number(iva);
                document.getElementById('APagar').value = APagar.toFixed(2);
                document.getElementById('APagar').style.backgroundColor = 'yellow';
                document.getElementById('Facturar').value = (Number(PrimaTotal) + Number(iva)).toFixed(2);
            })

            $('#ValorCCF').change(function() {
                var ccf = document.getElementById('ValorCCF').value
                document.getElementById('ValorCCFE').value = Number(ccf);
                var PrimaTotal = document.getElementById('SubTotal').value;
                var iva = document.getElementById('Iva').value;
                var APagar = Number(PrimaTotal) - Number(ccf) + Number(iva);
                document.getElementById('APagar').value = APagar.toFixed(2);
                document.getElementById('APagar').style.backgroundColor = 'yellow';
                document.getElementById('Facturar').value = (Number(PrimaTotal) + Number(iva)).toFixed(2);
            })


            function calculoCCF() {
                var comision = document.getElementById('TasaComision').value;
                var total = document.getElementById('PrimaDescontada').value;
                var valorDes = total * (comision / 100);
                document.getElementById('Comision').value = Number(valorDes).toFixed(2);


                if (document.getElementById('ComisionIva').value == 0) {
                    //  alert('si');
                    var IvaSobreComision = Number(valorDes) * 0.13;
                    document.getElementById('IvaSobreComision').value = Number(IvaSobreComision).toFixed(2);
                } else {
                    // alert('no');
                    var IvaSobreComision = 0;
                    document.getElementById('IvaSobreComision').value = 0;
                }
                if (document.getElementById('Retencion').hasAttribute('readonly')) {
                    var Retencion = 0;
                } else {
                    var Retencion = valorDes * 0.01;
                    document.getElementById('Retencion').value = Retencion;
                }
                var ValorCCF = Number(valorDes) + Number(IvaSobreComision) - Number(Retencion);
                // alert(ValorCCF);
                document.getElementById('ValorCCFE').value = Number(ValorCCF).toFixed(2);
                document.getElementById('ValorCCF').value = Number(ValorCCF).toFixed(2);
                var PrimaTotal = document.getElementById('SubTotal').value;
                var iva = document.getElementById('Iva').value;
                var APagar = Number(PrimaTotal) - Number(ValorCCF) + Number(iva);
                document.getElementById('APagar').value = APagar.toFixed(2);
                document.getElementById('Facturar').value = (Number(PrimaTotal) + Number(iva)).toFixed(2);

            }



        })

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
