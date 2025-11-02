<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


        <div class="x_title">
            <h4>&nbsp;&nbsp; Avisos de Cobro<small></small>
            </h4>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table width="100%" class="table table-striped" id="avisos">
                <thead>
                    <tr>
                        <th>N° Aviso</th>
                        <th>N° Correlativo</th>
                        <th>Fecha Impresión Aviso</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Final</th>
                        <th>Estados</th>
                        <th><i class="fa fa-filef"></i>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalle as $obj)
                    @if ($obj->ImpresionRecibo != null)
                    <tr>
                        <td>AC {{str_pad($obj->NumeroRecibo, 6, "0", STR_PAD_LEFT);}} {{date('y')}}</td>
                        <td>{{$obj->NumeroCorrelativo ? $obj->NumeroCorrelativo : ''}} </td>
                        <td>{{ \Carbon\Carbon::parse($obj->ImpresionRecibo)->format('d/m/Y') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }}
                        </td>
                        <td> {{ \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') }}
                        </td>
                        @if($obj->Activo == 0)
                        <td>Anulado</td>
                        @elseif($obj->ImpresionRecibo)
                        <td>Emitido</td>
                        @elseif($obj->PagoAplicado)
                        <td>Pagado</td>
                        @else
                        <td></td>
                        @endif
                        <td>
                            @if($obj->Activo <> 0)
                                <a href="{{ url('poliza/deuda/get_recibo') }}/{{ $obj->Id }}/1"  class="btn btn-info"><span class="fa fa-print"></span></a>
                                 &nbsp;
                                <a href="{{ url('poliza/deuda/get_recibo') }}/{{ $obj->Id }}/2" target="_blank" class="btn btn-success"><span class="fa fa-file-excel-o fa-lg"></span></a>
                                &nbsp;
                                <a href="{{ url('poliza/deuda/get_recibo_edit') }}/{{ $obj->Id }}" target="_blank" class="btn btn-warning"><span class="fa fa-pencil"></span></a>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
