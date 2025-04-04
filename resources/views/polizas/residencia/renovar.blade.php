@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>RESI - Poliza de Residencia Renovar o Cancelar Poliza <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('polizas/residencia')}}" class="btn btn-info fa fa-undo " style="color: white"> Atrás</a>
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


            <form method="POST" action="{{ route('residencia.renovarPoliza', $residencia->Id) }}">

                @csrf
                <div class="x_content" style="font-size: 12px;">
                    <br />
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Codigo" type="text" value="{{ $residencia->Codigo}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número de Póliza</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="NumeroPoliza" type="text" value="{{ $residencia->NumeroPoliza }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->aseguradoras->Nombre}}" class="form-control" readonly>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" value="{{$residencia->clientes->Nombre}}" class="form-control" readonly>

                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Nit" id="Nit" type="text" value="{{$residencia->Nit }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estatus</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="EstadoPoliza" id="EstadoPoliza" class="form-control" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($estados_poliza as $obj)

                                    @if($obj->Id <> 1)

                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                        @endforeach
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 " id="Renovado" style="display: show;">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                            </label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                @if ($residencia->Mensual == 1)
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Mensual" value="1" checked>
                                    <label class="control-label">Tasa Millar Mensual</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Anual" value="0">
                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                </div>
                                @else
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Mensual" value="1">
                                    <label class="control-label">Tasa Millar Mensual</label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="radio" name="tipoTasa" id="Anual" value="0" checked>
                                    <label class="control-label">Tasa ‰ Millar Anual</label>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Límite grupo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" style="display: none" step="any" name="LimiteGrupo" id="LimiteGrupo" value="{{ $residencia->LimiteGrupo }}" class="form-control"  onblur="changeGrupo(0)">
                                <input type="text" id="LimiteGrupoDisplay" class="form-control" oninput="formatLimiteGrupo()" onblur="updateLimiteGrupo()" value="{{ number_format($residencia->LimiteGrupo, 2, '.', ',') }}">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Límite individual</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" style="display: none" step="any" name="LimiteIndividual" id="LimiteIndividual" value="{{ $residencia->LimiteIndividual }}" class="form-control" >

                                <input type="text" step="any" id="LimiteIndividualDisplay" value="{{ number_format($residencia->LimiteIndividual, 2, '.', ',') }}" class="form-control" onchange="changeIndividual()" oninput="validateLimiteIndividual()">

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Tasa" value="{{$residencia->Tasa }}" class="form-control" id="Tasa">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Desde</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaDesde" id="VigenciaDesde" type="date" value="{{ $residencia->VigenciaDesde}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Hasta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaHasta" id="VigenciaHasta" type="date" value="{{$residencia->VigenciaHasta }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Ejecutivo" id="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivo as $obj)
                                    @if($obj->Id == $residencia->Ejecutivo)
                                    <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                    @else
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>



                    </div>



                    <br><br>
                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group" align="center">

                        <button class="btn btn-success" type="submit">Aceptar</button>
                        <a href="{{url('polizas/residencia')}}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                    </div>
                </div>



            </form>



        </div>


    </div>

</div>
</div>

</div>
@include('sweetalert::alert')
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-residencia");


        $("#EstadoPoliza").change(function() {
            if (document.getElementById('EstadoPoliza').value != 2) {
                $('#LimiteGrupo').attr('required');
                $('#LimiteIndividual').attr('required');
                $('#Tasa').attr('required');
                $('#VigenciaDesde').attr('required');
                $('#VigenciaHasta').attr('required');
                $('#Ejecutivo').attr('required');
                $("#Renovado").hide();

            } else {
                $('#LimiteGrupo').removeAttr('required');
                $('#LimiteIndividual').removeAttr('required');
                $('#Tasa').removeAttr('required');
                $('#VigenciaDesde').removeAttr('required');
                $('#VigenciaHasta').removeAttr('required');
                $('#Ejecutivo').removeAttr('required');
                $("#Renovado").show();
            }
        })
        $('#LimiteIndividual').change(function() {
            var individual = Number(document.getElementById('LimiteIndividual').value);
            var grupal = Number(document.getElementById('LimiteGrupo').value);
            if (individual >= grupal) {
                document.getElementById('LimiteIndividual').value = '';
                swal('El limite individual supera al limite grupal');
            }
        })
    })

    function changeGrupo(id) {
        if (id == 1) {
            //$("#LimiteGrupo").show();
            //$("#LimiteGrupoDisplay").hide();
            //  document.getElementById('LimiteGrupo').value = document.getElementById('LimiteGrupoDisplay').value;

        } else {
            //$("#LimiteGrupo").hide();
            //$("#LimiteGrupoDisplay").show();
            document.getElementById('LimiteGrupoDisplay').value = Number(document.getElementById('LimiteGrupo').value).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

    }

    function changeIndividual(id) {
   if (id == 1) {
            //$("#LimiteIndividual").show();
            //$("#LimiteIndividualDisplay").hide();
            //  document.getElementById('LimiteIndividual').value = document.getElementById('LimiteIndividualDisplay').value;

        } else {
           // $("#LimiteIndividual").hide();
            //$("#LimiteIndividualDisplay").show();
            // document.getElementById('LimiteIndividualDisplay').value = Number(document.getElementById('LimiteIndividual').value).toLocaleString('en-US', {
            //     minimumFractionDigits: 2,
            //     maximumFractionDigits: 2
            // });
        }
    }

    function modal_edit(id) {
        document.getElementById('ModalSaldoA').value = "";
        document.getElementById('ModalImpresionRecibo').value = "";
        document.getElementById('ModalComentario').value = "";
        document.getElementById('ModalEnvioCartera').value = "";
        document.getElementById('ModalEnvioPago').value = "";
        document.getElementById('ModalPagoAplicado').value = "";
        document.getElementById('ModalId').value = id;



        $.get("{{ url('polizas/vida/get_pago') }}" + '/' + id, function(data) {
            console.log(data);
            document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
            document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
            document.getElementById('ModalComentario').value = data.Comentario;
            if (data.EnvioCartera) {
                document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
            } else {
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.EnvioPago) {
                document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
            } else {
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.PagoAplicado) {
                document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            } else {
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
            }



        });
        $('#modal_editar_pago').modal('show');

    }
</script>

<script>
    function formatLimiteGrupo() {
        let input = document.getElementById('LimiteGrupoDisplay');
        input.value = input.value.replace(/[^\d.,]/g, ''); // Solo permite números, coma y punto
    }

    function updateLimiteGrupo() {
        let inputDisplay = document.getElementById('LimiteGrupoDisplay');
        let inputReal = document.getElementById('LimiteGrupo');
        let value = inputDisplay.value.replace(/,/g, ''); // Elimina las comas
        inputReal.value = parseFloat(value).toFixed(2); // Actualiza el valor en formato numérico
        inputDisplay.value = parseFloat(value).toLocaleString().replace(/\./g, ','); // Formatea el valor con coma para separación de miles
    }
</script>

<script>
    function validateLimiteIndividual() {
        let input = document.getElementById('LimiteIndividualDisplay');
        input.value = input.value.replace(/[^\d.,]/g, ''); // Solo permite números, coma y punto
    }

    function changeIndividual() {
        let inputDisplay = document.getElementById('LimiteIndividualDisplay');
        let inputReal = document.getElementById('LimiteIndividual');
        let value = inputDisplay.value.replace(/,/g, ''); // Elimina las comas
        inputReal.value = parseFloat(value); // Actualiza el valor en formato numérico
        inputDisplay.value = parseFloat(value).toLocaleString().replace(/\./g, ','); // Formatea el valor con coma para separación de miles
    }
</script>
@endsection
