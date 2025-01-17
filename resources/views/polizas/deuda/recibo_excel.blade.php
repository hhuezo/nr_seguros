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
            /* Aumentado para mayor legibilidad */
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

    </style>
</head>

@php
$prima_calculada = $detalle->MontoCartera * $deuda->Tasa;
@endphp

<body>
<table width="900px" border="0">
  <tr>
    <td width="600px"> <p>San Salvador, {{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('d') }} de {{ $meses[\Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('m') - 0 ] }} del {{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('Y') }}</p>               
	</td>
    <td rowspan="6" colspan="2" width="300px" ><center> 
      <img src="{{ public_path('img/logo.jpg') }}" alt="logo" width="80">
    </center></td>
  </tr>
  <tr>
    <td>Señor (a) (es): {{$recibo_historial->NombreCliente}}</td>
  </tr>
  <tr>
    <td>NIT: {{$recibo_historial->NombreCliente}} </td>
  </tr>
  <tr>
    <td>{{$recibo_historial->DireccionResidencia}}</td>
  </tr>
  <tr>
    <td>{{$recibo_historial->Departamento}}, {{$recibo_historial->Municipio}}</td>
  </tr>
  <tr>
    <td>Estimado (a)(o)(es):</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><p>Aviso de Cobro: AC {{ str_pad($recibo_historial->NumeroRecibo, 6, "0", STR_PAD_LEFT)}} {{ date('Y') }}</p></td>
  </tr>
</table>

<table width="900px" border="1" cellpadding="0">
  <tr>
    <td>Compañia aseguradora</td>
    <td colspan="2" width="400px">Producto de seguros</td>
  </tr>
  <tr>
    <td>{{$recibo_historial->CompaniaAseguradora}}</td>
    <td colspan="2">{{$deuda->Plan ? $recibo_historial->ProductoSeguros : ''}}</td>
  </tr>
  <tr>
    <td>Número de Póliza</td>
    <td style="width: 200px;">Vigencia Inicial (anual)</td>
    <td style="width: 200px;">Vigencia Final (anual)</td>
  </tr>
  <tr>
    <td>{{$recibo_historial->NumeroPoliza}}</td>
    <td>{{ \Carbon\Carbon::parse($recibo_historial->VigenciaDesde)->format('d/m/Y') }}</td>
    <td>{{ \Carbon\Carbon::parse($recibo_historial->VigenciaHasta)->format('d/m/Y') }}</td>
  </tr>
  <tr>
    <td rowspan="2">Periodo de cobro</td>
    <td align="center" style="width: 150px;">Fecha Inicio (mes)</td>
    <td align="center" style="width: 150px;">Fecha Fin (mes)</td>
  </tr>
  <tr>
    <td align="center">{{ \Carbon\Carbon::parse($recibo_historial->FechaInicio)->format('d/m/Y') }}</td>
    <td align="center">{{ \Carbon\Carbon::parse($recibo_historial->FechaFin)->format('d/m/Y') }}</td>
  </tr>
  <tr>
    <td>Anexo</td>
    <td colspan="2">{{$recibo_historial->Anexo}}</td>
  </tr>
   <tr>
     <td>Referencia</td>
     <td colspan="2">{{$recibo_historial->Referencia}}</td>
  </tr>
   <tr>
     <td>Factura (s) a Nombre de </td>
     <td colspan="2">{{$deuda->clientes->Nombre}}</td>
  </tr>
</table>


</body>

</html>
