@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nuevo cliente <small></small></h2>
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

                    <form action="{{ url('catalogo/cliente') }}" method="POST">
                        @csrf
                        <div class="form-horizontal">
                            <br>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        persona</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

                                        @if ($cliente->TipoPersona == 1)
                                            <select name="TipoPersona" id="TipoPersona" class="form-control">
                                                <option value="1" selected>Natural</option>
                                                <option value="1">Natural</option>
                                                <option value="2">Jurídica</option>
                                            </select>
                                        @else
                                            <select name="TipoPersona" id="TipoPersona" class="form-control">
                                                <option value="2" selected>Jurídica</option>
                                                <option value="1">Natural</option>
                                                <option value="2">Jurídica</option>
                                            </select>
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">NIT</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Nit" value="{{ old('Nit', $cliente->Nit) }}"
                                            data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text"
                                            autofocus="true">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Dui" value="{{ old('Dui', $cliente->Dui) }}"
                                            data-inputmask="'mask': ['99999999-9']" data-mask type="text"
                                            autofocus="true">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Registro
                                        fiscal</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="RegistroFiscal"
                                            value="{{ old('RegistroFiscal', $cliente->RegistroFiscal) }}" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre o Razon
                                        Social</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Nombre"
                                            value="{{ old('Nombre', $cliente->Nombre) }}" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                        nacimiento</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="FechaNacimiento"
                                            value="{{ old('FechaNacimiento', $cliente->FechaNacimiento) }}" type="date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estado
                                        familiar</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="EstadoFamiliar"
                                            value="{{ old('EstadoFamiliar', $cliente->EstadoFamiliar) }}" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero
                                        dependientes</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroDependientes"
                                            value="{{ old('NumeroDependientes', $cliente->NumeroDependientes) }}"
                                            type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Ocupacion</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Ocupacion"
                                            value="{{ old('Ocupacion', $cliente->Ocupacion) }}" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                        residencia</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="DireccionResidencia">{{ old('DireccionResidencia', $cliente->DireccionResidencia) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                        correspondecia</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="DireccionCorrespondencia">{{ old('DireccionCorrespondencia', $cliente->DireccionCorrespondencia) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                        residencia</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TelefonoRecidencia"
                                            value="{{ old('TelefonoRecidencia', $cliente->TelefonoRecidencia) }}"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                </div>

                            </div>



                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                        oficina</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TelefonoOficina"
                                            value="{{ old('TelefonoOficina', $cliente->TelefonoOficina) }}"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                        celular</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TelefonoCelular"
                                            value="{{ old('TelefonoCelular', $cliente->TelefonoCelular) }}"
                                            data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                        electrónico principal</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="CorreoPrincipal"
                                            value="{{ old('CorreoPrincipal', $cliente->CorreoPrincipal) }}"
                                            type="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                        electrónico secundario</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="CorreoSecundario"
                                            value="{{ old('CorreoSecundario', $cliente->CorreoSecundario) }}"
                                            type="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                        Vinculacion</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="FechaVinculacion"
                                            value="{{ old('FechaVinculacion', $cliente->FechaVinculacion) }}"
                                            type="date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                        Baja</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="FechaBaja"
                                            value="{{ old('FechaBaja', $cliente->FechaBaja) }}" type="date">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Estado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="Estado" class="form-control select2" style="width: 100%">
                                            @foreach ($cliente_estados as $obj)
                                                @if ($obj->id == $cliente->Estado)
                                                    <option value="{{ $obj->id }}" selected>
                                                        {{ $obj->nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $obj->id }}">
                                                        {{ $obj->nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Género</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        @if ($cliente->Genero == 1)
                                            <select name="Genero" class="form-control">
                                                <option value="1" selected>Masculino</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        @else
                                            <select name="Genero" class="form-control">
                                                <option value="2" selected>Femenino</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        contribuyente</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoContribuyente" class="form-control" style="width: 100%">
                                            @foreach ($tipos_contribuyente as $obj)
                                                @if ($obj->Id == $cliente->TipoContribuyente)
                                                    <option value="{{ $obj->Id }}" selected>
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $obj->Id }}">
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                        align="right">Referencia</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Referencia"
                                            value="{{ old('Referencia', $cliente->Referencia) }}" type="text">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Responsable
                                        Pago</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="ResponsablePago"
                                            value="{{ old('ResponsablePago', $cliente->ResponsablePago) }}"
                                            type="text">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ubicación de
                                        cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                            @foreach ($ubicaciones_cobro as $obj)
                                                @if ($obj->Id == $cliente->UbicacionCobro)
                                                    <option value="{{ $obj->Id }}" selected>
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $obj->Id }}">
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                    <a href="{{ url('catalogo/cliente') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
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
            $('#TipoPersona').change(function() {
                // if()
            })

        })
    </script>
@endsection