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


<!-- Agrega este div al final de tu archivo blade -->
<div id="loading-overlay">
    <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    var loadingOverlay = document.getElementById('loading-overlay');
    var submitButton = document.getElementById('submitButton');
    var myForm = document.getElementById('myForm');

    submitButton.addEventListener('click', function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        loadingOverlay.style.display = 'flex'; // Cambia a 'flex' para usar flexbox

        // Validación del formulario
        if (document.getElementById('LineaCredito_Subir').value === '') {
            Swal.fire('Debe seleccionar una línea de crédito');
            loadingOverlay.style.display = 'none'; // Oculta el overlay en caso de error
            return;
        } else if (document.getElementById('Archivo').value === '') {
            Swal.fire('Debe seleccionar un archivo');
            loadingOverlay.style.display = 'none'; // Oculta el overlay en caso de error
            return;
        }
        myForm.submit();
       
    });
});
</script>


<ul class="nav navbar-right panel_toolbox">
    <div class="btn btn-info float-right" data-toggle="modal" data-target="#modal_pago">
        Subir Archivo Excel</div>
</ul>
<div class="modal fade bs-example-modal-lg" id="modal_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Subir archivo Excel</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="myForm" action="{{ url('polizas/deuda/create_pago') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Linea de
                            Credito</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="LineaCredito" id="LineaCredito_Subir" class="form-control" required>
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($creditos as $obj)
                                <option value="{{ $obj->Id }}"> {{ $obj->tipoCarteras->Nombre }}
                                    {{ $obj->saldos->Abreviatura }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Año</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Axo" class="form-control">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                <option value="{{ $i }}"> {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Mes</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select name="Mes" class="form-control">
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                    {{ $meses[$i] }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            inicio</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="Id" value="{{ $deuda->Id }}" type="hidden" required>
                            <input class="form-control" type="date" name="FechaInicio" value="{{ $ultimo_pago ? date('Y-m-d', strtotime($ultimo_pago->FechaFinal)) : date('Y-m-d', strtotime($primerDia)) }}" {{ $ultimo_pago ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                            final</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input class="form-control" name="FechaFinal" value="{{ $ultimo_pago_fecha_final ? $ultimo_pago_fecha_final : date('Y-m-d', strtotime($ultimoDia)) }}" type="date" required>
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
                    <button type="button" class="btn btn-primary" id="submitButton">Subir Cartera</button>
                </div>
            </form>

            <div id="loading-indicator" style="text-align: center; display:none">
                <img src="{{ asset('img/ajax-loader.gif') }}">
                <br>
            </div>


        </div>
    </div>
</div>
<div>
    <form action="{{ url('polizas/deuda/agregar_pago') }}" method="POST">
        <div class="modal-header">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo pago</h5>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
        <div class="modal-body">
            <div class="box-body row">
                <input type="hidden" name="ExcelURL" id="ExcelURL" value="{{ session('ExcelURL') }}" class="form-control">
                <input type="hidden" name="Deuda" id="Deuda" value="{{ $deuda->Id }}" class="form-control">
                @csrf
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 ">

                    &nbsp;

                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                    <label class="control-label" align="right">Fecha Inicio</label>
                    <div class="form-group row">
                        <input class="form-control" name="FechaInicio" id="FechaInicio" type="date" value="{{ isset($fecha) ? $fecha->FechaInicio : '' }}" readonly>
                    </div>
                    <br>
                    <div class="form-group row" style="margin-top:-3%; text-align: center">
                        <label class="control-label" align="center">Líneas de Crédito</label>



                    </div>
                    @php($i = 0)
                    @php($total = 0)
                    @foreach ($creditos as $obj)
                    <div class="form-group row" style="margin-top:-3%;">
                        <label class="control-label" align="right"> {{ $obj->tipoCarteras->Nombre }}
                            <small>{{ $obj->saldos->Descripcion }}</small> </label>

                        {{-- <div class="form-group has-feedback">
                                    <input type="number" step="any" style="padding-left: 25%;"
                                        name="Credito{{ $obj->Id }}" id="Credito{{ $obj->Id }}"
                        value="{{ $obj->TotalLiniaCredito }}" class="form-control">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div> --}}

                    <div class="form-group has-feedback">
                        <input type="text" step="any" style="text-align: right; padding-left: 25%;" pattern="[0-9,]*\.?[0-9]+" oninput="validateInput(this)" name="Credito{{ $obj->Id }}" id="Credito{{ $i }}" value="{{ $obj->TotalLiniaCredito ? number_format($obj->TotalLiniaCredito, 2, '.', ',') : '' }}" class="form-control" onchange="calcularTotal()">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>

                @php($total = $total + $obj->TotalLiniaCredito)
                @php($i++)
                @endforeach

                <div class="form-group row" style="margin-top:-3%;">
                    <label class="control-label" align="right">Monto Cartera</label>
                    <div class="form-group has-feedback">
                        <input class="form-control" name="MontoCartera" onblur="show_MontoCartera()" id="MontoCartera" type="number" step="any" style="text-align: right; display: none;" value="{{ $total }}" required>
                        <input class="form-control" id="MontoCarteraView" type="text" step="any" onchange="calculoTotalMonto(this.value)" style="text-align: right;" value="{{ number_format($total, 2, '.', ',') }}" pattern="[0-9,]*\.?[0-9]+" oninput="validateInput(this)" required>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row ocultar">
                    <label class="control-label" align="right">Tasa %</label>
                    <div class="form-group has-feedback">
                        <input type="number" step="any" style="padding-left: 25%;" name="Tasa" id="Tasa" value="{{ $deuda->Tasa }}" class="form-control" readonly>
                        <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row" style="margin-top:-4%;">
                    <label class="control-label " align="right">Tasa mensual por millar
                    </label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" style="text-align: right;" id="tasaFinal" class="form-control" readonly>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row ocultar" style="margin-top:-4%;">
                    <label class="control-label " align="right">Prueba
                        Decimales</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" readonly id="PruebaDecimales" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row" style="margin-top:-4%;">
                    <label class="control-label " align="right">Prima
                        Calculada</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="PrimaCalculada" id="PrimaCalculada" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row ">
                    <label class="control-label " align="right">Extra Prima</label>

                    <div class="form-group has-feedback">
                        <input class="form-control" name="ExtraPrima" type="number" step="any" id="ExtPrima" style="text-align: right;" value="{{$total_extrapima  }}">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>

                <div class="form-group row ">
                    <label class="control-label" align="right">
                        Prima Total</label>
                    <div class="form-group has-feedback">
                        <input class="form-control" name="PrimaTotal" type="number" step="any" id="PrimaTotal" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="form-group row ocultar ">
                    <label class="control-label" align="right">Tasa de Descuento %</label>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group has-feedback">
                            <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento" style="padding-left: 25%;" value="{{ $deuda->TasaDescuento }}" readonly>
                            <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">(-) Descuento
                        Rentabilidad {{ $deuda->TasaDescuento }} %</label>


                    <div class="form-group has-feedback">
                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">
                        (=) Prima Descontada</label>
                    <div class="form-group has-feedback">
                        <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>
                <input type="hidden" name="Bomberos" id="Bomberos" value="{{ $bomberos }}">
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">(+) Impuestos
                        Bomberos</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="ImpuestoBomberos" id="ImpuestoBomberos" class="form-control" readonly style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">Gastos emisión</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="0" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>

                    </div>
                </div>
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">Otros</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="Otros" id="Otros" value="0" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">Sub Total</label>
                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="SubTotal" id="SubTotal" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">13% IVA</label>
                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="Iva" id="Iva" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>


                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">Menos valor CCF de
                        comisión</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="ValorCCF" id="ValorCCF" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <!-- <a href="" data-target="#modal-calculator" data-toggle="modal" class="col-md-1 control-label" style="text-align: center;"><span class="fa fa-calculator fa-lg"></span></a> -->

                </div>

                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">A pagar</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="APagar" id="APagar" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="form-group row" style="margin-top:-5%;">
                    <label class="control-label" align="right">Total Factura</label>


                    <div class="form-group has-feedback">
                        <input type="number" step="any" name="Facturar" id="Facturar" class="form-control" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>
                </div>

            </div>

            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 ">

                &nbsp;

            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                <div class="form-group row">
                    <label class="control-label" align="right">Fecha Final</label>
                    <input class="form-control" name="FechaFinal" id="FechaFinal" type="date" value="{{ isset($fecha) ? $fecha->FechaFinal : '' }}" readonly>
                </div>
                <br>
                <div class="form-group row">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                </div>
                <div class="form-group row">
                    <label class="control-label" align="right">% Comisión</label>


                    <div class="form-group has-feedback">
                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" style="padding-left: 25%;" value="{{ $deuda->TasaComision }}" readonly>
                        <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row ocultar" >
                    <label class="control-label" align="right" style="margin-top:-4%;">% Comisión</label>


                    <div class="form-group has-feedback">
                        <input class="form-control" name="Comision" id="Comision" type="number" step="any" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group row" style="margin-top:-4%;">
                    <label class="control-label" align="right">(+) 13% IVA </label>


                    <div class="form-group has-feedback">
                        <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>

                <div class="form-group row" style="margin-top:-4%;">
                    <label class="control-label" align="right">(-) 1% Retención</label>


                    <div class="form-group has-feedback">
                        <input class="form-control" name="Retencion" id="Retencion" type="number" step="any" style="text-align: right;" @if ($deuda->clientes->TipoContribuyente == 1 || $deuda->clientes->TipoContribuyente == 4) readonly @endif>
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>

                <div class="form-group row" style="margin-top:-4%;">
                    <label class="control-label" align="right">Valor CCF
                        Comisión</label>


                    <div class="form-group has-feedback">
                        <input class="form-control" id="ValorCCFE" type="number" step="any" style="text-align: right;">
                        <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                    </div>

                </div>
                <div class="form-group" style="margin-top:-4%;">
                    
                        <label class="control-label">Comentario</label>
                        <textarea name="Comentario" class="form-control" rows="5"></textarea>
                   
                </div>
            </div>

        </div>
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-aplicar">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Aplicación de cobro</h4>
                    </div>
                    <div class="modal-body">
                        <p>¿Esta seguro/a que desea aplicar el cobro?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button id="boton_pago" class="btn btn-primary">Confirmar
                            Cobro</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div align="center">
            <a class="btn btn-default" data-target="#modal-cancelar" data-toggle="modal" onclick="cancelarpago()">Cancelar Cobro</a>
            <a class="btn btn-primary" data-target="#modal-aplicar" data-toggle="modal" onclick="aplicarpago()">Generar Cobro</a>
        </div>

    </form>
</div>

<div class="modal fade" id="modal-cancelar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog">
        <form action="{{ url('deuda/cancelar_pago') }}" method="POST">
            @method('POST')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Cancelar de Cobro</h4>

                    <input type="hidden" name="Deuda" value="{{ $deuda->Id }}">
                    <input type="hidden" name="MesCancelar" value="{{ isset($fecha) ? $fecha->Mes : '' }}">
                    <input type="hidden" name="AxoCancelar" value="{{ isset($fecha) ? $fecha->Axo : '' }}">
                </div>
                <div class="modal-body">
                    <p>¿Esta seguro/a que desea cancelar el cobro?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-danger">Cancelar
                        Cobro</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    function validateInput(input) {
        // Eliminar caracteres no permitidos, pero mantener los separadores de miles
        input.value = input.value.replace(/[^\d.,]/g, '');

        // Verificar si hay más de un punto decimal y corregirlo
        if (input.value.split('.').length > 2) {
            input.value = input.value.replace(/\.+$/, '');
        }
    }

    function calcularTotal() {
        var registros = <?php echo $i; ?>;
        var total = 0;
        console.log(registros);
        for (var i = 0; i < registros; i++) {
            var number_text = document.getElementById('Credito' + i).value;
            var decimal_number = parseFloat(number_text.replace(/,/g, ''));
            total += decimal_number;
        }

        document.getElementById('MontoCartera').value = total;

        //formato para vista
        var formattedTotal = total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.getElementById('MontoCarteraView').value = formattedTotal;

        calculoGeneralMontoCartera();
    }

    function calculoTotalMonto(cantidad) {
        var decimal_number = parseFloat(cantidad.replace(/,/g, ''));
        document.getElementById('MontoCartera').value = decimal_number;
        calculoGeneralMontoCartera();
    }


    function calculoGeneralMontoCartera() {
        calculoPrimaCalculada();
        calculoPrimaTotal();
        calculoDescuento();
        calculoSubTotal();
        calculoCCF();
    }
</script>
</div>