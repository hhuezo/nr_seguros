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
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo persona</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoPersona" id="TipoPersona" class="form-control">
                                        <option value="">Seleccione ...</option>
                                        <option value="1">Natural</option>
                                        <option value="2">Jurídica</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">NIT</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nit" data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Dui" data-inputmask="'mask': ['99999999-9']" data-mask type="text" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Nombre" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Registro fiscal</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="RegistroFiscal" type="text">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha nacimiento</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaNacimiento" type="date">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estado familiar</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select class="form-control" name="EstadoFamiliar" >
                                        <option value="0">No Aplica</option>
                                        <option value="1">Soltero</option>
                                        <option value="2">Casado</option>
                                        <option value="3">Divorciado</option>
                                        <option value="4">Viudo</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Numero dependientes</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="NumeroDependientes" type="number">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ocupación</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Ocupacion" type="text">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionResidencia"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    residencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoResidencia" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                    correspondencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="DireccionCorrespondencia"></textarea>
                                </div>
                            </div>
                          

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    oficina</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoOficina" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            

                        </div>


                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                    celular</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="TelefonoCelular" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                    principal</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="CorreoPrincipal" type="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo secundario</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="CorreoSecundario" type="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    vinculación</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaVinculacion"  type="date">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                    baja</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="FechaBaja"  type="date">
                                </div>
                            </div>

                         
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Responsable
                                    pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="ResponsablePago" type="text">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ubicación de
                                    cobro</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="UbicacionCobro" class="form-control" style="width: 100%">
                                        @foreach ($ubicaciones_cobro as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Forma pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="FormaPago" class="form-control" style="width: 100%">
                                        @foreach ($formas_pago as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estado</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Estado" class="form-control" style="width: 100%">
                                        @foreach ($cliente_estados as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Género</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Genero" class="form-control">
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                    contribuyente</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="TipoContribuyente" class="form-control" style="width: 100%">
                                        @foreach ($tipos_contribuyente as $obj)
                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Referencia</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="Referencia" type="text">
                                </div>
                            </div>                          


                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-success">Aceptar</button>
                                <a href="{{ url('catalogo/cliente') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
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
        $('#TipoPersona').change(function(){
           // if()
        })

    })
</script>
@endsection