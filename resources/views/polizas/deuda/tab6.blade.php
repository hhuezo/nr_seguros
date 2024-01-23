<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


        <div class="x_title">
            <h4>&nbsp;&nbsp; Comentarios<small></small>
            </h4>
            <div class="clearfix" align="right"><button class="btn btn-primary" onclick="add_comment();"><i class="fa fa-plus"></i> Agregar Comentario</button></div>
        </div>
        <br>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table width="100%" class="table table-striped" id="comentarios">
                <thead>
                    <tr>
                        <th>Comentario</th>
                        <th>Tipo de <br> Comentario</th>
                        <th>Usuario</th>
                        <th>Fecha Ingreso</th>
                        <th><i class="fa fa-filef"></i>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comentarios as $obj)
                    <tr>
                        <td>{{$obj->Comentario}}</td>
                        @if($obj->Detalledeuda)
                        <td>Detalle del Cobro del {{\Carbon\Carbon::parse($obj->FechaIngreso)->format('d/m/Y')}}</td>
                        @else
                        <td>Póliza</td>
                        @endif
                        <td> {{ $obj->usuarios->name}}</td>
                        <td>{{ \Carbon\Carbon::parse($obj->FechaIngreso)->format('d/m/Y') }}</td>
                        <td><a href="" data-target="#modal-delete-comentario-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                        </td>
                    </tr>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-comentario-{{ $obj->Id }}">

                        <form method="POST" action="{{ url('polizas/deuda/eliminar_comentario') }}">
                            @method('POST')
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Eliminar Registro</h4>
                                        <input type="hidden" name="IdComment" value="{{$obj->Id}}">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade " id="modal_agregar_comentario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ url('polizas/deuda/agregar_comentario') }}">
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Comentario</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="deudaComment" value="{{$deuda->Id}}" class="form-control">

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">Tipo de Comentario</label>
                                    <select name="TipoComentario" id="TipoComentario" class="form-control">
                                        <option value="">Sobre Póliza</option>
                                        @foreach($detalle as $det)
                                        <option value="{{$det->Id}}">Sobre Cobro de {{ \Carbon\Carbon::parse($det->FechaInicio)->format('d/m/Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label">Comentario</label>
                                    <textarea class="form-control" rows="4" name="Comentario"></textarea>
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
</div>

<div class="modal fade " id="modal_agregar_comentario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('polizas/deuda/agregar_comentario') }}">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar Comentario</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        @csrf
                        <input type="hidden" name="DeudaComment" value="{{$deuda->Id}}" class="form-control">

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Tipo de Comentario</label>
                                <select name="TipoComentario" id="TipoComentario" class="form-control">
                                    <option value="">Sobre Póliza</option>
                                    @foreach($detalle as $det)
                                    <option value="{{$det->Id}}">Sobre Cobro de {{ \Carbon\Carbon::parse($det->FechaInicio)->format('d/m/Y') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Comentario</label>
                                <textarea class="form-control" rows="4" name="Comentario"></textarea>
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