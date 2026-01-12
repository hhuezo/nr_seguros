<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


        <div class="x_title">
            <h4>&nbsp;&nbsp; Histórico de pagos<small></small>
            </h4>
        </div>
        <br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table width="100%" class="table table-striped" id="historico">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Fecha Inicial</th>
                        <th>Fecha Final</th>
                        <th>Total de registros</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalle as $obj)
                    <tr>
                        <td>{{$obj->Axo}}</td>
                        <td>{{ ucfirst(\Carbon\Carbon::create(null, $obj->Mes, 1)->translatedFormat('F')) }}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') }}</td>
                        <td> {{ $obj->UsuariosReportados}}</td>
                        <td>
                            <div style=" display: flex;gap: 10px; align-items: center;">
                                <a class="btn btn-primary on-default edit-row" title="Consultar Pago"
                                   onclick="mostrar_historial({{$obj->Axo}}, {{$obj->Mes}}, {{ \Carbon\Carbon::parse($obj->FechaInicio)->format('Ymd') }}, {{ \Carbon\Carbon::parse($obj->FechaFinal)->format('Ymd') }}, {{$id}});">
                                    <i class="fa fa-eye fa-lg"></i>
                                </a>
                                <form action="{{url('polizas/deuda/exportar_historial')}}" method="post" style="display:inline-block;">
                                    @csrf
                                    <input type="hidden" name="Axo" id="Axo" value="{{$obj->Axo}}">
                                    <input type="hidden" name="Mes" id="Mes" value="{{$obj->Mes}}">
                                    <input type="hidden" name="FechaInicio" id="FechaInicio" value="{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('Ymd') }}">
                                    <input type="hidden" name="FechaFinal" id="FechaFinal" value="{{ \Carbon\Carbon::parse($obj->FechaFinal)->format('Ymd') }}">
                                    <input type="hidden" name="PolizaDeuda" id="PolizaDeuda" value="{{$id}}">
                                    <button class="btn btn-success on-default edit-row" style="margin-top: 15px" title="Exportar Pago">
                                        <i class="fa fa-file-excel-o fa-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade " id="modal_historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document" style="width: 70%!important;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <h5 class="modal-title" id="exampleModalLabel">Histórico de pagos</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body" id="historial_table">


                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>
