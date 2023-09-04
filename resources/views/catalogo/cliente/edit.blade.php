@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<?php

$cumpleanos = new DateTime($cliente->FechaNacimiento);
$hoy = new DateTime();
$annos = $hoy->diff($cumpleanos);
$annos->y;

?>
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Cliente <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">

                    <a href="{{url('catalogo/cliente')}}" class="btn btn-info fa fa-undo " style="color: white"></a>
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
                    <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#cliente" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Cliente</a>

                    </li>
                    <li role="presentation" class="{{ session('tab1') == 3 ? 'active' : '' }}"><a href="#pago" role="tab" id="profile-metodo" data-toggle="tab" aria-expanded="false">Métodos de
                            pago</a>
                    </li>
                    <li role="presentation" class="{{ session('tab1') == 4 ? 'active' : '' }}"><a href="#contacto" role="tab" id="profile-contacto" data-toggle="tab" aria-expanded="false">Libreta de Contactos</a>
                    </li>

                    <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#redes" role="tab" id="profile-necesidad" data-toggle="tab" aria-expanded="false">Necesidades, Preferencias y
                            Gustos</a>
                    </li>
                    <li role="presentation" class="{{ session('tab1') == 5 ? 'active' : '' }}"><a href="#habito" role="tab" id="profile-habito" data-toggle="tab" aria-expanded="false">Hábitos de
                            Consumo</a>
                    </li>
                    <li role="presentation" class="{{ session('tab1') == 6 ? 'active' : '' }}"><a href="#retroalimentacion" role="tab" id="profile-habito" data-toggle="tab" aria-expanded="false">Retroalimentación de NR</a>
                    </li>
                    <li role="presentation" class="{{ session('tab1') == 7 ? 'active' : '' }}"><a href="#documentacion" role="tab" id="profile" data-toggle="tab" aria-expanded="false">Documentación</a>
                    </li>


                </ul>


                <div id="myTabContent2" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} " id="cliente" aria-labelledby="home-tab">
                        <form method="POST" action="{{ route('cliente.update', $cliente->Id) }}">
                            @method('PUT')
                            @csrf

                            <div class="x_content">
                                <br />

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="Nombre" class="form-label">NIT</label>
                                            <input class="form-control" name="Nit" id="Nit" value="{{ $cliente->Nit }}" @if($cliente->TipoPersona == 1 && ($cliente->Dui == $cliente->Nit)) data-inputmask="'mask': ['99999999-9']" readonly @else data-inputmask="'mask': ['9999-999999-999-9']" @endif data-mask type="text">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Genero" class="form-label">Estado Cliente</label>
                                            <select name="Estado" class="form-control" style="width: 100%">
                                                @foreach ($cliente_estados as $obj)
                                                <option value="{{ $obj->Id }}" {{ $cliente->Estado == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
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
                                                    <input class="form-control" name="Dui" id="Dui" value="{{ $cliente->Dui}}" data-inputmask="'mask': ['99999999-9']" data-mask type="text">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row" id="Homolo">
                                                        <label for="Nombre" class="form-label">¿Homologado?</label><br>
                                                        <input name="Homologado" id="Homologado" type="checkbox" onchange="validaciones.cambiarEstado()"  @if($cliente->TipoPersona == 1 && ($cliente->Dui == $cliente->Nit)) checked @else @endif />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="TipoPersona" class="form-label">Tipo Persona</label>
                                            <select name="TipoPersona" id="TipoPersona" onchange="validaciones.cboTipoPersona(this.value)" class="form-control">
                                                <option value="1" {{ $cliente->TipoPersona == 1 ? 'selected' : '' }}>Natural
                                                </option>
                                                <option value="2" {{ $cliente->TipoPersona == 2 ? 'selected' : '' }}>Jurídica
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-lg-6">
                                            <label for="Nombre" class="form-label">Registro Fiscal</label>
                                            <input class="form-control" name="RegistroFiscal" id="RegistroFiscal" value="{{ $cliente->RegistroFiscal}}" type="text">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Genero" class="form-label">Género</label>
                                            <select name="Genero" id="Genero" class="form-control">
                                                <option value="" selected disabled>Seleccione ...</option>
                                                <option value="1" {{ $cliente->Genero == 1 ? 'selected' : '' }}>Masculino
                                                </option>
                                                <option value="2" {{ $cliente->Genero == 2 ? 'selected' : '' }}>Femenino
                                                </option>
                                                <option value="3" {{ $cliente->Genero == 3 ? 'selected' : '' }}>No Aplica
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-lg-6">
                                            <label for="Nombre" class="form-label">Nombre o Razón Social</label>
                                            <input class="form-control" id="Nombre" name="Nombre" value="{{ $cliente->Nombre }}" type="text">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Nombre" class="form-label">Tipo Contribuyente</label>
                                            <select name="TipoContribuyente" id="TipoContribuyente" class="form-control" onchange="validaciones.cboTipoContribuyente(this.value)" style="width: 100%">
                                                <option value="" disabled selected>Seleccione ...</option>
                                                @foreach ($tipos_contribuyente as $obj)
                                                <option value="{{ $obj->Id }}" {{ $cliente->TipoContribuyente == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="FechaNacimiento" class="form-label">Fecha Nacimiento o Fundación Sociedad</label>
                                            <input class="form-control" name="FechaNacimiento" id="FechaNacimiento" value="{{ $cliente->FechaNacimiento }}" type="date">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Referencia" class="form-label">Vinculado al Grupo o Referencia</label>
                                            <input class="form-control" name="Referencia" id="Referencia" value="{{ $cliente->Referencia }}" type="text">
                                        </div>
                                    </div>

                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="FechaNacimiento" class="form-label">Edad</label>
                                            <input class="form-control" id="EdadCalculada" value="<?php echo $annos->y; ?>" type="text" disabled>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-lg-6">
                                            <label for="Genero" class="form-label">Estado Familiar</label>
                                            <select class="form-control" name="EstadoFamiliar" id="EstadoFamiliar">
                                                <option value="" selected disabled>Seleccione ...</option>
                                                <option value="0" {{ $cliente->EstadoFamiliar == 0 ? 'selected' : '' }}>No Aplica
                                                </option>
                                                <option value="1" {{ $cliente->EstadoFamiliar == 1 ? 'selected' : '' }}>Soltero
                                                </option>
                                                <option value="2" {{ $cliente->EstadoFamiliar == 2 ? 'selected' : '' }}>Casado
                                                </option>
                                                <option value="3" {{ $cliente->EstadoFamiliar == 3 ? 'selected' : '' }}>
                                                    Divorciado
                                                </option>
                                                <option value="4" {{ $cliente->EstadoFamiliar == 4 ? 'selected' : '' }}>Viudo
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-lg-6">
                                            <label for="NumeroDependientes" class="form-label">Número Dependientes</label>
                                            <input class="form-control" name="NumeroDependientes" id="NumeroDependientes" value="{{ $cliente->NumeroDependientes }}" type="number">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Genero" class="form-label">Responsable de Pago</label>
                                            <input class="form-control" id="ResponsablePago" name="ResponsablePago" value="{{ $cliente->ResponsablePago }}" type="text">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-lg-6">
                                            <label for="Genero" class="form-label">Ocupación</label>
                                            <input class="form-control" id="Ocupacion" name="Ocupacion" value="{{ $cliente->Ocupacion }}" type="text">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Genero" class="form-label">Ubicación de cobro</label>
                                            <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                                <option value="" selected disabled>Seleccione ...</option>
                                                @foreach ($ubicaciones_cobro as $obj)
                                                <option value="{{ $obj->Id }}" {{ $cliente->UbicacionCobro == $obj->Id ? 'selected' : '' }}>
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
                                            <select id="Departamento" class="form-control" style="width: 100%">
                                                @foreach ($departamentos as $obj)
                                                <option value="{{ $obj->Id }}" {{ $departamento_actual == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="DireccionResidencia" class="form-label">Dirección Residencia</label>
                                            <textarea class="form-control" name="DireccionResidencia">{{ $cliente->DireccionResidencia }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="DireccionResidencia" class="form-label">Municipio</label>
                                            <select name="Municipio" id="Municipio" class="form-control select2" style="width: 100%">
                                                @foreach ($municipios as $obj)
                                                <option value="{{ $obj->Id }}" {{ $municipio_actual == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="DireccionResidencia" class="form-label">Dirección
                                                Correspondencia</label>
                                            <textarea class="form-control" name="DireccionCorrespondencia">{{ $cliente->DireccionCorrespondencia }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="DireccionResidencia" class="form-label">Distrito</label>
                                            <select id="Distrito" name="Distrito" required class="form-control select2" style="width: 100%">
                                                @foreach ($distritos as $obj)
                                                <option value="{{ $obj->Id }}" {{ $cliente->Distrito == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="Referencia" class="form-label">Teléfono Principal</label>
                                            <input class="form-control" name="TelefonoCelular" value="{{ $cliente->TelefonoCelular }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Referencia" class="form-label">Teléfono Principal</label>
                                            <input class="form-control" name="TelefonoCelular" value="{{ $cliente->TelefonoCelular }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="Referencia" class="form-label">Teléfono Residencia</label>
                                            <input class="form-control" name="TelefonoResidencia" value="{{ $cliente->TelefonoResidencia }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="Referencia" class="form-label">Teléfono Oficina</label>
                                            <input class="form-control" name="TelefonoOficina" value="{{ $cliente->TelefonoOficina }}" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="CorreoPrincipal" class="form-label">Correo Principal</label>
                                            <input class="form-control" name="CorreoPrincipal" value="{{ $cliente->CorreoPrincipal }}" type="email">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="CorreoPrincipal" class="form-label">Correo Secundario</label>
                                            <input class="form-control" name="CorreoSecundario" value="{{ $cliente->CorreoSecundario }}" type="email">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="FechaVinculacion" class="form-label">Fecha Vinculación</label>
                                            <input class="form-control" name="FechaVinculacion" value="{{ $cliente->FechaVinculacion }}" type="date">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-6">
                                            <label for="FechaVinculacion" class="form-label">Fecha Baja Cliente</label>
                                            <input class="form-control" name="FechaBaja" value="{{ $cliente->FechaBaja }}" type="date">
                                        </div>
                                    </div>

                                    <div class="row" style="padding-top: 15px!important;">
                                        <div class="col-md-12">
                                            <label for="Comentarios" class="form-label">Comentarios</label>
                                            <textarea class="form-control" name="Comentarios">{{ $cliente->Comentarios }}</textarea>
                                        </div>
                                    </div>


                                </div>




                            </div>

                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Aceptar</button>
                                <a href="{{ url('catalogo/cliente/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                            </div>

                        </form>


                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}" id="redes" aria-labelledby="home-tab">
                        <form method="POST" action="{{ url('catalogo/cliente/red_social') }}">
                            @csrf
                            <div class="x_content">
                                <br />
                                <input type="hidden" name="Id" value="{{ $cliente->Id }}">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group row">
                                        <label class="control-label ">Contacto Facebook</label>
                                        <input class="form-control" value="{{ $cliente->Facebook }}" name="Facebook">
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">Actividades Creativas</label>

                                        <input class="form-control" value="{{ $cliente->ActividadesCreativas }}" name="ActividadesCreativas" type="text">

                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">Estilo Vida</label>

                                        <input class="form-control" value="{{ $cliente->EstiloVida }}" name="EstiloVida" type="text">

                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">Sitio Web</label>

                                        <input class="form-control" name="SitioWeb" value="{{ $cliente->SitioWeb }}" type="text">

                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label ">Necesidad Protección</label>

                                        <select name="NecesidadProteccion" class="form-control" style="width: 100%">
                                            @foreach ($necesidades as $obj)
                                            <option value="{{ $obj->Id }}" {{ $cliente->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">Dispositivo personal preferido, Numerar en orden de preferencia del 1 al 6</label>
                                        <br>

                                        <div class="col-md-4">
                                            <label class="control-label ">Smartphones</label>

                                            <input class="form-control" name="Smartphone" value="@if($cliente->Smartphone <> 0){{$cliente->Smartphone}}@endif" type="number" required maxlength="1" min="0" Max="6">

                                            <label class="control-label ">Laptop</label>

                                            <input class="form-control" name="Laptop" value="@if($cliente->Laptop <> 0){{$cliente->Laptop}}@endif" type="number" required maxlength="1" min="0" Max="6">

                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label ">PC</label>

                                            <input class="form-control" name="PC" value="@if($cliente->PC <> 0){{$cliente->PC}}@endif" type="number" required maxlength="1" min="0" Max="6">

                                            <label class="control-label ">Tablet</label>

                                            <input class="form-control" name="Tablet" value="@if($cliente->Tablet <> 0){{$cliente->Tablet}}@endif" type="number" required maxlength="1" min="0" Max="6">

                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label ">SmartWatch</label>

                                            <input class="form-control" name="SmartWatch" value="@if($cliente->SmartWatch <> 0){{$cliente->SmartWatch}}@endif" type="number" required maxlength="1" min="0" Max="6">

                                            <label class="control-label ">Otros Dispositivos</label>

                                            <input class="form-control" name="DispositivosOtros" value="@if($cliente->DispositivosOtros <> 0){{$cliente->DispositivosOtros}}@endif" required type="number" maxlength="1" min="0" Max="6">

                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">Le Gusta Informarse</label>

                                        <select name="Informarse" class="form-control" style="width: 100%">
                                            @foreach ($informarse as $obj)
                                            <option value="{{ $obj->Id }}" {{ $cliente->Informarse == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <div class="form-group row">
                                        <label class="control-label ">Contacto Instagram</label>

                                        <input class="form-control" value="{{ $cliente->Instagram }}" name="Instagram">
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">¿Tiene mascota?</label> &nbsp;
                                        <input type="checkbox" name="TieneMascota" value="1" class="js-switch" {{ $cliente->TieneMascota == 1 ? 'checked' : '' }} />
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label ">Compañia de su Preferencia</label>

                                        <select name="AseguradoraPreferencia" class="form-control" style="width: 100%">

                                            @foreach ($aseguradoras as $obj)
                                            <option value="{{ $obj->Id }}" {{ $cliente->AseguradoraPreferencia == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label ">Motivo de Elección</label>

                                        <select name="MotivoEleccion" id="MotivoEleccion" class="form-control col-md-4" style="width: 100%">
                                            @foreach ($motivo_eleccion as $obj)
                                            <option value="{{ $obj->Id }}" {{ $cliente->MotivoEleccion == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <span class="fa fa-plus" onclick="addMotivo();" class="col-md-2" style="padding-left: 75%;"></span>

                                    </div>


                                    <div class="form-group row">
                                        <label class="control-label ">Preferencia de Compra</label>

                                        <select name="PreferenciaCompra" id="PreferenciaCompra" class="form-control col-md-4" style="width: 100%">
                                            @foreach ($preferencia_compra as $obj)
                                            <option value="{{ $obj->Id }}" {{ $cliente->PreferenciaCompra == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                            @endforeach
                                        </select>

                                        <span class="fa fa-plus" onclick="addPreferencia();" class="col-md-2" style="padding-left: 70%;"></span>

                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label ">Compra Habitualmente</label>
                                        <div class="">
                                            <label>
                                                <input type="checkbox" name="Efectivo" value="1" class="js-switch" {{ $cliente->Efectivo == 1 ? 'checked' : '' }} /> Efectivo
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;

                                            <label>
                                                <input type="checkbox" name="TarjetaCredito" value="1" class="js-switch" {{ $cliente->TarjetaCredito == 1 ? 'checked' : '' }} /> Tarjeta
                                                credito
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <br>
                                            <label>
                                                <input type="checkbox" name="App" value="1" class="js-switch" {{ $cliente->App == 1 ? 'checked' : '' }} />
                                                App
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="checkbox" name="MonederoEletronico" class="js-switch" value="1" {{ $cliente->MonederoEletronico == 1 ? 'checked' : '' }} />
                                                Monedero eletronico
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="checkbox" name="CompraOtros" class="js-switch" value="1" {{ $cliente->CompraOtros == 1 ? 'checked' : '' }} /> Otros
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">¿Que información desea recibir
                                        frecuentemente?</label>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <textarea name="Informacion" class="form-control">{{ $cliente->Informacion }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Aceptar</button>
                                <a href="{{ url('catalogo/cliente/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 3 ? 'active in' : '' }}" id="pago" aria-labelledby="home-tab">

                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-tarjeta"><i class="fa fa-plus fa-lg"></i>
                                Nuevo</button>
                        </div>

                        @if ($tarjetas->count() > 0)
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Método Pago</th>
                                    <th>Número Tarjeta</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Póliza Vinculada</th>
                                    <th>Acciones </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarjetas as $obj)
                                <tr>
                                    @if ($obj->MetodoPago)
                                    <td>{{ $obj->metodo_pago->Nombre }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td>{{ $obj->NumeroTarjeta }}</td>
                                    <td>{{ $obj->FechaVencimiento }}</td>
                                    <td>{{ $obj->PolizaVinculada }}</td>
                                    <td>
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_tarjeta({{ $obj->Id }},'{{ $obj->MetodoPago }}','{{ $obj->NumeroTarjeta }}','{{ $obj->FechaVencimiento }}','{{ $obj->PolizaVinculada }}')" data-target="#modal-edit-tarjeta" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_tarjeta({{ $obj->Id }})" data-target="#modal-delete-tarjeta" data-toggle="modal"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 300px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif



                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 4 ? 'active in' : '' }}" id="contacto" aria-labelledby="home-tab">

                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-contacto"><i class="fa fa-plus fa-lg"></i>
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
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_contacto({{ $obj->Id }},'{{ $obj->Cargo }}','{{ $obj->Nombre }}','{{ $obj->Telefono }}','{{ $obj->Email }}','{{ $obj->LugarTrabajo }}')" data-target="#modal-edit-contacto" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_contacto({{ $obj->Id }})" data-target="#modal-delete-contacto" data-toggle="modal"></i>


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 5 ? 'active in' : '' }}" id="habito" aria-labelledby="home-tab">
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-habito"><i class="fa fa-plus fa-lg"></i>
                                Nuevo</button>
                        </div>

                        @if ($habitos->count() > 0)
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Actividad economica</th>
                                    <th>Ingreso promedio</th>
                                    <th>Porción de ingresos que gasta en seguros mensual</th>
                                    <th>Nivel Educativo</th>
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
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_habito({{ $obj->Id }},'{{ $obj->ActividadEconomica }}','{{ $obj->IngresoPromedio }}','{{ $obj->GastoMensualSeguro }}','{{ $obj->NivelEducativo }}')" data-target="#modal-edit-habito" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_habito({{ $obj->Id }})" data-target="#modal-delete-habito" data-toggle="modal"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 6 ? 'active in' : '' }}" id="retroalimentacion" aria-labelledby="home-tab">
                        <div class="col-12" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-nuevo-retroalimentacion"><i class="fa fa-plus fa-lg"></i>
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
                                        @for ($i = 1; $i < 6; $i++) @if ($i <=$obj->ServicioCliente)
                                            <i class="fa fa-star fa-lg"></i>
                                            @else
                                            <i class="fa fa-star-o fa-lg"></i>
                                            @endif
                                            @endfor
                                    </td>
                                    <td>
                                        <i class="fa fa-pencil fa-lg" onclick="modal_edit_retroalimentacion({{ $obj->Id }},'{{ $obj->Producto }}','{{ $obj->ValoresAgregados }}','{{ $obj->Competidores }}','{{ $obj->Referidos }}','{{ $obj->QueQuisiera }}','{{ $obj->ServicioCliente }}')" data-target="#modal-edit-retroalimentacion" data-toggle="modal"></i>
                                        &nbsp;&nbsp;
                                        <i class="fa fa-trash fa-lg" onclick="modal_delete_retroalimentacion({{ $obj->Id }})" data-target="#modal-delete-retroalimentacion" data-toggle="modal"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div style="height: 200px">
                            <br>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Sin datos que mostrar.</strong>
                            </div>
                        </div>
                        @endif

                    </div>

                    <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 7 ? 'active in' : '' }}" id="documentacion" aria-labelledby="home-tab">
                        <form id="FormArchivo" action="{{ url('catalogo/cliente/documento') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$cliente->Id}}" name="Cliente">
                            <div >
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Archivo</label>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Archivo" type="file" required>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="clearfix"></div>
                            <div align="center">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Aceptar</button>
                            </div>
                        </form>
                        <br>
                        <div class="col-md-12">
                            <div class="col-md-3"> &nbsp;</div>
                            <div class="col-md-6">
                                <table class=" table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th style="width: 25%;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentos as $obj)
                                        @php($file = asset('storage/documentos/cliente/'.$obj->Nombre))
                                        <tr>
                                            <td><a href="{{ $file }}" class="btn btn-default" align="center"><i class="fa fa-download"></i> {{$obj->Nombre}}</a></td>
                                            <td> <i class="fa fa-trash fa-lg" data-target="#modal-delete-documento-{{ $obj->Id }}" data-toggle="modal"></i> </td>
                                        </tr>
                                        <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-documento-{{ $obj->Id }}">

                                            <form method="POST" action="{{ url('catalogo/cliente/documento_eliminar', $obj->Id) }}">
                                                @method('post')
                                                @csrf
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                            <h4 class="modal-title">Eliminar Registros {{$obj->Id}}</h4>
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

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <br>
            </div>



        </div>
    </div>
</div>


{{-- ventanas modales metodo de pago--}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-tarjeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/add_tarjeta') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nuevo método de pago</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-6">
                                Método Pago
                                <select name="MetodoPago" class="form-control" id="MetodoPago">
                                    @foreach ($metodos_pago as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                Número Tarjeta
                                <input type="text" name="NumeroTarjeta" id="tarjeta" class="form-control" data-inputmask="'mask': ['9999-9999-9999-9999']" disabled data-mask>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Fecha Vencimiento
                                <input type="text" id="vencimiento" class="form-control" data-inputmask="'mask': ['99/99']" data-mask disabled name="FechaVencimiento">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Póliza Vinculada
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
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-tarjeta">

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

<div class="col-12">
    <div class="modal fade modal-edit-tarjeta" id="modal-edit-tarjeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_tarjeta') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar tarjeta</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Id" id="ModalTarjetaId" class="form-control">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">

                        <div class="form-group">
                            <div class="col-sm-6">
                                Metodo pago
                                <select name="MetodoPago" id="ModalMetodoPago" class="form-control" disabled>
                                    @foreach ($metodos_pago as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                NumeroTarjeta
                                <input type="text" name="NumeroTarjeta" id="ModalNumeroTarjeta" class="form-control" data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Fecha vencimiento
                                <input type="text" name="FechaVencimiento" id="ModalFechaVencimiento" class="form-control" data-inputmask="'mask': ['99/99']" data-mask>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Poliza vinculada
                                <input type="text" name="PolizaVinculada" id="ModalPolizaVinculada" class="form-control" required>
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

{{-- modales contactos --}}
<div class="col-12">
    <div class="modal fade bs-modal-nuevo-contacto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/add_contacto') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Nuevo Contacto</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-6">
                                Nombre
                                <input type="text" name="Nombre" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                Cargo
                                <select name="Cargo" id="Cargo" class="form-control" required>
                                    @foreach ($cliente_contacto_cargos as $cargo)
                                    <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="col-sm-2">
                                Nuevo Cargo
                                <span class="fa fa-plus" onclick="addCargo();"></span>
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-sm-6">
                                Teléfono
                                <input type="text" name="Telefono" data-inputmask="'mask': ['9999-9999']" data-mask class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                Email
                                <input type="email" class="form-control" required name="Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Lugar Trabajo
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
    <div class="modal fade modal-edit-contacto" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit-contacto">
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
                            <div class="col-sm-6">
                                Nombre
                                <input type="text" name="Nombre" id="ModalContactoNombre" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Cargo
                                <select name="Cargo" id="ModalContactoCargo" class="form-control" required>
                                    @foreach ($cliente_contacto_cargos as $cargo)
                                    <option value="{{ $cargo->Id }}">{{ $cargo->Nombre }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Teléfono
                                <input type="text" name="Telefono" id="ModalContactoTelefono" data-inputmask="'mask': ['9999-9999']" data-mask class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                Email
                                <input type="email" required name="Email" id="ModalContactoEmail" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Lugar Trabajo
                                <input type="text" name="LugarTrabajo" id="ModalContactoLugarTrabajo" class="form-control" required>
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
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-contacto">

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
                        <h4 class="modal-title" id="myModalLabel">Nuevo Hábito</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-6">
                                Actividad Económica
                                <input type="text" name="ActividadEconomica" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Ingreso Promedio
                                <input type="number" step="0.001" min="0" class="form-control" required name="IngresoPromedio">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Porción de ingresos que gasta en seguros mensual
                                <input type="number" step="0.001" min="0" name="GastoMensualSeguro" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                Nivel Educativo
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
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-habito">

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
    <div class="modal fade modal-edit-habito" id="modal-edit-habito" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_habito') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar Hábito</h4>
                        <input type="hidden" name="Id" id="ModalHabitoId" class="form-control">
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-6">
                                Actividad Económica
                                <input type="text" name="ActividadEconomica" id="ModalHabitoActividadEconomica" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Ingreso Promedio
                                <input type="number" name="IngresoPromedio" id="ModalHabitoIngresoPromedio" step="0.001" min="0" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Porción de ingresos que gasta en seguros mensual
                                <input type="number" step="0.001" min="0" name="GastoMensualSeguro" id="ModalHabitoGastoMensualSeguro" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                Nivel Educativo
                                <input type="text" name="NivelEducativo" id="ModalHabitoNivelEducativo" class="form-control" required>
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
                            <div class="col-sm-6">
                                Producto de NR
                                <input type="text" name="Producto" required class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
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


                        <div class="form-group">
                            <div class="col-sm-6">
                                Valores agregados
                                <input type="text" name="ValoresAgregados" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Competidores
                                <input type="text" name="Competidores" class="form-control" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-6">
                                Que quisiera de Nr?
                                <input type="text" name="QueQuisiera" class="form-control" required>
                            </div>
                        </div>

                        <input type="hidden" name="ServicioCliente" id="ServicioCliente" value="0" class="form-control" required>

                        <div class="form-group">
                            <div class="col-sm-12">
                                Referidos
                                <input type="text" name="Referidos" class="form-control" required>
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
    <div class="modal fade modal-edit-retroalimentacion" id="modal-edit-retroalimentacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ url('catalogo/cliente/edit_retroalimentacion') }}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Editar Retroalimentación</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="Cliente" value="{{ $cliente->Id }}" class="form-control">
                        <input type="hidden" name="Id" id="ModalRetroId" class="form-control">
                        <div class="form-group">
                            <div class="col-sm-6">
                                Producto de NR
                                <input type="text" name="Producto" id="ModalRetroProducto" required class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
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


                        <div class="form-group">
                            <div class="col-sm-6">
                                Valores agregados
                                <input type="text" name="ValoresAgregados" id="ModalRetroValoresAgregados" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                Competidores
                                <input type="text" name="Competidores" id="ModalRetroCompetidores" class="form-control" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-6">
                                Que quisiera de NR?
                                <input type="text" name="QueQuisiera" id="ModalRetroQueQuisiera" class="form-control" required>
                            </div>
                        </div>

                        <input type="hidden" name="ServicioCliente" id="ModalRetroServicioCliente" class="form-control" required>
                        <div class="form-group">
                            <div class="col-sm-12">
                                Referidos
                                <input type="text" name="Referidos" id="ModalRetroReferidos" class="form-control" required>
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
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-retroalimentacion">

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
@include('catalogo.cliente.addCargo')
<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript">
    function addCargo() {
        $('#modal_addCargo').modal('show');
    }

    function addMotivo() {
        $('#modal_addMotivo').modal('show');
    }

    function addPreferencia() {
        $('#modal_addPreferencia').modal('show');
    }
    $(document).ready(function() {

        let homologadoCheck=$('#Homologado');
        let switchery = new Switchery(homologadoCheck[0]);

        $("#TipoPersona").change(function() {
        tipo_persona(switchery);
    });

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
        $("#MetodoPago").change(function() {
            var Metodo = document.getElementById('MetodoPago').value;
            if (Metodo == 2) {
                document.getElementById('tarjeta').removeAttribute('disabled');
                document.getElementById('vencimiento').removeAttribute('disabled');
            } else if (Metodo != 2) {
                document.getElementById('tarjeta').setAttribute('disabled', true);
                document.getElementById('vencimiento').setAttribute('disabled', true);
            }
        })


        $("#ModalMetodoPago").change(function() {
            var ModalMetodoPago = document.getElementById('ModalMetodoPago').value;
            if (ModalMetodoPago == 2) {
                document.getElementById('ModalNumeroTarjeta').removeAttribute('disabled');
                document.getElementById('ModalFechaVencimiento').removeAttribute('disabled');
            } else if (ModalMetodoPago != 2) {
                document.getElementById('ModalNumeroTarjeta').setAttribute('disabled', true);
                document.getElementById('ModalFechaVencimiento').setAttribute('disabled', true);
            }
        })

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


    function modal_edit_tarjeta(id, metodo, numero, fecha, poliza) {
        document.getElementById('ModalTarjetaId').value = id;
        document.getElementById('ModalMetodoPago').value = metodo;
        document.getElementById('ModalNumeroTarjeta').value = numero;
        document.getElementById('ModalFechaVencimiento').value = fecha;
        document.getElementById('ModalPolizaVinculada').value = poliza;
        if (metodo != 2) {
            document.getElementById('ModalNumeroTarjeta').setAttribute('disabled', true);
            document.getElementById('ModalFechaVencimiento').setAttribute('disabled', true);
        } else {
            document.getElementById('ModalNumeroTarjeta').setAttribute('disabled', false);
            document.getElementById('ModalFechaVencimiento').setAttribute('disabled', false);
        }
    }

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

    function modal_edit_retroalimentacion(id, producto, valores, competidores, referidos, quisiera, servicio) {

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

    function format_tarjeta() {
        var tarjeta = document.getElementById("tarjeta").value;
        console.log(tarjeta);
    }



    function tipo_persona(switchery) {
        let dui=$('#Dui');
        let nit=$('#Nit');
        let tipoPersona=$('#TipoPersona');
        let homologado=$('#Homologado');
        let genero=$('#Genero');
        let estadoFamiliar=$('#EstadoFamiliar');
        if (tipoPersona.val()==='2') {
            dui.prop('readonly', true);
            dui.val('');
            switchery.disable();
            if (homologado.prop('checked')) {
                switchery.setPosition(true);// Cambia a estado seleccionado
            }
            nit.val('');
            nit.prop('readonly', false);
            nit.inputmask('remove');
            nit.inputmask({
                'mask': '9999-999999-999-9'
            });
            genero.val('3');
            estadoFamiliar.val('0');
            /*genero.prop('readonly', true);
            estadoFamiliar.prop('readonly', true);*/
        } else {
            dui.prop('readonly', false);
            switchery.enable(); // Cambia a estado seleccionado
            genero.find('option:selected').prop('selected', false);
            genero.val(null);
            estadoFamiliar.find('option:selected').prop('selected', false);
            estadoFamiliar.val(null);
             /*genero.prop('readonly', false);
            estadoFamiliar.prop('readonly', false);*/
        }
    }


</script>

@endsection
