@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Modificar Negocio <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('catalogo/negocio')}}" class="btn btn-info fa fa-undo " style="color: white"> Atrás</a>
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
                    <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#negocio" id="negocio-tab" role="tab" data-toggle="tab" aria-expanded="true">Negocio</a>

                    </li>
                    <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#informacion_negocio" role="tab" id="informacion_negocio-tab" data-toggle="tab" aria-expanded="false">Información del Negocio</a>
                    </li>

                    <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#archivos" role="tab" id="archivos-tab" data-toggle="tab" aria-expanded="false">Archivos</a>
                    </li>

                    <li role="presentation" class="{{ session('tab1') == 4 ? 'active' : '' }}"><a href="#gestiones" role="tab" id="gestiones-tab" data-toggle="tab" aria-expanded="false">Gestiones</a>
                    </li>

                </ul>


                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} " id="negocio" aria-labelledby="home-tab">

                        <form method="POST" action="{{ route('negocio.update', $negocio->Id) }}">
                            @method('PUT')
                            @csrf

                            <div class="x_content">
                                <br />
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="TipoCarteraNr" class="form-label">Cartera</label>
                                            <select name="TipoCarteraNr" id="TipoCarteraNr" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($carteras as $obj)
                                                    <option value="{{ $obj->Id }}" {{ $negocio->TipoCarteraNr == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="EstadoVenta" class="form-label">Estado Venta</label>
                                            <select name="EstadoVenta" id="EstadoVenta"
                                                class="form-control select2" style="width: 100%" required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($estados_venta as $obj)
                                                    <option value="{{ $obj->Id }}" {{ $negocio->EstadoVenta == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="NombreCliente" class="form-label">Nombre del cliente O Prospecto</label>
                                            <input class="form-control validarCredenciales" type="text"
                                            value="{{$negocio->clientes->Nombre}}" name="NombreCliente" id="NombreCliente">
                                        </div>
                                        <div id="divDui" class="col-md-6">
                                            <label for="Dui" class="form-label">DUI </label>
                                            <input type="text" name="Dui" id="Dui"
                                            value="{{$negocio->clientes->Dui}}" data-inputmask="'mask': ['99999999-9']"
                                                onkeyup="mostrar();" class="form-control">
                                            <span id="helpBlockDuiNit" class="help-block">Este cliente ya
                                                existe.</span>
                                        </div>

                                        <div id="divNit" class="col-md-6">
                                            <label for="NitEmpresa" class="form-label">NIT Empresa </label>
                                            <input type="text" name="NitEmpresa" id="NitEmpresa"
                                            value="{{$negocio->clientes->Nit}}"
                                                data-inputmask="'mask': ['9999-999999-999-9']" onkeyup="mostrar();"
                                                class="form-control" >
                                                <span id="helpBlockDuiNit2" class="help-block">Este cliente ya
                                                    existe.</span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="TipoPersona" class="form-label">Tipo Cliente</label>
                                            <select name="TipoPersona" id="TipoPersona" class="form-control"
                                                onchange="identificadorCliente();">
                                                <option value="" selected disabled>Seleccione ...</option>
                                                <option value="1" {{ $negocio->clientes->TipoPersona == 1 ? 'selected' : '' }}>Natural</option>
                                                <option value="2" {{ $negocio->clientes->TipoPersona == 2 ? 'selected' : '' }}>Juridico</option>
                                            </select>
                                        </div>
                                        <div style="display: none;" class="col-md-4">
                                            <label for="Email" class="form-label">Email</label>
                                            <input type="email" class="form-control validarCredenciales" name="Email" id="Email" value="{{$negocio->clientes->CorreoPrincipal}}">
                                        </div>
                                        <div style="display: none;" class="col-md-4">
                                            <label for="EstadoCliente" class="form-label">Estado Cliente</label>
                                            <select disabled name="EstadoCliente" id="EstadoCliente" class="form-control select2">
                                                <option value="" selected disabled> Seleccione...</option>
                                                @foreach ($cliente_estado as $obj)
                                                    <option value="{{ $obj->Id }}" {{ $negocio->clientes->Estado == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
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
                                                    <option value="{{ $obj->Id }}" {{ $negocio->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
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
                                                    <option value="{{ $obj->Id }}" {{ $negocio->Ejecutivo == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
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
                                                    <option value="{{ $obj->Id }}" {{ $negocio->TipoNegocio == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="FechaVenta" class="form-label">Fecha Venta</label>
                                            <input type="date" pattern="\d{2}\s\w+\s\d{4}"
                                                class="form-control" name="FechaVenta" id="FechaVenta"  value="{{$negocio->FechaVenta}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="NumeroPoliza" class="form-label">Número de póliza</label>
                                            <input class="form-control" type="text" value="{{$negocio->NumeroPoliza}}"
                                                name="NumeroPoliza" id="NumeroPoliza">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-6">
                                            <label for="InicioVigencia" class="form-label">Fecha inicio Vigencia</label>
                                            <input type="date" pattern="\d{2}\s\w+\s\d{4}"
                                                class="form-control" name="InicioVigencia" id="InicioVigencia" value="{{$negocio->InicioVigencia}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="FormaPago" class="form-label">Periodo de Pago (Forma de
                                                Pago)</label>
                                            <select name="FormaPago" id="FormaPago" class="form-control select2"
                                                style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                <option value="1"  {{ $negocio->PeriodoPago == 1 ? 'selected' : '' }}>Anual</option>
                                                <option value="2"  {{ $negocio->PeriodoPago == 2 ? 'selected' : '' }}>Semestral</option>
                                                <option value="3"  {{ $negocio->PeriodoPago == 3 ? 'selected' : '' }}>Trimestral</option>
                                                <option value="4"  {{ $negocio->PeriodoPago == 4 ? 'selected' : '' }}>Mensual</option>
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
                                                    <option value="{{ $obj->Id }}" {{ $negocio->DepartamentoNr == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="NumCoutas" class="form-label">Cuotas</label>
                                            <input class="form-control" type="number" step="1" name="NumCoutas" id="NumCoutas" value="{{$negocio->NumCoutas}}">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-12">
                                            <label for="Observacion" class="form-label">Observaciones o
                                                anotaciones</label>
                                            <textarea name="Observacion" id="Observacion" rows="3" class="form-control"> {{$negocio->Observacion}}</textarea>

                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Modificar</button>
                                <a href="{{ url('catalogo/negocio/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                            </div>

                        </form>
                        <div>
                            <h4>Cotizaciones</h4>
                            <hr>
                        </div>
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-cotizacion"><i class="fa fa-plus fa-lg"></i>
                                Nueva Cotización</button>
                        </div>
                        @if ($cotizaciones->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Producto</th>
                                    <th>Plan</th>
                                    <th>Suma Asegurada</th>
                                    <th>Prima Neta Anual</th>
                                    <th>Aceptado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cotizaciones as $obj)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $obj->planes->productos->Nombre }}</td>
                                    <td>{{ $obj->planes->Nombre }}</td>
                                    <td>{{ $obj->SumaAsegurada }}</td>
                                    <td>{{ $obj->PrimaNetaAnual }}</td>
                                    <td>{{ $obj->Aceptado }}</td>
                                    <td>


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

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="informacion_negocio" aria-labelledby="home-tab">

                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}" id="archivos" aria-labelledby="home-tab">

                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 4 ? 'active in' : '' }}" id="gestiones" aria-labelledby="home-tab">

                    </div>

                </div>



            </div>
        </div>

    </div>

    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
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
        } else {
            $('#divDui').hide();
            $('#divNit').show();
        }
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
        $('#divNit').hide();
        $('#helpBlockDuiNit').hide();
        $('#helpBlockDuiNit2').hide();
        $("#EstadoCliente").val(3).trigger("change");

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

    })

</script>
</div>
@include('sweetalert::alert')

@endsection
