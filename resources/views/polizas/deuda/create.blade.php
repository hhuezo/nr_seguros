@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nuevo Poliza de Deuda &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VIDE - Seguro por Deuda<small></small>
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

                <form action="{{ url('polizas/deuda') }}" method="POST">
                    @csrf
                    <div class="form-horizontal" style="font-size: 12px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Codigo" id="Codigo" type="text" value="{{ old('Codigo') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
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
                                        <input class="form-control" name="Nit" id="Nit" type="text" value="{{ old('Nit') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite de Examenes Médico</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input type="number" step="any" name="LimiteMedico" id="LimiteMedico" value="{{ old('LimiteMedico') }}" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12">Responsabilidad Máxima por Asegurado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input type="number" step="any" name="MaxAsegurado" id="MaxAsegurado" value="{{ old('MaxAsegurado') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                        Especiales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="{{ old('ClausulasEspeciales') }}"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                        Adicionales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4" value="{{ old('BeneficiosAdicionales') }}"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Concepto</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="Concepto" row="3" col="4" value="{{ old('Concepto') }}" required> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comentarios del Cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="Comentario" row="3" col="4" value="{{ old('Comentario') }}"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Desde</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        Cartera</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCartera" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCartera as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
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
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                        Cartera (Saldos de Capital)
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SaldoCapital" id="SaldoCapital" type="number" step="any" value="{{ old('SaldoCapital') }}" readonly>
                                    </div>
                                </div>
                                <br>
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

                                
                                
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa ‰
                                    </label>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any" value="{{ old('Tasa') }}" required>
                                    </div>

                                    <label class="control-label col-md-2 col-sm-12 col-xs-12" align="right">Meses desfase
                                    </label>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <input class="form-control" name="MesesDesfase" type="number" id="MesesDesfase" step="any" value="{{ old('MesesDesfase') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sub
                                        Total</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SubTotal" type="number" id="SubTotal" step="any" value="{{ old('PrimaTotal') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Extra
                                        Prima</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ExtraPrima" type="number" step="any" id="ExtPrima" value="{{ old('ExtraPrima') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                        Prima Total</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="PrimaTotal" type="number" step="any" id="PrimaTotal" value="{{ old('PrimaToal') }}">
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ old('Descuento') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Descuento Especial
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="DescuentoEspecial" type="number" step="any" id="DescuentoEspecial" value="{{ old('DescuentoEspecial') }}">
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">
                                        Prima Descontada</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="PrimaDescontada" type="number" step="any" id="PrimaDescontada" value="{{ old('ExtraPrima') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Menos
                                        Valor CCF Comisión</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorCCF" id="ValorCCF" type="number" step="any" value="{{ old('ValorCCF') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">A
                                        Pagar</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="APagar" type="number" id="APagar" step="any" value="{{ old('APagar') }}">
                                    </div>
                                </div> -->
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Hasta</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estatus</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%" required>
                                            @foreach ($estadoPoliza as $obj)
                                            @if($obj->Id == 1)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                        Cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCobro" class="form-control select2" id="TipoCobro" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCobro as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                   
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                        Cartera (Monto Nominal)
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="MontoNominal" id="MontoNominal" type="number" step="any" value="{{ old('MontoNominal') }}" readonly>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <div class="form-group row">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ old('TasaComision') }}">
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                        Desc</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorDescuento" id="ValorDescuento" type="number" step="any" value="{{ old('ValorDescuento') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                        Desc Especial</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorDescuentoEspecial" id="ValorDescuentoEspecial" type="number" step="any" value="{{ old('ValorDescuentoEspecial') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                        IVA sobre comisión</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="any" value="{{ old('IvaSobreComision') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                        Retención</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                                        <input class="form-control" name="Retencion" id="Retencion" type="number" step="any" value="{{ old('Retencion') }}">

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                        Comisión</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number" step="any">
                                    </div>
                                </div> -->
                            </div>
                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <br>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@include('catalogo.cliente.modal_poliza')


@include('sweetalert::alert')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {


        $("#Anual").change(function() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            var tasaFinal = (tasa / 1000) / 12;
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
            calculoPrimaRepartida();
        })
        $("#Mensual").change(function() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            var tasaFinal = tasa / 1000;
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
            calculoPrimaRepartida();
        })

        $("#TipoCobro").change(function() {
            if (document.getElementById('TipoCobro').value == 1) {
                $("#Usuarios").show();
                document.getElementById('MontoCartera').setAttribute("readonly", true);
                document.getElementById('SubTotal').setAttribute("readonly", true);
            } else {
                $("#Usuarios").hide();
            }


        })

        $("#Codigo").change(function() {
            var codigo = document.getElementById('Codigo').value;
            var num = codigo.substr(-5, 9);
            document.getElementById('NumeroPoliza').value = num;
        })

        $("#Tasa").change(function() {
            if (document.getElementById('TipoCobro').value == 1) {
                $("#Usuarios").show();
                document.getElementById('MontoCartera').setAttribute("readonly", true);
                document.getElementById('SubTotal').setAttribute("readonly", true);
                tasaRepartido();
                calculoPrimaRepartida();
            } else {
                $("#Usuarios").hide();
                calculoSubTotal();
                calculoPrimaTotal();
                calculoPrimaDescontada();
                calculoCCF();
            }

        })
        $("#MontoCartera").change(function() {
            calculoSubTotal();
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        function tasaRepartido() {
            var tasa = document.getElementById('Tasa').value;
            document.getElementById('Tasa1').value = tasa;
            document.getElementById('Tasa2').value = tasa;
            document.getElementById('Tasa3').value = tasa;
            document.getElementById('Tasa4').value = tasa;
            document.getElementById('Tasa5').value = tasa;
            document.getElementById('Tasa6').value = tasa;

        }

        function calculoPrimaRepartida() {
            if (document.getElementById('Anual').checked == true) {
                var tasa1 = ((document.getElementById('Tasa1').value) / 1000) / 12;
                var tasa2 = ((document.getElementById('Tasa2').value) / 1000) / 12;
                var tasa3 = ((document.getElementById('Tasa3').value) / 1000) / 12;
                var tasa4 = ((document.getElementById('Tasa4').value) / 1000) / 12;
                var tasa5 = ((document.getElementById('Tasa5').value) / 1000) / 12;
                var tasa6 = ((document.getElementById('Tasa6').value) / 1000) / 12;
            } else if (document.getElementById('Mensual').checked == true) {
                var tasa1 = ((document.getElementById('Tasa1').value) / 1000);
                var tasa2 = ((document.getElementById('Tasa2').value) / 1000);
                var tasa3 = ((document.getElementById('Tasa3').value) / 1000);
                var tasa4 = ((document.getElementById('Tasa4').value) / 1000);
                var tasa5 = ((document.getElementById('Tasa5').value) / 1000);
                var tasa6 = ((document.getElementById('Tasa6').value) / 1000);
            }

            var prima1 = document.getElementById('SumaAsegurada1').value * tasa1;
            var prima2 = document.getElementById('SumaAsegurada2').value * tasa2;
            var prima3 = document.getElementById('SumaAsegurada3').value * tasa3;
            var prima4 = document.getElementById('SumaAsegurada4').value * tasa4;
            var prima5 = document.getElementById('SumaAsegurada5').value * tasa5;
            var prima6 = document.getElementById('SumaAsegurada6').value * tasa6;

            document.getElementById('Prima1').value = prima1;
            document.getElementById('Prima2').value = prima2;
            document.getElementById('Prima3').value = prima3;
            document.getElementById('Prima4').value = prima4;
            document.getElementById('Prima5').value = prima5;
            document.getElementById('Prima6').value = prima6;
            document.getElementById('SubTotal').value = Number(prima1) + Number(prima2) + Number(prima3) + Number(prima4) + Number(prima5) + Number(prima6);

        }


        $("#SumaAsegurada1").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada2").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada3").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada4").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada5").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada6").change(function() {
            calculoMontoCartera();
        })

        function calculoMontoCartera() {
            var suma1 = document.getElementById('SumaAsegurada1').value;
            var suma2 = document.getElementById('SumaAsegurada2').value;
            var suma3 = document.getElementById('SumaAsegurada3').value;
            var suma4 = document.getElementById('SumaAsegurada4').value;
            var suma5 = document.getElementById('SumaAsegurada5').value;
            var suma6 = document.getElementById('SumaAsegurada6').value;
            document.getElementById('MontoCartera').value = Number(suma1) + Number(suma2) + Number(suma3) + Number(suma4) + Number(suma5) + Number(suma6);
            calculoPrimaRepartida();
        }

        function calculoSubTotal() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            if (document.getElementById('Anual').checked == true) {
                var tasaFinal = (tasa / 1000) / 12;
            } else {
                var tasaFinal = tasa / 1000;
            }
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
        }
        $('#ExtPrima').change(function() {
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoPrimaTotal() {
            var sub = document.getElementById('SubTotal').value;
            var extra = document.getElementById('ExtPrima').value;
            var prima = Number(sub) + Number(extra);
            document.getElementById('PrimaTotal').value = Number(prima);
        }
        $("#Descuento").change(function() {
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoPrimaDescontada() {
            var prima = document.getElementById('PrimaTotal').value;
            var descuento = document.getElementById('Descuento').value;
            if (descuento == 0) {
                var total = Number(prima);
            } else {
                var total = Number(prima * (descuento / 100));
            }
            document.getElementById('PrimaDescontada').value = total + Number(document.getElementById('PrimaTotal').value);

        }

        $("#TasaComision").change(function() {
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
            var PrimaTotal = document.getElementById('PrimaTotal').value;
            var APagar = Number(PrimaTotal) - Number(ValorCCF);
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