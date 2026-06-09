@extends ('welcome')
@section('contenido')
@can('negocio create')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

<style>
    .negocio-section {
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 16px;
        margin-bottom: 16px;
        background: #fff;
    }
    .negocio-section h4 {
        margin: 0 0 14px 0;
        font-weight: 600;
        color: #2A3F54;
    }
    .negocio-actions {
        border-top: 1px solid #e5e7eb;
        padding-top: 16px;
        text-align: right;
    }
    .help-block {
        display: none;
    }
</style>

<div class="x_panel">
    <div class="x_title">
        <h2>Nuevo Negocio</h2>
        <ul class="nav navbar-right panel_toolbox">
            <a href="{{ url('catalogo/negocio') }}" class="btn btn-info" style="color: white">
                <i class="fa fa-arrow-left"></i> Atrás
            </a>
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
        <input type="hidden" name="IdCliente" id="IdCliente">

        <div class="negocio-section">
            <h4>Cliente o prospecto</h4>
            <div class="row">
                <div class="col-md-4">
                    <label for="TipoPersona" class="form-label">Tipo cliente</label>
                    <select name="TipoPersona" id="TipoPersona" class="form-control" required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="1">NATURAL</option>
                        <option value="2">JURIDICO</option>
                    </select>
                </div>
                <div id="divDui" class="col-md-4">
                    <label for="Dui" class="form-label">DUI</label>
                    <input type="text" name="Dui" id="Dui" value="{{ old('Dui') }}"
                        data-inputmask="'mask': ['99999999-9']" class="form-control">
                    <span id="helpBlockDuiNit" class="help-block">Este cliente ya existe.</span>
                </div>
                <div id="divNit" class="col-md-4">
                    <label for="NitEmpresa" class="form-label">NIT empresa</label>
                    <input type="text" name="NitEmpresa" id="NitEmpresa" value="{{ old('NitEmpresa') }}"
                        data-inputmask="'mask': ['9999-999999-999-9']" class="form-control">
                    <span id="helpBlockDuiNit2" class="help-block">Este cliente ya existe.</span>
                </div>
                <div class="col-md-4">
                    <label for="NombreCliente" class="form-label">Nombre del cliente o prospecto</label>
                    <input class="form-control validarCredenciales" type="text" name="NombreCliente"
                        id="NombreCliente"
                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                        required>
                </div>
            </div>

            <div class="row" style="margin-top: 12px;">
                <div style="display: none;" class="col-md-4">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" class="form-control validarCredenciales" name="Email" id="Email">
                </div>
                <div style="display: none;" class="col-md-4">
                    <label for="EstadoCliente" class="form-label">Estado cliente</label>
                    <select disabled name="EstadoCliente" id="EstadoCliente" class="form-control select2">
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($cliente_estado as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="negocio-section">
            <h4>Datos comerciales</h4>
            <div class="row">
                <div class="col-md-4">
                    <label for="TipoCarteraNr" class="form-label">Cartera</label>
                    <select name="TipoCarteraNr" id="TipoCarteraNr" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($carteras as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="EstadoVenta" class="form-label">Estado venta</label>
                    <select name="EstadoVenta" id="EstadoVenta" class="form-control select2" style="width: 100%" required>
                        <option value="" disabled selected>Seleccione...</option>
                        @foreach ($estados_venta as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="NecesidadProteccion" class="form-label">Ramo</label>
                    <select name="NecesidadProteccion" id="NecesidadProteccion" class="form-control select2" style="width: 100%;" required>
                        <option value="">Seleccione...</option>
                        @foreach ($necesidad_proteccion as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" style="margin-top: 12px;">
                <div class="col-md-4">
                    <label for="Ejecutivo" class="form-label">Ejecutivo</label>
                    <select name="Ejecutivo" id="Ejecutivo" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($ejecutivos as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="TipoNegocio" class="form-label">Tipo de negocio</label>
                    <select name="TipoNegocio" id="TipoNegocio" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($tipos_negocio as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="DepartamentoNr" class="form-label">Departamento que atendera</label>
                    <select name="DepartamentoNr" id="DepartamentoNr" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        @foreach ($departamentosnr as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="negocio-section">
            <h4>Vigencia y pago</h4>
            <div class="row">
                <div class="col-md-3">
                    <label for="FechaVenta" class="form-label">Fecha venta</label>
                    <input type="date" class="form-control" name="FechaVenta" id="FechaVenta">
                </div>
                <div class="col-md-3">
                    <label for="InicioVigencia" class="form-label">Inicio vigencia</label>
                    <input type="date" class="form-control" name="InicioVigencia" id="InicioVigencia">
                </div>
                <div class="col-md-3">
                    <label for="FormaPago" class="form-label">Periodo de pago</label>
                    <select name="FormaPago" id="FormaPago" class="form-control select2" style="width: 100%" required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="1">ANUAL</option>
                        <option value="2">SEMESTRAL</option>
                        <option value="3">TRIMESTRAL</option>
                        <option value="4">MENSUAL</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="NumCoutas" class="form-label">Cuotas</label>
                    <input class="form-control" type="number" step="1" min="0" name="NumCoutas" id="NumCoutas">
                </div>
            </div>

            <div class="row" style="margin-top: 12px;">
                <div class="col-md-4">
                    <label for="NumeroPoliza" class="form-label">Numero de poliza</label>
                    <input class="form-control" type="text" name="NumeroPoliza" id="NumeroPoliza"
                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                </div>
                <div class="col-md-8">
                    <label for="Observacion" class="form-label">Observaciones</label>
                    <textarea name="Observacion" id="Observacion" rows="2" class="form-control"
                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"></textarea>
                </div>
            </div>
        </div>

        <div class="negocio-actions">
            <a href="{{ url('catalogo/negocio/') }}" class="btn btn-primary">
                <i class="fa fa-times"></i> Cancelar
            </a>
            <button class="btn btn-success" type="submit">
                <i class="fa fa-floppy-o"></i> Guardar y continuar
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    function borrarDatosCliente() {
        $('#NombreCliente').val('');
        $('#Email').val('');
        $("#EstadoCliente").val(3).trigger("change");
        $("#divDui").removeClass("has-error");
        $("#divNit").removeClass("has-error");
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
                text: 'Debe seleccionar el tipo de cliente',
                icon: 'warning',
                confirmButtonText: 'Aceptar',
                timer: 3500
            });
            $('#Dui').val('');
            $('#NitEmpresa').val('');
            return;
        }

        let parametros = {
            "IdCliente": null,
            "Dui": $('#Dui').val(),
            "Nit": $('#NitEmpresa').val(),
            "tipoPersona": $('#TipoPersona').val()
        };

        $.ajax({
            type: "get",
            url: "{{ URL::to('negocio/getCliente') }}",
            data: parametros,
            success: function(data) {
                $('#IdCliente').val('');
                borrarDatosCliente();
                if (data.cliente !== null) {
                    $('#IdCliente').val(data.cliente.Id);
                    $('#NombreCliente').val(data.cliente.Nombre);
                    $('#Email').val(data.cliente.CorreoPrincipal);
                    $("#EstadoCliente").val(data.cliente.Estado === 2 ? 3 : data.cliente.Estado).trigger("change");
                    $("#divDui").addClass("has-error");
                    $("#divNit").addClass("has-error");
                    $('#helpBlockDuiNit').show();
                    $('#helpBlockDuiNit2').show();
                }
            }
        });
    }

    $(".validarCredenciales").on("input", function() {
        let dui = $('#Dui').val().replace(/[^\d]/g, '');
        let nit = $('#NitEmpresa').val().replace(/[^\d]/g, '');
        let tipoPersona = $('#TipoPersona').val();

        if (!tipoPersona) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Debe seleccionar el tipo de cliente',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            $('#NombreCliente').val('');
            $('#Email').val('');
            return;
        }

        if (dui.length !== 9 && nit.length !== 14) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Debe rellenar el documento antes de continuar',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            $('#NombreCliente').val('');
            $('#Email').val('');
        }
    });

    $(document).ready(function() {
        $("#opcionNegocio").addClass("current-page");
        $("#botonMenuNegocio").addClass("active");
        $("#menuNegocio").css("display", "block");

        $('#divNit').hide();
        $('#helpBlockDuiNit').hide();
        $('#helpBlockDuiNit2').hide();
        $("#EstadoCliente").val(3).trigger("change");

        $('#TipoPersona').on('change', identificadorCliente);
        $('#Dui, #NitEmpresa').on('keyup', mostrar);
    });
</script>
@else
    <p class="text-center text-danger">No tiene permiso para crear.</p>
@endcan
@endsection
