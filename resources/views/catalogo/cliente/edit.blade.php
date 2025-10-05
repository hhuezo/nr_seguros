@extends ('welcome')
@section('contenido')
    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <?php

    $cumpleanos = new DateTime($cliente->FechaNacimiento);
    $hoy = new DateTime();
    $annos = $hoy->diff($cumpleanos);
    $annos->y;

    ?>

    <style>
        /* Estilo para el cuadro que contiene los campos */
        .campo-container {
            border: 1px solid #c0ccda;
            padding: 10px;
            border-radius: 10px;
        }

        /* Estilo para los campos individuales */
        .campo {
            margin-bottom: 10px;
        }

        /* Estilo para el título "Ruta de Cobro" */
        .titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>

    <div class="x_panel">
        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
        @endif
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Cliente <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">

                        <a href="{{ url('catalogo/cliente') }}?idRegistro={{$cliente->Id}}" class="btn btn-info fa fa-undo " style="color: white">
                            Atrás</a>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div id="error-messages" class="alert alert-danger"
                    style="display: {{ count($errors) > 0 ? 'block' : 'none' }}">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ $tab == 1 ? 'active' : '' }}"><a href="#cliente" id="home-tab"
                                role="tab" data-toggle="tab" aria-expanded="true">Cliente</a>

                        </li>
                        <li role="presentation" class="{{ $tab == 2 ? 'active' : '' }}"><a href="#pago" role="tab"
                                id="profile-metodo" data-toggle="tab" aria-expanded="false">Métodos de
                                pago</a>
                        </li>
                        <li role="presentation" class="{{ $tab == 3 ? 'active' : '' }}"><a href="#contacto" role="tab"
                                id="profile-contacto" data-toggle="tab" aria-expanded="false">Libreta de Contactos</a>
                        </li>

                        <li role="presentation" class="{{ $tab == 4 ? 'active' : '' }}"><a href="#redes" role="tab"
                                id="profile-necesidad" data-toggle="tab" aria-expanded="false">Necesidades, Preferencias y
                                Gustos</a>
                        </li>
                        <li role="presentation" class="{{ $tab == 5 ? 'active' : '' }}"><a href="#habito" role="tab"
                                id="profile-habito" data-toggle="tab" aria-expanded="false">Hábitos de
                                Consumo</a>
                        </li>
                        <li role="presentation" class="{{ $tab == 6 ? 'active' : '' }}"><a href="#retroalimentacion"
                                role="tab" id="profile-habito" data-toggle="tab" aria-expanded="false">Retroalimentación
                                de NR</a>
                        </li>
                        <li role="presentation" class="{{ $tab == 7 ? 'active' : '' }}"><a href="#documentacion"
                                role="tab" id="profile" data-toggle="tab" aria-expanded="false">Documentación</a>
                        </li>


                    </ul>


                    <div id="myTabContent2" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }}" id="cliente"
                            aria-labelledby="home-tab">
                            <form method="POST" action="{{ route('cliente.update', $cliente->Id) }}" id="myform">
                                @method('PUT')
                                @csrf

                                <div class="x_content">
                                    <br />
                                    <div class="container">
                                        <div class="row">
                                            <!-- Columna Izquierda (6 unidades) -->
                                            <div class="col-md-6">

                                                <!-- Campos para la columna izquierda -->
                                                <div class="form-group">
                                                    <label for="TipoPersona" class="form-label">Tipo Persona *</label>
                                                    <select name="TipoPersona" id="TipoPersona"
                                                        onchange="validaciones.cboTipoPersona(this.value)"
                                                        class="form-control" style="text-transform: uppercase">
                                                        <option value="1"
                                                            {{ $cliente->TipoPersona == 1 ? 'selected' : '' }}>NATURAL
                                                        </option>
                                                        <option value="2"
                                                            {{ $cliente->TipoPersona == 2 ? 'selected' : '' }}>JURIDICA
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Nombre" class="form-label">NIT * </label>
                                                    <input class="form-control" name="Nit" id="Nit"
                                                        value="{{ $cliente->Nit }}"
                                                        @if ($cliente->TipoPersona == 1 && $cliente->Dui == $cliente->Nit) data-inputmask="'mask':
                                                ['99999999-9']" readonly @else data-inputmask="'mask':
                                                ['9999-999999-999-9']" @endif
                                                        data-mask type="text">
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <label for="Nombre" class="form-label">DUI *</label>
                                                            <input class="form-control" name="Dui" id="Dui"
                                                                value="{{ $cliente->Dui }}"
                                                                data-inputmask="'mask': ['99999999-9']" data-mask
                                                                type="text">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row" id="Homolo">
                                                                <label for="Nombre"
                                                                    class="form-label">¿Homologado?</label><br>
                                                                <input name="Homologado" id="Homologado" type="checkbox"
                                                                    onchange="validaciones.cambiarEstado()"
                                                                    @if ($cliente->TipoPersona == 1 && $cliente->Dui == $cliente->Nit) checked @else @endif />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <label for="Nombre" class="form-label">Pasaporte </label>
                                                            <input class="form-control" name="Pasaporte" id="Pasaporte"
                                                                value="{{ $cliente->Pasaporte }}" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row">
                                                                <label for="Extranjero"
                                                                    class="form-label">¿Extranjero?</label><br>
                                                                <label class="switch">
                                                                    <input type="checkbox" name="Extranjero"
                                                                        {{ $cliente->Extranjero == true ? 'checked' : '' }}
                                                                        id="Extranjero">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="Nombre" class="form-label">Registro Fiscal </label>
                                                    <input class="form-control" name="RegistroFiscal" id="RegistroFiscal"
                                                        value="{{ $cliente->RegistroFiscal }}" type="text">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Nombre" class="form-label">Nombre o Razón Social
                                                        *</label>
                                                    <input class="form-control" id="Nombre" name="Nombre"
                                                        value="{{ strtoupper($cliente->Nombre) }}" type="text"
                                                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                                    <!-- se agrego strtoupper, to uppercase, uppercase -->
                                                </div>
                                                <div class="form-group">
                                                    <label for="FechaNacimiento" class="form-label">Fecha de Nacimiento ó
                                                        Fundación de persona Jurídica *</label>
                                                    <input class="form-control" name="FechaNacimiento"
                                                        id="FechaNacimiento" value="{{ $cliente->FechaNacimiento }}"
                                                        type="date">
                                                </div>
                                                <div class="form-group">
                                                    <label for="FechaNacimiento" class="form-label">Edad *</label>
                                                    <input class="form-control" id="EdadCalculada"
                                                        value="<?php echo $annos->y; ?>" type="text" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Genero" class="form-label">Estado Familiar </label>
                                                    <select class="form-control" name="EstadoFamiliar"
                                                        id="EstadoFamiliar" style="text-transform: uppercase;">
                                                        <option value="" selected disabled>Seleccione ...</option>
                                                        <option value="0"
                                                            {{ $cliente->EstadoFamiliar == 0 ? 'selected' : '' }}>NO APLICA
                                                        </option>
                                                        <option value="1"
                                                            {{ $cliente->EstadoFamiliar == 1 ? 'selected' : '' }}>SOLTERO
                                                        </option>
                                                        <option value="2"
                                                            {{ $cliente->EstadoFamiliar == 2 ? 'selected' : '' }}>CASADO
                                                        </option>
                                                        <option value="3"
                                                            {{ $cliente->EstadoFamiliar == 3 ? 'selected' : '' }}>
                                                            DIVORCIADO
                                                        </option>
                                                        <option value="4"
                                                            {{ $cliente->EstadoFamiliar == 4 ? 'selected' : '' }}>VIUDO
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="NumeroDependientes" class="form-label">Número
                                                        Dependientes</label>
                                                    <input class="form-control" name="NumeroDependientes"
                                                        id="NumeroDependientes"
                                                        value="{{ $cliente->NumeroDependientes }}" type="number">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Genero" class="form-label">Ocupación </label>
                                                    <input class="form-control" id="Ocupacion" name="Ocupacion"
                                                        type="text" value="{{ strtoupper($cliente->Ocupacion) }}"
                                                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                                </div>
                                                <div class="form-group" style="padding-bottom: 38px;">

                                                </div>
                                                <div class="form-group">
                                                    <label for="DireccionResidencia" class="form-label">Dirección
                                                        Residencia</label>
                                                    <textarea class="form-control" name="DireccionResidencia"
                                                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">{{ strtoupper($cliente->DireccionResidencia) }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="DireccionResidencia" class="form-label">Dirección
                                                        Correspondencia *</label>
                                                    <textarea class="form-control" name="DireccionCorrespondencia" id="DireccionCorrespondencia"
                                                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">{{ strtoupper($cliente->DireccionCorrespondencia) }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Referencia" class="form-label">Teléfono Principal Contacto
                                                        *</label>
                                                    <input class="form-control" name="TelefonoCelular"
                                                        id="TelefonoCelular" value="{{ $cliente->TelefonoCelular }}"
                                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Referencia" class="form-label">Teléfono Residencia</label>
                                                    <input class="form-control" name="TelefonoResidencia"
                                                        value="{{ $cliente->TelefonoResidencia }}"
                                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Referencia" class="form-label">Teléfono Oficina </label>
                                                    <input class="form-control" name="TelefonoOficina"
                                                        value="{{ $cliente->TelefonoOficina }}"
                                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                                </div>
                                                <div class="form-group">
                                                    <label for="TelefonoCelular2" class="form-label">Teléfono Celular
                                                    </label>
                                                    <input class="form-control" name="TelefonoCelular2"
                                                        value="{{ $cliente->TelefonoCelular2 }}"
                                                        data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                                </div>
                                                <div class="form-group">
                                                    <label for="NumeroExtrajero" class="form-label">Número
                                                        Extrajero</label>
                                                    <input class="form-control" name="NumeroExtrajero"
                                                        value="{{ $cliente->NumeroExtrajero }}" data-mask type="text">
                                                </div>
                                                <div class="form-group">
                                                    <label for="CorreoPrincipal" class="form-label">Correo Principal
                                                        *</label>
                                                    <input class="form-control" name="CorreoPrincipal"
                                                        id="CorreoPrincipal" value="{{ $cliente->CorreoPrincipal }}"
                                                        type="email">
                                                </div>
                                                <div class="form-group">
                                                    <label for="CorreoSecundario" class="form-label">Correo Secundario
                                                    </label>
                                                    <input class="form-control" name="CorreoSecundario"
                                                        value="{{ $cliente->CorreoSecundario }}" type="email">
                                                </div>
                                                <div class="form-group">
                                                    <label for="FechaVinculacion" class="form-label">Fecha Vinculación
                                                        *</label>
                                                    <input class="form-control" name="FechaVinculacion"
                                                        id="FechaVinculacion" value="{{ $cliente->FechaVinculacion }}"
                                                        type="date">
                                                </div>
                                                <div class="form-group">
                                                    <label for="FechaVinculacion" class="form-label">Fecha Baja Cliente
                                                    </label>
                                                    <input class="form-control" name="FechaBaja"
                                                        value="{{ $cliente->FechaBaja }}" type="date">
                                                </div>
                                                <div class="form-group">

                                                </div>
                                            </div>

                                            <!-- Columna Derecha (6 unidades) -->
                                            <div class="col-md-6">
                                                <!-- Campos para la columna derecha -->
                                                <div class="form-group">
                                                    <label for="Genero" class="form-label">Estado Cliente *</label>
                                                    <select name="Estado" id="Estado" class="form-control"
                                                        style="width: 100%;">
                                                        <option value=""> Seleccione...</option>

                                                        @foreach ($cliente_estados as $obj)
                                                            <option value="{{ $obj->Id }}"
                                                                {{ $cliente->Estado == $obj->Id ? 'selected' : '' }}>
                                                                {{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Genero" class="form-label">Género *</label>
                                                    <select name="Genero" id="Genero" class="form-control"
                                                        style="text-transform: uppercase;">
                                                        <option value="" selected disabled>Seleccione ...</option>
                                                        <option value="1"
                                                            {{ $cliente->Genero == 1 ? 'selected' : '' }}>MASCULINO
                                                        </option>
                                                        <option value="2"
                                                            {{ $cliente->Genero == 2 ? 'selected' : '' }}>FEMENINO
                                                        </option>
                                                        <option value="3"
                                                            {{ $cliente->Genero == 3 ? 'selected' : '' }}>NO
                                                            APLICA
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Nombre" class="form-label">Tipo Contribuyente *</label>
                                                    <select name="TipoContribuyente" id="TipoContribuyente"
                                                        class="form-control"
                                                        onchange="validaciones.cboTipoContribuyente(this.value)"
                                                        style="width: 100%;">
                                                        <option value="" disabled selected>Seleccione ...</option>
                                                        @foreach ($tipos_contribuyente as $obj)
                                                            <option value="{{ $obj->Id }}"
                                                                {{ $cliente->TipoContribuyente == $obj->Id ? 'selected' : '' }}>
                                                                {{ $obj->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group" style="padding-bottom: 90px!important;">
                                                    <label for="Referencia" class="form-label">Vinculado al Grupo o
                                                        Referencia </label>
                                                    <input class="form-control" name="Referencia" id="Referencia"
                                                        value="{{ strtoupper($cliente->Referencia) }}" type="text"
                                                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                                </div>

                                                <div class="campo-container">
                                                    <div class="titulo">Formas de pago</div>
                                                    <div class="form-group">
                                                        <label for="Genero" class="form-label">Responsable de
                                                            Pago</label>
                                                        <input class="form-control" id="ResponsablePago"
                                                            name="ResponsablePago"
                                                            value="{{ strtoupper($cliente->ResponsablePago) }}"
                                                            type="text"
                                                            oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Genero" class="form-label">Método de Pago
                                                            *</label>
                                                        <select name="UbicacionCobro" id="UbicacionCobro"
                                                            class="form-control" style="width: 100%; ">
                                                            <option value="" selected disabled>Seleccione ...
                                                            </option>
                                                            @foreach ($ubicaciones_cobro as $obj)
                                                                <option value="{{ $obj->Id }}"
                                                                    {{ $cliente->UbicacionCobro == $obj->Id ? 'selected' : '' }}>
                                                                    {{ $obj->Nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="campo-container">
                                                        <div class="titulo">
                                                            Ruta
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="DireccionResidencia"
                                                                class="form-label">Departamento
                                                                *</label>
                                                            <select id="Departamento" class="form-control"
                                                                style="width: 100%">
                                                                @foreach ($departamentos as $obj)
                                                                    <option value="{{ $obj->Id }}"
                                                                        {{ $departamento_actual == $obj->Id ? 'selected' : '' }}>
                                                                        {{ $obj->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="DireccionResidencia" class="form-label">Municipio
                                                                *</label>
                                                            <select name="Municipio" id="Municipio"
                                                                class="form-control select2" style="width: 100%">
                                                                @foreach ($municipios as $obj)
                                                                    <option value="{{ $obj->Id }}"
                                                                        {{ $municipio_actual == $obj->Id ? 'selected' : '' }}>
                                                                        {{ $obj->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="DireccionResidencia" class="form-label">Distrito
                                                                *</label>
                                                            <select id="Distrito" name="Distrito" required
                                                                class="form-control select2" style="width: 100%">
                                                                @foreach ($distritos as $obj)
                                                                    <option value="{{ $obj->Id }}"
                                                                        {{ $cliente->Distrito == $obj->Id ? 'selected' : '' }}>
                                                                        {{ $obj->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="padding-top: 10px!important;">
                                                        <label for="BancoPrefencia" class="form-label">Banco de su
                                                            Preferencia </label>
                                                        <input class="form-control" name="BancoPrefencia"
                                                            value="{{ strtoupper($cliente->BancoPrefencia) }}"
                                                            type="text"
                                                            oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="CuentasDevolucionPrimas" class="form-label">Cuentas
                                                            para
                                                            devolución de Primas </label>
                                                        <input class="form-control" name="CuentasDevolucionPrimas"
                                                            value="{{ $cliente->CuentasDevolucionPrimas }}"
                                                            type="text">
                                                    </div>

                                                </div>


                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="Comentarios" class="form-label">Comentarios</label>
                                                    <textarea class="form-control" name="Comentarios"
                                                        oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                                                        {{ strtoupper($cliente->Comentarios) }}</textarea>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="Comentarios" class="form-label">* Campo requerido</label>


                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="form-group" align="center">
                                    <button class="btn btn-success" onclick="validar_cliente()"
                                        type="button">Aceptar</button>
                                    <a href="{{ url('catalogo/cliente/') }}"><button class="btn btn-primary"
                                            type="button">Cancelar</button></a>
                                </div>

                            </form>


                        </div>
                        {{-- tab 4 necesidades --}}
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 4 ? 'active in' : '' }}" id="redes"
                            aria-labelledby="home-tab">

                            <form method="POST" action="{{ url('catalogo/cliente/red_social') }}">
                                @csrf
                                <div class="x_content">
                                    <br />
                                    <input type="hidden" name="Id" value="{{ $cliente->Id }}">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label ">Contacto Facebook </label>
                                            <input class="form-control" value="{{ $cliente->Facebook }}"
                                                name="Facebook">
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label ">Actividades Recreativas </label>

                                            <input class="form-control" value="{{ $cliente->ActividadesCreativas }}"
                                                name="ActividadesCreativas"
                                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                                                type="text">

                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label ">Estilo Vida </label>

                                            <input class="form-control" value="{{ $cliente->EstiloVida }}"
                                                name="EstiloVida"
                                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)"
                                                type="text">

                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label ">Sitio Web </label>

                                            <input class="form-control" name="SitioWeb" value="{{ $cliente->SitioWeb }}"
                                                type="text">

                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label ">Necesidad Protección </label>

                                            <select name="NecesidadProteccion" class="form-control" style="width: 100%">
                                                <option value="">Seleccione...</option>
                                                @foreach ($necesidades as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ $cliente->NecesidadProteccion == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label ">Dispositivo personal preferido, Numerar en orden
                                                de preferencia del 1 al 6</label>
                                            <br>

                                            <div class="col-md-4">
                                                <label class="control-label ">Smartphones </label>

                                                <input class="form-control" name="Smartphone"
                                                    value="@if ($cliente->Smartphone != 0) {{ $cliente->Smartphone }} @endif"
                                                    type="number" maxlength="1" min="0" Max="6">

                                                <label class="control-label ">Laptop </label>

                                                <input class="form-control" name="Laptop"
                                                    value="@if ($cliente->Laptop != 0) {{ $cliente->Laptop }} @endif"
                                                    type="number" maxlength="1" min="0" Max="6">

                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label ">PC </label>

                                                <input class="form-control" name="PC"
                                                    value="@if ($cliente->PC != 0) {{ $cliente->PC }} @endif"
                                                    type="number" maxlength="1" min="0" Max="6">

                                                <label class="control-label ">Tablet </label>

                                                <input class="form-control" name="Tablet"
                                                    value="@if ($cliente->Tablet != 0) {{ $cliente->Tablet }} @endif"
                                                    type="number" maxlength="1" min="0" Max="6">

                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label ">SmartWatch </label>

                                                <input class="form-control" name="SmartWatch"
                                                    value="@if ($cliente->SmartWatch != 0) {{ $cliente->SmartWatch }} @endif"
                                                    type="number" maxlength="1" min="0" Max="6">

                                                <label class="control-label ">Otros Dispositivos </label>

                                                <input class="form-control" name="DispositivosOtros"
                                                    value="@if ($cliente->DispositivosOtros != 0) {{ $cliente->DispositivosOtros }} @endif"
                                                    type="number" maxlength="1" min="0" Max="6">

                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label ">Le Gusta Informarse </label>

                                            <select name="Informarse" class="form-control" style="width: 100%; ">
                                                <option value=""> Seleccione...</option>
                                                @foreach ($informarse as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ $cliente->Informarse == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label ">¿Desee que le envíen información?</label> &nbsp;
                                            <input type="checkbox" name="EnvioInformacion" value="1"
                                                class="js-switch"
                                                {{ $cliente->EnvioInformacion == 1 ? 'checked' : '' }} />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                        <div class="form-group row">
                                            <label class="control-label ">Contacto Instagram </label>

                                            <input class="form-control" value="{{ $cliente->Instagram }}"
                                                name="Instagram">
                                        </div>
                                        <div class="form-group row">
                                            <br>
                                            <label class="control-label ">¿Tiene mascota? </label> &nbsp;
                                            <input type="checkbox" name="TieneMascota" value="1" class="js-switch"
                                                {{ $cliente->TieneMascota == 1 ? 'checked' : '' }} />
                                            <br><br>
                                        </div>


                                        <div class="form-group row" style="margin-top:-6px">
                                            <label class="control-label ">Compañia de su Preferencia </label>

                                            <select name="AseguradoraPreferencia" class="form-control"
                                                style="width: 100%">
                                                <option value=""> Seleccione...</option>
                                                @foreach ($aseguradoras as $obj)
                                                    <option value="{{ $obj->Id }}"
                                                        {{ $cliente->AseguradoraPreferencia == $obj->Id ? 'selected' : '' }}>
                                                        {{ $obj->Nombre }}
                                                    </option>
                                                @endforeach

                                            </select>

                                        </div>


                                        <div>
                                            <label class="control-label ">Motivo de Elección </label>
                                            <div class="input-group row">

                                                <select name="MotivoEleccion" id="MotivoEleccion"
                                                    class="form-control col-md-4" style="width: 100%">
                                                    <option value=""> Seleccione...</option>
                                                    @foreach ($motivo_eleccion as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->MotivoEleccion == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary"
                                                        data-target="#modal_addMotivo" data-toggle="modal">+</button>
                                                </span>
                                            </div>
                                        </div>


                                        <div>
                                            <label class="control-label ">Preferencia de Compra </label>
                                            <div class="input-group row">

                                                <select name="PreferenciaCompra" id="PreferenciaCompra"
                                                    class="form-control col-md-4" style="width: 100% ">
                                                    <option value=""> Seleccione...</option>
                                                    @foreach ($preferencia_compra as $obj)
                                                        <option value="{{ $obj->Id }}"
                                                            {{ $cliente->PreferenciaCompra == $obj->Id ? 'selected' : '' }}>
                                                            {{ $obj->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary"
                                                        data-target="#modal_addPreferencia" data-toggle="modal">+</button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label ">Compra Habitualmente </label>
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
                                                    crédito
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
                                                    <input type="checkbox" name="MonederoEletronico" class="js-switch"
                                                        value="1"
                                                        {{ $cliente->MonederoEletronico == 1 ? 'checked' : '' }} />
                                                    Monedero eletrónico
                                                </label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <label>
                                                    <input type="checkbox" name="CompraOtros" class="js-switch"
                                                        value="1"
                                                        {{ $cliente->CompraOtros == 1 ? 'checked' : '' }} /> Otros
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12 col-sm-12 col-xs-12"
                                            style="text-align: left;">¿Que información desea recibir
                                            frecuentemente?</label>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea name="Informacion" class="form-control"
                                                oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">{{ $cliente->Informacion }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" align="center">
                                    <button class="btn btn-success" type="submit">Aceptar</button>
                                    <a href="{{ url('catalogo/cliente/') }}"><button class="btn btn-primary"
                                            type="button">Cancelar</button></a>
                                </div>
                            </form>
                        </div>

                        {{-- tab 2 metodos de pago --}}
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="pago"
                            aria-labelledby="home-tab">

                            <div class="col-12" style="text-align: right;">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target=".bs-modal-nuevo-tarjeta"><i class="fa fa-plus fa-lg"></i>
                                    Nuevo</button>
                            </div>

                            @if ($tarjetas->count() > 0)
                                <table id="MetodosPagoTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
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
                                                <td>{{ $obj->Id }}</td>
                                                @if ($obj->MetodoPago)
                                                    <td>{{ $obj->metodo_pago->Nombre }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{ 'XXXX-XXXX-XXXX-' . substr($obj->NumeroTarjeta, -4) }}</td>
                                                <td>XX/XX</td>
                                                <td>{{ $obj->PolizaVinculada }}</td>
                                                <td>
                                                    <i class="fa fa-eye-slash fa-lg"
                                                        onclick="auntenticar_usuario_metodos_pago({{ $loop->index }})"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-pencil fa-lg"
                                                        onclick="modal_edit_tarjeta({{ $obj->Id }},'{{ $obj->MetodoPago }}','{{ 'XXXX-XXXX-XXXX-' . substr($obj->NumeroTarjeta, -4) }}','XX/XX','{{ $obj->PolizaVinculada }}')"
                                                        data-target="#modal-edit-tarjeta" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
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
                        {{-- tab 3 contactos --}}
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 3 ? 'active in' : '' }}" id="contacto"
                            aria-labelledby="home-tab">

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
                                                        data-target="#modal-edit-contacto" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_contacto({{ $obj->Id }})"
                                                        data-target="#modal-delete-contacto" data-toggle="modal"></i>


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

                        {{-- tab 5 habitos --}}
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 5 ? 'active in' : '' }}" id="habito"
                            aria-labelledby="home-tab">
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
                                                    <i class="fa fa-pencil fa-lg"
                                                        onclick="modal_edit_habito({{ $obj->Id }},'{{ $obj->ActividadEconomica }}','{{ $obj->IngresoPromedio }}','{{ $obj->GastoMensualSeguro }}','{{ $obj->NivelEducativo }}')"
                                                        data-target="#modal-edit-habito" data-toggle="modal"></i>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-trash fa-lg"
                                                        onclick="modal_delete_habito({{ $obj->Id }})"
                                                        data-target="#modal-delete-habito" data-toggle="modal"></i>
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

                        {{-- tab 6 retroalimentacion --}}
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 6 ? 'active in' : '' }}"
                            id="retroalimentacion" aria-labelledby="home-tab">
                            <div class="col-12" style="text-align: right;">
                                <button class="btn btn-primary" data-toggle="modal"
                                    data-target=".bs-modal-nuevo-retroalimentacion"><i class="fa fa-plus fa-lg"></i>
                                    Nuevo</button>
                            </div>

                            @if ($retroalimentacion->count() > 0)
                                <br>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto de NR</th>
                                            <th>Valores Agregados</th>
                                            <th>Competidores</th>
                                            <th>Referidos</th>
                                            <th>¿Que quisiera de NR?</th>
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

                        {{-- tab 7 documentacion --}}
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 7 ? 'active in' : '' }}"
                            id="documentacion" aria-labelledby="home-tab">
                            <form id="FormArchivo" action="{{ url('catalogo/cliente/documento') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $cliente->Id }}" name="Cliente">
                                <div>
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12"
                                            align="right">Archivo</label>
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
                                            @foreach ($documentos as $obj)
                                                <tr>
                                                    <td><a href="{{ asset('documentos/cliente') }}/{{ $obj->Nombre }}"
                                                            class="btn btn-default" align="center" target="_blank"><i
                                                                class="fa fa-download"></i>
                                                            {{ $obj->NombreOriginal }}</a></td>
                                                    <td> <i class="fa fa-trash fa-lg"
                                                            data-target="#modal-delete-documento-{{ $obj->Id }}"
                                                            data-toggle="modal"></i> </td>
                                                </tr>
                                                <div class="modal fade modal-slide-in-right" aria-hidden="true"
                                                    role="dialog" tabindex="-1"
                                                    id="modal-delete-documento-{{ $obj->Id }}">

                                                    <form method="POST"
                                                        action="{{ url('catalogo/cliente/documento_eliminar', $obj->Id) }}">
                                                        @method('post')
                                                        @csrf
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                    <h4 class="modal-title">Eliminar Registros
                                                                        {{ $obj->Id }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Confirme si desea Eliminar el Registro</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Cerrar</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Confirmar</button>
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


    {{-- ventanas modales metodo de pago --}}
    @include('catalogo.cliente.modals_tab2')


    {{-- modales contactos --}}
    @include('catalogo.cliente.modals_tab3')

    {{-- ventanas modales necesidades --}}
    @include('catalogo.cliente.modals_tab4')

    {{-- modales habito --}}
    @include('catalogo.cliente.modals_tab5')

    {{-- modales retroalimentacion --}}
    @include('catalogo.cliente.modals_tab6')





    <script type="text/javascript">
        $(document).ready(function() {
            $("#opcionCliente").addClass("current-page");
            $("#botonMenuCliente").addClass("active");
            $("#menuCliente").css("display", "block");

            let homologadoCheck = $('#Homologado');
            let switchery = new Switchery(homologadoCheck[0]);
            tipo_persona_edit(switchery);

            $("#TipoPersona").change(function() {
                tipo_persona(switchery);
            });

            $('#FechaNacimiento').on('change', function() {
                var fecha_nacimiento = new Date($(this).val());
                var fecha_actual = new Date();

                var edad = fecha_actual.getFullYear() - fecha_nacimiento.getFullYear();

                var mes_nacimiento = fecha_nacimiento.getMonth();
                var mes_actual = fecha_actual.getMonth();

                if (mes_actual < mes_nacimiento || (mes_actual === mes_nacimiento && fecha_actual
                        .getDate() <
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
            });


            $("#ModalMetodoPago").change(function() {
                var ModalMetodoPago = document.getElementById('ModalMetodoPago').value;
                if (ModalMetodoPago == 2) {
                    document.getElementById('ModalNumeroTarjeta').removeAttribute('disabled');
                    document.getElementById('ModalFechaVencimiento').removeAttribute('disabled');
                } else if (ModalMetodoPago != 2) {
                    document.getElementById('ModalNumeroTarjeta').setAttribute('disabled', true);
                    document.getElementById('ModalFechaVencimiento').setAttribute('disabled', true);
                }
            });


            function togglePasaporte() {
                if ($("#Extranjero").is(':checked')) {
                    $("#Pasaporte").prop('readonly', false);
                } else {
                    $("#Pasaporte").prop('readonly', true).val('');
                }
            }

            // Ejecutar al cargar la página
            togglePasaporte();

            // Ejecutar cada vez que el checkbox cambie
            $("#Extranjero").change(togglePasaporte);

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
            document.getElementById('ModalPolizaVinculada').value = poliza.toUpperCase();
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
            document.getElementById('ModalContactoNombre').value = nombre.toUpperCase();
            document.getElementById('ModalContactoTelefono').value = telefono;
            document.getElementById('ModalContactoEmail').value = email;
            document.getElementById('ModalContactoLugarTrabajo').value = lugar.toUpperCase();
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
            document.getElementById('ModalHabitoActividadEconomica').value = actividad.toUpperCase();
            document.getElementById('ModalHabitoIngresoPromedio').value = ingreso;
            document.getElementById('ModalHabitoGastoMensualSeguro').value = gasto;
            document.getElementById('ModalHabitoNivelEducativo').value = nivel.toUpperCase();
            //$('#modal_borrar_documento').modal('show');
        }

        function modal_delete_retroalimentacion(id) {
            document.getElementById('IdRetroalimentacion').value = id;
            //$('#modal_borrar_documento').modal('show');
        }

        function modal_edit_retroalimentacion(id, producto, valores, competidores, referidos, quisiera, servicio) {

            document.getElementById('ModalRetroId').value = id;
            document.getElementById('ModalRetroProducto').value = producto.toUpperCase();
            document.getElementById('ModalRetroValoresAgregados').value = valores.toUpperCase();
            document.getElementById('ModalRetroCompetidores').value = competidores.toUpperCase();
            document.getElementById('ModalRetroReferidos').value = referidos.toUpperCase();
            document.getElementById('ModalRetroQueQuisiera').value = quisiera.toUpperCase();
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
                    string_star = string_star +
                        '<i class="fa fa-star fa-2x" style="padding-right: 5px;" onclick="check_stars(' + i + ')"></i>';
                } else {
                    string_star = string_star +
                        '<i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="check_stars(' + i + ')"></i>';
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
                    string_star = string_star +
                        '<i class="fa fa-star fa-2x" style="padding-right: 5px;" onclick="modal_check_stars(' + i +
                        ')"></i>';
                } else {
                    string_star = string_star +
                        '<i class="fa fa-star-o fa-2x" style="padding-right: 5px;" onclick="modal_check_stars(' + i +
                        ')"></i>';
                }
            }
            $('#modal_stars').html(string_star);
        }

        function format_tarjeta() {
            var tarjeta = document.getElementById("tarjeta").value;
            console.log(tarjeta);
        }

        function auntenticar_usuario_metodos_pago(numeroFila) {
            Swal.fire({
                title: 'Verifica que eres tú',
                html: '<input id="verificarInput1" class="swal2-input" placeholder="Email">' +
                    '<input id="verificarInput2" type="password" class="swal2-input" placeholder="Contraseña">',
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                preConfirm: () => {
                    let verificarInput1 = Swal.getPopup().querySelector('#verificarInput1').value;
                    let verificarInput2 = Swal.getPopup().querySelector('#verificarInput2').value;

                    if (!verificarInput1 || !verificarInput2) {
                        Swal.showValidationMessage('Por favor, completa ambos campos');
                    }

                    return {
                        verificarInput1,
                        verificarInput2
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let {
                        verificarInput1,
                        verificarInput2
                    } = result.value;
                    //console.log('Usuario:', verificarInput1);
                    //console.log('Contraseña:', verificarInput2);

                    let parametros = {
                        "email": verificarInput1,
                        "password": verificarInput2,
                    };
                    $.ajax({
                        url: "{{ url('catalogo/cliente/verificarCredenciales', '') }}",
                        type: 'GET',
                        data: parametros,
                        success: function(response) {
                            // Las credenciales son válidas, puedes mostrar los datos sensibles
                            console.log(response.mensaje);
                            // Realiza las acciones necesarias para mostrar los datos sensibles
                            info_sensible(numeroFila);

                        },
                        error: function(error) {
                            // Las credenciales son incorrectas, muestra un mensaje de error
                            console.error(error.responseJSON.mensaje);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Credenciales erróneas',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }
            });

        }

        function info_sensible(numeroFila) {
            let fila = $("#MetodosPagoTable tbody tr").eq(numeroFila);
            let boton_ojo = fila.find("td:nth-child(6)").find("i:eq(0)");
            let boton_editar = fila.find("td:nth-child(6)").find("i:eq(1)");
            let id_metodo_pago = fila.find("td:nth-child(1)");
            let numero_tarjeta_credito = fila.find("td:nth-child(3)");
            let fecha_vencimiento_tarjeta = fila.find("td:nth-child(4)");
            let poliza_vinculada_tarjeta = fila.find('td:nth-child(5)');

            datos_tarjeta(id_metodo_pago.text(), function(datosRecibidos) {
                // Aquí puedes trabajar con los datosRecibidos
                //console.log(datosRecibidos);
                numero_tarjeta_credito.text(datosRecibidos.NumeroTarjeta);
                fecha_vencimiento_tarjeta.text(datosRecibidos.FechaVencimiento);
                boton_ojo.attr("class", "fa fa-eye fa-lg");
                boton_ojo.attr("onclick", "censurar_info_sensible(" + numeroFila + ")");
                boton_editar.attr("onclick", "modal_edit_tarjeta(" + id_metodo_pago.text() + ",'2','" +
                    numero_tarjeta_credito.text() + "','" + fecha_vencimiento_tarjeta.text() + "','" +
                    poliza_vinculada_tarjeta.text() + "')");
            });

        }

        function censurar_info_sensible(numeroFila) {
            let fila = $("#MetodosPagoTable tbody tr").eq(numeroFila);
            let boton_ojo = fila.find("td:nth-child(6)").find("i:eq(0)");
            let boton_editar = fila.find("td:nth-child(6)").find("i:eq(1)");
            let id_metodo_pago = fila.find("td:nth-child(1)");
            let numero_tarjeta_credito = fila.find("td:nth-child(3)");
            let fecha_vencimiento_tarjeta = fila.find("td:nth-child(4)");
            let poliza_vinculada_tarjeta = fila.find('td:nth-child(5)');

            numero_tarjeta_credito.text('XXXX-XXXX-XXXX-' + numero_tarjeta_credito.text().slice(-4));
            fecha_vencimiento_tarjeta.text('XX/XX');

            boton_ojo.attr("class", "fa fa-eye-slash fa-lg");
            boton_ojo.attr("onclick", "auntenticar_usuario_metodos_pago(" + numeroFila + ")");
            boton_editar.attr("onclick", "modal_edit_tarjeta(" + id_metodo_pago.text() + ",'2','" + numero_tarjeta_credito
                .text() + "','" + fecha_vencimiento_tarjeta.text() + "','" + poliza_vinculada_tarjeta.text() + "')");

        }

        function datos_tarjeta(id_registro_metodo_pago, callback) {
            let parametros = {
                "id_registro_metodo_pago": id_registro_metodo_pago,
            };
            $.ajax({
                url: "{{ url('catalogo/cliente/getMetodoPago', '') }}",
                type: 'GET',
                data: parametros,
                success: function(response) {
                    // Handle successful response
                    //console.log(response.datosRecibidos);
                    callback(response.datosRecibidos);

                },
                error: function(error) {
                    // Handle error
                    console.error(error);
                    callback(0);
                }
            });
        }

        function tipo_persona(switchery) {
            let dui = $('#Dui');
            let nit = $('#Nit');
            let tipoPersona = $('#TipoPersona');
            let homologado = $('#Homologado');
            let genero = $('#Genero');
            let estadoFamiliar = $('#EstadoFamiliar');
            if (tipoPersona.val() === '2') {
                dui.prop('readonly', true);
                dui.val('');
                dependientes.prop('readonly', true);
                residencia.prop('readonly', true);
                telefono.prop('readonly', true);
                switchery.disable();
                if (homologado.prop('checked')) {
                    switchery.setPosition(true); // Cambia a estado seleccionado
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
                dependientes.prop('readonly', false);
                residencia.prop('readonly', false);
                telefono.prop('readonly', false);
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

        function tipo_persona_edit(switchery) {
            let dui = $('#Dui');
            let nit = $('#Nit');
            let tipoPersona = $('#TipoPersona');
            let homologado = $('#Homologado');
            let genero = $('#Genero');
            let estadoFamiliar = $('#EstadoFamiliar');
            if (tipoPersona.val() === '2') {
                dui.prop('readonly', true);
                dui.val('');
                switchery.disable();
                if (homologado.prop('checked')) {
                    switchery.setPosition(true); // Cambia a estado seleccionado
                }
                //nit.val('');
                nit.prop('readonly', false);
                nit.inputmask('remove');
                nit.inputmask({
                    'mask': '9999-999999-999-9'
                });
                genero.val('3');
                estadoFamiliar.val('0');
                /*genero.prop('readonly', true);
                estadoFamiliar.prop('readonly', true);*/
            }
            /*else {
                           dui.prop('readonly', false);
                           switchery.enable(); // Cambia a estado seleccionado
                           genero.find('option:selected').prop('selected', false);
                           genero.val(null);
                           estadoFamiliar.find('option:selected').prop('selected', false);
                           estadoFamiliar.val(null);
                           /*genero.prop('readonly', false);
                           estadoFamiliar.prop('readonly', false);
                       }*/
        }

        function validar_cliente() {
            var tipoPersona = document.getElementById('TipoPersona').value;
            var nit = document.getElementById('Nit').value;
            var dui = document.getElementById('Dui').value;
            var nombre = document.getElementById('Nombre').value;
            var fechaNacimiento = document.getElementById('FechaNacimiento').value;
            var direccionCorrespondencia = document.getElementById('DireccionCorrespondencia').value;
            var telefonoCelular = document.getElementById('TelefonoCelular').value;
            var correoPrincipal = document.getElementById('CorreoPrincipal').value;
            var fechaVinculacion = document.getElementById('FechaVinculacion').value;
            var estado = document.getElementById('Estado').value;
            var genero = document.getElementById('Genero').value;
            var tipoContribuyente = document.getElementById('TipoContribuyente').value;
            var ubicacionCobro = document.getElementById('UbicacionCobro').value;
            var departamento = document.getElementById('Departamento').value;
            var municipio = document.getElementById('Municipio').value;
            var distrito = document.getElementById('Distrito').value;


            // Construir la URL con los parámetros
            var url = new URL('{{ url('catalogo/cliente/validar_cliente') }}');
            var params = {
                Dui: dui,
                Nit: nit,
                Nombre: nombre,
                TipoPersona: tipoPersona,
                FechaNacimiento: fechaNacimiento,
                DireccionCorrespondencia: direccionCorrespondencia,
                TelefonoCelular: telefonoCelular,
                CorreoPrincipal: correoPrincipal,
                FechaVinculacion: fechaVinculacion,
                Estado: estado,
                Genero: genero,
                TipoContribuyente: tipoContribuyente,
                UbicacionCobro: ubicacionCobro,
                Departamento: departamento,
                Municipio: municipio,
                Distrito: distrito,
                ClienteId: {{ $cliente->Id }}
            };
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            // Realizar la solicitud GET
            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Limpiar mensajes de error previos
                    var errorDiv = document.getElementById('error-messages');
                    errorDiv.innerHTML = '';

                    console.log(data);

                    // Verificar si hay errores de validación
                    if (data.success === false && data.errors) {
                        // Mostrar los errores de validación
                        for (const [key, messages] of Object.entries(data.errors)) {
                            messages.forEach(message => {
                                var p = document.createElement('p');
                                p.textContent = message;
                                errorDiv.appendChild(p);
                            });
                        }

                        errorDiv.style.display = 'block';
                    } else {
                        // Manejar la respuesta exitosa
                        console.log('Validación exitosa', data);

                        // Obtener el formulario por su ID
                        var form = document.getElementById('myform');

                        // Realizar el submit del formulario
                        form.submit();
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                });

            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        }
    </script>

@endsection
