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
                <form action="{{ url('catalogo/negocio') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="x_content">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="TipoCarteraNr" class="form-label">Cartera</label>
                                    <select name="TipoCarteraNr" id="TipoCarteraNr" class="form-control select2" style="width: 100%" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($carteras as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="EstadoVenta" class="form-label">Estado Venta</label>
                                    <select name="EstadoVenta" id="EstadoVenta" class="form-control select2" style="width: 100%" required>
                                        <option value="" disabled selected>Seleccione...</option>
                                        @foreach ($estados_venta as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 12px!important;">
                                <div class="col-md-6">
                                    <label for="TipoPersona" class="form-label">Tipo Cliente</label>
                                    <select name="TipoPersona" id="TipoPersona" class="form-control"
                                        onchange="identificadorCliente();">
                                        <option value="" selected disabled>Seleccione ...</option>
                                        <option value="1">NATURAL</option>
                                        <option value="2">JURIDICO</option>
                                    </select>
                                </div>
                                <div id="divDui" class="col-md-6">
                                    <label for="Dui" class="form-label">DUI </label>
                                    <input type="text" name="Dui" id="Dui"
                                        value="{{ old('Dui') }}" data-inputmask="'mask': ['99999999-9']"
                                        onkeyup="mostrar();" class="form-control">
                                    <span id="helpBlockDuiNit" class="help-block">Este cliente ya
                                        existe.</span>
                                </div>

                                <div id="divNit" class="col-md-6">
                                    <label for="NitEmpresa" class="form-label">NIT Empresa </label>
                                    <input type="text" name="NitEmpresa" id="NitEmpresa"
                                        value="{{ old('NitEmpresa') }}"
                                        data-inputmask="'mask': ['9999-999999-999-9']" onkeyup="mostrar();"
                                        class="form-control" >
                                        <span id="helpBlockDuiNit2" class="help-block">Este cliente ya
                                            existe.</span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 12px!important;">

                                <div class="col-md-6">
                                    <label for="NombreCliente" class="form-label">Nombre del cliente O Prospecto</label>
                                    <input class="form-control validarCredenciales" type="text"
                                        value="" name="NombreCliente" id="NombreCliente" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                </div>
                                <div style="display: none;" class="col-md-4">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="email" class="form-control validarCredenciales" name="Email" id="Email">
                                </div>
                                <div style="display: none;" class="col-md-4">
                                    <label for="EstadoCliente" class="form-label">Estado Cliente</label>
                                    <select disabled name="EstadoCliente" id="EstadoCliente" class="form-control select2">
                                        <option value="" selected disabled> Seleccione...</option>
                                        @foreach ($cliente_estado as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                              <!--  <div class="col-md-4">
                                    <label for="MetodoPago" class="form-label">Método de
                                        pago</label>
                                    <select name="MetodoPago" id="MetodoPago"
                                        class="form-control select2" style="width: 100%" required>
                                        <option value="" selected disabled>Seleccione...</option>

                                    </select>
                                </div>-->
                                <div class="col-md-6">
                                    <label for="NecesidadProteccion" class="form-label">Ramo</label>
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
                            <div class="row" style="margin-top: 12px!important;">
                                <div class="col-md-6">
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
                                <div class="col-md-6">
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
                            <div class="row" style="margin-top: 12px!important;">
                                <div class="col-md-6">
                                    <label for="FechaVenta" class="form-label">Fecha Venta</label>
                                    <input type="date" pattern="\d{2}\s\w+\s\d{4}"
                                        class="form-control" name="FechaVenta" id="FechaVenta">
                                </div>
                                <div class="col-md-6">
                                    {{-- NO SE LE AGREGO EL ONINPUT UPPER - NO SE SI TENDRA NUMEROS O TEXTO O COMBINADOS --}}
                                    <label for="NumeroPoliza" class="form-label">Número de póliza</label>
                                    <input class="form-control" type="text" value=""
                                        name="NumeroPoliza" id="NumeroPoliza" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 12px!important;">
                                <div class="col-md-6">
                                    <label for="InicioVigencia" class="form-label">Fecha inicio Vigencia</label>
                                    <input type="date" pattern="\d{2}\s\w+\s\d{4}"
                                        class="form-control" name="InicioVigencia" id="InicioVigencia">
                                </div>
                                <div class="col-md-6">
                                    <label for="FormaPago" class="form-label">Periodo de Pago (Forma de
                                        Pago)</label>
                                    <select name="FormaPago" id="FormaPago" class="form-control select2"
                                        style="width: 100%" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        <option value="1">ANUAL</option>
                                        <option value="2">SEMESTRAL</option>
                                        <option value="3">TRIMESTRAL</option>
                                        <option value="4">MENSUAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 12px!important;">
                                <div class="col-md-6">
                                    <label for="DepartamentoNr" class="form-label">Departamento que
                                        atenderá</label>
                                    <select name="DepartamentoNr" id="DepartamentoNr"
                                        class="form-control select2" style="width: 100%" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($departamentosnr as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="NumCoutas" class="form-label">Cuotas</label>
                                    <input class="form-control" type="number" step="1" name="NumCoutas" id="NumCoutas">
                                </div>
                            </div>
                            <div class="row" style="padding-top: 15px !important;">
                                <div class="col-md-12">
                                    <label for="Observacion" class="form-label">Observaciones o
                                        anotaciones</label>
                                    <textarea name="Observacion" id="Observacion" rows="3" class="form-control" oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"></textarea>

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
            @include('sweetalert::alert')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                                title: 'Advertencia',
                                text: 'Debe de rellenar el campo de ' + campo +
                                    ' antes de continuar con los demás campos',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            })
                            $('#NombreCliente').val('');
                            $('#Email').val('');
                        }
                    } else {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'Debe seleccionar el Tipo Persona',
                            icon: 'warning',
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
                    //$("#FormaPago").val('').trigger("change");
                    $("#divDui").removeClass("has-error");
                    $("#divNit").removeClass("has-error");
                    //$("#MetodoPago").find("option:not(:first-child)").remove();
                    //$("#MetodoPago").val(null).trigger("change"); // Clear and update the Select2 element
                    $('#helpBlockDuiNit').hide();
                    $('#helpBlockDuiNit2').hide();


                }

                function identificadorCliente() {
                    $('#Dui').val('');
                    $('#NitEmpresa').val('');
                    $('#IdCliente').val('');
                    borrarDatosCliente();
                    if ($('#TipoPersona').val() == 1) {
                        $('#divDui').show();
                        $('#divNit').hide();
                        $('#Dui').prop('required', true);
                        $('#NitEmpresa').removeAttr('required');

                    } else {
                        $('#divDui').hide();
                        $('#divNit').show();
                        $('#Dui').removeAttr('required');
                        $('#NitEmpresa').prop('required', true);

                    }
                }

                function mostrar() {
                    if ($('#TipoPersona').val() === null) {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'Debe seleccionar el Tipo Persona',
                            icon: 'warning',
                            confirmButtonText: 'Aceptar',
                            timer: 3500
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
                                    if (data.cliente.Estado===2) {
                                        $("#EstadoCliente").val(3).trigger("change");
                                    }else{
                                        $("#EstadoCliente").val(data.cliente.Estado).trigger("change");
                                    }

                                    //$("#FormaPago").val(data.cliente.FormaPago).trigger("change");
                                    $("#divDui").addClass("has-error");
                                    $("#divNit").addClass("has-error");
                                    $('#helpBlockDuiNit').show();
                                    $('#helpBlockDuiNit2').show();

                                  /*  $.each(data.metodo_pago, function(index, datos) {
                                        $("#MetodoPago").append(new Option(datos.NumeroTarjeta, datos.Id, false,
                                            false));
                                    });
                                    $("#MetodoPago").trigger("change"); // Trigger change event to refresh Select2 */
                                }
                            }
                        });

                    }

                }
                $(document).ready(function() {
                    $("#opcionNegocio").addClass("current-page");
                    $("#botonMenuNegocio").addClass("active");
                    $("#menuNegocio").css("display", "block");

                    $('#divNit').hide();
                    $('#helpBlockDuiNit').hide();
                    $('#helpBlockDuiNit2').hide();
                    $("#EstadoCliente").val(3).trigger("change");
                    identificadorCliente();
                })

            </script>
            @include('sweetalert::alert')
        @endsection
