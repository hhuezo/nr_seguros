@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nuevo Poliza de Vida &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VICO - Vida Colectivo Seguros<small></small>
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

            <div class="x_content">
                <br />


                <div class="form-horizontal" style="font-size: 12px;">
                    <form action="{{ url('polizas/vida') }}" method="POST">
                        @csrf
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Codigo" id="Codigo" type="text" value="{{ old('Codigo') }}" onblur="get_usuarios(this.value)" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($aseguradora as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($cliente as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12"><i onclick="modal_cliente();" class="fa fa-plus fa-lg" style="padding-top: 60%;"></i></div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Nit" id="Nit" type="text" value="{{ old('Nit') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                        Asegurado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="{{ old('GrupoAsegurado') }}" required> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                        Especiales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="{{ old('ClausulasEspeciales') }}"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                        Adicionales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4" value="{{ old('BeneficiosAdicionales') }}"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Concepto</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="Concepto" row="3" col="4" value="{{ old('Concepto') }}" required> </textarea>
                                    </div>
                                </div>
                                <div id="creditos" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite Menor</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input type="number" step="any" name="LimiteGrupo" id="LimiteGrupo" value="{{ old('LimiteGrupo') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite Mayor</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input type="number" step="any" name="LimiteIndividual" id="LimiteIndividual" value="{{ old('LimiteIndividual') }}" class="form-control">
                                        </div>
                                    </div>

                                </div>


                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Desde</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Hasta</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        Cartera</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCartera" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCartera as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($ejecutivo as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estatus</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%" required>
                                            @foreach ($estadoPoliza as $obj)
                                            @if ($obj->Id == 1)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                        Cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCobro" class="form-control select2" id="TipoCobro" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCobro as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <!-- radio button -->
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Mensual" value="1" checked>
                                            <label class="control-label">Tasa ‰ Millar Mensual</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Anual" value="0">
                                            <label class="control-label">Tasa ‰ Millar Anual</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <input type="hidden" name="Bomberos" id="Bomberos" value="{{ $bomberos }}">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                        Cartera
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="MontoCartera" id="MontoCartera" type="number" step="any" value="{{ old('MontoCartera') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <input type="hidden" name="Bomberos" id="Bomberos" value="{{ $bomberos }}">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Multi Tarifa
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Multitarifa" id="Multitarifa" type="checkbox" step="any" value="{{ old('Multitarifa') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa ‰
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any" value="{{ old('Tasa') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa Comision
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ old('TasaComision') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa Descuento
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TasaDescuento" id="TasaDescuento" type="number" step="any" value="{{ old('TasaDescuento') }}">
                                    </div>
                                </div>
                            </div>

                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <br>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>




@include('catalogo.cliente.modal_poliza')


@include('sweetalert::alert')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        if (document.getElementById('Multitarifa').checked == true) {
            $('#Tasa').attr('readonly');
            $('#Tasa').removeAttr('required');
        } else {
            $('#Tasa').removeAttr('readonly');
            $('#Tasa').attr('required');
        }

        $("#TipoCobro").change(function() {
            if (document.getElementById('TipoCobro').value == 1) {
                document.getElementById('MontoCartera').removeAttribute('required');
                document.getElementById('LimiteGrupo').removeAttribute('required');
                document.getElementById('LimiteIndividual').removeAttribute('required');
                document.getElementById('MontoCartera').value = 0;
                $("#creditos").hide();
            } else {
                $('#MontoCartera').attr('required', 'required');
                document.getElementById('MontoCartera').value = "";
                $('#creditos').show();
                $('#LimiteGrupo').attr('required', 'required');
                $('#LimiteIndividual').attr('required', 'required');
            }
        })

        $("#Asegurado").change(function() {
            // alert(document.getElementById('Asegurado').value);
            $('#response').html('<div><img src="{{ url(' / img / ajax - loader.gif ') }}"/></div>');
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
</script>
@endsection