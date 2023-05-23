<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Numero Usuarios</th>
            <th>Suma Asegurada</th>
            <th>MontoCartera</th>
            <th>Tasa</th>
            <th>Sub Total Asegurado</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @php($totalUsuario = 0)
        @php($montocartera = 0)
        @php($subtotal = 0)

        @foreach($usuario_vidas as $obj)
        <tr>
            <td>{{$obj->NumeroUsuario}}</td>
            <td>{{$obj->SumaAsegurada}}</td>
            <td>{{$obj->SubTotalAsegurado}}</td>
            <td>{{$obj->Tasa}}</td>
            <td>{{$obj->TotalAsegurado}}</td>
            <td>
                <a data-target="#modal-usuario-edit-{{ $obj->Id }}" data-toggle="modal" class="on-default edit-row">
                    <i class="fa fa-pencil fa-lg"></i></a> 
                &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{$obj->Id}}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
            </td>
        </tr>
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{ $obj->Id }}">

            <form method="POST" action="{{ url('poliza/vida/usuario_eliminar/', $obj->Id) }}">
                @method('delete')
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Eliminar Registro</h4>
                        </div>
                        <div class="modal-body">
                            <p>Confirme si desea Eliminar el Registro</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>


        <div class="modal fade" id="modal-usuario-edit-{{$obj->Id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ url('poliza/vida/usuario_edit') }}" method="POST">
                        <div class="modal-header">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <h5 class="modal-title" id="exampleModalLabel">Editar usuario - Polizas de Vida Colectivo </h5>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="Id" id="Id" value="{{$obj->Id}}">
                                    <input type="hidden" id="ModalId" name="Vida" value="{{$obj->Vida}}">
                                    <div class="form-group row">
                                        <input type="hidden" id="ModalTipoTasa">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero de Poliza</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Poliza" id="ModalPolizaa" type="number" autofocus="true" readonly value="{{$obj->Poliza}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero de Usuarios</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                            <input class="form-control" name="NumeroUsuario" id="ModalNumeroUsuarioo" type="number" autofocus="true" value="{{$obj->NumeroUsuario}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Suma Asegurada</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SumaAsegurada" id="ModalSumaAseguradaa" type="text" autofocus="true" value="{{$obj->SumaAsegurada}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">SubTotal</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SubTotal" id="ModalSubTotall" type="text" autofocus="true" value="{{$obj->SubTotalAsegurado}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                            <input class="form-control" name="Tasa" id="ModalTasaUsuarioo" type="number" step="0.01" autofocus="true" value="{{$obj->Tasa}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">TotalAsegurado</label>
                                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TotalAsegurado" id="ModalTotalAseguradoo" type="number" step="0.01" value="{{$obj->TotalAsegurado}}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="modal-footer" align="center">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        @php($totalUsuario += $obj->NumeroUsuario)
        @php($montocartera += $obj->SubTotalAsegurado)
        @php($subtotal += $obj->TotalAsegurado)
        @endforeach
    <tfoot>
        <tr>
            <td>Totales{{$totalUsuario}}</td>
            <td></td>
            <td>${{$montocartera}}</td>
            <td></td>
            <td>${{$subtotal}}</td>
            <td></td>
        </tr>
    </tfoot>
    </tbody>
</table>
>