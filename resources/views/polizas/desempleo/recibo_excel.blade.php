<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 10px 12px;
            text-align: left;
        }

        p {
            margin: 5px 0;
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            font-size: 12px;
        }

        /* Ajustes para los detalles del cobro */
        .table-details td,
        .table-details th {
            padding: 10px;
            text-align: right;
        }

        .table-details td {
            border: 1px solid #000;
        }

        .header {
            background-color: lightgrey;
        }

        .header td,
        .header th {
            font-weight: bold;
            text-align: center;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        /* Controlar el ancho de las columnas */
        .col-fixed {
            width: 150px;
        }

        .col-wide {
            width: 300px;
        }

        .col-narrow {
            width: 100px;
        }
    </style>
</head>

@php
$prima_calculada = $detalle->MontoCartera * $desempleo->Tasa;
@endphp

<body>
    <table width="900px" border="0">
        <tr>
            <td width="600px" colspan="4">
                <p>San Salvador, {{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('d') }} de {{ $meses[\Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('m') - 0 ] }} del {{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('Y') }}</p>
            </td>
            <td rowspan="6" colspan="3" width="300px">
                <center>
                    <img src="{{ public_path('img/logo.jpg') }}" alt="logo" width="150">
                </center>
            </td>
        </tr>
        <tr>
            <td colspan="4">Señor (a) (es): {{$recibo_historial->NombreCliente}}</td>
        </tr>
        <tr>
            <td colspan="4">NIT: {{$recibo_historial->NombreCliente}} </td>
        </tr>
        <tr>
            <td colspan="4">{{$recibo_historial->DireccionResidencia}}</td>
        </tr>
        <tr>
            <td colspan="4">{{$recibo_historial->Departamento}}, {{$recibo_historial->Municipio}}</td>
        </tr>
        <tr>
            <td colspan="4">Estimado (a)(o)(es):</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
            <td colspan="3" style="text-align: center;">
                <p>Aviso de Cobro: AC {{ str_pad($recibo_historial->NumeroRecibo, 6, "0", STR_PAD_LEFT)}} {{ date('Y') }}</p>
            </td>
        </tr>
    </table>

    <table width="900px" border="1" cellpadding="0">
        <tr>
            <td colspan="3">Compañia aseguradora</td>
            <td colspan="4" class="col-wide">Producto de seguros</td>
        </tr>
        <tr>
            <td colspan="3">{{$recibo_historial->CompaniaAseguradora}}</td>
            <td colspan="4">{{$desempleo->Plan ? $recibo_historial->ProductoSeguros : ''}}</td>
        </tr>
        <tr>
            <td colspan="3">Número de Póliza</td>
            <td class="col-narrow" colspan="2">Vigencia Inicial (anual)</td>
            <td class="col-narrow" colspan="2">Vigencia Final (anual)</td>
        </tr>
        <tr>
            <td colspan="3">{{$recibo_historial->NumeroPoliza}}</td>
            <td colspan="2">{{ \Carbon\Carbon::parse($recibo_historial->VigenciaDesde)->format('d/m/Y') }}</td>
            <td colspan="2">{{ \Carbon\Carbon::parse($recibo_historial->VigenciaHasta)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="3">Periodo de cobro</td>
            <td colspan="2" class="col-narrow" align="center">Fecha Inicio (mes)</td>
            <td colspan="2" class="col-narrow" align="center">Fecha Fin (mes)</td>
        </tr>
        <tr>
            <td align="center" colspan="2">{{ \Carbon\Carbon::parse($recibo_historial->FechaInicio)->format('d/m/Y') }}</td>
            <td align="center" colspan="2">{{ \Carbon\Carbon::parse($recibo_historial->FechaFin)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td colspan="3">Anexo</td>
            <td colspan="4">{{$recibo_historial->Anexo}}</td>
        </tr>
        <tr>
            <td colspan="3">Referencia</td>
            <td colspan="4">{{$recibo_historial->Referencia}}</td>
        </tr>
        <tr>
            <td colspan="3">Factura (s) a Nombre de</td>
            <td colspan="4">{{$desempleo->clientes->Nombre}}</td>
        </tr>
    </table>
    <table width="700" border="0">
        <tr>
            <td colspan="7" tyle="text-align: center;">
                Detalles del cobro generado
            </td>
        </tr>
        <tr>
            <td>Monto de Cartera</td>
            <td style="text-align: right;">${{number_format($recibo_historial->MontoCartera,2,'.',',')}}</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="3" style="text-align: center;">Estructura del CCF de comisión</td>
        </tr>
        <tr>
            <td>Prima calculada</td>
            <td style="text-align: right;">${{number_format($recibo_historial->PrimaCalculada,2,'.',',')}}</td>
            <td colspan="2"></td>
            <td>Porcentaje de comisión</td>
            <td colspan="2" style="text-align: right;">{{$desempleo->TasaComision == '' ? 0: $desempleo->TasaComision}}%</td>
        </tr>
        <tr>
            <td>Extra Prima</td>
            <td style="text-align: right;">${{number_format($recibo_historial->ExtraPrima,2,'.',',')}}</td>
            <td colspan="2"></td>
            <td>(=) Prima descontada</td>
            <td colspan="2" style="width: 150px; text-align: right;">${{number_format($recibo_historial->PrimaDescontada,2,'.',',')}}</td>
        </tr>
        <tr>
            <td>(-) Descuento rentabilidad (0%)</td>
            <td style="text-align: right;">${{number_format($recibo_historial->Descuento,2,'.',',')}}</td>
            <td colspan="2"></td>
            <td>Valor de la comisión </td>
            <td colspan="2" style="width: 150px; text-align: right;">${{number_format($recibo_historial->Comision,2,'.',',')}}</td>
        </tr>
        <tr>
            <td>(=) Prima descontada</td>
            <td style="text-align: right;">${{number_format($recibo_historial->PrimaDescontada,2,'.',',')}}</td>
            <td colspan="2"></td>
            <td>(+) 13% IVA</td>
            <td colspan="2" style="width: 150px; text-align: right;">${{number_format($recibo_historial->IvaSobreComision,2,'.',',')}}</td>
        </tr>
        <tr>
            <td>(-) Estructura CCF de Comisión</td>
            <td style="text-align: right;">(${{number_format($recibo_historial->ValorCCF,2,'.',',')}})</td>
            <td colspan="2"></td>
            <td>Sub Total de comision</td>
            <td colspan="2" style="width: 150px; text-align: right;">${{number_format($recibo_historial->IvaSobreComision + $recibo_historial->Comision,2,'.',',')}}</td>
        </tr>

        <tr>
            <td>Total a pagar</td>
            <td style="text-align: right;"><b>${{number_format($recibo_historial->TotalAPagar,2,'.',',')}}</b></td>
            <td colspan="2"></td>
            <td>Retencion 1% </td>
            <td colspan="2" style="width: 150px; text-align: right;">${{number_format($recibo_historial->Retencion,2,'.',',')}}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align: right;">&nbsp;</td>
            <td colspan="2"></td>
            <td>Valor del CCF por Comisión</td>
            <td colspan="2" style="width: 150px; text-align: right;">${{number_format($recibo_historial->ValorCCF,2,'.',',')}}</td>
        </tr>
    </table>

    <table width="700" border="0">
        <tr>
            <td style="width: 200px;">Cuota</td>
            <td style="width: 200px;">Número de documento</td>
            <td style="width: 200px;">Fecha de vencimiento</td>
            <td style="width: 200px;">Prima A Cobrar</td>
            <td style="width: 200px;">Total Comisión</td>
            <td style="width: 200px;">Otros</td>
            <td style="width: 200px;">Pago liquido de prima </td>
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

    <table border="0" cellspacing="0" style="width: 100%;">
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>

            <td colspan="7" style="text-align: justify;">
                {{$configuracion->Nota}}
            </td>
        </tr>
    </table>
    <table border="0" cellspacing="0" style="width: 40%;" align="right">
        <tr style="text-align: right;">
            <td colspan="5"></td>
            <td>Firma cliente </td>
            <td>_______________________</td>
        </tr>
        <tr style="text-align: right;">
            <td colspan="5"></td>
            <td>Nombre cliente </td>
            <td>_______________________</td>
        </tr>
        <tr style="text-align: right;">
            <td colspan="5"></td>
            <td>Fecha Recibido </td>
            <td>_______________________</td>
        </tr>
        <tr style="text-align: right;">
            <td colspan="5"></td>
            <td>Elaborado por:</td>
            <td>{{$detalle->usuarios->name}}</td>
        </tr>
        <tr style="text-align: right;">
            <td colspan="5"></td>
            <td>Fecha</td>
            <td>{{date('d/m/Y h:m:s A')}}</td>
        </tr>

    </table>
    <table style="width: 100%; text-align: center;">
        <tr>
            <td colspan="7" style="text-align: center;">
      {{$configuracion->Pie}}

            </td>
        </tr>
    </table>


</body>

</html>
