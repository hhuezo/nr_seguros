@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Cliente <small></small></h2>
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

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#cliente"
                                id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Cliente</a>

                        </li>
                        <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#redes"
                                role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Necesidades y gustos</a>
                        </li>

                        <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#pago"
                                role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Métodos de pago</a>
                        </li>
                    </ul>


                    <div id="myTabContent2" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} "
                            id="cliente" aria-labelledby="home-tab">
                            <form method="POST" action="{{ route('cliente.update', $cliente->Id) }}">
                                @method('PUT')
                                @csrf

                                <div class="x_content">
                                    <br />

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                                persona</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="TipoPersona" id="TipoPersona" class="form-control">
                                                    <option value="1"
                                                        {{ $cliente->TipoPersona == 1 ? 'selected' : '' }}>Natural
                                                    </option>
                                                    <option value="2"
                                                        {{ $cliente->TipoPersona == 2 ? 'selected' : '' }}>Jurídica
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">NIT</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->Nit }}" name="Nit"
                                                    data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text"
                                                    autofocus="true">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Dui</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->Dui }}" name="Dui"
                                                    data-inputmask="'mask': ['99999999-9']" data-mask type="text"
                                                    autofocus="true">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Nombre</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Nombre" value="{{ $cliente->Nombre }}"
                                                    type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Registro
                                                fiscal</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="RegistroFiscal"
                                                    value="{{ $cliente->RegistroFiscal }}" type="text">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                nacimiento</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="FechaNacimiento"
                                                    value="{{ $cliente->FechaNacimiento }}" type="date">
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Edad</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->Edad }}" type="text"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Estado
                                                familiar</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select class="form-control" name="EstadoFamiliar">
                                                    <option value="0"
                                                        {{ $cliente->EstadoFamiliar == 0 ? 'selected' : '' }}>No
                                                        Aplica</option>
                                                    <option value="1"
                                                        {{ $cliente->EstadoFamiliar == 1 ? 'selected' : '' }}>
                                                        Soltero</option>
                                                    <option value="2"
                                                        {{ $cliente->EstadoFamiliar == 2 ? 'selected' : '' }}>
                                                        Casado
                                                    </option>
                                                    <option value="3"
                                                        {{ $cliente->EstadoFamiliar == 3 ? 'selected' : '' }}>
                                                        Divorciado</option>
                                                    <option value="4"
                                                        {{ $cliente->EstadoFamiliar == 4 ? 'selected' : '' }}>Viudo
                                                    </option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Numero
                                                dependientes</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroDependientes"
                                                    value="{{ $cliente->NumeroDependientes }}" type="number">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Ocupación</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Ocupacion"
                                                    value="{{ $cliente->Ocupacion }}" type="text">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Dirección
                                                residencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <textarea class="form-control" name="DireccionResidencia">{{ $cliente->DireccionResidencia }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Teléfono
                                                residencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="TelefonoResidencia"
                                                    value="{{ $cliente->TelefonoResidencia }}"
                                                    data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Dirección
                                                correspondencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <textarea class="form-control" name="DireccionCorrespondencia">{{ $cliente->DireccionCorrespondencia }}</textarea>
                                            </div>
                                        </div>


                                    </div>


                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Teléfono
                                                oficina</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="TelefonoOficina"
                                                    value="{{ $cliente->TelefonoOficina }}"
                                                    data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Teléfono
                                                celular</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="TelefonoCelular"
                                                    value="{{ $cliente->TelefonoCelular }}"
                                                    data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Correo
                                                principal</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="CorreoPrincipal"
                                                    value="{{ $cliente->CorreoPrincipal }}" type="email">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Correo
                                                secundario</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="CorreoSecundario"
                                                    value="{{ $cliente->CorreoSecundario }}" type="email">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                vinculación</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="FechaVinculacion"
                                                    value="{{ $cliente->FechaVinculacion }}" type="date">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                baja</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="FechaBaja"
                                                    value="{{ $cliente->FechaBaja }}" type="date">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Responsable
                                                pago</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="ResponsablePago"
                                                    value="{{ $cliente->ResponsablePago }}" type="text">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Ubicación de
                                                cobro</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                                    @foreach ($ubicaciones_cobro as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->UbicacionCobro == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Forma
                                                pago</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="FormaPago" class="form-control" style="width: 100%">
                                                    @foreach ($formas_pago as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->FormaPago == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Estado</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="Estado" class="form-control" style="width: 100%">
                                                    @foreach ($cliente_estados as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->Estado == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Género</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="Genero" class="form-control">
                                                    <option value="1" {{ $cliente->Genero == 1 ? 'selected' : '' }}>
                                                        Masculino
                                                    </option>
                                                    <option value="2" {{ $cliente->Genero == 2 ? 'selected' : '' }}>
                                                        Femenino
                                                    </option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                                contribuyente</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="TipoContribuyente" class="form-control"
                                                    style="width: 100%">
                                                    @foreach ($tipos_contribuyente as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->TipoContribuyente == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Referencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Referencia"
                                                    value="{{ $cliente->Referencia }}" type="text">
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
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}"
                            id="redes" aria-labelledby="home-tab">
                            <form method="POST" action="{{ url('catalogo/cliente/red_social') }}">

                                @csrf

                                <div class="x_content">
                                    <br />
                                    <input type="hidden" name="Id" value="{{ $cliente->Id }}">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Contacto facebook</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->Facebook }}"
                                                    name="Facebook">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Actividades creativas</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->ActividadesCreativas }}"
                                                    name="ActividadesCreativas" type="text">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Estilo vida</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->EstiloVida }}"
                                                    name="EstiloVida" type="text">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sitio
                                                web</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="SitioWeb"
                                                    value="{{ $cliente->SitioWeb }}" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Necesidad proteccion</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="NecesidadProteccion" class="form-control"
                                                    style="width: 100%">
                                                    @foreach ($necesidades as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Dispositivo personal preferido</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" name="Laptop" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->Laptop == 1 ? 'checked' : '' }} /> Laptop
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;

                                                    <label>
                                                        <input type="checkbox" name="PC" value="1"
                                                            class="js-switch" {{ $cliente->PC == 1 ? 'checked' : '' }} />
                                                        PC
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;

                                                    <label>
                                                        <input type="checkbox" name="Tablet" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->Tablet == 1 ? 'checked' : '' }} /> Tablet
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <br>
                                                    <label>
                                                        <input type="checkbox" name="SmartWatch" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->SmartWatch == 1 ? 'checked' : '' }} /> SmartWatch
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label>
                                                        <input type="checkbox" name="DispositivosOtros" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->DispositivosOtros == 1 ? 'checked' : '' }} />
                                                        Otros dispositivos
                                                    </label>
                                                </div>

                                            </div>
                                        </div>



                                    </div>


                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Le
                                                gusta informarse</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="Informarse" class="form-control" style="width: 100%">
                                                    @foreach ($informarse as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->Informarse == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Contacto instagram</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" value="{{ $cliente->Instagram }}"
                                                    name="Instagram">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tiene
                                                mascota?</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <label>
                                                    <input type="checkbox" name="TieneMascota" value="1"
                                                        class="js-switch"
                                                        {{ $cliente->TieneMascota == 1 ? 'checked' : '' }} />
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Motivo eleccion</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="MotivoEleccion" class="form-control" style="width: 100%">
                                                    @foreach ($motivo_eleccion as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->MotivoEleccion == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Preferencia compra</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="PreferenciaCompra" class="form-control"
                                                    style="width: 100%">
                                                    @foreach ($preferencia_compra as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->PreferenciaCompra == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                                align="right">Compra habitualmente</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" name="Efectivo" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->Efectivo == 1 ? 'checked' : '' }} /> Efectivo
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;

                                                    <label>
                                                        <input type="checkbox" name="TarjetaCredito" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->TarjetaCredito == 1 ? 'checked' : '' }} /> Tarjeta
                                                        credito
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <br>
                                                    <label>
                                                        <input type="checkbox" name="App" value="1"
                                                            class="js-switch" {{ $cliente->App == 1 ? 'checked' : '' }} />
                                                        App
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label>
                                                        <input type="checkbox" name="MonederoEletronico" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->MonederoEletronico == 1 ? 'checked' : '' }} />
                                                        Monedero eletronico
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label>
                                                        <input type="checkbox" name="CompraOtros" value="1"
                                                            class="js-switch"
                                                            {{ $cliente->CompraOtros == 1 ? 'checked' : '' }} /> Otros
                                                    </label>
                                                </div>

                                            </div>
                                        </div>


                                    </div>


                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">¿Que información desea recibir frecuentemente?</label>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea name="Informacion" class="form-control">{{$cliente->Informacion}}</textarea>
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


                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}"
                            id="pago" aria-labelledby="home-tab">

                            <div class="col-12" style="text-align: right;">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target=".bs-modal-nuevo-tarjeta"><i class="fa fa-plus fa-lg"></i>
                                    Nuevo</button>
                            </div>

                            @if ($tarjetas->count() > 0)
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Numero tarjeta</th>
                                            <th>Fecha vencimiento</th>
                                            <th>Poliza vinculada</th>

                                            <th><i class="fa fa-trash fa-lg"></i> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tarjetas as $obj)
                                            <tr>
                                                <td>{{ $obj->NumeroTarjeta }}</td>
                                                <td>{{ $obj->FechaVencimiento }}</td>
                                                <td>{{ $obj->PolizaVinculada }}</td>
                                                <td><i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_tarjeta({{ $obj->Id }})"
                                                        data-target="#modal-delete-tarjeta" data-toggle="modal"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div style="height: 300px">
                                    <br>
                                    <div class="alert alert-danger alert-dismissible " role="alert">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close"><span aria-hidden="true">×</span>
                                        </button>
                                        <strong>Sin datos que mostrar.</strong>
                                    </div>
                                </div>
                            @endif



                        </div>
                    </div>
                </div>








                {{-- tabs inferiores --}}

                <div class="col-md-12 col-sm-12  ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><i class="fa fa-bars"></i> </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

                                    <li role="presentation" class="{{ session('tab2') == 1 ? 'active' : '' }}"><a
                                            href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab"
                                            aria-expanded="false">Contactos</a>
                                    </li>

                                    <li role="presentation" class="{{ session('tab2') == 2 ? 'active' : '' }}"><a
                                            href="#tab_content3" role="tab" id="uniforme-tab" data-toggle="tab"
                                            aria-expanded="false">Habitos consumo</a>
                                    </li>

                                    <li role="presentation" class="{{ session('tab2') == 3 ? 'active' : '' }}"><a
                                            href="#tab_content4" role="tab" id="profile-tab" data-toggle="tab"
                                            aria-expanded="false">Retroalimentacion</a>
                                    </li>


                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div role="tabpanel"
                                        class="tab-pane fade {{ session('tab2') == 1 ? 'active in' : '' }}"
                                        id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="col-12" style="text-align: right;">
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target=".bs-modal-nuevo-contacto"><i class="fa fa-plus fa-lg"></i>
                                                Nuevo</button>
                                        </div>
                                        @if ($contactos->count() > 0)
                                            <br>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Cargo</th>
                                                        <th>Telefono</th>
                                                        <th>Email</th>
                                                        <th>Lugar trabajo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($contactos as $obj)
                                                        <tr>
                                                            <td>{{ $obj->Nombre }}</td>
                                                            @if ($obj->cargo)
                                                                <td>{{ $obj->cargo->Nombre }}</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                            <td>{{ $obj->Telefono }}</td>
                                                            <td>{{ $obj->Email }}</td>
                                                            <td>{{ $obj->LugarTrabajo }}</td>
                                                            <td>
                                                                <i class="fa fa-pencil fa-lg"
                                                                    onclick="modal_edit_contacto({{ $obj->Id }},'{{ $obj->Cargo }}','{{ $obj->Nombre }}','{{ $obj->Telefono }}','{{ $obj->Email }}','{{ $obj->LugarTrabajo }}')"
                                                                    data-target="#modal-edit-contacto"
                                                                    data-toggle="modal"></i>
                                                                &nbsp;&nbsp;
                                                                <i class="fa fa-trash fa-lg"
                                                                    onclick="modal_delete_contacto({{ $obj->Id }})"
                                                                    data-target="#modal-delete-contacto"
                                                                    data-toggle="modal"></i>


                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div style="height: 200px">
                                                <br>
                                                <div class="alert alert-danger alert-dismissible " role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Sin datos que mostrar.</strong>
                                                </div>
                                            </div>
                                        @endif











                                    </div>
                                    <div role="tabpanel"
                                        class="tab-pane fade {{ session('tab2') == 2 ? 'active in' : '' }}"
                                        id="tab_content3" aria-labelledby="uniforme-tab">

                                        <div class="col-12" style="text-align: right;">
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target=".bs-modal-nuevo-habito"><i class="fa fa-plus fa-lg"></i>
                                                Nuevo</button>
                                        </div>

                                        @if ($habitos->count() > 0)
                                            <br>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Actividad economica</th>
                                                        <th>Ingreso promedio</th>
                                                        <th>Gasto mensual seguro</th>
                                                        <th>NivelEducativo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($habitos as $obj)
                                                        <tr>
                                                            <td>{{ $obj->ActividadEconomica }}</td>
                                                            <td>${{ $obj->IngresoPromedio }}</td>
                                                            <td>${{ $obj->GastoMensualSeguro }}</td>
                                                            <td>{{ $obj->NivelEducativo }}</td>
                                                            <td>
                                                                <i class="fa fa-pencil fa-lg"
                                                                    onclick="modal_edit_habito({{ $obj->Id }},'{{ $obj->ActividadEconomica }}','{{ $obj->IngresoPromedio }}','{{ $obj->GastoMensualSeguro }}','{{ $obj->NivelEducativo }}')"
                                                                    data-target="#modal-edit-habito"
                                                                    data-toggle="modal"></i>
                                                                &nbsp;&nbsp;
                                                                <i class="fa fa-trash fa-lg"
                                                                    onclick="modal_delete_habito({{ $obj->Id }})"
                                                                    data-target="#modal-delete-habito"
                                                                    data-toggle="modal"></i>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div style="height: 200px">
                                                <br>
                                                <div class="alert alert-danger alert-dismissible " role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Sin datos que mostrar.</strong>
                                                </div>
                                            </div>
                                        @endif



                                    </div>

                                    <div role="tabpanel"
                                        class="tab-pane fade {{ session('tab2') == 3 ? 'active in' : '' }}"
                                        id="tab_content4" aria-labelledby="uniforme-tab">
                                        <div class="col-12" style="text-align: right;">
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target=".bs-modal-nuevo-retroalimentacion"><i
                                                    class="fa fa-plus fa-lg"></i>
                                                Nuevo</button>
                                        </div>

                                        @if ($retroalimentacion->count() > 0)
                                            <br>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Valores agregados</th>
                                                        <th>Competidores</th>
                                                        <th>Referidos</th>
                                                        <th>Que quisiera?</th>
                                                        <th>Servicio al cliente</th>
                                                        <th>Acciones </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($retroalimentacion as $obj)
                                                        <tr>
                                                            <td>{{ $obj->Producto }}</td>
                                                            <td>{{ $obj->ValoresAgregados }}</td>
                                                            <td>{{ $obj->Competidores }}</td>
                                                            <td>{{ $obj->Referidos }}</td>
                                                            <td>{{ $obj->QueQuisiera }}</td>
                                                            <td>
                                                                @for ($i = 1; $i < 6; $i++)
                                                                    @if ($i <= $obj->ServicioCliente)
                                                                        <i class="fa fa-star fa-lg"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o fa-lg"></i>
                                                                    @endif
                                                                @endfor
                                                            </td>
                                                            <td>
                                                                <i class="fa fa-pencil fa-lg"
                                                                    onclick="modal_edit_retroalimentacion({{ $obj->Id }},'{{ $obj->Producto }}','{{ $obj->ValoresAgregados }}','{{ $obj->Competidores }}','{{ $obj->Referidos }}','{{ $obj->QueQuisiera }}','{{ $obj->ServicioCliente }}')"
                                                                    data-target="#modal-edit-retroalimentacion"
                                                                    data-toggle="modal"></i>
                                                                &nbsp;&nbsp;
                                                                <i class="fa fa-trash fa-lg"
                                                                    onclick="modal_delete_retroalimentacion({{ $obj->Id }})"
                                                                    data-target="#modal-delete-retroalimentacion"
                                                                    data-toggle="modal"></i>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div style="height: 200px">
                                                <br>
                                                <div class="alert alert-danger alert-dismissible " role="alert">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Sin datos que mostrar.</strong>
                                                </div>
                                            </div>
                                        @endif




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>


        {{-- ventanas modales --}}
        <div class="col-12">
            <div class="modal fade bs-modal-nuevo-tarjeta" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/add_tarjeta') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Nueva tarjeta</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        NumeroTarjeta
                                        <input type="text" class="form-control"
                                            data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask required
                                            name="NumeroTarjeta">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Fecha vencimiento
                                        <input type="text" class="form-control" data-inputmask="'mask': ['99/99']"
                                            data-mask required name="FechaVencimiento">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Poliza vinculada
                                        <input type="text" name="PolizaVinculada" class="form-control" required>
                                    </div>
                                </div>




                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                id="modal-delete-tarjeta">

                <form method="POST" action="{{ url('catalogo/cliente/delete_tarjeta') }}">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <input type="hidden" name="Id" id="IdTarjeta">
                                <h4 class="modal-title">Eliminar Registro</h4>
                            </div>
                            <div class="modal-body">
                                <p>Confirme si desea Eliminar el Registro</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- modales contactos --}}
        <div class="col-12">
            <div class="modal fade bs-modal-nuevo-contacto" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/add_contacto') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Nuevo contacto</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Nombre
                                        <input type="text" name="Nombre" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Cargo
                                        <select name="Cargo" class="form-control" required>
                                            @foreach ($cliente_contacto_cargos as $cargo)
                                                <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Telefono
                                        <input type="text" name="Telefono" data-inputmask="'mask': ['9999-9999']"
                                            data-mask class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Email
                                        <input type="email" class="form-control" required name="Email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Lugar trabajo
                                        <input type="text" class="form-control" required name="LugarTrabajo">
                                    </div>
                                </div>



                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="modal fade modal-edit-contacto" tabindex="-1" role="dialog" aria-hidden="true"
                id="modal-edit-contacto">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/edit_contacto') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Editar contacto</h4>
                                <input type="hidden" name="Id" id="ModalContactoId" class="form-control" required>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Nombre
                                        <input type="text" name="Nombre" id="ModalContactoNombre"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Cargo
                                        <select name="Cargo" id="ModalContactoCargo" class="form-control" required>
                                            @foreach ($cliente_contacto_cargos as $cargo)
                                                <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Telefono
                                        <input type="text" name="Telefono" id="ModalContactoTelefono"
                                            data-inputmask="'mask': ['9999-9999']" data-mask class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Email
                                        <input type="email" required name="Email" id="ModalContactoEmail"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Lugar trabajo
                                        <input type="text" name="LugarTrabajo" id="ModalContactoLugarTrabajo"
                                            class="form-control" required>
                                    </div>
                                </div>



                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                id="modal-delete-contacto">

                <form method="POST" action="{{ url('catalogo/cliente/delete_contacto') }}">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <input type="hidden" name="Id" id="IdContacto">
                                <h4 class="modal-title">Eliminar Registro</h4>
                            </div>
                            <div class="modal-body">
                                <p>Confirme si desea Eliminar el Registro</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- modales habito --}}
        <div class="col-12">
            <div class="modal fade bs-modal-nuevo-habito" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/add_habito') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Nuevo habito</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Actividad economica
                                        <input type="text" name="ActividadEconomica" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Ingreso promedio
                                        <input type="number" step="0.001" min="0" class="form-control"
                                            required name="IngresoPromedio">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Gasto mensual seguro
                                        <input type="number" step="0.001" min="0" name="GastoMensualSeguro"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Nivel educativo
                                        <input type="text" name="NivelEducativo" class="form-control" required>
                                    </div>
                                </div>

                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                id="modal-delete-habito">

                <form method="POST" action="{{ url('catalogo/cliente/delete_habito') }}">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <input type="hidden" name="Id" id="IdHabito">
                                <h4 class="modal-title">Eliminar Registro</h4>
                            </div>
                            <div class="modal-body">
                                <p>Confirme si desea Eliminar el Registro</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12">
            <div class="modal fade modal-edit-habito" id="modal-edit-habito" tabindex="-1" role="dialog"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/edit_habito') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Editar habito</h4>
                                <input type="hidden" name="Id" id="ModalHabitoId" class="form-control">
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Actividad economica
                                        <input type="text" name="ActividadEconomica"
                                            id="ModalHabitoActividadEconomica" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Ingreso promedio
                                        <input type="number" name="IngresoPromedio" id="ModalHabitoIngresoPromedio"
                                            step="0.001" min="0" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Gasto mensual seguro
                                        <input type="number" step="0.001" min="0" name="GastoMensualSeguro"
                                            id="ModalHabitoGastoMensualSeguro" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Nivel educativo
                                        <input type="text" name="NivelEducativo" id="ModalHabitoNivelEducativo"
                                            class="form-control" required>
                                    </div>
                                </div>

                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>
        {{-- modales retroalimentacion --}}
        <div class="col-12">
            <div class="modal fade bs-modal-nuevo-retroalimentacion" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/add_retroalimentacion') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Retroalimentacion</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Producto de NR
                                        <input type="text" name="Producto" required class="form-control">
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Valores agregados
                                        <input type="text" name="ValoresAgregados" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Competidores
                                        <input type="text" name="Competidores" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Referidos
                                        <input type="text" name="Referidos" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Que quisiera?
                                        <input type="text" name="QueQuisiera" class="form-control" required>
                                    </div>
                                </div>

                                <input type="hidden" name="ServicioCliente" id="ServicioCliente" value="0"
                                    class="form-control" required>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Servicio al ciente <br>
                                        <div id="stars">
                                            <i class="fa fa-star-o fa-2x" onclick="check_stars(1)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="check_stars(2)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="check_stars(3)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="check_stars(4)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="check_stars(5)"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="modal fade modal-edit-retroalimentacion" id="modal-edit-retroalimentacion" tabindex="-1"
                role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ url('catalogo/cliente/edit_retroalimentacion') }}">
                        @csrf
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Editar retroalimentacion</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                                <input type="hidden" name="Id" id="ModalRetroId" class="form-control">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Producto de NR
                                        <input type="text" name="Producto" id="ModalRetroProducto" required class="form-control">
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Valores agregados
                                        <input type="text" name="ValoresAgregados" id="ModalRetroValoresAgregados" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Competidores
                                        <input type="text" name="Competidores" id="ModalRetroCompetidores" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Referidos
                                        <input type="text" name="Referidos" id="ModalRetroReferidos" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Que quisiera?
                                        <input type="text" name="QueQuisiera" id="ModalRetroQueQuisiera"  class="form-control"
                                            required>
                                    </div>
                                </div>

                                <input type="hidden" name="ServicioCliente" id="ModalRetroServicioCliente"
                                    class="form-control" required>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        Servicio al ciente <br>
                                        <div id="modal_stars">
                                            <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(1)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(2)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(3)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(4)"></i>
                                            <i class="fa fa-star-o fa-2x" onclick="modal_check_stars(5)"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div>&nbsp; </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                id="modal-delete-retroalimentacion">

                <form method="POST" action="{{ url('catalogo/cliente/delete_retroalimentacion') }}">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <input type="hidden" name="Id" id="IdRetroalimentacion">
                                <h4 class="modal-title">Eliminar Registro</h4>
                            </div>
                            <div class="modal-body">
                                <p>Confirme si desea Eliminar el Registro</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- jQuery -->
        <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {

            });

            function modal_delete_tarjeta(id) {
                document.getElementById('IdTarjeta').value = id;
                //$('#modal_borrar_documento').modal('show');
            }

            function modal_edit_contacto(id, cargo, nombre, telefono, email, lugar) {
                document.getElementById('ModalContactoId').value = id;
                document.getElementById('ModalContactoCargo').value = cargo;
                document.getElementById('ModalContactoNombre').value = nombre;
                document.getElementById('ModalContactoTelefono').value = telefono;
                document.getElementById('ModalContactoEmail').value = email;
                document.getElementById('ModalContactoLugarTrabajo').value = lugar;
                //$('#modal_borrar_documento').modal('show');
            }

            function modal_delete_contacto(id) {
                document.getElementById('IdContacto').value = id;
                $('#modal_borrar_documento').modal('show');
            }

            function modal_delete_habito(id) {
                document.getElementById('IdHabito').value = id;
                //$('#modal_borrar_documento').modal('show');
            }

            function modal_edit_habito(id, actividad, ingreso, gasto, nivel) {
                //alert(ingreso);
                document.getElementById('ModalHabitoId').value = id;
                document.getElementById('ModalHabitoActividadEconomica').value = actividad;
                document.getElementById('ModalHabitoIngresoPromedio').value = ingreso;
                document.getElementById('ModalHabitoGastoMensualSeguro').value = gasto;
                document.getElementById('ModalHabitoNivelEducativo').value = nivel;
                //$('#modal_borrar_documento').modal('show');
            }

            function modal_delete_retroalimentacion(id) {
                document.getElementById('IdRetroalimentacion').value = id;
                //$('#modal_borrar_documento').modal('show');
            }

            function modal_edit_retroalimentacion(id, producto, valores, competidores, referidos,quisiera,servicio) {
      
                document.getElementById('ModalRetroId').value = id;
                document.getElementById('ModalRetroProducto').value = producto;
                document.getElementById('ModalRetroValoresAgregados').value = valores;
                document.getElementById('ModalRetroCompetidores').value = competidores;
                document.getElementById('ModalRetroReferidos').value = referidos;
                document.getElementById('ModalRetroQueQuisiera').value = quisiera;
                document.getElementById('ModalRetroServicioCliente').value = servicio;
                modal_check_stars(servicio);
                //$('#modal_borrar_documento').modal('show');
            }

            //onclick="modal_edit_retroalimentacion({{ $obj->Id }},'{{ $obj->Producto }}','{{ $obj->ValoresAgregados }}',
            //'{{ $obj->Competidores }}','{{ $obj->Referidos }}','{{ $obj->QueQuisiera }}','{{ $obj->ServicioCliente }}')"

                                                                    
            function check_stars(id) {
                //console.log("id: " + id);
                document.getElementById('ServicioCliente').value = id;

                string_star = "";
                for (i = 1; i < 6; i++) {
                    if (i <= id) {
                        string_star = string_star + '<i class="fa fa-star fa-2x" onclick="check_stars(' + i + ')"></i>';
                    } else {
                        string_star = string_star + '<i class="fa fa-star-o fa-2x" onclick="check_stars(' + i + ')"></i>';
                    }
                }
                $('#stars').html(string_star);
            }

            function modal_check_stars(id) {
                //console.log("id: " + id);
                document.getElementById('ModalRetroServicioCliente').value = id;

                string_star = "";
                for (i = 1; i < 6; i++) {
                    if (i <= id) {
                        string_star = string_star + '<i class="fa fa-star fa-2x" onclick="modal_check_stars(' + i + ')"></i>';
                    } else {
                        string_star = string_star + '<i class="fa fa-star-o fa-2x" onclick="modal_check_stars(' + i + ')"></i>';
                    }
                }
                $('#modal_stars').html(string_star);
            }
        </script>

    @endsection
