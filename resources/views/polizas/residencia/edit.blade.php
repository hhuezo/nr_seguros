@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>RESI - Poliza de Residencia <small></small></h2>
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


            <form method="POST" action="{{ route('residencia.update', $residencia->Id) }}">
                @method('PUT')
                @csrf
                <div class="x_content" style="font-size: 12px;">
                    <br />
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="NumeroPoliza" type="text" value="{{ $residencia->NumeroPoliza }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Codigo" type="text" value="{{ $residencia->Codigo}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->aseguradoras->Nombre}}" class="form-control" readonly>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->clientes->Nombre}}" class="form-control" readonly>

                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Nit" id="Nit" type="text" value="{{$residencia->Nit }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Desde</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaDesde" type="text" value="{{ \Carbon\Carbon::parse($residencia->VigenciaDesde)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Hasta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaHasta" type="text" value="{{ \Carbon\Carbon::parse($residencia->VigenciaHasta)->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->ejecutivos->Nombre}}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estatus</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->estadoPolizas->Nombre}}" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite grupo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="any" name="LimiteGrupo" value="{{ $residencia->LimiteGrupo }}" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite individual</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="any" name="LimiteIndividual" value="{{ $residencia->LimiteIndividual }}" class="form-control" readonly>
                                </div>
                            </div>
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Monto cartera</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="MontoCartera" value="{{ $residencia->MontoCartera }}" class="form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Tasa" value="{{$residencia->Tasa }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Descuento %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Descuento" Id="Descuento" value="{{ $residencia->Descuento }}" class="form-control">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Valor prima</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="ValorPrima" id="ValorPrima" value="{{ $residencia->Prima }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">IVA</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Iva" Id="Iva" value="{{ $residencia->Iva }}" class="form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Menos valor CCF de comision</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Comision" id="ValorCCF" value="{{ $residencia->Comision }}" class="form-control">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">A pagar</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="APagar" id="APagar" value="{{ $residencia->APagar }}" class="form-control">
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Descuento con Iva</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                @if($residencia->DescuentoIva == 1)
                                <input type="checkbox" name="DescuentoIva" checked style="width: 10%; height: 5%;">
                                @else
                                <input type="checkbox" name="DescuentoIva" style="width: 10%; height: 5%;">
                                @endif

                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Gastos emision</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="{{ $residencia->GastosEmision }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Impuestos bomberos</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="ImpuestosBomberos" id="ImpuestosBomberos" value="{{ $residencia->ImpuestosBomberos}}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
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
                                {{ $residencia->Comentario }}
                                </textarea>
                            </div>

                        </div>
                    </div>

                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <br>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 1px solid;">
                        <br>
                        <br>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impresión
                                    de Recibo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="ImpresionRecibo" id="ImpresionRecibo" type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                    Cartera</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="EnvioCartera" id="EnvioCartera" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                    Pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="EnvioPago" id="EnvioPago" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Saldo
                                    A</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="SaldoA" id="SaldoA" type="text" style="background-color: yellow;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pago
                                    Aplicado</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="PagoAplicado" id="PagoAplicado" type="text">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="x_title">
                            <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                            <div class="clearfix"></div>
                        </div>

                        <div>
                            <br>
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Tasa</th>
                                    <th>Descuento</th>
                                    <th>A Pagar</th>
                                    <th>Impresion de Recibo</th>
                                    <th>Envio de Cartera</th>
                                    <th>Envio de Pago</th>
                                    <th>Pago Aplicado</th>
                                </tr>
                                @foreach($detalle as $obj)
                                <tr>
                                    <td>{{$obj->Tasa}}</td>
                                    <td>{{$obj->Descuento}}</td>
                                    <td>{{$obj->APagar}}</td>
                                    <td>{{$obj->ImpresionRecibo}}</td>
                                    <td>{{$obj->EnvioCartera}}</td>
                                    <td>{{$obj->EnvioPago}}</td>
                                    <td>{{$obj->PagoAplicado}}</td>
                                </tr>
                                @endforeach
                            </table>

                        </div>
                    <br><br>
                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>



                    <div class="form-group" align="center">
                        <span id="habilitar" class="btn btn-warning">Habilitar</span>
                        <button class="btn btn-success" type="submit">Modificar</button>
                        <a href="#"><button class="btn btn-primary" type="button">Cancelar</button></a>
                    </div>
                </div>



            </form>



        </div>


    </div>
</div>
@include('sweetalert::alert')
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#habilitar").click(function() {
            //  $("#btn_guardar").click(function() {
            document.getElementById('ImpresionRecibo').removeAttribute('readonly');
            document.getElementById('ImpresionRecibo').type = 'date';
            document.getElementById('EnvioCartera').type = 'date';
            document.getElementById('EnvioPago').type = 'date';
            document.getElementById('PagoAplicado').type = 'date';
            document.getElementById('SaldoA').type = 'date';
            document.getElementById('ValorDescuento').value = 0;
            document.getElementById('IvaSobreComision').value = 0;
            document.getElementById('Retencion').value = 0;
            document.getElementById('ValorCCFE').value = 0;

        })
    })
</script>
@endsection