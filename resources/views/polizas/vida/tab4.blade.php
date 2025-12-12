<div class="x_title">
    <h2>Estado de Pagos <small></small>
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
                <th style="text-align: center;">Mes/Año</th>
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
                <td style="text-align: center;">{{ $poliza_vida->NumeroPoliza }}</td>
                <td style="text-align: center;">{{ $obj->Mes }}/{{ $obj->Axo }}</td>
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
                <td style="text-align: center;" class="row-error">Anulado</td>
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



                    @if ($obj->Activo == 1)
                    <button class="btn btn-warning" data-target="#modal-view-{{ $obj->Id }}" data-toggle="modal">
                        <i class="fa fa-eye" align="center" title="Ver Actividad de Aviso de cobro"></i>
                    </button>
                    <a href="" data-target="#modal-anular-{{ $obj->Id }}" data-toggle="modal" title="Anular Aviso de Cobro">
                        <button class="btn btn-danger" style="background-color: #ff5733;">
                            <i class="fa fa-close fa-lg"></i>
                        </button>
                    </a>

                    <a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" title="Eliminar Aviso de Cobro">
                        <button class="btn btn-danger">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </a>
                            @if($poliza_vida->Aseguradora == 3)
                            <form action="{{ url('vida/exportar_excel_fede') }}" method="POST" style="display: inline-block; vertical-align: middle;">
                            @else
                            <form action="{{ url('vida/exportar_excel') }}" method="POST" style="display: inline-block; vertical-align: middle;">
                            @endif
                            @csrf
                            <input type="hidden" value="{{ $poliza_vida->Id }}" name="Vida">
                            <input type="hidden" value="{{ $obj->Id }}" name="VidaDetalle">
                            <button class="btn btn-success" style="margin-top: 15px">
                                <i class="fa fa-file-excel-o" align="center" title="Descargar Cartera a excel"></i>
                            </button>
                        </form>
                        @endif


                </td>


            </tr>
            @include('polizas.vida.modal_edit')
            @endforeach
        </tbody>

        <tfoot>
            <td colspan="6" style="text-align: right;"><b>Total de Poliza:</b> </td>
            <td colspan="5" style="text-align: right;"><b>${{ number_format($total, 2, '.', ',') }}</b> </td>
            <td colspan="2"></td>
        </tfoot>
    </table>

</div>

<div class="modal fade " id="modal_editar_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('polizas/vida/edit_pago') }}">
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
<script>
    function modal_edit(id) {

        // document.getElementById('ModalSaldoA').value = "";
        // document.getElementById('ModalImpresionRecibo').value = "";
        document.getElementById('ModalComentario').value = "";
        document.getElementById('ModalEnvioCartera').value = "";
        document.getElementById('ModalEnvioPago').value = "";
        document.getElementById('ModalPagoAplicado').value = "";
        document.getElementById('ModalId').value = id;



        $.get("{{ url('polizas/vida/get_pago') }}" + '/' + id, function(data) {


            console.log(data);
            if (data.SaldoA != null) {
                document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
            }

            if (data.ImpresionRecibo != null) {
                document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
                $("#ModalEnvioCartera").removeAttr("readonly");
            }



            document.getElementById('ModalComentario').value = data.Comentario;
            if (data.EnvioCartera) {
                document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
                $("#ModalEnvioCartera").prop("readonly", true);
            } else {
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }


            if (data.EnvioPago) {
                document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
                $("#ModalEnvioPago").prop("readonly", true);
            } else {
                //  $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.PagoAplicado) {
                document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);

                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }
            // // else {
            //     $("#ModalEnvioCartera").prop("readonly", true);
            //     $("#ModalEnvioPago").prop("readonly", true);
            // }



        });
        $('#modal_editar_pago').modal('show');

    }
</script>
