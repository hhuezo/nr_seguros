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
                    @foreach ($historico as $obj)
                    <tr>
                        <td>{{$obj->Axo}}</td>
                        <<td>{{ ucfirst(\Carbon\Carbon::create(null, $obj->Mes, 1)->translatedFormat('F')) }}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->FechaInicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->FechaFinal)->format('d/m/Y') }}</td>
                        <td> {{ $obj->total_registros}}</td>
                        <td> <a class="btn btn-primary on-default edit-row" title="Consultar Pago">
                            <i class="fa fa-eye fa-lg" onclick="mostrar_historial();"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade " id="modal_historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
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
                    <div class="box-body">


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
