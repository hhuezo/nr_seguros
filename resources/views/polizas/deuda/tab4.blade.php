<div class="x_title">
    <h2>Estado de Pagos<small></small>
    </h2>
    <div class="clearfix"></div>
</div>

<div>
    <br>
    <table id="tblCobros" width="100%" class="table table-striped">
        <thead>
            <tr>
                <th style="text-align: center;">Póliza</th>
                <th style="text-align: center;">Fecha Inicio <br> Vigencia</th>
                <th style="text-align: center;">Fecha Final <br> Vigencia</th>
                <th style="text-align: center;">Cuota</th>
                <th style="text-align: center;">Correlativo</th>
                <th style="text-align: center;">Fecha de <br> Vencimiento</th>
                <th style="text-align: center;">Fecha de <br> Aplicación de pago</th>
                <th style="text-align: center;">Valor (US$)</th>
                <th style="text-align: center;">Estatus</th>
                <th style="text-align: center;">Opciones</th>
            </tr>
        </thead>
        @php
        $total = 0;
        @endphp
        <tbody>
            @foreach ($detalle as $obj)
            <tr>
                @php
                $fileUrl = asset($obj->ExcelURL);
                @endphp
                <td style="text-align: center;">{{ $deuda->NumeroPoliza }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') }}</td>
                <td style="text-align: center;">01/01</td>
                @if($obj->NumeroCorrelativo)
                <td style="text-align: center;">{{$obj->NumeroCorrelativo}}</td>
                @else
                <td></td>
                @endif
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }} </td>
                @if($obj->PagoAplicado)
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($obj->PagoAplicado)->format('d/m/Y') }}</td>
                @else
                <td></td>
                @endif
                @if($obj->Activo == 0)
                <td style="text-align: right;">$0.00</td>
                @else
                <td style="text-align: right;">$ {{ number_format($obj->APagar, 2, '.', ',') }}
                    @php
                    $total += $obj->APagar;
                    @endphp
                </td>
                @endif
                @if($obj->Activo == 0)
                <td style="text-align: center;">Anulado</td>
                @elseif(!$obj->PagoAplicado)
                <td style="text-align: center;">Pendiente</td>
                @elseif($obj->PagoAplicado)
                <td style="text-align: center;">Pagado</td>
                @else
                <td style="text-align: center;"></td>
                @endif
                <td style="text-align: center;">
                    @if($obj->Activo == 0)

                    @elseif(!$obj->ImpresionRecibo)
                    <a href="" target="_blank" data-target="#modal-recibo-{{ $obj->Id }}" title="Generar Recibo" data-toggle="modal"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                    @else
                    <i class="fa fa-pencil fa-lg" onclick="modal_edit({{ $obj->Id }})" title="Actualizar Fechas de Cobro"></i>
                    @endif
                    &nbsp;&nbsp;
                    <a href="{{ $fileUrl }}" class="fa fa-file-excel-o" align="center" title="Descargar Cartera"></a>&nbsp;&nbsp;
                    <i data-target="#modal-view-{{ $obj->Id }}" data-toggle="modal" class="fa fa-eye" align="center" title="Ver Detalles"></i>&nbsp;&nbsp;
                    @if($obj->Activo == 1)
                    <a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" title="Anular Cartera"><i class="fa fa-trash fa-lg"></i></a> &nbsp;&nbsp;
                    @endif



                </td>

            </tr>
            @include('polizas.deuda.modal_edit')
            @endforeach
        </tbody>
        <tfoot>
            <td colspan="3" style="text-align: right;"><b>Total de Poliza:</b> </td>
            <td colspan="5" style="text-align: right;"><b>${{number_format($total, 2, '.', ',')}}</b> </td>
            <td colspan="2"></td>
        </tfoot>
    </table>

</div>