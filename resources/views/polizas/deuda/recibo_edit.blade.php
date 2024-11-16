@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    border: 1px solid #fff;
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
<div class="x_panel">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
      <div class="x_title">
        <h2>Editar Recibo<small></small>
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
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
      <form action="{{url('poliza/deuda/get_recibo_edit')}}" method="POST">
        @csrf
        <input type="hidden" value="{{$recibo_historial->PolizaDeudaDetalle}}" name="id_deuda_detalle">
        <table cellspadding="0" cellspacing="0" style="border: white solid !important;">
          <tr>
            <td style="border-right: white solid !important;">
              San Salvador, <input type="text" placeholder="dd" style="width: 30px;" value="{{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('d') }}" name="DiaImpresionRecibo">
              <!--  <input type="text" placeholder="mes"  value="{{ $meses[\Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('m') - 0 ] }}" name="MesImpresionRecibo"> -->
              de <select name="MesImpresionRecibo" id="" style="width: 110px;">
                @for($i = 1; $i < count($meses); $i++)
                @if($meses[\Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('m') - 0 ] == $meses[$i])
                  <option value="{{ $i }}" selected>{{ $meses[$i] }}</option>
                  @endif
                  <option value="{{$i }}">{{ $meses[$i] }}</option>
                  @endfor
              </select>

              del <input type="text" placeholder="aaaa" style="width: 50px;" value="{{ \Carbon\Carbon::parse($recibo_historial->ImpresionRecibo)->format('Y') }}" name="AxoImpresionRecibo"> <br>


              Señor (a) (es): <br> <input type="text" placeholder="Nombre del Cliente" value="{{$recibo_historial->NombreCliente}}" style="width: 877px;" name="NombreCliente"> <br>
              NIT: <input type="text" placeholder="NIT del Cliente" value="{{$recibo_historial->NitCliente}}" style="width: 200px;" name="NitCliente"> <br>
              <label> <small>Dirección de Residencia</small></label>
              <input type="text" placeholder="Dirección del Cliente" value="{{$recibo_historial->DireccionResidencia}}" style="width: 877px;" name="DireccionResidencia"> <br>
              <label> <small>Departamento, Municipio</small></label><br>
              <input type="text" placeholder="Departamento" value="{{$recibo_historial->Departamento}}" style="width: 200px;" name="Departamento">,
              <input type="text" placeholder="Municipio" value="{{$recibo_historial->Municipio}}" style="width: 667px;" name="Municipio">
              <br><br><br>
              Estimado (a)(o)(es):
              <br>
            </td>
            <td style="width: 25%; text-align: center;">
              <img src="{{ asset('img/logo.jpg') }}" alt="logo" width="165" style="vertical-align: top;">
              <br>
              <p style="border: 1px solid #000; text-align: center;">Aviso de Cobro: <br>
                <label> <small>Numero de Recibo</small></label>
                <input type="text" placeholder="Número de Recibo" value="AC {{ str_pad($recibo_historial->NumeroRecibo,6,"0",STR_PAD_LEFT)}} {{date('Y')}}" name="NumeroRecibo">
              </p>
            </td>
          </tr>
        </table>
        <br><br>
        <table cellspadding="0" cellspacing="0">
          <tr>
            <td><b>Compañia aseguradora</b></td>
            <td colspan="2"> <b>Producto de seguros</b></td>
          </tr>
          <tr>
            <td><input type="text" placeholder="Nombre de la Aseguradora" value="{{$recibo_historial->CompaniaAseguradora}}" name="CompaniaAseguradora"></td>
            <td colspan="2"><input type="text" placeholder="Producto de Seguros" value="{{$recibo_historial->ProductoSeguros}}" name="ProductoSeguros"></td>
          </tr>
          <tr>
            <td> <b>Número de Póliza</b></td>
            <td><b>Vigencia Inicial (anual)</b></td>
            <td><b>Vigencia Final (anual)</b></td>
          </tr>
          <tr>
            <td><input type="text" placeholder="Número de Póliza" value="{{$recibo_historial->NumeroPoliza}}" name="NumeroPoliza"></td>
            <td><input type="date" value="{{$recibo_historial->VigenciaDesde}}" name="VigenciaDesde"></td>
            <td><input type="date" value="{{$recibo_historial->VigenciaHasta}}" name="VigenciaHasta"></td>
          </tr>
        </table>

        <table>
          <tr>
            <td rowspan="2"><b>Periodo de cobro</b></td>
            <td align="center">Fecha Inicio (mes)</td>
            <td align="center">Fecha Fin (mes)</td>
          </tr>
          <tr>
            <td align="center"><input type="date" value="{{$recibo_historial->FechaInicio}}" name="FechaInicio"></td>
            <td align="center"><input type="date" value="{{$recibo_historial->FechaFin}}" name="FechaFin"></td>
          </tr>
          <tr>
            <td><b>Anexo</b></td>
            <td colspan="2" align="center"><input type="text" placeholder="Anexo" value="{{$recibo_historial->Anexo}}" name="Anexo"></td>
          </tr>
          <tr>
            <td><b>Referencia</b></td>
            <td colspan="2" align="center"><input type="text" placeholder="Referencia" value="{{$recibo_historial->Referencia}}" name="Referencia"></td>
          </tr>
          <tr>
            <td><b>Factura (s) a Nombre de</b></td>
            <td colspan="2" align="center"><input type="text" placeholder="Nombre del Facturador" value="{{$recibo_historial->FacturaNombre}}" name="FacturaNombre"></td>
          </tr>
        </table>
        <br>
        <table>
          <tr>
            <td colspan="4" class="center"><b>Detalles del cobro generado</b></td>
          </tr>
        </table>
        <table>
          <tr>
            <td style="width: 45%;">
              <table>
                <tr>
                  <td><b>Monto de Cartera</b></td>
                  <td class="right"><input type="text" readonly placeholder="Monto de Cartera" style="text-align: right;" value="{{$recibo_historial->MontoCartera}}" name="MontoCartera"></td>
                </tr>
                <tr>
                  <td><b>Prima calculada</b></td>
                  <td class="right"><input type="text" readonly placeholder="Prima Calculada" style="text-align: right;" value="{{$recibo_historial->PrimaCalculada}}" name="PrimaCalculada"></td>
                </tr>
                <tr>
                  <td><b>Extra Prima</b></td>
                  <td class="right"><input type="text" readonly placeholder="Extra Prima " style="text-align: right;" value="{{$recibo_historial->ExtraPrima}}" name="ExtraPrima"></td>
                </tr>
                <tr>
                  <td><b>(-) Descuento rentabilidad ({{$recibo_historial->PordentajeDescuento}}%)</b></td>
                  <td class="right"><input type="text" readonly placeholder="Descuento Rentabilidad (%)" style="text-align: right;" value="{{$recibo_historial->Descuento}}" name="Descuento"></td>
                </tr>
                <tr>
                  <td><b>(=) Prima descontada</b></td>
                  <td class="right"><input type="text" readonly placeholder="Prima Descontada" style="text-align: right;" value="{{$recibo_historial->PrimaDescontada}}" name="PrimaDescontada"></td>
                </tr>
                <tr>
                  <td><b>(-) Estructura CCF de Comisión</b></td>
                  <td class="right"><input type="text" readonly placeholder="Estructura CCF" style="text-align: right;" value="{{$recibo_historial->ValorCCF}}" name="ValorCCF"></td>
                </tr>
                <tr>
                  <td><b>Total a pagar</b></td>
                  <td class="right"><b><input type="text" readonly placeholder="Total a Pagar" style="text-align: right;" value="{{$recibo_historial->TotalAPagar}}" name="TotalAPagar"></b></td>
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
                  <td class="right"><input type="text" readonly placeholder="Porcentaje de Comisión" style="text-align: right;" value="{{$recibo_historial->TasaComision}}" name="TasaComision"></td>
                </tr>
                <tr>
                  <td>(=) Prima descontada</td>
                  <td class="right"><input type="text" readonly placeholder="Prima Descontada" style="text-align: right;" value="{{$recibo_historial->PrimaDescontada}}" name="PrimaDescontada"></td>
                </tr>
                <tr>
                  <td>Valor de la comisión</td>
                  <td class="right"><input type="text" readonly placeholder="Valor de Comisión" style="text-align: right;" value="{{$recibo_historial->Comision}}" name="Comision"></td>
                </tr>
                <tr>
                  <td>(+) 13% IVA</td>
                  <td class="right"><input type="text" readonly placeholder="IVA" style="text-align: right;" value="{{$recibo_historial->IvaSobreComision}}" name="IvaSobreComision"></td>
                </tr>
                <tr>
                  <td>Sub Total de comision</td>
                  <td class="right"><input type="text" readonly placeholder="Subtotal Comisión" style="text-align: right;" value="{{$recibo_historial->SubTotalComision}}" name="SubTotalComision"></td>
                </tr>
                <tr>
                  <td>Retencion 1%</td>
                  <td class="right"><input type="text" readonly placeholder="Retención 1%" style="text-align: right;" value="{{$recibo_historial->Retencion}}" name="Retencion"></td>
                </tr>
                <tr>
                  <td>Valor del CCF por Comisión</td>
                  <td class="right"><input type="text" readonly placeholder="Valor CCF" style="text-align: right;" value="{{$recibo_historial->ValorCCF}}" name="ValorCCF"></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br>
        <table>
          <tr>
            <td>Cuota</td>
            <td>Número de documento</td>
            <td>Fecha de vencimiento</td>
            <td>Prima A Cobrar</td>
            <td>Total Comisión</td>
            <td>Otros</td>
            <td>Pago liquido de prima</td>
          </tr>
          <tr>
            <td class="center"><input type="text" placeholder="Cuota" value="{{$recibo_historial->Cuota}}" name="Cuota"></td>
            <td><input type="text" placeholder="Número de Documento" value="{{$recibo_historial->NumeroCorrelativo}}" name="NumeroCorrelativo"></td>
            <td><input type="date" value="{{$recibo_historial->FechaVencimiento}}" name="FechaVencimiento"></td>
            <td class="right"><input type="text" readonly placeholder="Prima a Cobrar" style="text-align: right;" value="{{$recibo_historial->PrimaDescontada}}" name="PrimaDescontada"></td>
            <td class="right"><input type="text" readonly placeholder="Total Comisión" style="text-align: right;" value="{{$recibo_historial->ValorCCF}}" name="ValorCCF"></td>
            <td class="right"><input type="text" readonly placeholder="Otros" style="text-align: right;" value="{{$recibo_historial->Otros}}" name="Otros"></td>
            <td class="right"><input type="text" readonly placeholder="Pago Líquido de Prima" style="text-align: right;" value="{{$recibo_historial->TotalAPagar}}" name="TotalAPagar"></td>
          </tr>
          <tr>
            <td colspan="3" align="center">TOTAL </td>
            <td class="right"><input type="text" readonly placeholder="Total Prima a Cobrar" style="text-align: right;" value="{{$recibo_historial->PrimaDescontada}}" name="PrimaDescontada"></td>
            <td class="right"><input type="text" readonly placeholder="Total de Comisión" style="text-align: right;" value="{{$recibo_historial->ValorCCF}}" name="ValorCCF"></td>
            <td class="right"></td>
            <td class="right"><input type="text" readonly placeholder="Total liquido de prima" style="text-align: right;" value="{{$recibo_historial->TotalAPagar}}" name="TotalAPagar"></td>
          </tr>
        </table>
        <br>
        <div align="center">
          <button type="submit" class="btn btn-primary"> Aceptar</button>
          <button type="button" class="btn btn-default"> Cancelar</button>
        </div>
        <!-- <table cellspadding="0" cellspacing="0" style="border: white solid !important;">
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
        <table>
          <tr>
            <td style="text-align: center;">Registro de operaciones: <br> El presente recibo debe ser llenado de manera legible y ser conservado por el beneficiario para la reclamación de seguros en caso de siniestro.</td>
          </tr>
        </table> -->
      </form>
    </div>
  </div>
</div>

@endsection