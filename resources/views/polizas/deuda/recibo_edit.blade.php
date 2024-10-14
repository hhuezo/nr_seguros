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
      margin: 0;
      padding: 0;
    }

    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 50px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 5px;
      text-align: left;
    }

    th {
      background-color: lightgrey;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }
    
    input {
      width: calc(100% - 10px);
      margin: 5px 0;
      padding: 5px;
      border: 1px solid #000;
    }
  </style>
</head>

<body>
  <table>
    <tr>
      <td>
        San Salvador, <input type="text" placeholder="dd" style="width: 30px;"> de <input type="text" placeholder="mes"> del <input type="text" placeholder="aaaa"> <br>
        Señor (a) (es): <br> <input type="text" placeholder="Nombre del Cliente"> <br>
        NIT: <input type="text" placeholder="NIT del Cliente"> <br>
        <input type="text" placeholder="Dirección del Cliente"> <br>
        <input type="text" placeholder="Departamento">, <input type="text" placeholder="Municipio">
        <br><br><br>
        Estimado (a)(o)(es):
      </td>
      <td style="width: 25%;">
        <img src="{{ public_path('img/logo.jpg') }}" alt="logo" width="165">
        <br>
        <p style="border: 1px solid #000; text-align: center;">Aviso de Cobro: <br>
          AC <input type="text" placeholder="Número de Recibo"> {{date('Y')}}</p>
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <td>Compañia aseguradora</td>
      <td colspan="2">Producto de seguros</td>
    </tr>
    <tr>
      <td><input type="text" placeholder="Nombre de la Aseguradora"></td>
      <td colspan="2"><input type="text" placeholder="Producto de Seguros"></td>
    </tr>
    <tr>
      <td>Número de Póliza</td>
      <td>Vigencia Inicial (anual)</td>
      <td>Vigencia Final (anual)</td>
    </tr>
    <tr>
      <td><input type="text" placeholder="Número de Póliza"></td>
      <td><input type="date"></td>
      <td><input type="date"></td>
    </tr>
  </table>
  <table>
    <tr>
      <td rowspan="2">Periodo de cobro</td>
      <td align="center">Fecha Inicio (mes)</td>
      <td align="center">Fecha Fin (mes)</td>
    </tr>
    <tr>
      <td align="center"><input type="date"></td>
      <td align="center"><input type="date"></td>
    </tr>
    <tr>
      <td>Anexo</td>
      <td colspan="2" align="center"><input type="text" placeholder="Anexo"></td>
    </tr>
    <tr>
      <td>Referencia</td>
      <td colspan="2" align="center"><input type="text" placeholder="Referencia"></td>
    </tr>
    <tr>
      <td>Factura (s) a Nombre de</td>
      <td colspan="2" align="center"><input type="text" placeholder="Nombre del Facturador"></td>
    </tr>
  </table>
  <br>
  <table>
    <tr>
      <td colspan="4" class="center">Detalles del cobro generado</td>
    </tr>
  </table>
  <table>
    <tr>
      <td style="width: 45%;">
        <table>
          <tr>
            <td>Monto de Cartera</td>
            <td class="right"><input type="text" placeholder="Monto de Cartera"></td>
          </tr>
          <tr>
            <td>Prima calculada</td>
            <td class="right"><input type="text" placeholder="Prima Calculada"></td>
          </tr>
          <tr>
            <td>Extra Prima</td>
            <td class="right"><input type="text" placeholder="Extra Prima"></td>
          </tr>
          <tr>
            <td>(-) Descuento rentabilidad (%)</td>
            <td class="right"><input type="text" placeholder="Descuento Rentabilidad (%)"></td>
          </tr>
          <tr>
            <td>(=) Prima descontada</td>
            <td class="right"><input type="text" placeholder="Prima Descontada"></td>
          </tr>
          <tr>
            <td>(-) Estructura CCF de Comisión</td>
            <td class="right"><input type="text" placeholder="Estructura CCF"></td>
          </tr>
          <tr>
            <td><b>Total a pagar</b></td>
            <td class="right"><b><input type="text" placeholder="Total a Pagar"></b></td>
          </tr>
        </table>
      </td>

      <td style="width: 10%;"></td>
      <td style="width: 45%;">
        <table>
          <tr>
            <td colspan="2">Estructura del CCF de comisión</td>
          </tr>
          <tr>
            <td>Porcentaje de comisión</td>
            <td class="right"><input type="text" placeholder="Porcentaje de Comisión"></td>
          </tr>
          <tr>
            <td>(=) Prima descontada</td>
            <td class="right"><input type="text" placeholder="Prima Descontada"></td>
          </tr>
          <tr>
            <td>Valor de la comisión</td>
            <td class="right"><input type="text" placeholder="Valor de Comisión"></td>
          </tr>
          <tr>
            <td>(+) 13% IVA</td>
            <td class="right"><input type="text" placeholder="IVA"></td>
          </tr>
          <tr>
            <td>Sub Total de comision</td>
            <td class="right"><input type="text" placeholder="Subtotal Comisión"></td>
          </tr>
          <tr>
            <td>Retencion 1%</td>
            <td class="right"><input type="text" placeholder="Retención 1%"></td>
          </tr>
          <tr>
            <td>Valor del CCF por Comisión</td>
            <td class="right"><input type="text" placeholder="Valor CCF"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table>
    <tr>
      <th>Cuota</th>
      <th>Número de documento</th>
      <th>Fecha de vencimiento</th>
      <th>Prima A Cobrar</th>
      <th>Total Comisión</th>
      <th>Otros</th>
      <th>Pago liquido de prima</th>
    </tr>
    <tr>
      <td class="center"><input type="text" placeholder="Cuota"></td>
      <td><input type="text" placeholder="Número de Documento"></td>
      <td><input type="date"></td>
      <td class="right"><input type="text" placeholder="Prima a Cobrar"></td>
      <td class="right"><input type="text" placeholder="Total Comisión"></td>
      <td class="right"><input type="text" placeholder="Otros"></td>
      <td class="right"><input type="text" placeholder="Pago Líquido de Prima"></td>
    </tr>
  </table>
  <br>
  <table>
    <tr>
      <td style="text-align: justify;">Nota: Si el pago no se efectúa en la fecha de vencimiento, se generarán recargos por mora, de acuerdo a lo establecido en el contrato de póliza.</td>
    </tr>
    <tr>
      <td>
        <div style="border-top: 1px solid #000; width: 100%;"></div>
        <br>
        Firma y Sello de la Aseguradora
      </td>
    </tr>
  </table>
  <footer>
    <p style="text-align: center;">Registro de operaciones: <br> El presente recibo debe ser llenado de manera legible y ser conservado por el beneficiario para la reclamación de seguros en caso de siniestro.</p>
  </footer>
</body>

</html>
