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
                    <div class="col-sm-4">
                        <label class="control-label">Número de Póliza</label>
                        <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}">
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">NIT</label>
                        <input class="form-control" name="Nit" id="Nit" type="text" value="{{ old('Nit') }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label ">Código</label>
                        <input class="form-control" name="Codigo" type="text" value="{{ ($ultimo->Id)+1 }}" readonly>
                    </div>

                    <div class="col-sm-8">
                        <label class="control-label">Aseguradora</label>
                        <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($aseguradoras as $obj)
                            <option value="{{ $obj->Id }}">{{$obj->Id}} {{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <label class="control-label">Asegurado</label>
                        <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                            <option value="" disabled selected>Seleccione...</option>
                            @foreach ($cliente as $obj)
                            <option value="{{ $obj->Id }}">{{$obj->Id}} {{ $obj->Nombre }} {{$obj->Dui}} {{$obj->Nit}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Vigencia Desde</label>
                        <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">
{{--                        <input class="form-control formatoFecha"  name="VigenciaDesde" placeholder="dd/mm/yyyy" type="text" value="{{ old('VigenciaDesde') }}">--}}
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Vigencia Hasta</label>
                        <input class="form-control" name="VigenciaHasta" type="date" placeholder="dd/mm/yyyy"  value="{{ old('VigenciaHasta') }}">
{{--                        <input class="form-control formatoFecha" name="VigenciaHasta" type="text" placeholder="dd/mm/yyyy"  value="{{ old('VigenciaHasta') }}">--}}
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Estatus</label>
                        <select name="EstadoPoliza" class="form-control" style="width: 100%" required>
                            @foreach ($estados_poliza as $obj)
                            @if($obj->Id == 1)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Ejecutivo</label>
                        <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                            <option value="" disabled selected>Seleccione...</option>
                            @foreach ($ejecutivo as $obj)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Descuento de Rentabilidad %</label>
                        <input class="form-control" name="TasaDescuento" type="number" step="any" id="TasaDescuento" required min="0">
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Descuento de IVA</label>
                        <input class="form-control" name="DescuentoIva" type="checkbox" id="DescuentoIva" required>
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <input type="hidden" name="Bomberos" id="Bomberos" value="{{$bomberos}}">
                        <label class="control-label">Límite de Grupo</label>
                        <input type="number" step="any" name="LimiteGrupo" id="LimiteGrupo" value="{{ old('LimiteGrupo') }}" class="form-control" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Límite Individual</label>
                        <input type="number" step="any" name="LimiteIndividual" id="LimiteIndividual" value="{{ old('LimiteIndividual') }}" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Tasa %</label>
                        <input type="number" step="any" name="Tasa" id="Tasa" value="{{ old('Tasa') }}" class="form-control" required min="0" max="100">
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Tasa de Comisión %</label>
                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" required min="0" max="100">
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
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
                    </div>


                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <br>
                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit" id="btn-guardar">Guardar</button>
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
        $("#btn_guardar").on('click', function() {
            $("#btn_guardar").prop('disabled');
        });


        $("#Tasa").change(function() {

            if (document.getElementById('Tasa').value <= 1 || document.getElementById('Tasa').value >= 100) {
                document.getElementById('Tasa').value = '';
                swal('La tasa no debe ser menor a 0 ni mayor de 100');
            }
        })

        $("#TasaComision").change(function() {

            if (document.getElementById('TasaComision').value <= 1 || document.getElementById('TasaComision').value >= 100) {
                document.getElementById('TasaComision').value = '';
                swal('La tasa de comision no debe ser menor a 0 ni mayor de 100');
            }
        })

        $("#TasaDescuento").change(function() {

            if (document.getElementById('TasaDescuento').value <= 1 || document.getElementById('TasaDescuento').value >= 100) {
                document.getElementById('TasaDescuento').value = '';
                swal('El descuento de rentabilidad no debe ser menor a 0 ni mayor de 100');
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






    });

    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }
</script>



@endsection
