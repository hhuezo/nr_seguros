@extends ('welcome')
@section('contenido')
<script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>RESI - Seguro de residencias <small></small></h2>
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
            <form action="{{ url('polizas/residencia') }}" method="POST" class="forms-sample">
                @csrf
                <div class="x_content" style="font-size: 12px;">
                    <br />
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Codigo" id="Codigo" type="text" value="{{ old('Codigo') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradoras as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($cliente as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12"><i onclick="modal_cliente();" class="fa fa-plus fa-lg" style="padding-top: 60%;"></i></div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Nit" id="Nit" type="text" value="{{ old('Nit') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Desde</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Hasta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivo as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estatus</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="EstadoPoliza" class="form-control" style="width: 100%">
                                    @foreach ($estados_poliza as $obj)
                                    @if($obj->Id == 1)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite grupo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="LimiteGrupo" id="LimiteGrupo" value="{{ old('LimiteGrupo') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite individual</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="LimiteIndividual" id="LimiteIndividual" value="{{ old('LimiteIndividual') }}" class="form-control">
                            </div>
                        </div>
                        <!-- radio button -->
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                            </label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Mensual" value="1" checked>
                                    <label class="control-label">Tasa ‰ Millar Mensual</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Anual" value="0">
                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <input type="hidden" name="Bomberos" id="Bomberos" value="{{$bomberos}}">



                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Monto Cartera</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="MontoCartera" id="MontoCartera" value="{{ old('MontoCartera') }}" class="form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Tasa" id="Tasa" value="{{ old('Tasa') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Prima Calculada</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="PrimaCalculada" id="PrimaCalculada" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Extra
                                Prima</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="ExtraPrima" type="number" step="any" id="ExtPrima">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                Prima Total</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="PrimaTotal" type="number" step="any" id="PrimaTotal" value="{{ old('PrimaToal') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa de Descuento %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ old('Descuento') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                Prima Descontada</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" value="{{ old('PrimaDescontada') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Impuestos bomberos</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="ImpuestoBomberos" id="ImpuestoBomberos" value="{{ old('ImpuestosBomberos') }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Gastos emisión</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Otros</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Otros" id="Otros" value="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Sub Total</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="SubTotal" id="SubTotal" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">IVA</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Iva" id="Iva" value="{{ old('Iva') }}" class="form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Menos valor CCF de comision</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Comision" id="ValorCCF" value="{{ old('Comision') }}" class="form-control">
                            </div>
                            <!-- <a href="" data-target="#modal-calculator" data-toggle="modal" class="col-md-1 control-label" style="text-align: center;"><span class="fa fa-calculator fa-lg"></span></a> -->
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">A pagar</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="APagar" id="APagar" value="{{ old('APagar') }}" class="form-control">
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">

                        <div class="form-group row">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                Desc</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="ValorDescuento" id="ValorDescuento" type="number" step="any">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                IVA sobre comisión</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                Retención</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Retencion" id="Retencion" type="number" step="any">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                Comisión</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number" step="any">
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left">Comentario del cobro</label>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="Comentario" class="form-control">
                                {{ old('Comentario') }}
                                </textarea>
                            </div>

                        </div>
                    </div>

                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <br>
                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('polizas/residencia/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>
            @include('catalogo.cliente.modal_poliza')

        </div>

    </div>
</div>


<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    // func
    $(document).ready(function() {
        $("#Anual").change(function() {
            calculoPrimaCalculada();
            calculoPrimaTotal();
            calculoDescuento();
            calculoSubTotal();
            calculoCCF();
        })
        $("#Mensual").change(function() {
            calculoPrimaCalculada();
            calculoPrimaTotal();
            calculoDescuento();
            calculoSubTotal();
            calculoCCF();
        })
        $('#MontoCartera').change(function() {
            if (document.getElementById('LimiteGrupo').value < document.getElementById('MontoCartera').value) {
                swal('Su monto de cartera a superado al techo establecido en la poliza');
                document.getElementById('MontoCartera').value = 0;
            } else {
                calculoPrimaCalculada();
                calculoPrimaTotal();
                calculoDescuento();
                calculoSubTotal();
                calculoCCF();
            }


        })
        $("#Tasa").change(function() {
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
            var tasa = document.getElementById('Tasa').value;
            if (document.getElementById('Anual').checked == true) {
                var tasaFinal = (tasa / 1000) / 12;
            } else {
                var tasaFinal = tasa / 1000;
            }
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('PrimaCalculada').value = sub;
            //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
            // document.getElementById('ImpuestoBomberos').value = bomberos;

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
            if (tasa < 1) {
                document.getElementById('Descuento').value = tasa * primaTotal;
            } else {
                document.getElementById('Descuento').value = (tasa / 100) * primaTotal;
            }
            document.getElementById('PrimaDescontada').value = primaTotal - document.getElementById('Descuento').value;
            //  var bomberos = (monto * (0.04 / 12) / 1000); //valor de impuesto varia por gobierno
            if(document.getElementById('Bomberos').value == 0){
                document.getElementById('ImpuestoBomberos').value = 0 ;
            }else{
                document.getElementById('ImpuestoBomberos').value = (((document.getElementById('MontoCartera').value * (document.getElementById('Bomberos').value)/100)/ 12) /1000);
                
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
            document.getElementById('SubTotal').value = Number(bomberos) + Number(primaDescontada) + Number(gastos) + Number(otros);
            document.getElementById('Iva').value = document.getElementById('SubTotal').value * 0.13;
        }

        $('#TasaComision').change(function() {
            calculoCCF();
        })

        function calculoCCF() {
            var comision = document.getElementById('TasaComision').value;
            var total = document.getElementById('PrimaDescontada').value;
            var valorDes = total * (comision / 100);
            document.getElementById('ValorDescuento').value = Number(valorDes);
            var IvaSobreComision = Number(valorDes) * 0.13;
            document.getElementById('IvaSobreComision').value = Number(IvaSobreComision);
            if (document.getElementById('Retencion').hasAttribute('readonly')) {
                var Retencion = 0;
            } else {
                var Retencion = valorDes * 0.01;
                document.getElementById('Retencion').value = Retencion;
            }
            var ValorCCF = Number(valorDes) + Number(IvaSobreComision) - Number(Retencion);
            // alert(ValorCCF);
            document.getElementById('ValorCCFE').value = Number(ValorCCF);
            document.getElementById('ValorCCF').value = Number(ValorCCF);
            var PrimaTotal = document.getElementById('SubTotal').value;
            var iva = document.getElementById('Iva').value;
            var APagar = Number(PrimaTotal) - Number(ValorCCF) + Number(iva);
            document.getElementById('APagar').value = APagar;
        }
        

        $("#Asegurado").change(function() {
                // alert(document.getElementById('Asegurado').value);
                $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
                var parametros = {
                    "Cliente": document.getElementById('Asegurado').value
                };
                $.ajax({
                    type: "get",
                    //ruta para obtener el horario del doctor
                    url: "{{ url('get_cliente') }}",
                    data: parametros,
                    success: function(data) {
                        console.log(data);
                        document.getElementById('Nit').value = data.Nit;
                        if (data.TipoContribuyente == 1) {
                            document.getElementById('Retencion').setAttribute("readonly", true);
                            document.getElementById('Retencion').value = 0;
                            calculoCCF();
                        }


                    }
                });
            });






    });

    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }
</script>



@endsection