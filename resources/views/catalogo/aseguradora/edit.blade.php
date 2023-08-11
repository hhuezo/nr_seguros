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
                    <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#cliente" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Aseguradora</a>

                    </li>
                    <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#redes" role="tab" id="profile-necesidad" data-toggle="tab" aria-expanded="false">Contactos
                            frecuentes</a>
                    </li>

                    <!-- <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#necesidad" role="tab" id="profile-necesidad" data-toggle="tab" aria-expanded="false">Necesidades</a>
                    </li> -->
                </ul>


                <div id="myTabContent2" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} " id="cliente" aria-labelledby="home-tab">

                        <form method="POST" action="{{ route('aseguradoras.update', $aseguradora->Id) }}">
                            @method('PUT')
                            @csrf

                            <div class="x_content">
                                <br />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Código</label>
                                        <input type="text" name="Nombre" value="{{$aseguradora->Id}}" class="form-control" readonly autofocus="true">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">Tipo Contribuyente</label>
                                        <select name="TipoContribuyente" class="form-control">
                                            @foreach ($tipo_contribuyente as $obj)
                                            <option value="{{ $obj->Id }}" {{ $aseguradora->TipoContribuyente == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Nit Empresa</label>
                                        <input type="text" name="Nit" value="{{ $aseguradora->Nit }}" class="form-control" data-inputmask="'mask': ['9999-999999-999-9']">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label ">Página Web</label>
                                        <input type="text" name="PaginaWeb" value="{{ $aseguradora->PaginaWeb }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Registro fiscal</label>
                                        <input type="text" name="RegistroFiscal" value="{{ $aseguradora->RegistroFiscal }}" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">Fecha constitución</label>
                                        <input type="date" name="FechaConstitucion" value="{{ $aseguradora->FechaConstitucion }}" class="form-control">
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Nombre o Razón Social</label>
                                        <input type="text" name="Nombre" value="{{ $aseguradora->Nombre }}" class="form-control" required autofocus="true">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">Dirección</label>
                                        <textarea name="Direccion" rows="1" class="form-control">{{ $aseguradora->Direccion }}</textarea>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Abreviatura</label>
                                        <input type="text" name="Abreviatura" value="{{ $aseguradora->Abreviatura }}" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">Teléfono fijo de asistencia</label>
                                        <input type="text" name="TelefonoFijo" value="{{ $aseguradora->TelefonoFijo }}" class="form-control" data-inputmask="'mask': ['9999-9999']">
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-sm-6">
                                        <label class="control-label ">Fecha vinculación</label>
                                        <input type="date" name="FechaVinculacion" value="{{ $aseguradora->FechaVinculacion }}" class="form-control">
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="control-label ">Teléfono whatsapp asistencia</label>
                                        <input type="text" name="TelefonoWhatsapp" value="{{ $aseguradora->TelefonoWhatsapp }}" class="form-control" data-inputmask="'mask': ['9999-9999']">
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-md-6">
                                        <label for="DireccionResidencia" class="form-label">Departamento</label>
                                        <select id="Departamento" class="form-control" style="width: 100%">
                                            @foreach ($departamentos as $obj)
                                            <option value="{{ $obj->Id }}" {{ $departamento_actual == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="DireccionResidencia" class="form-label">Municipio</label>
                                        <select name="Municipio" id="Municipio" class="form-control select2" style="width: 100%">
                                            @foreach ($municipios as $obj)
                                            <option value="{{ $obj->Id }}" {{ $municipio_actual == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 15px!important;">
                                    <div class="col-md-6">
                                        <label for="DireccionResidencia" class="form-label">Distrito</label>
                                        <select id="Distrito" name="Distrito" required class="form-control select2" style="width: 100%">
                                            @foreach ($distritos as $obj)
                                            <option value="{{ $obj->Id }}" {{ $aseguradora->Distrito == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Modificar</button>
                                <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                            </div>

                        </form>

                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="redes" aria-labelledby="home-tab">
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-contacto"><i class="fa fa-plus fa-lg"></i>
                                Nuevo</button>
                        </div>
                        @if ($contactos->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cargo/Función</th>
                                    <th>Teléfonos Contacto</th>
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
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_contacto({{ $obj->Id }},'{{ $obj->Cargo }}','{{ $obj->Nombre }}','{{ $obj->Telefono }}','{{ $obj->Email }}')" data-target="#modal-edit-contacto" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_contacto({{ $obj->Id }})" data-target="#modal-delete-contacto" data-toggle="modal"></i>


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}" id="necesidad" aria-labelledby="home-tab">



                        <div class="x_content">
                            <br />
                            <form method="POST" action="{{ url('catalogo/aseguradora/attach_necesidad_proteccion') }}">

                                @csrf
                                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 "></div>
                                <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12 ">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12">Tipo póliza</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input type="hidden" name="aseguradora_id" value="{{ $aseguradora->Id }}">
                                            <select name="tipo_poliza_id" id="TipoPoliza" class="form-control">
                                                @foreach ($tipos_poliza as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12">Necesidad de
                                            protección</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <select name="necesidad_proteccion_id" id="NecesidadProteccion" class="form-control select2" style="width: 100%">
                                                @foreach ($necesidades_proteccion as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>


                                    <div class="form-group" align="center" style="text-align: right;">
                                        <button class="btn btn-success" type="submit">Guardar</button>
                                    </div>


                                </div>

                            </form>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 "></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            @if ($necesidades_proteccion_actual->count() > 0)
                            <br>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Necesidad de proteccion</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($necesidades_proteccion_actual as $obj)
                                    <tr>
                                        @if ($obj->tipo_poliza)
                                        <td>{{ $obj->tipo_poliza->Nombre }}</td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{ $obj->Nombre }}</td>

                                        <td>
                                            &nbsp;&nbsp;
                                            <i class="fa fa-trash fa-lg" onclick="modal_delete_necesidad({{ $obj->Id }})" data-target="#modal-delete-necesidad" data-toggle="modal"></i>


                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div style="height: 200px">
                                <br>
                                <div class="alert alert-danger alert-dismissible " role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
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
                            <input type="hidden" name="Aseguradora" value="{{ $aseguradora->Id }}" class="form-control">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Nombre
                                    <input type="text" name="Nombre" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Cargo <span class="fa fa-plus" onclick="addCargo();" class="col-md-2" style="padding-left: 75%;"></span>
                                    <select name="Cargo" id="ModalCargo" class="form-control" required>
                                        @foreach ($cargos as $cargo)
                                        <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-6">
                                    Telefono
                                    <input type="text" name="Telefono" data-inputmask="'mask': ['9999-9999']" data-mask class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
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
        <div class="modal fade modal-edit-contacto" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-contacto">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ url('catalogo/aseguradora/edit_contacto') }}">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Editar contacto</h4>
                            <input type="hidden" name="Id" id="ModalContactoId" class="form-control" required>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="Aseguradora" value="{{ $aseguradora->Id }}" class="form-control">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Nombre
                                    <input type="text" name="Nombre" id="ModalContactoNombre" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Cargo
                                    <select name="Cargo" id="ModalContactoCargo" class="form-control" required>
                                        @foreach ($cargos as $cargo)
                                        <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    Telefono
                                    <input type="text" name="Telefono" id="ModalContactoTelefono" data-inputmask="'mask': ['9999-9999']" data-mask class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    Email
                                    <input type="email" required name="Email" id="ModalContactoEmail" class="form-control">
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
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-contacto">

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
        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-necesidad">

            <form method="POST" action="{{ url('catalogo/aseguradora/detach_necesidad_proteccion') }}">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <input type="hidden" name="necesidad_proteccion_id" id="IdNecesidadProteccion">
                            <input type="hidden" name="aseguradora_id" value="{{ $aseguradora->Id }}">
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


    <div class="modal fade" id="modal_addCargo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Cargo, modulo aseguradora</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">

                        <div class="x_content">
                            <br />

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nombre</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="Nombre" id="ModalNombreCargo" class="form-control" autofocus="true">
                                </div>
                                <label class="col-sm-3 control-label">&nbsp;</label>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="btn_cancelar_cargo">Cancelar</button>
                    <button type="button" id="btn_guardar_cargo" class="btn btn-primary">Aceptar</button>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {

    });

    function addCargo() {
        $('#modal_addCargo').modal('show');
    }

    $("#btn_guardar_cargo").click(function() {

        //   alert('adadad');
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nombre": document.getElementById('ModalNombreCargo').value
        };
        $.ajax({
            type: "get",
            url: "{{ url('catalogo/aseguradora/addCargo') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#ModalCargo").html(_select);
                $('#modal_addCargo').modal('hide');
            }
        })
    });
    $("#btn_cancelar_cargo").click(function() {
        $('#modal_addCargo').modal('hide');
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

    function modal_delete_necesidad(id) {
        document.getElementById('IdNecesidadProteccion').value = id;
        //$('#modal_borrar_documento').modal('show');
    }

    $("#TipoPoliza").change(function() {

        var TipoPoliza = $(this).val();

        $.get("{{ url('catalogo/aseguradora/get_necesidad') }}" + '/' + TipoPoliza, function(data) {

            //console.log(data);
            var _select = ''
            for (var i = 0; i < data.length; i++)
                _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                '</option>';

            $("#NecesidadProteccion").html(_select);

        });

    });
</script>
</div>
@include('sweetalert::alert')

@endsection
