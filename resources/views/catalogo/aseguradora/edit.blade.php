@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Modificar aseguradora <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">

                    </ul>
                    <div class="clearfix"></div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#cliente"
                                id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Aseguradora</a>

                        </li>
                        <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#redes"
                                role="tab" id="profile-necesidad" data-toggle="tab" aria-expanded="false">Contactos
                                frecuentes</a>
                        </li>

                        <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#necesidad"
                                role="tab" id="profile-necesidad" data-toggle="tab"
                                aria-expanded="false">Necesidades</a>
                        </li>
                    </ul>


                    <div id="myTabContent2" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} "
                            id="cliente" aria-labelledby="home-tab">

                            <form method="POST" action="{{ route('aseguradoras.update', $aseguradora->Id) }}">
                                @method('PUT')
                                @csrf

                                <div class="x_content">
                                    <br />
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Nombre o razón
                                                social</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="Nombre" value="{{ $aseguradora->Nombre }}"
                                                    class="form-control" required autofocus="true">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Nit</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="Nit" value="{{ $aseguradora->Nit }}"
                                                    class="form-control" data-inputmask="'mask': ['9999-999999-999-9']">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Registro
                                                fiscal</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="RegistroFiscal"
                                                    value="{{ $aseguradora->RegistroFiscal }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Abreviatura</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="Abreviatura"
                                                    value="{{ $aseguradora->Abreviatura }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha
                                                vinculacion</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="date" name="FechaVinculacion"
                                                    value="{{ $aseguradora->FechaVinculacion }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tipo
                                                contribuyente</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="TipoContribuyente" class="form-control">
                                                    @foreach ($tipo_contribuyente as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $aseguradora->TipoContribuyente == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>





                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">


                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Página Web</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="PaginaWeb"
                                                    value="{{ $aseguradora->PaginaWeb }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha
                                                constitucion</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="date" name="FechaConstitucion"
                                                    value="{{ $aseguradora->FechaConstitucion }}" class="form-control">
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Dirección</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <textarea name="Direccion" rows="3" class="form-control">{{ $aseguradora->Direccion }}</textarea>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono fijo</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="TelefonoFijo"
                                                    value="{{ $aseguradora->TelefonoFijo }}" class="form-control"
                                                    data-inputmask="'mask': ['9999-9999']">
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Teléfono
                                                whatsapp</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="text" name="TelefonoWhatsapp"
                                                    value="{{ $aseguradora->TelefonoWhatsapp }}" class="form-control"
                                                    data-inputmask="'mask': ['9999-9999']">
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="form-group" align="center">
                                    <button class="btn btn-success" type="submit">Modificar</button>
                                    <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary"
                                            type="button">Cancelar</button></a>
                                </div>

                            </form>

                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}"
                            id="redes" aria-labelledby="home-tab">
                            <div class="col-12" style="text-align: right;">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target=".bs-modal-nuevo-contacto"><i class="fa fa-plus fa-lg"></i>
                                    Nuevo</button>
                            </div>
                            @if ($contactos->count() > 0)
                                <br>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cargo</th>
                                            <th>Telefono</th>
                                            <th>Email</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contactos as $obj)
                                            <tr>
                                                <td>{{ $obj->Nombre }}</td>
                                                @if ($obj->cargo)
                                                    <td>{{ $obj->cargo->Nombre }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{ $obj->Telefono }}</td>
                                                <td>{{ $obj->Email }}</td>
                                                <td>
                                                    <i class="fa fa-pencil fa-lg"
                                                        onclick="modal_edit_contacto({{ $obj->Id }},'{{ $obj->Cargo }}','{{ $obj->Nombre }}','{{ $obj->Telefono }}','{{ $obj->Email }}')"
                                                        data-target="#modal-edit-contacto" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_contacto({{ $obj->Id }})"
                                                        data-target="#modal-delete-contacto" data-toggle="modal"></i>


                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div style="height: 200px">
                                    <br>
                                    <div class="alert alert-danger alert-dismissible " role="alert">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Sin datos que mostrar.</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}"
                            id="necesidad" aria-labelledby="home-tab">



                            <div class="x_content">
                                <br />
                                <form method="POST" action="{{ url('catalogo/aseguradora/attach_tipo_poliza') }}">

                                    @csrf
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 "></div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tipo póliza</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input type="hidden" name="aseguradora_id"
                                                    value="{{ $aseguradora->Id }}">
                                                <select name="tipo_poliza_id" class="form-control select2"
                                                    style="width: 100%">
                                                    @foreach ($tipos_poliza as $obj)
                                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>


                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                        <button class="btn btn-success" type="submit">Agregar</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 "></div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                                @if ($tipos_poliza_actual->count() > 0)
                                    <br>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>                                               
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tipos_poliza_actual as $obj)
                                                <tr>
                                                    <td>{{ $obj->Nombre }}</td>
                                                   
                                                    <td>                                                      
                                                        &nbsp;&nbsp;
                                                        <i class="fa fa-trash fa-lg"
                                                            onclick="modal_delete_tipo({{ $obj->Id }})"
                                                            data-target="#modal-delete-tipo" data-toggle="modal"></i>


                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div style="height: 200px">
                                        <br>
                                        <div class="alert alert-danger alert-dismissible " role="alert">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close"><span aria-hidden="true">×</span>
                                            </button>
                                            <strong>Sin datos que mostrar.</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>



                </div>






            </div>



            {{-- modales contactos --}}
            <div class="col-12">
                <div class="modal fade bs-modal-nuevo-contacto" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ url('catalogo/aseguradora/add_contacto') }}">
                            @csrf
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Nuevo contacto</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="Aseguradora" value="{{ $aseguradora->Id }}"
                                        class="form-control">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Nombre
                                            <input type="text" name="Nombre" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Cargo
                                            <select name="Cargo" class="form-control" required>
                                                @foreach ($cargos as $cargo)
                                                    <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Telefono
                                            <input type="text" name="Telefono" data-inputmask="'mask': ['9999-9999']"
                                                data-mask class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Email
                                            <input type="email" class="form-control" required name="Email">
                                        </div>
                                    </div>

                                </div>
                                <div>&nbsp; </div>
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
                <div class="modal fade modal-edit-contacto" tabindex="-1" role="dialog" aria-hidden="true"
                    id="modal-edit-contacto">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ url('catalogo/aseguradora/edit_contacto') }}">
                            @csrf
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Editar contacto</h4>
                                    <input type="hidden" name="Id" id="ModalContactoId" class="form-control"
                                        required>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="Aseguradora" value="{{ $aseguradora->Id }}"
                                        class="form-control">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Nombre
                                            <input type="text" name="Nombre" id="ModalContactoNombre"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Cargo
                                            <select name="Cargo" id="ModalContactoCargo" class="form-control" required>
                                                @foreach ($cargos as $cargo)
                                                    <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Telefono
                                            <input type="text" name="Telefono" id="ModalContactoTelefono"
                                                data-inputmask="'mask': ['9999-9999']" data-mask class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            Email
                                            <input type="email" required name="Email" id="ModalContactoEmail"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <div>&nbsp; </div>
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
                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                    id="modal-delete-contacto">

                    <form method="POST" action="{{ url('catalogo/aseguradora/delete_contacto') }}">
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <input type="hidden" name="Id" id="IdContacto">
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
            </div>


            <div class="col-12">
                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                    id="modal-delete-tipo">

                    <form method="POST" action="{{ url('catalogo/aseguradora/detach_tipo_poliza') }}">
                        @csrf
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <input type="hidden" name="tipo_poliza_id" id="IdTipo">
                                    <input type="hidden" name="aseguradora_id" value="{{$aseguradora->Id}}">
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
            </div>

        </div>

        <!-- jQuery -->
        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {

            });


            function modal_edit_contacto(id, cargo, nombre, telefono, email) {
                document.getElementById('ModalContactoId').value = id;
                document.getElementById('ModalContactoCargo').value = cargo;
                document.getElementById('ModalContactoNombre').value = nombre;
                document.getElementById('ModalContactoTelefono').value = telefono;
                document.getElementById('ModalContactoEmail').value = email;
                //$('#modal_borrar_documento').modal('show');
            }

            function modal_delete_contacto(id) {
                document.getElementById('IdContacto').value = id;
                $('#modal_borrar_documento').modal('show');
            }

            function modal_delete_tipo(id) {
                document.getElementById('IdTipo').value = id;
                //$('#modal_borrar_documento').modal('show');
            }
        </script>
    </div>
    @include('sweetalert::alert')

@endsection
