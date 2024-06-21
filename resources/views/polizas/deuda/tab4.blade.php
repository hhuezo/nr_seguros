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
                <th style="display: none;">Id</th>
                <th style="text-align: center;">Póliza</th>
                <th style="text-align: center;">Fecha Inicio <br> Vigencia</th>
                <th style="text-align: center;">Fecha Final <br> Vigencia</th>
                <th style="text-align: center;">Fecha de Creación</th>
                <th style="text-align: center;">Nro de Aviso Cobro</th>
                <th style="text-align: center;">Cuota</th>
                <th style="text-align: center;">Nro de Documento</th>
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
                    <td style="display: none;">{{$obj->Id}}</td>
                    <td style="text-align: center;">{{ $deuda->NumeroPoliza }}</td>
                    <td style="text-align: center;">{{ $obj->FechaInicio ? \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') : ''}}</td>
                    <td style="text-align: center;">{{ $obj->FechaFinal ? \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') : ''}}</td>
                    <td style="text-align: center;">{{ $obj->ImpresionRecibo ? \Carbon\Carbon::parse($obj->ImpresionRecibo)->format('d/m/Y') : ''}}</td>
                    <td style="text-align: center;"> AC {{ str_pad($obj->NumeroRecibo,6,"0",STR_PAD_LEFT)}} {{date('Y')}}</td>
                    <td style="text-align: center;">01/01</td>
                    @if ($obj->NumeroCorrelativo)
                        <td style="text-align: center;">{{$obj->NumeroCorrelativo }}</td>
                    @else
                        <td></td>
                    @endif
                    <td style="text-align: center;">{{ $obj->FechaInicio ? \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') : ''}}
                    </td>
                    @if ($obj->PagoAplicado)
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($obj->PagoAplicado)->format('d/m/Y') }}
                        </td>
                    @else
                        <td></td>
                    @endif
                    @if ($obj->Activo == 0)
                        <td style="text-align: right;">$0.00</td>
                    @else
                        <td style="text-align: right;">$ {{ number_format($obj->APagar, 2, '.', ',') }}
                            @php
                                $total += $obj->APagar;
                            @endphp
                        </td>
                    @endif
                    @if ($obj->Activo == 0)
                        <td style="text-align: center;">Anulado</td>
                    @elseif(!$obj->PagoAplicado)
                        <td style="text-align: center;">Pendiente</td>
                    @elseif($obj->PagoAplicado)
                        <td style="text-align: center;">Pagado</td>
                    @else
                        <td style="text-align: center;"></td>
                    @endif
                   

                    <td style="text-align: center;">
                        @if ($obj->Activo == 0)
                        @elseif (!$obj->ImpresionRecibo)
                            <a href="" target="_blank" data-target="#modal-recibo-{{ $obj->Id }}" title="Generar Aviso de cobro" data-toggle="modal">
                                <button class="btn btn-primary"><i class="fa fa-file-text-o" aria-hidden="true"></i></button>
                            </a>
                        @elseif(!$obj->PagoAplicado)
                            <button class="btn btn-primary" onclick="modal_edit({{ $obj->Id }})">
                                <i class="fa fa-pencil fa-lg" title="Actualizar Fechas de Cobro"></i>
                            </button>
                        @endif
                    
                        <button class="btn btn-warning" data-target="#modal-view-{{ $obj->Id }}" data-toggle="modal">
                            <i class="fa fa-eye" align="center" title="Ver Actividad de Aviso de cobro"></i>
                        </button>
                    
                        @if ($obj->Activo == 1)
                            <a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" title="Eliminar Aviso de Cobro">
                                <button class="btn btn-danger">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>
                            </a>
                        @endif
                    
                        <form action="{{ url('exportar_excel') }}" method="POST" style="display: inline-block; vertical-align: middle;">
                            @csrf
                            <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                            <input type="hidden" value="{{ $obj->Id }}" name="DeudaDetalle">
                            <button class="btn btn-success" style="margin-top: 15px">
                                <i class="fa fa-file-excel-o" align="center" title="Descargar Cartera a excel"></i>
                            </button>
                        </form>
                    </td>
                    

                </tr>
                @include('polizas.deuda.modal_edit')
            @endforeach
        </tbody>
        <tfoot>
            <td colspan="5" style="text-align: right;"><b>Total de Poliza:</b> </td>
            <td colspan="5" style="text-align: right;"><b>${{ number_format($total, 2, '.', ',') }}</b> </td>
            <td colspan="2"></td>
        </tfoot>
    </table>

</div>

<div class="modal fade " id="modal_editar_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('polizas/deuda/edit_pago') }}">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Gestión de cobro de póliza</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        @csrf
                        <input type="hidden" name="Id" id="ModalId" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Saldo a</label>
                                <input type="date" name="SaldoA" id="ModalSaldoA" class="form-control"
                                    value="{{ date('Y-m-d') }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Impresión de Recibo</label>
                                <input type="date" name="ImpresionRecibo" id="ModalImpresionRecibo"
                                    value="{{ date('Y-m-d') }}" class="form-control" readonly>
                            </div>
                            <!-- <div class="col-sm-3">
                                                                <label class="control-label">&nbsp;</label>
                                                                <i class="btn btn-default fa fa-print form-control" id="btn_impresion"></i>
                                                            </div> -->
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Envio cartera</label>
                                <input type="date" name="EnvioCartera" id="ModalEnvioCartera"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Envio pago</label>
                                <input type="date" name="EnvioPago" id="ModalEnvioPago" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Pago aplicado</label>
                                <input type="date" name="PagoAplicado" id="ModalPagoAplicado"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Comentario</label>
                                <textarea class="form-control" rows="4" name="Comentario" id="ModalComentario"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </div>
            </form>

        </div>
    </div>
</div>
