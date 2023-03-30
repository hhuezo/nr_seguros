@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Modificar cliente <small></small></h2>
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


                <form method="POST" action="{{ route('cliente.update', $cliente->Id) }}">
                    @method('PUT')
                    @csrf

                    <div class="x_content">
                        <br />

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">NIT</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nit" value="{{ $cliente->Nit }}"
                                        data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text"
                                        autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Dui" value="{{ $cliente->Dui }}"
                                        data-inputmask="'mask': ['99999999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nombre" value="{{ $cliente->Nombre }}" type="text"
                                        onblur="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionResidencia" onblur="this.value = this.value.toUpperCase();">{{ $cliente->DireccionResidencia }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    correspondecia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionCorrespondencia" onblur="this.value = this.value.toUpperCase();">{{ $cliente->DireccionCorrespondencia }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoResidencia" value="{{ $cliente->TelefonoResidencia }}"
                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    oficina</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoOficina" value="{{ $cliente->TelefonoOficina }}"
                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    celular</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoCelular" value="{{ $cliente->TelefonoCelular }}"
                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                    electrónico</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Correo" value="{{ $cliente->Correo }}" type="email">
                                </div>
                            </div>

                        </div>








                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ruta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Ruta" class="form-control select2" style="width: 100%">
                                        @foreach ($rutas as $obj)
                                            @if ($obj->Id == $cliente->Ruta)
                                                <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                            @else
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Responsable
                                    pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input name="ResponsablePago" value="{{ $cliente->ResponsablePago }}" type="text"
                                        class="form-control" onblur="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    contribuyente</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoContribuyente" class="form-control" style="width: 100%">
                                        @foreach ($tipos_contribuyente as $obj)
                                            @if ($obj->Id == $cliente->TipoContribuyente)
                                                <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                            @else
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ubicación de
                                    cobro</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                        @foreach ($ubicaciones_cobro as $obj)
                                            @if ($obj->Id == $cliente->UbicacionCobro)
                                                <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                                            @else
                                                <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Contacto</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input name="Contacto" type="text" value="{{ $cliente->Contacto }}"
                                        class="form-control" onblur="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                    align="right">Referencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input name="Referencia" type="text" value="{{ $cliente->Referencia }}"
                                        class="form-control" onblur="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número
                                    tarjeta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input name="NumeroTarjeta" value="{{ $cliente->NumeroTarjeta }}" class="form-control"
                                        data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    vencimiento</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="FechaVencimiento" value="{{ $cliente->FechaVencimiento }}"
                                        class="form-control" data-inputmask="'mask': ['99/99']" data-mask>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Género</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Genero" class="form-control">
                                        @if ($cliente->Genero == 1)
                                            <option value="1" selected>Masculino</option>
                                        @else
                                            <option value="1">Masculino</option>
                                        @endif

                                        @if ($cliente->Genero == 2)
                                            <option value="2" selected>Femenino</option>
                                        @else
                                            <option value="2">Femenino</option>
                                        @endif

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    persona</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoPersona" class="form-control">
                                        @if ($cliente->TipoPersona == 1)
                                            <option value="1" selected>Natural</option>
                                        @else
                                            <option value="1">Natural</option>
                                        @endif

                                        @if ($cliente->TipoPersona == 2)
                                            <option value="2" selected>Jurídica</option>
                                        @else
                                            <option value="2">Jurídica</option>
                                        @endif

                                    </select>
                                </div>
                            </div>

                        </div>





                    </div>

                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Modificar</button>
                        <a href="{{ url('catalogo/cliente/') }}"><button class="btn btn-primary"
                                type="button">Cancelar</button></a>
                    </div>

                </form>



            </div>


        </div>
    </div>
    @include('sweetalert::alert')
    </div>
@endsection
