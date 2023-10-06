<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recibo</title>
</head>
@php
$prima_calculada = $detalle->MontoCartera * $residencia->Tasa;
@endphp

<body>
  <table style="width: 100%;">
    <tr>
      <td>
        San Salvador, {{ \Carbon\Carbon::parse($detalle->ImpresionRecibo)->format('d') }} de {{ $meses[\Carbon\Carbon::parse($detalle->ImpresionRecibo)->format('m') - 0 ] }} del {{ \Carbon\Carbon::parse($detalle->ImpresionRecibo)->format('Y') }} <br>
        Señor (a) (es): <br> {{$residencia->clientes->Nombre}} <br>
        NIT: {{$residencia->clientes->Nit}} <br>
        {{$residencia->clientes->DireccionResidencia}} <br>
        {{$residencia->clientes->distrito->municipio->departamento->Nombre}}, {{$residencia->clientes->distrito->municipio->Nombre}}
        <br>
        <br><br>
        Estimado (a)(o)(es):
      </td>
      <td style="width: 25%;">
        <img src="" alt="logo" width="65">
        <br>
        <p style="border: 1 solid #000; text-align: center;">Aviso de Cobro: <br>
          AC 000001 2023</p> <!--  falta agregar el numero del aviso de cobro -->
      </td>
    </tr>
  </table>
  <table border="1" cellspacing="0" style="width: 100%;">
    <tr>
      <td colspan="2">Compañia aseguradora</td>
      <td colspan="2">Producto de seguros</td>
    </tr>
    <tr>
      <td colspan="2">{{$residencia->aseguradoras->Nombre}}</td>
      <td colspan="2">Productos</td>
    </tr>
    <tr>
      <td>Numero de Poliza</td>
      <td>Vigencia Inicial</td>
      <td>Vigencia Final</td>
      <td>Anexo</td>
    </tr>
    <tr>
      <td>{{$residencia->NumeroPoliza}}</td>
      <td>{{ \Carbon\Carbon::parse($residencia->VigenciaDesde)->format('d/m/Y') }}</td>
      <td>{{ \Carbon\Carbon::parse($residencia->VigenciaHasta)->format('d/m/Y') }}</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">Referencia</td>
      <td colspan="2">Agregar referencia</td>
    </tr>
    <tr>
      <td colspan="2">Factura (s) a Nombre de</td>
      <td colspan="2">{{$residencia->clientes->Nombre}} </td>
    </tr>
  </table>
  <table style="width: 100%;">
    <tr>
      <td colspan="4" style="text-align: center;">
        <br> Detalles del cobro generado
      </td>
    </tr>
  </table>
  <table style="width: 100%;">
    <tr>
      <td style="width: 45%;">
        <table border="1" cellspacing="0">
          <tr>
            <td style="width: 65%;">Prima Calculada</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[0],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>(-) Descuento rentabilidad ({{$residencia->Tasa}}%)</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[1],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>(=) Prima descontada</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[2],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>(+) impuesto bomberos</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[3],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>Sub Total</td>
            <td style="width: 35%; text-align: right;"> ${{number_format($calculo[4],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>13% IVA S/Sub Total</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[5],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>(-) Estructura CCF de Comisión</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[8],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>Prima neta por pagar</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[9],2,'.',',')}}</td>
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
            <td style="width: 35%; text-align: right;">{{$residencia->Comision}} %</td>
          </tr>
          <tr>
            <td>Valor de la comisión</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[6],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>(+) 13% IVA</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[7],2,'.',',')}}</td>
          </tr>
          <tr>
            <td>Valor del CCF por Comisión</td>
            <td style="width: 35%; text-align: right;">${{number_format($calculo[8],2,'.',',')}}</td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
  <br><br>
  <table border="1" cellspacing="0" style="width: 100%;">
    <tr>
      <th>Cuota</th>
      <th>Número Correlativo</th>
      <th>Fecha Inicio</th>
      <th>Fecha Final</th>
      <th>Prima Neta (Sin Impuestos)</th>
      <th>IVA</th>
      <th>Gastos Emisión</th>
      <th>Gastos Fracciona.</th>
      <th>Total a facturar</th>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>{{ \Carbon\Carbon::parse($residencia->FechaInicio)->format('d/m/Y') }}</td>
      <td>{{ \Carbon\Carbon::parse($residencia->FechaFinal)->format('d/m/Y') }}</td>
      <td style="text-align: right;">${{number_format($calculo[4],2,'.',',')}}</td>
      <td style="text-align: right;">${{number_format($calculo[5],2,'.',',')}}</td>
      <td style="text-align: right;">${{number_format($detalle->GastosEmision,2,'.',',')}}</td>
      <td style="text-align: right;">${{number_format($detalle->Otros,2,'.',',')}}</td>
      <td style="text-align: right;">${{number_format(($calculo[4] + $calculo[5] + $detalle->GastosEmision + $detalle->Otros),2,'.',',')}}</td>
    </tr>
  </table>
  <br><br>

  <table border="0" cellspacing="0" style="width: 100%;">

    <tr>

      <td>
        <p style="text-align: justify;">Es importante que posee 30 dias adiciones despues de la fecha de vencimiento para el pago de sus primas,
          caso contrario la compañía de seguros no se hará responsable por la cobertura del bien asegurado en caso de un reclamo 
          <br>
          Ademas hacemos de su conocimiento que en caso que usted no pueda presentarse a la compañía de seguros a realizar 
          los pagos de las cuotas de su póliza puede hacerlo a travez de nuestra empresa, comunicandose a  nuestras oficinas a 
          los telefonos 2521-3700 ó 7601-2895 para programar el dia y la hora en la cual nuestra área de mensajería se hará presente 
          al lugar convenido a retirar los cheques o dinero en efectivo por el pago de sus seguros eniandole posteriormente, la 
          factura o comprobante de crédito fiscal emitido y cancelado por la compañia aseguradora.										

          <br>
          Esperando lo anterior sea de satisfacción, nos ponemos a sus apreciables órdenes por cualqueir consulta adicional al respecto.														
										

        </p>
      </td>
    </tr>
  </table>
  <br><br>

  <table border="0" cellspacing="0" style="width: 40%;" align="right">
    <tr>
      <td style="text-align: right;">Firma cliente	</td>
      <td>_______________________</td>
    </tr>
    <tr style="text-align: right;">
      <td>Nombre cliente	</td><td>_______________________</td>
    </tr>
    <tr style="text-align: right;">
      <td>Fecha Recibido	</td><td>_______________________</td>
    </tr>
    <tr style="text-align: right;">
      <td>Elaborado por:</td><td>_______________________</td>
    </tr>
    <tr style="text-align: right;">
      <td>Fecha</td><td>_______________________</td>
    </tr>

  </table>
  <br><br>
 
  <br><br>
  <table style="width: 100%;">
    <tr>
      <td>
        NR Seguros, S.A. de C.V
        <br>
        Colonia San Ernesto, pasaje San Carlos #154, sobre el bulevar de los Herores, San Salvador.

      </td>
    </tr>
  </table>


</body>

</html>