@extends ('welcome')
@section('contenido')
<script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>RESI - Seguro de residencias <small></small></h2>
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
            <form action="{{ url('polizas/residencia') }}" method="POST" class="forms-sample">
                @csrf
                <div class="x_content" style="font-size: 12px;">
                    <br />
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                      
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
                                    @foreach ($aseguradoras as $obj)
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

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <input type="hidden" name="Bomberos" id="Bomberos" value="{{$bomberos}}">


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite grupo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="LimiteGrupo" id="LimiteGrupo" value="{{ old('LimiteGrupo') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite individual</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="LimiteIndividual" id="LimiteIndividual" value="{{ old('LimiteIndividual') }}" class="form-control">
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
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Monto Cartera</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="MontoCartera" id="MontoCartera" value="{{ old('MontoCartera') }}" class="form-control">
                            </div>
                        </div> -->


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Tasa" id="Tasa" value="{{ old('Tasa') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa de Descuento %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Comision %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estatus</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="EstadoPoliza" class="form-control" style="width: 100%">
                                    @foreach ($estados_poliza as $obj)
                                    @if($obj->Id == 1)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>



                    </div>

                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <br>
                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('polizas/residencia/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>
            @include('catalogo.cliente.modal_poliza')

        </div>

    </div>
</div>


<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    // func
    $(document).ready(function() {



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






    });

    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }
</script>



@endsection