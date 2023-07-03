<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recibo</title>
</head>

<body>
  <p style="text-align: center; width: 450px;">{{$deuda->aseguradoras->Nombre}}</p>
  <br><br><br>
  <p style="text-align: left; width: 450px;">{{$detalle->APagar}} Fecha Inicio: {{$detalle->APagar}} <br>
    {{$deuda->clientes->Nombre}} <br>
    {{$detalle->ComentarioCobro}} <br>
    <br><br>

    Poliza: {{$deuda->NumeroPoliza}} &nbsp; &nbsp; &nbsp; Fecha Vencimiento: {{$deuda->VigenciaHasta}} <br>
  </p>
  <p style="text-align: right; width: 450px;">
    Valor: {{$detalle->APagar}}
  </p>
  <p style="text-align: left; width: 450px;"> Vigencia de: {{$deuda->VigenciaDesde}} a {{$deuda->VigenciaHasta}}
    <br>
  </p>
  <p style="text-align: right; width: 450px;"> Valor: {{$detalle->APagar}}
    <br>
  </p>

</body>

</html>