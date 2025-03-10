@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .table-simulated {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        border: 1px solid #dee2e6;
        border-radius: 5px;
        overflow: hidden;
    }

    .table-header {
        display: contents;
        background-color: #343a40 !important;
        color: rgb(122, 122, 122);
        font-weight: bold;
    }

    .table-header div {
        padding: 10px;
        border-bottom: 2px solid #dee2e6;
        text-align: center;
    }

    .table-row {
        display: contents;
        border-bottom: 1px solid #dee2e6;
    }

    .table-row div {
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
        text-align: center;
    }

    .table-row:nth-child(even) {
        background-color: #f8f9fa;
    }
</style>
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="x_title">
                <h2>Pólizas / Deuda / Póliza de deuda / Renovar póliza<small></small>
                </h2>

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
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="{{ session('tab') == 1 ? 'active' : '' }}"><a href="#tab_content1"
                            id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                    </li>
                    <li role="presentation" class=" "><a onclick="noGuardardo();">Tasa diferencia</a>
                    </li>
                    <li role="presentation" class=""><a onclick="noGuardardo();">Requisitos Minimos de
                            Asegurabilidad </a>
                    </li>


                </ul>

                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in"
                        id="tab_content1" aria-labelledby="home-tab">
                        <form action="{{ url('polizas/deuda/renovar') }}" method="POST">
                            @csrf
                            <input type="hidden" id="Id" name="Id" value="{{$deuda->Id}}">
                            <div class="x_content" style="font-size: 12px;">
                                <div class="col-sm-12 row">
                                    <div class="col-sm-4">
                                        <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                                        <label class="control-label" align="right">Número de Póliza *</label>
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                            value="{{ $deuda->NumeroPoliza }}" readonly>
                                    </div>

                                    <div class="col-sm-4">&nbsp;</div>

                                    <div class="col-sm-4" style="display: none !important;">
                                        <label class="control-label" align="right">Código *</label>
                                        <input class="form-control" name="Codigo" type="text"
                                            value="{{ $deuda->Codigo }}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" align="right">Aseguradora *</label>
                                    <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="">Seleccione...</option>
                                        @foreach ($aseguradora as $obj)
                                        @if ($obj->Id == $deuda->Aseguradora)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Productos *</label>
                                    <select name="Productos" id="Productos" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $deuda->planes && $obj->Id == $deuda->planes->Producto ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Planes *</label>
                                    <select name="Planes" id="Planes" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($planes as $obj)
                                        @if ($obj->Id == $deuda->Plan)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <label class="control-label" align="right">Asegurado *</label>
                                    <select name="Asegurado" id="Asegurado" class="form-control select2"
                                        style="width: 100%" disabled>
                                        <option value="">Seleccione...</option>
                                        @foreach ($cliente as $obj)
                                        @if ($obj->Id == $deuda->Asegurado)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">DUI / NIT *</label>
                                    <input class="form-control" name="Nit" id="Nit" type="text"
                                        value="{{ $deuda->Nit }}" readonly>
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Desde</label>
                                    <input class="form-control" type="date"
                                        value="{{ $deuda->VigenciaDesde }}" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Hasta</label>
                                    <input class="form-control" type="date"
                                        value="{{ $deuda->VigenciaHasta }}" readonly>
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Desde de Renovación *</label>
                                    <input class="form-control" name="VigenciaDesde" type="date"
                                        value="{{ $deuda->VigenciaHasta }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Vigencia Hasta de Renovación *</label>
                                    <input class="form-control" name="VigenciaHasta" type="date"
                                        value="{{ $fechaHastaRenovacion }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Estado *</label>
                                    <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
                                        @foreach ($estadoPoliza as $obj)
                                        @if ($obj->Id == 2)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Ejecutivo *</label>
                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%"
                                        required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($ejecutivo as $obj)
                                        @if ($obj->Id == $deuda->Ejecutivo)
                                        <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                        </option>
                                        @else
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Descuento de Rentabilidad *</label>
                                    <input class="form-control" name="Descuento" type="number" step="any"
                                        id="Descuento" value="{{ $deuda->Descuento }}" required>
                                </div>
                                <div class="col-sm-4">
                                    &nbsp;
                                </div>
                                <div class="col-md-12">
                                    &nbsp;
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Edad Máxima Terminación *</label>
                                    <input type="number" name="EdadMaximaTerminacion" class="form-control" required
                                        value="{{ $deuda->EdadMaximaTerminacion }}">
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Responsabilidad Máxima *</label>
                                    <div class=" form-group has-feedback">
                                        <input type="number" step="any" name="ResponsabilidadMaxima"
                                            id="ResponsabilidadMaxima" style="padding-left: 15%;display: none;"
                                            value="{{ $deuda->ResponsabilidadMaxima }}" class="form-control" required
                                            onblur="ResponsabilidadMax(this.value)">
                                        <input type="text" step="any"
                                            style="padding-left: 15%; display: block;" id="ResponsabilidadMaximaTexto"
                                            value="{{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }}"
                                            class="form-control" required
                                            onfocus="ResponsabilidadMaxTexto(this.value)">
                                        <span class="fa fa-dollar form-control-feedback left"
                                            aria-hidden="true"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    &nbsp;
                                </div>
                                <div class="col-md-12">
                                    &nbsp;
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label " align="right">Clausulas Especiales </label>
                                    <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4">{{ $deuda->ClausulasEspeciales }} </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Beneficios Adicionales</label>
                                    <textarea class="form-control" name="Beneficios" row="3" col="4">{{ $deuda->Beneficios }} </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Concepto</label>
                                    <textarea class="form-control" name="Concepto" row="3" col="4">{{ $deuda->Concepto }}</textarea>
                                </div>
                                <div class="col-sm-4 ocultar" style="display: none !important;">
                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                            {{ $deuda->Mensual == 1 ? 'checked' : '' }}>
                                        <label class="control-label">Tasa Millar Mensual *</label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="Anual" value="0"
                                            {{ $deuda->Mensual == 0 ? 'checked' : '' }}>
                                        <label class="control-label">Tasa ‰ Millar Anual *</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Tasa Millar Mensual *</label>
                                    <input class="form-control" name="Tasa" type="number" id="Tasa"
                                        step="any" value="{{ $deuda->Tasa }}" required>
                                </div>
                                <div class="col-sm-4" align="center">
                                    <br>
                                    <label class="control-label" align="center">Vida</label>
                                    <input id="Vida" type="checkbox" class="js-switch"
                                        {{ $deuda->Vida != '' ? 'checked' : '' }} />
                                </div>
                                <div class="col-sm-4" align="center">
                                    <br>
                                    <label class="control-label" align="center">Desempleo</label>
                                    <input id="Desempleo" type="checkbox" class="js-switch"
                                        {{ $deuda->Desempleo != '' ? 'checked' : '' }} />
                                </div>
                                <div class="col-sm-12">
                                    &nbsp;
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" align="right">% de Comisión *</label>
                                    <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                        step="any" value="{{ $deuda->TasaComision }}">
                                </div>
                                <div class="col-sm-2"><br>
                                    <label class="control-label" align="right">¿IVA incluído?</label>
                                    <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                                        {{ $deuda->ComisionIva == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="col-sm-4">
                                    <div id="poliza_vida"
                                        style="display: {{ $deuda->Vida != '' ? 'block' : 'none' }};">
                                        <label class="control-label">Numero de Poliza Vida *</label>
                                        <input name="Vida" type="text" class="form-control"
                                            value="{{ $deuda->Vida }}" />
                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <div id="poliza_desempleo"
                                        style="display:  {{ $deuda->Desempleo != '' ? 'block' : 'none' }};">
                                        <label class="control-label">Número de Póliza Desempleo *</label>
                                        <input name="Desempleo" type="text" class="form-control"
                                            value="{{ $deuda->Desempleo }}" />
                                    </div>

                                </div>

                            </div>


                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>

                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success">Guardar y Continuar</button>
                                    <a href="{{ url('polizas/deuda') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-credito-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-tipo="1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('polizas/deuda/agregar_credito') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Nueva linea de credito</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label ">Línea de Crédito</label>
                            <input class="form-control" type="hidden" name="Deuda" value="{{$deuda->Id}}">
                            <select name="TipoCartera" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach ($tipoCartera as $obj)
                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Saldos y Montos</label>
                            <select name="Saldos" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach ($saldos as $obj)
                                <option value="{{ $obj->Id }}">
                                    {{ $obj->Abreviatura }} -
                                    {{ $obj->Descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Monto Máximo</label>
                            <input class="form-control" type="number" min="1.00" step="any"
                                name="MontoMaximoIndividual">
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






@include('sweetalert::alert')


<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    function noGuardardo() {
        Swal.fire('Renovación', 'Debe guardar los datos iniciales de la poliza');
    }

    function formatearCantidad(cantidad) {
        let numero = Number(cantidad);
        return numero.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function ResponsabilidadMax(id) {
        document.getElementById('ResponsabilidadMaximaTexto').value = formatearCantidad(id);
        $("#ResponsabilidadMaxima").hide();
        $("#ResponsabilidadMaximaTexto").show();
    }

    function ResponsabilidadMaxTexto(id) {
        // document.getElementById('ResponsabilidadMaxima').value = document.getElementById('ResponsabilidadMaximaTexto');
        $("#ResponsabilidadMaxima").show();
        $("#ResponsabilidadMaximaTexto").hide();
    }

    function MontoMaxIndividual(id) {
        document.getElementById('MontoMaximoIndividualTexto').value = formatearCantidad(id);
        $("#MontoMaximoIndividual").hide();
        $("#MontoMaximoIndividualTexto").show();
    }

    function MontoMaxIndividualTexto(id) {
        // document.getElementById('MontoMaximoIndividual').value = document.getElementById('MontoMaximoIndividualTexto');
        $("#MontoMaximoIndividual").show();
        $("#MontoMaximoIndividualTexto").hide();
    }

    function add_rango() {
        $("#modal_rango").modal('show');
    }

    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }

    function modal_requisitos() {
        $('#modal_requisitos').modal('show');
    }

    function modal_creditos() {
        $('#modal_creditos').modal('show');
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {

        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-deuda");

        $("#fechas").change(function() {
            if (document.getElementById('fechas').checked == true) {
                $('#fecha_otorgamiento').show();
                $("#FechaDesde").prop('required', true);
                $("#FechaHasta").prop('required', true);
                $("#TasaFecha").prop('required', true);


            } else {
                $('#fecha_otorgamiento').hide();
                $("#FechaDesde").removeAttr('required');
                $("#FechaHasta").removeAttr('required');
                $("#TasaFecha").removeAttr('required');
            }
        })

        $("#montos").change(function() {
            if (document.getElementById('montos').checked == true) {
                $('#monto_otorgamiento').show();
                $("#MontoDesde").prop('required', true);
                $("#MontoHasta").prop('required', true);
                $("#TasaMonto").prop('required', true);

            } else {
                $('#monto_otorgamiento').hide();
                $("#MontoDesde").removeAttr('required');
                $("#MontoHasta").removeAttr('required');
                $("#TasaMonto").removeAttr('required');
            }
        })
        $("#edad").change(function() {
            if (document.getElementById('edad').checked == true) {
                $('#edad_otorgamiento').show();
                $("#EdadDesde").prop('required', true);
                $("#EdadHasta").prop('required', true);
                $("#TasaEdad").prop('required', true);

            } else {
                $('#edad_otorgamiento').hide();
                $("#EdadDesde").removeAttr('required');
                $("#EdadHasta").removeAttr('required');
                $("#TasaEdad").removeAttr('required');
            }
        })

        $("#montos").change(function() {
            if (document.getElementById('montos').checked == true) {
                $('#monto_otorgamiento').show();

            } else {
                $('#monto_otorgamiento').hide();
            }
        })


        $("#Asegurado").change(function() {
            // alert(document.getElementById('Asegurado').value);
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            var parametros = {
                "Cliente": document.getElementById('Asegurado').value
            };
            $.ajax({
                type: "get",
                //ruta para obtener el horario del doctor
                url: "{{ url('get_cliente') }}",
                data: parametros,
                success: function(data) {
                    console.log(data);
                    document.getElementById('Nit').value = data.Nit;
                    if (data.TipoContribuyente == 1) {
                        document.getElementById('Retencion').setAttribute("readonly", true);
                        document.getElementById('Retencion').value = 0;

                    }


                }
            });
        });
        $("#EdadInicial2").prop("disabled", true);
        $("#EdadFinal2").prop("disabled", true);
        $("#MontoInicial2").prop("disabled", true);
        $("#MontoFinal2").prop("disabled", true);

        $("#EdadInicial3").prop("disabled", true);
        $("#EdadFinal3").prop("disabled", true);
        $("#MontoInicial3").prop("disabled", true);
        $("#MontoFinal3").prop("disabled", true);
        $("#Vida").change(function() {
            if (document.getElementById('Vida').checked == true) {
                $('#poliza_vida').show();
            } else {
                $('#poliza_vida').hide();
            }
        })

        $("#Desempleo").change(function() {
            if (document.getElementById('Desempleo').checked == true) {
                $('#poliza_desempleo').show();
            } else {
                $('#poliza_desempleo').hide();
            }
        })




        $("#Aseguradora").change(function() {
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            // var para la Departamento
            var Aseguradora = $(this).val();

            //funcionpara las distritos
            $.get("{{ url('get_producto') }}" + '/' + Aseguradora, function(data) {
                //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                console.log(data);
                var _select = '<option value=""> Seleccione </option>';
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                    '</option>';
                $("#Productos").html(_select);
            });
        })

        $("#Productos").change(function() {
            $('#response').html('<div><img src="../../../public/img/ajax-loader.gif"/></div>');
            // var para la Departamento
            var Productos = $(this).val();

            //funcionpara las distritos
            $.get("{{ url('get_plan') }}" + '/' + Productos, function(data) {
                //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                console.log(data);
                var _select = '<option value=""> Seleccione </option>';
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                    '</option>';
                $("#Planes").html(_select);
            });
        })
    });

    $("#Activar1").change(function() {
        if (document.getElementById('Activar1').checked == true && document.getElementById('EdadFinal').value ==
            "" && document.getElementById('MontoFinal').value == "") {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar los datos anteriores',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            });
            document.getElementById('Activar1').checked = false;
        } else {
            document.getElementById('EdadInicial2').value = parseInt(document.getElementById('EdadFinal')
                .value) + 1;
            document.getElementById('MontoInicial2').value = parseFloat(document.getElementById('MontoFinal')
                .value) + 0.01;
            if (document.getElementById('Activar1').checked == true) {
                $("#EdadInicial2").prop("disabled", false);
                $("#EdadFinal2").prop("disabled", false);
                $("#MontoInicial2").prop("disabled", false);
                $("#MontoFinal2").prop("disabled", false);
            } else {
                $("#EdadInicial2").prop("disabled", true);
                $("#EdadFinal2").prop("disabled", true);
                $("#MontoInicial2").prop("disabled", true);
                $("#MontoFinal2").prop("disabled", true);

                document.getElementById('EdadInicial2').value = "";
                document.getElementById('EdadFinal2').value = "";
                document.getElementById('MontoInicial2').value = "";
                document.getElementById('MontoFinal2').value = "";

                document.getElementById('EdadInicial3').value = "";
                document.getElementById('EdadFinal3').value = "";
                document.getElementById('MontoInicial3').value = "";
                document.getElementById('MontoFinal3').value = "";
            }
        }

    });

    $("#Activar2").change(function() {
        if (document.getElementById('Activar2').checked == true && document.getElementById(
                'EdadFinal2').value == "" &&
            document.getElementById('MontoFinal2').value == "") {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar los datos anteriores',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            });
            document.getElementById('Activar2').checked = false;
        } else {
            document.getElementById('EdadInicial3').value = parseInt(document.getElementById('EdadFinal2')
                .value) + 1;
            document.getElementById('MontoInicial3').value = parseFloat(document.getElementById('MontoFinal2')
                .value) + 0.01;
            if (document.getElementById('Activar2').checked == true) {
                $("#EdadInicial3").prop("disabled", false);
                $("#EdadFinal3").prop("disabled", false);
                $("#MontoInicial3").prop("disabled", false);
                $("#MontoFinal3").prop("disabled", false);
            }
        }
    });

    $("#btn_modal_guardar").click(function() {
        validar();
    });

    function validar() {
        if (document.getElementById('Requisito').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo requisitos es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('EdadFinal').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo edad final es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('MontoInicial').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo monto inicial es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('MontoFinal').value.trim() == "") {
            Swal.fire({
                title: 'Error!',
                text: 'El campo monto final es requerido',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('Activar1').checked == true &&
            (document.getElementById('EdadInicial2').value.trim() == "" || document.getElementById('EdadFinal2').value
                .trim() == "" ||
                document.getElementById('MontoInicial2').value.trim() == "" || document.getElementById('MontoFinal2')
                .value.trim() == "")

        ) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else if (document.getElementById('Activar2').checked == true &&
            (document.getElementById('EdadInicial3').value.trim() == "" || document.getElementById('EdadFinal3').value
                .trim() == "" ||
                document.getElementById('MontoInicial3').value.trim() == "" || document.getElementById('MontoFinal3')
                .value.trim() == "")

        ) {
            Swal.fire({
                title: 'Error!',
                text: 'Debe llenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else {
            guardar();
        }
    }

    function guardar() {
        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Requisito": document.getElementById('Requisito').value,
            "EdadInicial": document.getElementById('EdadInicial').value,
            "EdadFinal": document.getElementById('EdadFinal').value,
            "MontoInicial": document.getElementById('MontoInicial').value,
            "MontoFinal": document.getElementById('MontoFinal').value,
            "EdadInicial2": document.getElementById('EdadInicial2').value,
            "EdadFinal2": document.getElementById('EdadFinal2').value,
            "MontoInicial2": document.getElementById('MontoInicial2').value,
            "MontoFinal2": document.getElementById('MontoFinal2').value,
            "EdadInicial3": document.getElementById('EdadInicial3').value,
            "EdadFinal3": document.getElementById('EdadFinal3').value,
            "MontoInicial3": document.getElementById('MontoInicial3').value,
            "MontoFinal3": document.getElementById('MontoFinal3').value
        };
        $.ajax({
            type: "post",
            url: "{{ url('polizas/deuda/store_requisitos') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                if (document.getElementById('DataRequisitos').value == "") {
                    document.getElementById('DataRequisitos').value = data;
                } else {
                    document.getElementById('DataRequisitos').value = document.getElementById(
                        'DataRequisitos').value + "," + data;
                }
                $('#modal_requisitos').modal('hide');
                get_requisitos();
            }
        });
    }

    function get_requisitos() {
        var parametros = {
            "Requisitos": document.getElementById('DataRequisitos').value,
        };
        $.ajax({
            type: "get",
            url: "{{ url('polizas/deuda/get_requisitos') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#divRequisitos').html(data);
            }
        });
    }
</script>


@endsection
