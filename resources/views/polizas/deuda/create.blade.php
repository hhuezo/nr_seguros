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

            <div class="x_content">
                <br />

                <form action="{{ url('polizas/deuda') }}" method="POST">
                    @csrf
                    <div class="form-horizontal" style="font-size: 12px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número de Póliza</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}">
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
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Codigo" id="Codigo" type="text" value="{{ old('Codigo') }}" required>
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
                                <div class="form-group row"></div>
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
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Limite Maximo</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="LimiteMaximo" type="number" step="any" value="{{ old('LimiteMaximo') }}">
                                    </div>
                                </div>
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
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa ‰
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any" value="{{ old('Tasa') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                        Descuento
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ old('Descuento') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                        Comision
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ old('TasaComision') }}">
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

                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vida</label>
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                        <input name="Vida" id="Vida" type="checkbox" class="js-switch" />
                                    </div>
                                    <input type="hidden" id="DataRequisitos" name="Requisitos">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <div class="x_title">
                                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_title">
                                        <h4>&nbsp;&nbsp; Declaracion de Tipos de Carteras<small></small>
                                        </h4>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                            Cartera 1</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <select name="TipoCartera1" class="form-control select2" style="width: 100%" required>
                                                @foreach ($tipoCartera as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            ‰ 1</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TasaCartera1" id="TasaCartera1" type="text" value="{{ old('TasaCartera1') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                            Cartera 2</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <select name="TipoCartera2" class="form-control select2" style="width: 100%" required>
                                                @foreach ($tipoCartera as $obj)
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            ‰ 2</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TasaCartera2" id="TasaCartera2" type="text" value="{{ old('TasaCartera2') }}">
                                        </div>
                                    </div>

                                </div>
                                <div id="poliza_vida" style="display: none;" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <div class="x_title">
                                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_title">
                                        <h4>&nbsp;&nbsp; Poliza de Vida Colectivo<small></small>
                                        </h4>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número de Póliza</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input class="form-control" name="NumeroPolizaVida" id="NumeroPolizaVida" type="text" value="{{ old('NumeroPolizaVida') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Suma
                                            Uniforme por Usuario</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input class="form-control" name="SumaUniforme" id="SumaUniforme" type="text" value="{{ old('SumaUniforme') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                            ‰</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input class="form-control" name="TasaVida" id="TasaVida" type="text" value="{{ old('TasaVida') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>




                            <div class="col-md-12 col-sm-12  ">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Tabla de Requisitos Minimos de Asegurabilidad &nbsp;</h2>
                                        <div class="float-right" style="text-align: right;"> <button type="button" onclick="modal_requisitos()" class="btn btn-primary"><i class="fa fa-plus"></i></button></div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="x_content">

                                        <div class="table-responsive" id="divRequisitos">

                                        </div>


                                    </div>
                                </div>
                            </div>

                            <br>

                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@include('catalogo.cliente.modal_poliza')
@include('polizas.deuda.modal_requisitos')


@include('sweetalert::alert')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
</script>
@endsection