@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nueva aseguradora <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <a href="{{url('catalogo/aseguradoras')}}" class="btn btn-info fa fa-undo " style="color: white" ></a>
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
            <form action="{{ url('catalogo/aseguradoras') }}" method="POST" class="forms-sample">
                @csrf
                <div class="x_content">
                    <br />
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label ">Código</label>
                            <input type="text" name="Nombre" value="{{($ultimoId->Id) +1}}" class="form-control" readonly autofocus="true">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Tipo Contribuyente</label>
                            <select name="TipoContribuyente" class="form-control">
                                @foreach ($tipo_contribuyente as $obj)
                                <option value="{{$obj->Id}}" {{ old('TipoContribuyente') == $obj->Id ? 'selected' : '' }}>{{$obj->Nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label class="control-label ">Nit Empresa</label>
                            <input type="text" name="Nit" value="{{old('Nit')}}" class="form-control" data-inputmask="'mask': ['9999-999999-999-9']">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Página Web</label>
                            <input type="text" name="PaginaWeb" value="{{old('PaginaWeb')}}" class="form-control">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label class="control-label ">Registro fiscal</label>
                            <input type="text" name="RegistroFiscal" value="{{old('RegistroFiscal')}}" class="form-control">
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label">Fecha constitución</label>
                            <input type="date" name="FechaConstitucion" value="{{old('FechaConstitucion')}}" class="form-control">
                        </div>
                    </div>

                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label class="control-label ">Nombre o Razon Social</label>
                            <input type="text" name="Nombre" value="{{old('Nombre')}}" class="form-control" required autofocus="true">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Dirección</label>
                            <textarea name="Direccion" rows="1" class="form-control">{{old('Direccion')}}</textarea>
                        </div>
                    </div>

                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label class="control-label ">Abreviatura</label>
                            <input type="text" name="Abreviatura" value="{{old('Abreviatura')}}" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Teléfono fijo de asistencia</label>
                            <input type="text" name="TelefonoFijo" value="{{old('TelefonoFijo')}}" class="form-control" data-inputmask="'mask': ['9999-9999']">
                        </div>
                    </div>

                    <div class="row" style="padding-top: 15px!important;">

                        <div class="col-sm-6">
                            <label class="control-label ">Fecha vinculación</label>
                            <input type="date" name="FechaVinculacion" value="{{old('FechaVinculacion')}}" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label ">Teléfono whatsapp asistencia</label>
                            <input type="text" name="TelefonoWhatsapp" value="{{old('TelefonoWhatsapp')}}" class="form-control" data-inputmask="'mask': ['9999-9999']">
                        </div>
                    </div>

                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Departamento</label>
                            <select id="Departamento" class="form-control select2" style="width: 100%">
                                @foreach ($departamentos as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Municipio</label>
                            <select name="Municipio" id="Municipio" required class="form-control select2" style="width: 100%">
                                @foreach ($municipios as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 15px!important;">
                        <div class="col-sm-6">
                            <label for="DireccionResidencia" class="form-label">Distrito</label>
                            <select id="Distrito" name="Distrito" class="form-control select2" style="width: 100%">
                                @foreach ($distritos as $obj)
                                <option value="{{ $obj->Id }}" {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>
            </form>


        </div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#Departamento").change(function() {
            // var para la Departamento
            var Departamento = $(this).val();

            //funcionpara las municipios
            $.get("{{ url('get_municipio') }}" + '/' + Departamento, function(data) {
                //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                console.log(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                    '</option>';
                $("#Municipio").html(_select);
            });


        });

        $("#Municipio").change(function() {
            // var para la Departamento
            var Municipio = $(this).val();

            //funcionpara las distritos
            $.get("{{ url('get_distrito') }}" + '/' + Municipio, function(data) {
                //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                console.log(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '"  >' + data[i].Nombre +
                    '</option>';
                $("#Distrito").html(_select);
            });


        });
    });
</script>
@endsection