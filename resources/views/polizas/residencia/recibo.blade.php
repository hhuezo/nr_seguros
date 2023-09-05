<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recibo</title>
</head>

<body>
  <p style="text-align: center; width: 450px;">{{$residencia->aseguradoras->Nombre}}</p>
  <br><br><br>
  <p style="text-align: left; width: 450px;">{{ \Carbon\Carbon::parse($detalle->FechaInicio)->format('d/m/Y') }} Fecha Inicio: {{ \Carbon\Carbon::parse($detalle->Final)->format('d/m/Y') }} <br>
    {{$residencia->clientes->Nombre}} <br>
    {{$detalle->ComentarioCobro}} <br>
    <br><br>

    Poliza: {{$residencia->NumeroPoliza}} &nbsp; &nbsp; &nbsp; Fecha Vencimiento: {{ \Carbon\Carbon::parse($residencia->VigenciaHasta)->format('d/m/Y') }}<br>
  </p>
  <p style="text-align: right; width: 450px;">
    Valor: {{$detalle->APagar}}
  </p>
  <p style="text-align: left; width: 450px;"> Vigencia de: {{ \Carbon\Carbon::parse($residencia->VigenciaDesde)->format('d/m/Y') }}  a {{ \Carbon\Carbon::parse($residencia->VigenciaHasta)->format('d/m/Y') }}
    <br>
  </p>
  <p style="text-align: right; width: 450px;"> Valor: {{$detalle->APagar}}
    <br>
  </p>

</body>

</html>