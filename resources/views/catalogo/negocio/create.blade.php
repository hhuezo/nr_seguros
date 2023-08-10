@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo Negocio <small></small></h2>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#cliente"
                                    id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos Negocio</a>

                            </li>
                            <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#redes"
                                    role="tab" id="profile-necesidad" data-toggle="tab"
                                    aria-expanded="false">Información de contacto</a>
                            </li>
                        </ul>

                        <div id="myTabContent2" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} "
                                id="cliente" aria-labelledby="home-tab">
                                <form action="{{ url('catalogo/negocio') }}" method="POST" class="forms-sample">
                                    @csrf
                                    <div class="x_content">
                                        <input type="hidden" name="CantidadDependiente" id="CantidadDependiente"
                                            value="{{ old('CantidadDependiente') }}" class="form-control" required
                                            autofocus="true">
                                        <input type="hidden" name="datos_localstorage" id="datos_localstorage">


                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label for="IdCliente" class="form-label">Código Cliente</label>
                                                    <input type="text" class="form-control" name="IdCliente"
                                                        id="IdCliente" placeholder="Ingrese codigo de cliente"
                                                        onkeyup="mostrarId();">
                                                </div><!-- /.col-lg-6 -->
                                                <div class="col-md-4">
                                                    <label for="NombreCliente" class="form-label">Nombre del cliente</label>
                                                    <input class="form-control validarCredenciales" type="text"
                                                        value="" name="NombreCliente" id="NombreCliente">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="TipoPersona" class="form-label">Tipo Cliente</label>
                                                    <select name="TipoPersona" id="TipoPersona" class="form-control"
                                                        onchange="identificadorCliente();">
                                                        <option value="" selected disabled>Seleccione ...</option>
                                                        <option value="1">Natural</option>
                                                        <option value="2">Juridico</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="row" style="margin-top: 12px!important;">

                                                <div id="divDui" class="col-md-4">
                                                    <label for="Dui" class="form-label">DUI </label>
                                                    <input type="text" name="Dui" id="Dui"
                                                        value="{{ old('Dui') }}" data-inputmask="'mask': ['99999999-9']"
                                                        onkeyup="mostrar();" class="form-control">
                                                    <span id="helpBlockDuiNit" class="help-block">Este cliente ya
                                                        existe.</span>
                                                </div>

                                                <div id="divNit" class="col-md-4">
                                                    <label for="NitEmpresa" class="form-label">NIT Empresa </label>
                                                    <input type="text" name="NitEmpresa" id="NitEmpresa"
                                                        value="{{ old('NitEmpresa') }}"
                                                        data-inputmask="'mask': ['9999-999999-999-9']" onkeyup="mostrar();"
                                                        class="form-control" ">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="Email" class="form-label">Email</label>
                                                        <input type="email" class="form-control validarCredenciales" name="Email" id="Email">
                                                    </div>
                                                </div>
                                            <div class="row" style="margin-top: 12px!important;">
                                                <div class="col-md-4">
                                                    <label for="EstadoCliente" class="form-label">Estado Cliente</label>
                                                    <select disabled name="EstadoCliente" id="EstadoCliente" class="form-control select2">
                                                        <option value="" selected disabled> Seleccione...</option>
                                                        @foreach ($cliente_estado as $obj)
                                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="Ejecutivo" class="form-label">Ejecutivo</label>
                                                    <select name="Ejecutivo" id="Ejecutivo" class="form-control select2"
                                                        style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        @foreach ($ejecutivos as $obj)
                                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="EstadoVenta" class="form-label">Estado Venta</label>
                                                    <select name="EstadoVenta" id="EstadoVenta"
                                                        class="form-control select2" style="width: 100%" required>
                                                        <option value="" disabled selected>Seleccione...</option>
                                                        @foreach ($estados_venta as $obj)
                                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-top: 15px !important;">
                                                <div class="col-md-4">
                                                    <label for="TipoPoliza" class="form-label">Ramo</label>
                                                    <select name="TipoPoliza" id="TipoPoliza"
                                                        class="form-control select2" style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        @foreach ($tipos_poliza as $obj)
                                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="NumeroPoliza" class="form-label">Número de póliza</label>
                                                    <input class="form-control" type="text" value=""
                                                        name="NumeroPoliza" id="NumeroPoliza">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="NecesidadProteccion" class="form-label">Necesidad de
                                                        protección</label>
                                                    <select name="NecesidadProteccion" id="NecesidadProteccion"
                                                        class="form-control select2" style="width: 100%;" required>
                                                        <option value="">Seleccione...</option>
                                                        @foreach ($necesidad_proteccion as $obj)
                                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-top: 15px !important;">
                                                <div class="col-md-4">
                                                    <label for="TipoNecesidad" class="form-label">Tipo de Necesidad
                                                        (consultar
                                                        nombre)</label>
                                                    <select name="TipoNecesidad" id="TipoNecesidad"
                                                        class="form-control select2" style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="PlanTipoProducto" class="form-label">Plan / Tipo de
                                                        producto</label>
                                                    <input type="text" class="form-control" id="PlanTipoProducto"
                                                        name="PlanTipoProducto" placeholder="Ingrese el plan">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="TipoNegocio" class="form-label">Tipo de negocio</label>
                                                    <select name="TipoNegocio" id="TipoNegocio"
                                                        class="form-control select2" style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        @foreach ($tipos_negocio as $obj)
                                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-top: 15px !important;">
                                                <div class="col-md-4">
                                                    <label for="DepartamentoAtiende" class="form-label">Departamento que
                                                        atenderá</label>
                                                    <select name="DepartamentoAtiende" id="DepartamentoAtiende"
                                                        class="form-control select2" style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        <option value="1">OPS AUTOMOTOR</option>
                                                        <option value="2">OPS DAÑOS</option>
                                                        <option value="3">OPS VIDA</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="MetodoPago" class="form-label">Método de
                                                        pago</label>
                                                    <select name="MetodoPago" id="MetodoPago"
                                                        class="form-control select2" style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>

                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="FormaPago" class="form-label">Periodo de Pago (Forma de
                                                        Pago)</label>
                                                    <select name="FormaPago" id="FormaPago" class="form-control select2"
                                                        style="width: 100%" required>
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        <option value="">Anual</option>
                                                        <option value="">Semestral</option>
                                                        <option value="">Trimestral</option>
                                                        <option value="">Mensual</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-top: 15px !important;">
                                                <div class="col-md-4">
                                                    <label for="FechaVenta" class="form-label">Fecha Venta</label>
                                                    <input type="date" pattern="\d{2}\s\w+\s\d{4}"
                                                        class="form-control" name="FechaVenta" id="FechaVenta">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="InicioVigencia" class="form-label">Inicio Vigencia</label>
                                                    <input type="date" pattern="\d{2}\s\w+\s\d{4}"
                                                        class="form-control" name="InicioVigencia" id="InicioVigencia">
                                                </div>

                                            </div>
                                            <div class="row" style="padding-top: 15px !important;">
                                                <div class="col-md-12">
                                                    <label for="Observacion" class="form-label">Observaciones o
                                                        anotaciones</label>
                                                    <textarea name="Observacion" id="Observacion" rows="3" class="form-control"></textarea>

                                                </div>
                                            </div>

                                            <div class="row" style="padding-top: 15px !important;">
                                                <input type="hidden" id="DataAseguradora" name="Aseguradoras">


                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div>
                                                        <h4>Cotizaciones de Aseguradoras</h4>
                                                        <hr>
                                                    </div>
                                                    <div class="x_title">
                                                        <div class="float-right" style="text-align: right;">
                                                            <button type="button" onclick="modal_aseguradora()"
                                                                class="btn btn-info"><i class="fa fa-plus"></i> Nueva
                                                                Cotización
                                                            </button>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="x_content">
                                                        <div class="table-responsive" id="divAseguradoras">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>



                                    </div>

                                    <div class="form-group" align="center">
                                        <button class="btn btn-success" type="submit">Guardar</button>
                                        <a href="{{ url('catalogo/negocio/') }}"><button class="btn btn-primary"
                                                type="button">Cancelar</button></a>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}"
                                id="redes" aria-labelledby="home-tab">

                                <div class="row" style="padding-top: 15px !important;">
                                    <input type="hidden" id="DataAseguradora" name="Aseguradoras">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div class="x_title">
                                            <div class="float-right" style="text-align: right;">
                                                <button type="button" onclick="modal_informacion_negocio()"
                                                    class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Registro
                                                </button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive" id="divTblInformacinoContacto">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Contacto</th>
                                                            <th scope="col">Descripción de la operación</th>
                                                            <th scope="col">Telefonos de contacto</th>
                                                            <th scope="col">Observaciones</th>
                                                            <th scope="col">Opciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaCuerpo">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('sweetalert::alert')

                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                </div>
            </div>

            @include('catalogo.negocio.modal_aseguradora')
            @include('catalogo.negocio.informacion_negocio')
            @include('catalogo.negocio.informacion_negocio_edit')



            <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
            <script type="text/javascript">
                $(".validarCredenciales").on("input", function() {
                    let Dui = $('#Dui').val().replace(/[^\d]/g, '');
                    let NitEmpresa = $('#NitEmpresa').val().replace(/[^\d]/g, '');
                    let campo = '';
                    if ($('#TipoPersona').val() !== null) {
                        if ($('#TipoPersona').val() === '1') {
                            campo = 'DUI';
                        } else {
                            campo = 'NIT Empresa';
                        }

                        if (Dui.length !== 9 && NitEmpresa.length !== 14) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Debe de rellenar el campo de ' + campo +
                                    ' antes de continuar con los demás campos',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            })
                            $('#NombreCliente').val('');
                            $('#Email').val('');
                        }
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Debe seleccionar el Tipo Persona',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        })
                        $('#NombreCliente').val('');
                        $('#Email').val('');
                    }
                });

                function borrarDatosCliente() {
                    $('#NombreCliente').val('');
                    $('#Email').val('');
                    $("#EstadoCliente").val(3).trigger("change");
                    $("#FormaPago").val('').trigger("change");
                    $("#divDui").removeClass("has-error");
                    $("#divNit").removeClass("has-error");
                    $("#MetodoPago").find("option:not(:first-child)").remove();
                    $("#MetodoPago").val(null).trigger("change"); // Clear and update the Select2 element
                    $('#helpBlockDuiNit').hide();

                }

                function identificadorCliente() {
                    $('#Dui').val('');
                    $('#NitEmpresa').val('');
                    $('#IdCliente').val('');
                    borrarDatosCliente();
                    if ($('#TipoPersona').val() == 1) {
                        $('#divDui').show();
                        $('#divNit').hide();
                    } else {
                        $('#divDui').hide();
                        $('#divNit').show();
                    }
                }

                function mostrarId() {
                    let IdCliente = $('#IdCliente').val();
                    let parametros = {
                        "IdCliente": IdCliente,
                    };
                    $.ajax({
                        type: "get",
                        url: "{{ URL::to('negocio/getCliente') }}",
                        data: parametros,
                        success: function(data) {
                            console.log(data /*data.metodo_pago[0]*/ );
                            $('#Dui').val('');
                            $('#NitEmpresa').val('');
                            $("#TipoPersona option[value='']").prop("selected", true);
                            borrarDatosCliente();
                            if (data.cliente !== null) {
                                $('#Dui').val(data.cliente.Dui);
                                $('#NitEmpresa').val(data.cliente.Nit);
                                $('#NombreCliente').val(data.cliente.Nombre);
                                $('#Email').val(data.cliente.CorreoPrincipal);
                                $("#TipoPersona option[value='" + data.cliente.TipoPersona + "']").prop("selected",
                                    true);
                                $("#EstadoCliente").val(data.cliente.Estado).trigger("change");
                                $("#FormaPago").val(data.cliente.FormaPago).trigger("change");
                                $("#divDui").addClass("has-error");
                                $("#divNit").addClass("has-error");
                                $('#helpBlockDuiNit').show();
                                if ($('#TipoPersona').val() == 1) {
                                    $('#divDui').show();
                                    $('#divNit').hide();
                                } else {
                                    $('#divDui').hide();
                                    $('#divNit').show();
                                }

                                $.each(data.metodo_pago, function(index, datos) {
                                    $("#MetodoPago").append(new Option(datos.NumeroTarjeta, datos.Id, false,
                                        false));
                                });
                                $("#MetodoPago").trigger("change"); // Trigger change event to refresh Select2
                            }
                        }
                    });



                }

                function mostrar() {
                    if ($('#TipoPersona').val() === null) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Debe seleccionar el Tipo Persona',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                            timer: 1500
                        })
                        $('#Dui').val('');
                        $('#NitEmpresa').val('');
                    } else {
                        let Dui = $('#Dui').val();
                        let Nit = $('#NitEmpresa').val();
                        let tipoPersona = $('#TipoPersona').val();
                        let parametros = {
                            "IdCliente": null,
                            "Dui": Dui,
                            "Nit": Nit,
                            "tipoPersona": tipoPersona
                        };

                        $.ajax({
                            type: "get",
                            url: "{{ URL::to('negocio/getCliente') }}",
                            data: parametros,
                            success: function(data) {
                                console.log(data /*data.metodo_pago[0]*/ );
                                $('#IdCliente').val('');
                                borrarDatosCliente();
                                if (data.cliente !== null) {
                                    $('#IdCliente').val(data.cliente.Id);
                                    $('#NombreCliente').val(data.cliente.Nombre);
                                    $('#Email').val(data.cliente.CorreoPrincipal);
                                    //este funciona sin select2//$("#EstadoCliente option[value='"+data.cliente.Estado+"']").prop("selected", true);
                                    $("#EstadoCliente").val(data.cliente.Estado).trigger("change");
                                    $("#FormaPago").val(data.cliente.FormaPago).trigger("change");
                                    $("#divDui").addClass("has-error");
                                    $("#divNit").addClass("has-error");
                                    $('#helpBlockDuiNit').show();

                                    $.each(data.metodo_pago, function(index, datos) {
                                        $("#MetodoPago").append(new Option(datos.NumeroTarjeta, datos.Id, false,
                                            false));
                                    });
                                    $("#MetodoPago").trigger("change"); // Trigger change event to refresh Select2
                                }
                            }
                        });

                    }

                }
                $(document).ready(function() {
                    $('#divNit').hide();
                    $('#helpBlockDuiNit').hide();
                    $("#EstadoCliente").val(3).trigger("change");


                    $("#NecesidadProteccion").change(function() {
                        if (document.getElementById('NecesidadProteccion').value == 10) {
                            var select = $("#TipoNecesidad");

                            // Array con las opciones
                            var opciones = [{
                                    value: '1',
                                    text: 'Individual'
                                },
                                {
                                    value: '2',
                                    text: 'Colectivo'
                                },
                                {
                                    value: '3',
                                    text: 'Familiar'
                                }
                                // Agregar más opciones si es necesario
                            ];
                        } else if (document.getElementById('NecesidadProteccion').value == 11) {
                            var select = $("#TipoNecesidad");

                            // Array con las opciones
                            var opciones = [{
                                    value: '1',
                                    text: 'Individual'
                                },
                                {
                                    value: '2',
                                    text: 'Colectivo'
                                },
                                {
                                    value: '4',
                                    text: 'Usuarios'
                                }
                                // Agregar más opciones si es necesario
                            ];
                        } else if (document.getElementById('NecesidadProteccion').value == 1 || document
                            .getElementById('NecesidadProteccion').value == 2 ||
                            document.getElementById('NecesidadProteccion').value == 5 || document.getElementById(
                                'NecesidadProteccion').value == 13) {
                            var select = $("#TipoNecesidad");

                            // Array con las opciones
                            var opciones = [{
                                    value: '1',
                                    text: 'Individual'
                                },
                                {
                                    value: '2',
                                    text: 'Colectivo'
                                }
                                // Agregar más opciones si es necesario
                            ];
                        } else {
                            var select = $("#TipoNecesidad");

                            // Array con las opciones
                            var opciones = [{
                                    value: '2',
                                    text: 'Colectivo'
                                }
                                // Agregar más opciones si es necesario
                            ];
                        }


                        // Generar el HTML con las opciones
                        var opcionesHTML = "";
                        $.each(opciones, function(index, opcion) {
                            opcionesHTML += "<option value='" + opcion.value + "'>" + opcion.text +
                                "</option>";
                        });

                        // Reemplazar el contenido del select con las opciones generadas
                        select.html(opcionesHTML);

                    })

                    $("#TipoNecesidad").change(function() {
                        if (document.getElementById('NecesidadProteccion').value == 10 && document.getElementById(
                                'TipoNecesidad').value == 3) {
                            $("#CantidadDependientes").show();
                        } else {
                            $("#CantidadDependientes").hide();
                        }
                    })

                    $("#TipoPersona").change(function() {

                        if (document.getElementById('TipoPersona').value == 2) {
                            // $('#DuiRepresentantes').show();
                            $('#Duis').hide();
                            $('#NitEmpresas').show();
                            $('#Nits').hide();
                            $('#Homolo').hide();
                            document.getElementById('Dui').removeAttribute('required');
                            // document.getElementById('DuiRepresentantes').setAttribute('required', true);
                            // document.getElementById('Nit').removeAttribute('required');
                            document.getElementById('NitEmpresa').setAttribute('required', true);
                        } else {
                            //$('#DuiRepresentantes').hide();
                            $('#Duis').show();
                            $('#NitEmpresas').hide();
                            $('#Nits').show();
                            $('#Homolo').show();
                            //document.getElementById('DuiRepresentantes').removeAttribute('required');
                            document.getElementById('Dui').setAttribute('required', true);
                            document.getElementById('NitEmpresa').removeAttribute('required');
                            //document.getElementById('Nit').setAttribute('required', true);
                        }
                    })
                    $("#Homologado").change(function() {

                        if (document.getElementById('Homologado').checked == true) {
                            $('#Nit').prop('readonly', true);
                            $("#Nit").removeAttr("data-inputmask");
                            $("#Nit").attr("data-inputmask", "{'mask': '99999999-9'}");
                            //document.getElementById('Nit').value = document.getElementById('Dui').value;

                            // document.getElementById('Nit').removeAttribute("data-mask");

                        } else {
                            document.getElementById('Nit').value = '';
                            $('#Nit').removeAttr('readonly');
                            $("#Nit").removeAttr("data-inputmask");
                            $("#Nit").attr("data-inputmask", "{'mask': '9999-999999-999-9'}");
                            //  document.getElementById('Nit').removeAttribute("data-mask");
                        }
                    })
                })

                function modal_aseguradora() {
                    if (document.getElementById('NecesidadProteccion').value == '' || document.getElementById('TipoNecesidad')
                        .value == '') {
                        ///   alert('Debe seleccionar el Tipo Persona');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Seleccione la necesidad y el plan para continuar ',
                            icon: 'info',
                            confirmButtonText: 'Aceptar',
                            timer: 3500
                        })
                    } else {
                        document.getElementById('ModalNecesidad').value = document.getElementById('NecesidadProteccion').value;
                        document.getElementById('ModalTipoNecesidad').value = document.getElementById('TipoNecesidad').value;
                        //  document.getElementById('ModalDepedientes').value = document.getElementById('CantidadDependiente').value;



                        var necesidad = document.getElementById('NecesidadProteccion').value;
                        var plan = document.getElementById('TipoNecesidad').value;
                        var dependientes = document.getElementById('CantidadDependiente').value;

                        document.getElementById('SumaAsegurada').value = 0;
                        document.getElementById('Prima').value = '';
                        document.getElementById('FechaNacimiento').value = '';
                        document.getElementById('Cantidad').value = '';
                        document.getElementById('Marca').value = '';
                        document.getElementById('Modelo').value = '';
                        document.getElementById('Axo').value = '';
                        document.getElementById('Placa').value = '';
                        document.getElementById('Direccion').value = '';
                        document.getElementById('Giro').value = '';
                        document.getElementById('ValorConstruccion').value = '';
                        document.getElementById('ValorContenido').value = '';
                        document.getElementById('Vida').value = '';
                        document.getElementById('Dental').value = '';
                        document.getElementById('CantidadPersona').value = '';
                        document.getElementById('Contributivo').value = '';
                        document.getElementById('MaximoVitalicio').value = '';
                        document.getElementById('CantidadTitulares').value = '';
                        document.getElementById('Fumador').value = '';
                        document.getElementById('InvalidezParcial').value = '';
                        document.getElementById('InvalidezTotal').value = '';
                        document.getElementById('GastosFunerario').value = '';
                        document.getElementById('EnfermedadesGrave').value = '';
                        document.getElementById('Termino').value = '';
                        document.getElementById('Ahorro').value = '';
                        document.getElementById('Plazo').value = '';
                        document.getElementById('SesionBeneficio').value = '';
                        document.getElementById('Cobertura').value = '';
                        document.getElementById('TipoCartera').value = '';
                        document.getElementById('FechaNacimientoFamiliar').value = '';
                        document.getElementById('GeneroFamiliar').value = '';
                        document.getElementById('ParentescoFamiliar').value = '';
                        document.getElementById('VidaFamiliar').value = '';
                        document.getElementById('DentalFamiliar').value = '';

                        $("#FechaNacimientos").hide();
                        $("#Generos").hide();
                        $("#Cantidads").hide();
                        $("#Marcas").hide();
                        $("#Modelos").hide();
                        $("#Axos").hide();
                        $("#Placas").hide();
                        $("#Direccions").hide();
                        $("#Giros").hide();
                        $("#ValorConstruccions").hide();
                        $("#ValorContenidos").hide();
                        $("#Vidas").hide();
                        $("#Dentals").hide();
                        $("#CantidadPersonas").hide();
                        $("#Contributivos").hide();
                        $("#MaximoVitalicios").hide();
                        $("#CantidadTitularess").hide();
                        $("#Fumadors").hide();
                        $("#InvalidezParcials").hide();
                        $("#InvalidezTotals").hide();
                        $("#GastosFunerarios").hide();
                        $("#EnfermedadesGraves").hide();
                        $("#Terminos").hide();
                        $("#Ahorros").hide();
                        $("#Plazos").hide();
                        $("#SesionBeneficios").hide();
                        $("#Coberturas").hide();
                        $("#TipoCarteras").hide();
                        $("#FechaNacimientosFamiliar").hide();
                        $("#GenerosFamiliar").hide();
                        $("#ParentescosFamiliar").hide();
                        $("#VidasFamiliar").hide();
                        $("#DentalsFamiliar").hide();
                        for (let i = 0; i < dependientes; i++) {
                            $("#FechaNacimientosFamiliar" + i).hide();
                            $("#GenerosFamiliar" + i).hide();
                        }
                        if (necesidad == 13 && plan == 1) { //accidentes e individual
                            $("#FechaNacimientos").show();
                            $("#Generos").show();
                        } else if (necesidad == 13 && plan == 2) { //accidentes  y colectivo

                            $("#Cantidads").show();
                        } else if (necesidad == 1 && plan == 1) { //autos e individual
                            $("#Marcas").show();
                            $("#Modelos").show();
                            $("#Axos").show();
                            $("#Placas").show();
                        } else if (necesidad == 1 && plan == 2) { //autos y colectivo
                            $("#Cantidads").show();
                        } else if (necesidad == 2 && plan == 1) { //incendio y individual
                            $("#Direccions").show();
                            $("#Giros").show();
                            $("#ValorConstruccions").show();
                            $("#ValorContenidos").show();
                        } else if (necesidad == 10 && plan == 1) { //gastos medicos e individual
                            $("#FechaNacimientos").show();
                            $("#Generos").show();
                            $("#Vidas").show();
                            $("#Dentals").show();
                        } else if (necesidad == 10 && plan == 2) { //gastos medicos y colectivo
                            $("#CantidadPersonas").show();
                            $("#Contributivos").show();
                            $("#MaximoVitalicios").show();
                            $("#CantidadTitularess").show();
                        } else if (necesidad == 10 && plan == 3) { //gastos medicos y familiares
                            // for (let i = 0; i < dependientes; i++) {
                            //     $("#FechaNacimientosFamiliar" + i).show();
                            //     $("#GenerosFamiliar" + i).show();
                            // }

                            $("#FechaNacimientosFamiliar").show();
                            $("#GenerosFamiliar").show();
                            $("#ParentescosFamiliar").show();
                            $("#VidasFamiliar").show();
                            $("#DentalsFamiliar").show();

                        } else if (necesidad == 11 && plan == 1) { //vida y individual
                            $("#FechaNacimientos").show();
                            $("#Generos").show();
                            $("#Fumadors").show();
                            $("#InvalidezParcials").show();
                            $("#InvalidezTotals").show();
                            $("#GastosFunerarios").show();
                            $("#EnfermedadesGraves").show();
                            $("#Terminos").show();
                            $("#Ahorros").show();
                            $("#Plazos").show();
                            $("#SesionBeneficios").show();

                        } else if (necesidad == 11 && plan == 2) { //vida y colectivo
                            $("#Coberturas").show();
                        } else if (necesidad == 11 && plan == 3) { //vida y usuarios
                            $("#Coberturas").show();
                        } else if (necesidad == 14 && plan == 2) { //vida deuda y colectivo
                            $("#Coberturas").show();
                            $("#TipoCarteras").show();
                        }
                        $('#modal_aseguradora').modal('show');
                    }

                }

                function modal_informacion_negocio() {

                    ///   alert('Debe seleccionar el Tipo Persona');
                    /*   Swal.fire({
                           title: 'Error!',
                           text: 'Seleccione la necesidad y el plan para continuar ',
                           icon: 'info',
                           confirmButtonText: 'Aceptar',
                           timer: 3500
                       })*/

                    $('#modal_informacion_negocio').modal('show');


                }
            </script>
            @include('sweetalert::alert')
        @endsection
