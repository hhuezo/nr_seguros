@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .ocultar {
            display: none;
        }
    </style>
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Pólizas / Deuda / Póliza de deuda / Nueva póliza<small></small>
                    </h2>
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab"
                                data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                        </li>
                        <li role="presentation" class=" "><a onclick="noGuardardo();">Tasa diferencia</a>
                        </li>
                        <li role="presentation" class=""><a onclick="noGuardardo();">Requisitos Minimos de
                                Asegurabilidad </a>
                        </li>


                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                            <form action="{{ url('polizas/deuda') }}" method="POST">
                                @csrf
                                <div class="x_content" style="font-size: 12px;">
                                    <div class="col-sm-12 row">
                                        <div class="col-sm-4 ">
                                            <label class="control-label" align="right">Número de Póliza *</label>
                                            <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                                value="{{ old('NumeroPoliza') }}" required>
                                        </div>

                                        <div class="col-sm-4" style="display: none !important;">
                                            <label class="control-label" align="right">Código *</label>
                                            <input class="form-control" name="Codigo" type="text"
                                                value="{{ $ultimo }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Aseguradora *</label>
                                        <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label">Productos *</label>
                                        <select name="Productos" id="Productos" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($productos as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label">Planes *</label>
                                        <select name="Planes" id="Planes" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="" selected disabled>Seleccione...</option>
                                            @foreach ($planes as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Asegurado *</label>
                                        <select name="Asegurado" id="Asegurado" class="form-control select2"
                                            style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($cliente as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Nit *</label>
                                        <input class="form-control" name="Nit" id="Nit" type="text"
                                            value="{{ old('Nit') }}" readonly>
                                    </div>

                                    <div class="col-sm-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Desde *</label>
                                        <input class="form-control" name="VigenciaDesde" type="date"
                                            value="{{ old('VigenciaDesde') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Hasta *</label>
                                        <input class="form-control" name="VigenciaHasta" type="date"
                                            value="{{ old('VigenciaHasta') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Estatus *</label>
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%"
                                            required>
                                            @foreach ($estadoPoliza as $obj)
                                                @if ($obj->Id == 1)
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
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Descuento de Rentabilidad *</label>
                                        <input class="form-control" name="Descuento" type="number" step="any"
                                            id="Descuento" value="{{ old('Descuento') }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="control-label " align="right">Clausulas Especiales </label>
                                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4"
                                            value="{{ old('ClausulasEspeciales') }}"> </textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Beneficios Adicionales </label>
                                        <textarea class="form-control" name="Beneficios" row="3" col="4" value="{{ old('Beneficios') }}"> </textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Concepto </label>
                                        <textarea class="form-control" name="Concepto" row="3" col="4" value="{{ old('Concepto') }}"
                                            required> </textarea>
                                    </div>

                                    <div class="col-sm-4 ocultar" style="display: none !important;">
                                        <br>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Mensual" value="1" checked>
                                            <label class="control-label">Tasa ‰ Millar Mensual *</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Anual" value="0">
                                            <label class="control-label">Tasa ‰ Millar Anual *</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Tasa ‰ Millar Mensual* </label>
                                        <input class="form-control" name="Tasa" type="number" id="Tasa"
                                            step="any" value="{{ old('Tasa') }}" required>
                                    </div>
                                    <div class="col-sm-4" align="center">
                                        <br>
                                        <label class="control-label" align="center">Vida</label>
                                        <input id="Vida" type="checkbox" class="js-switch" />
                                    </div>
                                    <div class="col-sm-4" align="center">
                                        <br>
                                        <label class="control-label" align="center">Desempleo</label>
                                        <input id="Desempleo" type="checkbox" class="js-switch" />
                                    </div>
                                    <div class="col-sm-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="control-label" align="right">% Tasa de Comisión *</label>
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                            step="any">
                                    </div>
                                    <div class="col-sm-2"><br>
                                        <label class="control-label" align="right">¿IVA incluído?</label>
                                        <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch">
                                    </div>
                                    <div class="col-sm-4">
                                        <div id="poliza_vida" style="display: none;">
                                            <label class="control-label">Número de Póliza Vida</label>
                                            <input name="Vida" type="text" class="form-control" />
                                        </div>

                                    </div>
                                    <div class="col-sm-4">
                                        <div id="poliza_desempleo" style="display: none;">
                                            <label class="control-label">Número de Póliza Desempleo</label>
                                            <input name="Desempleo" type="text" class="form-control" />
                                        </div>

                                    </div>
                                    <div class="col-sm-12 row">* Campo requerido</div>

                                </div>


                                <div class="x_title">
                                    <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                    <div class="clearfix"></div>
                                </div>

                                <br>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group" align="center">
                                        <button type="submit" class="btn btn-success">Guardar y Continuar</button>
                                        <a href="{{ url('poliza/vida') }}"><button type="button"
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


    @include('sweetalert::alert')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        function noGuardardo() {
            Swal.fire('Debe guardar los datos inicial de la poliza');
        }
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");

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
                        // if (data.TipoContribuyente == 1) {
                        //     document.getElementById('Retencion').setAttribute("readonly", true);
                        //     document.getElementById('Retencion').value = 0;
                        //     calculoCCF();
                        // }


                    }
                });
            });


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
@endsection
