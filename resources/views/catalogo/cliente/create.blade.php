@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Registro de Cliente <small></small></h2>
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
                <form action="{{ url('catalogo/cliente') }}" method="POST">
                    @csrf

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="Nombre" class="form-label">NIT</label>
                                <input class="form-control" name="Nit" id="Nit" value="{{ old('Nit') }}" data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text">
                            </div>

                            <div class="col-lg-6">
                                <label for="Genero" class="form-label">Estado Cliente</label>
                                <select name="Estado" class="form-control" style="width: 100%">
                                    @foreach ($cliente_estados as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="Nombre" class="form-label">DUI</label>
                                        <input class="form-control" name="Dui" id="Dui" value="{{ old('Dui') }}" data-inputmask="'mask': ['99999999-9']" data-mask type="text">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row" id="Homolo">
                                            <label for="Nombre" class="form-label">¿Homologado?</label><br>
                                            <input name="Homologado" id="Homologado" type="checkbox" onchange="validaciones.cambiarEstado()" class="js-switch" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="TipoPersona" class="form-label">Tipo Persona</label>
                                <select name="TipoPersona" id="TipoPersona" onchange="validaciones.cboTipoPersona(this.value)" class="form-control">
                                    <option value="" disabled selected> Seleccione ...</option>
                                    <option value="1" {{ old('TipoPersona') == 1 ? 'selected' : '' }}>Natural
                                    </option>
                                    <option value="2" {{ old('TipoPersona') == 2 ? 'selected' : '' }}>Jurídica
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-lg-6">
                                <label for="Nombre" class="form-label">Registro Fiscal</label>
                                <input class="form-control" name="RegistroFiscal" id="RegistroFiscal" value="{{ old('RegistroFiscal') }}" type="text">
                            </div>
                            <div class="col-lg-6">
                                <label for="Genero" class="form-label">Género</label>
                                <select name="Genero" id="Genero" class="form-control">
                                    <option value="" selected disabled>Seleccione ...</option>
                                    <option value="1" {{ old('Genero') == 1 ? 'selected' : '' }}>Masculino
                                    </option>
                                    <option value="2" {{ old('Genero') == 2 ? 'selected' : '' }}>Femenino
                                    </option>
                                    <option value="3" {{ old('Genero') == 2 ? 'selected' : '' }}>No Aplica
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-lg-6">
                                <label for="Nombre" class="form-label">Nombre o Razón Social</label>
                                <input class="form-control" id="Nombre" name="Nombre" value="{{ old('Nombre') }}" type="text">
                            </div>
                            <div class="col-lg-6">
                                <label for="Nombre" class="form-label">Tipo Contribuyente</label>
                                <select name="TipoContribuyente" id="TipoContribuyente" class="form-control" onchange="validaciones.cboTipoContribuyente(this.value)" style="width: 100%">
                                    <option value="" disabled selected>Seleccione ...</option>
                                    @foreach ($tipos_contribuyente as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('TipoContribuyente') == $obj->Id ? 'selected' : '' }}>
                                        {{ $obj->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="FechaNacimiento" class="form-label">Fecha Nacimiento</label>
                                <input class="form-control" name="FechaNacimiento" id="FechaNacimiento" value="{{ old('FechaNacimiento') }}" type="date">
                            </div>
                            <div class="col-md-6">
                                <label for="Referencia" class="form-label">Vinculado al Grupo o Referencia</label>
                                <input class="form-control" name="Referencia" id="Referencia" value="{{ old('Referencia') }}" type="text">
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="FechaNacimiento" class="form-label">Edad</label>
                                <input class="form-control" id="EdadCalculada" value="" type="text" disabled>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-lg-6">
                                <label for="Genero" class="form-label">Estado Familiar</label>
                                <select class="form-control" name="EstadoFamiliar">
                                    <option value="0" {{ old('TipoPersona') == 0 ? 'selected' : '' }}>No Aplica
                                    </option>
                                    <option value="1" {{ old('TipoPersona') == 1 ? 'selected' : '' }}>Soltero
                                    </option>
                                    <option value="2" {{ old('TipoPersona') == 2 ? 'selected' : '' }}>Casado
                                    </option>
                                    <option value="3" {{ old('TipoPersona') == 3 ? 'selected' : '' }}>
                                        Divorciado
                                    </option>
                                    <option value="4" {{ old('TipoPersona') == 4 ? 'selected' : '' }}>Viudo
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-lg-6">
                                <label for="NumeroDependientes" class="form-label">Número Dependientes</label>
                                <input class="form-control" name="NumeroDependientes" id="NumeroDependientes" value="{{ old('NumeroDependientes') }}" type="number">
                            </div>
                            <div class="col-lg-6">
                                <label for="Genero" class="form-label">Responsable de Pago</label>
                                <input class="form-control" id="ResponsablePago" name="ResponsablePago" value="{{ old('ResponsablePago') }}" type="text">
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-lg-6">
                                <label for="Genero" class="form-label">Ocupación</label>
                                <input class="form-control" id="Ocupacion" name="Ocupacion" value="{{ old('Ocupacion') }}" type="text">
                            </div>
                            <div class="col-lg-6">
                                <label for="Genero" class="form-label">Ubicación de cobro</label>
                                <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                    <option value="" selected disabled>Seleccione ...</option>
                                    @foreach ($ubicaciones_cobro as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('UbicacionCobro') == $obj->Id ? 'selected' : '' }}>
                                        {{ $obj->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                                <label for="DireccionResidencia" class="form-label">Departamento</label>
                                <select id="Departamento" class="form-control select2" style="width: 100%">
                                    @foreach ($departamentos as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="DireccionResidencia" class="form-label">Dirección Residencia</label>
                                <textarea class="form-control" name="DireccionResidencia">{{ old('DireccionResidencia') }}</textarea>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <label for="DireccionResidencia" class="form-label">Dirección
                                    Correspondencia</label>
                                <textarea class="form-control" name="DireccionCorrespondencia">{{ old('DireccionCorrespondencia') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="DireccionResidencia" class="form-label">Distrito</label>
                                <select id="Distrito" name="Distrito" class="form-control select2" style="width: 100%">
                                    @foreach ($distritos as $obj)
                                    <option value="{{ $obj->Id }}" {{ old('Estado') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="Referencia" class="form-label">Teléfono Principal</label>
                                <input class="form-control" name="TelefonoCelular" value="{{ old('TelefonoCelular') }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                            </div>
                        </div>

                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="Referencia" class="form-label">Teléfono Residencia</label>
                                <input class="form-control" name="TelefonoResidencia" value="{{ old('TelefonoResidencia') }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="Referencia" class="form-label">Teléfono Oficina</label>
                                <input class="form-control" name="TelefonoOficina" value="{{ old('TelefonoOficina') }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="CorreoPrincipal" class="form-label">Correo Principal</label>
                                <input class="form-control" name="CorreoPrincipal" value="{{ old('CorreoPrincipal') }}" type="email">
                            </div>
                        </div>

                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="CorreoPrincipal" class="form-label">Correo Secundario</label>
                                <input class="form-control" name="CorreoSecundario" value="{{ old('CorreoPrincipal') }}" type="email">
                            </div>

                        </div>

                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="FechaVinculacion" class="form-label">Fecha Vinculación</label>
                                <input class="form-control" name="FechaVinculacion" value="{{ old('FechaVinculacion') }}" type="date">
                            </div>

                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-6">
                                <label for="FechaVinculacion" class="form-label">Fecha Baja Cliente</label>
                                <input class="form-control" name="FechaBaja" value="{{ old('FechaBaja') }}" type="date">
                            </div>
                        </div>

                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-md-12">
                                <label for="Comentarios" class="form-label">Comentarios</label>
                                <textarea class="form-control" name="Comentarios">{{ old('Comentarios') }}</textarea>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px !important;">
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a href="{{ url('catalogo/cliente') }}">
                                <button type="button" class="btn btn-primary">Cancelar
                                </button>
                            </a>
                        </div>
                    </div>

                </form>


            </div>

        </div>
    </div>
</div>
@include('sweetalert::alert')
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#FechaNacimiento').on('change', function() {
            var fecha_nacimiento = new Date($(this).val());
            var fecha_actual = new Date();

            var edad = fecha_actual.getFullYear() - fecha_nacimiento.getFullYear();

            var mes_nacimiento = fecha_nacimiento.getMonth();
            var mes_actual = fecha_actual.getMonth();

            if (mes_actual < mes_nacimiento || (mes_actual === mes_nacimiento && fecha_actual.getDate() <
                    fecha_nacimiento.getDate())) {
                edad--;
            }

            if (isNaN(edad)) {
                $('#EdadCalculada').val('Seleccione una fecha valida');
            } else {
                $('#EdadCalculada').val(edad);
            }
        });

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
    const validaciones = {
        cboTipoPersona(idTipoPersona) {
            console.log(idTipoPersona)
            // 1 natural
            // 2 jurudica
            if (idTipoPersona === '1') {
                document.getElementById("TipoContribuyente").value = ""; // no aplica
                // $('#TipoContribuyente').trigger('change');
            }

        },
        cboTipoContribuyente(idTipoContribuyente) {
            document.getElementById("RegistroFiscal").disabled = idTipoContribuyente === '4';
        },
        cambiarEstado() {
            console.log("se activo la funcion");

            if (document.getElementById('Homologado').checked) {
                $('#Nit').prop('readonly', true);
                $('#Nit').inputmask('remove');
                $('#Nit').inputmask({
                    'mask': '99999999-9'
                });
                $('#Nit').val($('#Dui').val());
            } else {
                $('#Nit').prop('readonly', false);
                $('#Nit').inputmask('remove');
                $('#Nit').inputmask({
                    'mask': '9999-999999-999-9'
                });
                $('#Nit').val('');
            }
        }
    }
</script>
@endsection
