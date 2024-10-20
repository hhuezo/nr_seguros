@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo Poliza de Deuda &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VIDE - Seguro por Deuda<small></small>
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
                        <li role="presentation" class=" "><a>Declaracion de Lineas de Credito</a>
                        </li>
                        <li role="presentation" class=""><a>Requisitos Minimos de Asegurabilidad </a>
                        </li>
                        <li role="presentation" class=""><a>Tasa diferenciada </a>
                        </li>

                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="tab_content1"
                            aria-labelledby="home-tab">
                            <form action="{{ url('polizas/deuda/actualizar/') }}{{ $deuda->Id }}" method="POST">
                                @csrf
                                <div class="x_content" style="font-size: 12px;">
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Número de Póliza *</label>
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                            value="{{ $deuda->NumeroPoliza }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Nit *</label>
                                        <input class="form-control" name="Nit" id="Nit" type="text"
                                            value="{{ $deuda->Nit }}" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Código *</label>
                                        <input class="form-control" name="Codigo" type="text"
                                            value="{{ $deuda->Codigo }}" readonly>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Aseguradora *</label>
                                        <select name="Aseguradora" class="form-control select2" style="width: 100%"
                                            required>
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
                                    <div class="col-sm-8">
                                        <label class="control-label" align="right">Asegurado *</label>
                                        <select name="Asegurado" id="Asegurado" class="form-control select2"
                                            style="width: 100%" required>
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
                                    <div class="col-sm-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Desde *</label>
                                        <input class="form-control" name="VigenciaDesde" type="date"
                                            value="{{ $deuda->VigenciaDesde }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Vigencia Hasta *</label>
                                        <input class="form-control" name="VigenciaHasta" type="date"
                                            value="{{ $deuda->VigenciaHasta }}" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Estatus *</label>
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
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
                                        <label class="control-label " align="right">Clausulas Especiales</label>
                                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4"
                                            value="{{ $deuda->ClausulasEspeciales }}"> </textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Beneficios Adicionales</label>
                                        <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4"
                                            value="{{ $deuda->BeneficiosAdeicionales }}"> </textarea>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Concepto</label>
                                        <textarea class="form-control" name="Concepto" row="3" col="4" value="{{ $deuda->Concepto }}"
                                            required> </textarea>
                                    </div>
                                    <div class="col-sm-4">
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
                                        <label class="control-label" align="right">Tasa ‰ *</label>
                                        <input class="form-control" name="Tasa" type="number" id="Tasa"
                                            step="any" value="{{ $deuda->Tasa }}" required>
                                    </div>
                                    <div class="col-sm-4" align="center">
                                        <br>
                                        <label class="control-label" align="center">Vida</label>
                                        <input id="Vida" type="checkbox" class="js-switch" />
                                    </div>
                                    <div class="col-sm-12">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" align="right">Tasa de Comision % *</label>
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                                            step="any" value="{{ $deuda->TasaComision }}">
                                    </div>
                                    <div class="col-sm-4" id="poliza_vida" style="display: none;">

                                        <label class="control-label">Número de Póliza Vida</label>
                                        <input name="Vida" type="text" class="form-control" />
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


        @include('sweetalert::alert')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- jQuery -->
        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
        <script type="text/javascript">
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


                $("#Anual").change(function() {
                    var monto = document.getElementById('MontoCartera').value;
                    var tasa = document.getElementById('Tasa').value;
                    var tasaFinal = (tasa / 1000) / 12;
                    var sub = Number(monto) * Number(tasaFinal);
                    document.getElementById('SubTotal').value = sub;
                    calculoPrimaRepartida();
                })
                $("#Mensual").change(function() {
                    var monto = document.getElementById('MontoCartera').value;
                    var tasa = document.getElementById('Tasa').value;
                    var tasaFinal = tasa / 1000;
                    var sub = Number(monto) * Number(tasaFinal);
                    document.getElementById('SubTotal').value = sub;
                    calculoPrimaRepartida();
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
                                calculoCCF();
                            }


                        }
                    });
                });

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
