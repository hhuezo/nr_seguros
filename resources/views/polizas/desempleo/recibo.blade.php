<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

    </style>
</head>
@php
$prima_calculada = $detalle->MontoCartera * $desempleo->Tasa;
@endphp

<body style="margin-top: -10%;">
    <table style="width: 100%;">
        <tr>
            <td>
               San Salvador, {{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('d') }} de {{ $meses[\Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('m') - 0 ] }} del {{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('Y') }} <br>
                Señor (a) (es): <br> {{$recibo_historial->NombreCliente}} <br>
                NIT: {{$recibo_historial->Nit}} <br>
                {{$recibo_historial->DireccionResidencia}} <br>
                {{$recibo_historial->Departamento}}, {{$recibo_historial->Municipio}}
                <br>
                <br><br>
                Estimado (a)(o)(es):
            </td>
            <td style="width: 25%;">
                <img src="{{ public_path('img/logo.jpg') }}" alt="logo" width="165">
                <br>
                <p style="border: 1 solid #000; text-align: center;">Aviso de Cobro: <br>
                    AC {{ str_pad($recibo_historial->NumeroRecibo,6,"0",STR_PAD_LEFT)}} {{date('Y')}}</p> <!--  falta agregar el numero del aviso de cobro -->
            </td>
        </tr>
    </table>
    <table border="1" cellspacing="0" style="width: 100%;">
        <tr style="background-color: lightgrey;">
            <td>Compañia aseguradora</td>
            <td colspan="2">Producto de seguros</td>
        </tr>
        <tr>
            <td>{{$recibo_historial->CompaniaAseguradora}}</td>
            <td colspan="2">@if($desempleo->Plan) {{$recibo_historial->ProductoSeguros}}@endif</td>
        </tr>
        <tr style="background-color: lightgrey;">
            <td>Número de Póliza</td>
            <td>Vigencia Inicial (anual)</td>
            <td>Vigencia Final (anual)</td>
        </tr>
        <tr>
            <td>{{$recibo_historial->NumeroPoliza}}</td>
            <td>{{ \Carbon\Carbon::parse($recibo_historial->VigenciaDesde)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($recibo_historial->VigenciaHasta)->format('d/m/Y') }}</td>
        </tr>
    </table>
    <table border="1" cellspacing="0" style="width: 100%;">
        <tr>
            <td rowspan="2" style="background-color: lightgrey;">Periodo de cobro</td>
            <td align="center">Fecha Inicio (mes)</td>
            <td align="center">Fecha Fin (mes)</td>
        </tr>
        <tr>
            <td align="center">{{ \Carbon\Carbon::parse($recibo_historial->FechaInicio)->format('d/m/Y') }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($recibo_historial->FechaFin)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td style="background-color: lightgrey;">Anexo</td>
            <td colspan="2" align="center">{{$recibo_historial->Anexo}}</td>
        </tr>
        <tr>
            <td style="background-color: lightgrey;">Referencia</td>
            <td colspan="2" align="center">{{$recibo_historial->Referencia}}</td>
        </tr>
        <tr>
            <td style="background-color: lightgrey;">Factura (s) a Nombre de</td>
            <td colspan="2" align="center">{{$desempleo->cliente->Nombre}} </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;" border="1" cellspacing="0">
        <tr>
            <td colspan="4" style="text-align: center;background-color: lightgrey;">
                Detalles del cobro generado
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 45%;">
                <table border="1" cellspacing="0">
                    <tr>
                        <td style="width: 65%;">Monto de Cartera</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->MontoCartera,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td style="width: 65%;">Prima calculada</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->PrimaCalculada,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td style="width: 65%;">Extra Prima</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->ExtraPrima,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>(-) Descuento rentabilidad ({{$recibo_historial->PordentajeDescuento == '' ? 0 : $recibo_historial->PordentajeDescuento}}%)</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->Descuento,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>(=) Prima descontada</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->PrimaDescontada,2,'.',',')}}</td>
                    </tr>
                    <!-- <tr>
            <td>Sub Total</td>
            <td style="width: 35%; text-align: right;"> ${{number_format($recibo_historial->SubTotal,2,'.',',')}}</td>
          </tr>
          <tr>
            <td>13% IVA</td>
            <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->Iva,2,'.',',')}}</td>
          </tr>
          <tr>
            <td>Total Factura</td>
            <td style="width: 35%; text-align: right;">${{number_format(($recibo_historial->SubTotal+$recibo_historial->Iva),2,'.',',')}}</td>
          </tr>  -->
                    <tr>
                        <td>(-) Estructura CCF de Comisión</td>
                        <td style="width: 35%; text-align: right;">(${{number_format($recibo_historial->ValorCCF,2,'.',',')}})</td>
                    </tr>
                    <tr>
                        <td><b>Total a pagar</b></td>
                        <td style="width: 35%; text-align: right;"><b>${{number_format($recibo_historial->TotalAPagar,2,'.',',')}}</b></td>
                    </tr>
                </table>
            </td>

            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <table border="1" cellspacing="0" align="rigth">
                    <tr>
                        <td colspan="2">Estructura del CCF de comisión</td>
                    </tr>
                    <tr>
                        <td>Porcentaje de comisión </td>
                        <td style="width: 35%; text-align: right;">{{$desempleo->TasaComision == '' ? 0: $desempleo->TasaComision}}%</td>
                    </tr>
                    <tr>
                        <td>(=) Prima descontada</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->PrimaDescontada,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>Valor de la comisión</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->Comision,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>(+) 13% IVA</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->IvaSobreComision,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>Sub Total de comision</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->IvaSobreComision + $recibo_historial->Comision,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>Retencion 1%</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->Retencion,2,'.',',')}}</td>
                    </tr>
                    <tr>
                        <td>Valor del CCF por Comisión</td>
                        <td style="width: 35%; text-align: right;">${{number_format($recibo_historial->ValorCCF,2,'.',',')}}</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <br><br>
    <table border="1" cellspacing="0" style="width: 100%;">
        <tr style="background-color: lightgrey;">
            <th style="width: 14.28%;">Cuota</th>
            <th style="width: 14.28%;">Número de <br> documento</th>
            <th style="width: 14.28%;">Fecha de vencimiento</th>
            <th style="width: 14.28%;">Prima A Cobrar</th>
            <th style="width: 14.28%;">Total Comisión</th>
            <th style="width: 14.28%;">Otros</th>
            <th style="width: 14.28%;">Pago liquido de prima</th>
        </tr>

        <tr>
            <td style="text-align: center;">01/01</td>
            <td style="text-align: center;">{{$recibo_historial->NumeroCorrelativo}}</td>
            <td style="text-align: center;">{{ \Carbon\Carbon::parse($recibo_historial->FechaInicio)->format('d/m/Y') }}</td>
            <td style="text-align: right;">${{number_format($recibo_historial->PrimaDescontada,2,'.',',')}}</td>
            <td style="text-align: right;">${{number_format(($recibo_historial->ValorCCF ),2,'.',',')}}</td>
            <td style="text-align: right;">${{number_format($recibo_historial->Otros,2,'.',',')}}</td>
            <td style="text-align: right;">${{number_format($recibo_historial->TotalAPagar,2,'.',',')}}</td>
        </tr>
        <tr>
            <td colspan="3" align="center">TOTAL </td>
            <td style="text-align: right;">${{number_format($recibo_historial->PrimaDescontada,2,'.',',')}}</td>
            <td style="text-align: right;">${{number_format($recibo_historial->ValorCCF,2,'.',',')}}</td>
            <td></td>
            <td style="text-align: right;">${{number_format(($recibo_historial->TotalAPagar),2,'.',',')}}</td>
        </tr>
    </table>
    <br>

    <table border="0" cellspacing="0" style="width: 100%;">

        <tr>

            <td>
                <p style="text-align: justify;">Es importante que posee 30 días adiciones después de la fecha de
                    vencimiento para el pago de sus primas, caso contrario la compañía de seguros no se hará responsable
                    por la cobertura del bien asegurado en caso de un reclamo.
                    <br>
                    Además hacemos de su conocimiento que en caso que usted no pueda presentarse a la compañía de seguros a realizar los pagos de
                    las cuotas de su póliza puede hacerlo a través de nuestra empresa, comunicándose a nuestras oficinas a los teléfonos 2521-3700 o 7601-2895
                    para programar el día y la hora en la cual nuestra área de mensajería se hará presente al lugar convenido a retirar los cheques o
                    dinero en efectivo por el pago de sus seguros enviándole posteriormente, la factura o comprobante de crédito fiscal emitido y
                    cancelado por la compañía aseguradora.

                    <br>
                    Esperando lo anterior sea de satisfacción, nos ponemos a sus apreciables órdenes por cualquier consulta adicional al respecto.


                </p>
            </td>
        </tr>
    </table>
    <br><br>

    <table border="0" cellspacing="0" style="width: 40%;" align="right">
        <tr style="text-align: right;">
            <td>Firma cliente </td>
            <td>_______________________</td>
        </tr>
        <tr style="text-align: right;">
            <td>Nombre cliente </td>
            <td>_______________________</td>
        </tr>
        <tr style="text-align: right;">
            <td>Fecha Recibido </td>
            <td>_______________________</td>
        </tr>
        <tr style="text-align: right;">
            <td>Elaborado por:</td>
            <td>{{$detalle->usuarios->name}}</td>
        </tr>
        <tr style="text-align: right;">
            <td>Fecha</td>
            <td>{{date('d/m/Y h:m:s A')}}</td>
        </tr>

    </table>
    <br><br>

    <br><br>
    <footer>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td>
                    NR Seguros, S.A. de C.V
                    <br>
                    Colonia San Ernesto, pasaje San Carlos #154, Bulevar de los Heroes, San Salvador Centro. <br>
                    Oficina escalón: 11 Calle poniente entre 79 y 81 avenida norte #3 Colonia Escalón.

                </td>
            </tr>
        </table>
    </footer>



</body>

</html>
