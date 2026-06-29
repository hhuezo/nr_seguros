<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @php
            $avisosActivos = $detalle->filter(function ($obj) {
                return $obj->ImpresionRecibo != null && (int) ($obj->Activo ?? 0) !== 0;
            });
        @endphp


        <div class="x_title">
            <div class="row">
                <div class="col-sm-6">
                    <h4>&nbsp;&nbsp; Avisos de Cobro<small></small></h4>
                </div>
                <div class="col-sm-6 text-right">
                    @can('vida aviso print')
                        <button type="button" class="btn btn-success"
                            onclick="mostrarAvisosCobroPoliza('{{ url('poliza/vida/get_recibos_poliza/' . $poliza_vida->Id) }}')"
                            {{ $avisosActivos->count() === 0 ? 'disabled' : '' }}>
                            <i class="fa fa-file-pdf-o"></i> Descargar avisos cobro
                        </button>
                    @endcan
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table width="100%" class="table table-striped" id="avisos">
                <thead>
                    <tr>
                        <th>N° Aviso</th>
                        <th>N° Correlativo</th>
                        <th>Mes/Año</th>
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
                                <td>AC {{ str_pad($obj->NumeroRecibo, 6, '0', STR_PAD_LEFT) }} {{ \Carbon\Carbon::parse($obj->FechaIngreso)->format('Y') }}</td>
                                <td>{{ $obj->NumeroCorrelativo ? $obj->NumeroCorrelativo : '' }} </td>
                                <td>{{ $obj->Mes }}/{{ $obj->Axo }}</td>
                                <td>{{ \Carbon\Carbon::parse($obj->ImpresionRecibo)->format('d/m/Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }}
                                </td>
                                <td> {{ \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') }}
                                </td>
                                @if ($obj->Activo == 0)
                                    <td>Anulado</td>
                                @elseif($obj->ImpresionRecibo)
                                    <td>Emitido</td>
                                @elseif($obj->PagoAplicado)
                                    <td>Pagado</td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    @if ($obj->Activo != 0)
                                        @can('vida aviso print')
                                        <a href="{{ url('poliza/vida/get_recibo') }}/{{ $obj->Id }}/1"
                                            target="_blank" class="btn btn-info"><span class="fa fa-print"></span></a>
                                        &nbsp;
                                        @endcan
                                     
                                        @can('vida aviso edit')
                                        &nbsp;
                                        <a href="{{ url('poliza/vida/get_recibo_edit') }}/{{ $obj->Id }}"
                                            target="_blank" class="btn btn-warning"><span
                                                class="fa fa-pencil"></span></a>
                                        @endcan
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

<div class="modal fade" id="modal-avisos-cobro-poliza" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Avisos de cobro de la póliza</h4>
            </div>
            <div class="modal-body" style="padding: 0;">
                <iframe id="iframe-avisos-cobro-poliza" src="" style="width: 100%; height: 80vh; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function mostrarAvisosCobroPoliza(url) {
        $('#iframe-avisos-cobro-poliza').attr('src', url);
        $('#modal-avisos-cobro-poliza').modal('show');
    }

    $('#modal-avisos-cobro-poliza').on('hidden.bs.modal', function() {
        $('#iframe-avisos-cobro-poliza').attr('src', '');
    });
</script>
