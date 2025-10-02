{{-- modales retroalimentacion --}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-retroalimentacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/add_retroalimentacion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Retroalimentación</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <input type="hidden" name="ServicioCliente" id="ServicioCliente" value="0" class="form-control" required>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Producto de NR *
                                    <input type="text" name="Producto" required oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Servicio al ciente * <br>
                                    <div id="stars">
                                        <i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="check_stars(1)"></i>
                                        <i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="check_stars(2)"></i>
                                        <i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="check_stars(3)"></i>
                                        <i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="check_stars(4)"></i>
                                        <i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="check_stars(5)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Valores agregados *
                                    <input type="text" name="ValoresAgregados" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Competidores *
                                    <input type="text" name="Competidores" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    ¿Que quisiera de NR? *
                                    <input type="text" name="QueQuisiera" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    Referidos *
                                    <input type="text" name="Referidos" class="form-control"  oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="row"> * Campo requerido</div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

            </form>

        </div>
    </div>
</div>

<div class="col-12">
    <div class="modal fade modal-edit-retroalimentacion" id="modal-edit-retroalimentacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_retroalimentacion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar Retroalimentación</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <input type="hidden" name="Id" id="ModalRetroId" class="form-control">
                        <input type="hidden" name="ServicioCliente" id="ModalRetroServicioCliente" class="form-control" required>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Producto de NR *
                                    <input type="text" name="Producto" id="ModalRetroProducto" required oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Servicio al ciente *<br>
                                    <div id="modal_stars">
                                        <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(1)"></i>
                                        <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(2)"></i>
                                        <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(3)"></i>
                                        <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(4)"></i>
                                        <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(5)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Valores agregados *
                                    <input type="text" name="ValoresAgregados" id="ModalRetroValoresAgregados" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"  required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    Competidores *
                                    <input type="text" name="Competidores" id="ModalRetroCompetidores" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    ¿Que quisiera de NR? *
                                    <input type="text" name="QueQuisiera" id="ModalRetroQueQuisiera" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    Referidos *
                                    <input type="text" name="Referidos" id="ModalRetroReferidos" class="form-control"  oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="row"> * Campo requerido </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-retroalimentacion">

        <form method="POST" action="{{ url('catalogo/cliente/delete_retroalimentacion') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <input type="hidden" name="Id" id="IdRetroalimentacion">
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </div>
</div>
