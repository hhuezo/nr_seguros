@extends('welcome')

@section('contenido')
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            background-color: #f8f9fa;
        }

        .recibo-container {
            width: 1200px;
            height: 800px;
            margin: 30px auto;
            margin-top: 81px;
            background: #fff;
            padding: 30px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            vertical-align: middle;
        }

        th {
            background: #f1f1f1;
            text-align: center;
        }

        input.form-control {
            border: none;
            border-bottom: 1px solid #000;
            border-radius: 0;
            font-size: 12px;
            padding: 2px 4px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .no-border td {
            border: none !important;
        }

        .section-title {
            background: #e9ecef;
            font-weight: bold;
            text-align: center;
        }

        .border-row tr {
            border: 1px #ccc solid;
        }

        input.form-control:not([readonly]),
        select.form-control:not([readonly]) {
            border: 1px solid #28a745 !important;
        }
    </style>

    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>


    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <div class="recibo-container" style="overflow-y: scroll">
        <form action="{{ url('poliza/vida/get_recibo_edit') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="id_vida_detalle" value="{{ $recibo_historial->PolizaVidaDetalle }}">

            <table class="no-border">
                <tr>
                    <td>
                        <div class="form-inline">
                            San Salvador,&nbsp;
                            <input type="text" class="form-control form-control-sm mx-1 text-center" style="width: 40px;"
                                name="DiaImpresionRecibo"
                                value="{{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('d') }}">

                            de&nbsp;
                            <select name="MesImpresionRecibo" class="form-control form-control-sm mx-1 text-center"
                                style="width: 120px;">
                                @for ($i = 1; $i < count($meses); $i++)
                                    <option value="{{ $i }}"
                                        {{ $meses[\Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('m') - 0] == $meses[$i] ? 'selected' : '' }}>
                                        {{ $meses[$i] }}
                                    </option>
                                @endfor
                            </select>

                            del&nbsp;
                            <input type="text" class="form-control form-control-sm mx-1 text-center" style="width: 60px;"
                                name="AxoImpresionRecibo"
                                value="{{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('Y') }}">
                        </div>
                        <br>

                        Señor(a)(es): <br>
                        <input type="text" class="form-control" name="NombreCliente"
                            value="{{ $recibo_historial->NombreCliente }}">
                        <br>
                        NIT:
                        <input type="text" class="form-control d-inline-block" style="width:200px" name="NitCliente"
                            value="{{ $recibo_historial->NitCliente }}">
                        <br>
                        <label><small>Dirección de Residencia</small></label>
                        <input type="text" class="form-control" name="DireccionResidencia"
                            value="{{ $recibo_historial->DireccionResidencia }}">
                    </td>

                    <td class="text-center" style="width:35%;">
                        <img src="{{ asset('img/logo.jpg') }}" width="140" style="margin-top: 52px;"><br>

                        <div class="border p-2 mt-2">
                            <strong>Aviso de Cobro:</strong><br>

                            <div class="d-flex justify-content-center align-items-center">
                                <span class="mr-1 font-weight-bold"> AC
                                    {{ str_pad($recibo_historial->NumeroRecibo, 6, '0', STR_PAD_LEFT) }}
                                    {{ date('Y') }}</span>
                            </div>
                        </div>
                    </td>

                </tr>
            </table>

            <table>
                <tr>
                    <th>Compañía aseguradora</th>
                    <th colspan="2">Producto de seguros</th>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="CompaniaAseguradora"
                            value="{{ $recibo_historial->CompaniaAseguradora }}"></td>
                    <td colspan="2"><input type="text" class="form-control" name="ProductoSeguros"
                            value="{{ $recibo_historial->ProductoSeguros }}"></td>
                </tr>
                <tr>
                    <th>Número de Póliza</th>
                    <th>Vigencia Inicial (anual)</th>
                    <th>Vigencia Final (anual)</th>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="NumeroPoliza"
                            value="{{ $recibo_historial->NumeroPoliza }}"></td>
                    <td><input type="date" class="form-control" name="VigenciaDesde"
                            value="{{ $recibo_historial->VigenciaDesde }}"></td>
                    <td><input type="date" class="form-control" name="VigenciaHasta"
                            value="{{ $recibo_historial->VigenciaHasta }}"></td>
                </tr>
            </table>

            <table>
                <tr class="section-title">
                    <td colspan="3">Periodo de cobro</td>
                </tr>
                <tr>
                    <th></th>
                    <th>Fecha Inicio (mes)</th>
                    <th>Fecha Fin (mes)</th>
                </tr>
                <tr>
                    <td><b>Periodo</b></td>
                    <td><input type="date" class="form-control text-center" name="FechaInicio"
                            value="{{ $recibo_historial->FechaInicio }}"></td>
                    <td><input type="date" class="form-control text-center" name="FechaFin"
                            value="{{ $recibo_historial->FechaFin }}"></td>
                </tr>
                <tr>
                    <td><b>Anexo</b></td>
                    <td colspan="2"><input type="text" class="form-control" name="Anexo"
                            value="{{ $recibo_historial->Anexo }}"></td>
                </tr>
                <tr>
                    <td><b>Referencia</b></td>
                    <td colspan="2"><input type="text" class="form-control" name="Referencia"
                            value="{{ $recibo_historial->Referencia }}"></td>
                </tr>
                <tr>
                    <td><b>Factura a nombre de</b></td>
                    <td colspan="2"><input type="text" class="form-control" name="FacturaNombre"
                            value="{{ $recibo_historial->FacturaNombre }}"></td>
                </tr>
            </table>

            <table>
                <tr class="section-title">
                    <td colspan="4">Detalles del cobro generado</td>
                </tr>
            </table>

            <div class="row">
                <table class="no-border">
                    <tr>
                        <td>
                            <div class="col-6">
                                <table class="border-row">
                                    <tr>
                                        <td>Monto de Cartera</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->MontoCartera }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Prima calculada</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->PrimaCalculada }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Extra Prima</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->ExtraPrima }}" readonly></td>
                                    </tr>
                                    @if ($recibo_historial->PordentajeDescuento > 0)
                                        <tr>
                                            <td>(-) Descuento rentabilidad ({{ $recibo_historial->PordentajeDescuento }}%)
                                            </td>
                                            <td class="text-right"><input type="text" class="form-control text-right"
                                                    value="{{ $recibo_historial->Descuento }}" readonly></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>(=) Prima descontada</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->PrimaDescontada }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>(-) Estructura CCF de Comisión</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->ValorCCF }}" readonly></td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Total a pagar</td>
                                        <td class="text-right"><input type="text"
                                                class="form-control text-right font-weight-bold"
                                                value="{{ $recibo_historial->TotalAPagar }}" readonly></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td>
                            <div class="col-6">
                                <table class="border-row">
                                    <tr>
                                        <td colspan="2"><strong>Estructura del CCF de comisión</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Porcentaje de comisión</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->TasaComision }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>(=) Prima descontada</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->PrimaDescontada }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Valor de la comisión</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->Comision }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>(+) 13% IVA</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->IvaSobreComision }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Sub Total de comisión</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->SubTotalComision }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Retención 1%</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->Retencion }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Valor del CCF por Comisión</td>
                                        <td class="text-right"><input type="text" class="form-control text-right"
                                                value="{{ $recibo_historial->ValorCCF }}" readonly></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>


                </table>

            </div>

            <table class="mt-3">
                <tr>
                    <th>Cuota</th>
                    <th>Número de documento</th>
                    <th>Fecha de vencimiento</th>
                    <th>Prima A Cobrar</th>
                    <th>Total Comisión</th>
                    <th>Otros</th>
                    <th>Pago líquido de prima</th>
                </tr>
                <tr>
                    <td><input type="text" class="form-control text-center" name="Cuota"
                            value="{{ $recibo_historial->Cuota }}">
                    </td>
                    <td><input type="text" class="form-control text-center" name="NumeroCorrelativo"
                            value="{{ $recibo_historial->NumeroCorrelativo }}"></td>
                    <td><input type="date" class="form-control" name="FechaVencimiento"
                            value="{{ $recibo_historial->FechaVencimiento }}">
                    </td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->PrimaDescontada }}" readonly></td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->ValorCCF }}" readonly></td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->Otros }}" readonly></td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->TotalAPagar }}" readonly></td>
                </tr>
                <tr class="font-weight-bold">
                    <td colspan="3" class="text-center">TOTAL</td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->PrimaDescontada }}" readonly></td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->ValorCCF }}" readonly></td>
                    <td></td>
                    <td class="text-right"><input type="text" class="form-control text-right"
                            value="{{ $recibo_historial->TotalAPagar }}" readonly></td>
                </tr>
            </table>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Aceptar</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">Cancelar</a>
            </div>

        </form>
    </div>
@endsection
