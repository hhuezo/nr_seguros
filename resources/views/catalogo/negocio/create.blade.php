@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Nuevo Negocio <small></small></h2>
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


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ session('tab1') == 1 ? 'active' : '' }}"><a href="#cliente"
                                                                                                      id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos Negocio</a>

                        </li>
                        <li role="presentation" class="{{ session('tab1') == 2 ? 'active' : '' }}"><a href="#redes"
                                                                                                      role="tab" id="profile-necesidad" data-toggle="tab" aria-expanded="false">Información de contacto</a>
                        </li>
                    </ul>

                    <div id="myTabContent2" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 1 ? 'active in' : '' }} "
                             id="cliente" aria-labelledby="home-tab">
                        <form action="{{ url('catalogo/negocio') }}" method="POST" class="forms-sample">
                            @csrf
                            <div class="x_content">
            {{--                    <div>--}}
            {{--                        <h4>Prospecto del cliente</h4>--}}
            {{--                    </div>--}}
            {{--                    <h5>Prospecto de Cliente<small></small></h5>--}}
                                <input type="hidden" name="CantidadDependiente" id="CantidadDependiente" value="{{ old('CantidadDependiente') }}" class="form-control" required autofocus="true">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="BuscarCliente" class="form-label">Código Cliente</label>
                                                <input type="text" class="form-control" id="NombreCliente"
                                                       name="NombreCliente" placeholder="Ingrese codigo de cliente">
                                        </div><!-- /.col-lg-6 -->
                                        <div class="col-md-4">
                                            <label for="Nombre" class="form-label">Nombre</label>
                                            <input class="form-control" type="text" value=""
                                                   id="Nombre" name="Nombre" >
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Nombre" class="form-label">Tipo Cliente</label>
                                            <select name="TipoPersona" id="TipoPersona" class="form-control">
                                                <option value="" selected disabled>Seleccione ...</option>
                                                <option value="1">Natural</option>
                                                <option value="2">Juridica</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row" style="margin-top: 12px!important;">
                                        <div class="col-md-4">
                                            <label for="Estado" class="form-label">Estado Cliente</label>
                                            <select name="EstadoCliente" id="EstadoCliente" class="form-control select2" >
                                                <option value="" selected disabled> Seleccione...</option>
                                                <option value="1">Pospecto</option>
                                                <option value="2">Cliente</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Ejecutivo" class="form-label">Ejecutivo</label>
                                            <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($ejecutivos as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="EstadoVenta" class="form-label">Estado Venta</label>
                                            <select name="EstadoVenta" class="form-control select2" style="width: 100%" required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($estados_venta as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-4">
                                            <label for="Ejecutivo" class="form-label">Ramo</label>
                                            <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                <option value="">Daños</option>
                                                <option value="">Vida</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Nombre" class="form-label">Número de póliza</label>
                                            <input class="form-control" type="text" value=""
                                                   id="Nombre" name="Nombre" >
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Nombre" class="form-label">Necesidad de protección</label>
                                            <select name="NecesidadProteccion" id="NecesidadProteccion" class="form-control select2" style="width: 100%;" required>
                                                <option value="">Seleccione...</option>
                                                @foreach($necesidad_proteccion as $obj)
                                                    <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-4">
                                            <label for="Estado" class="form-label">Tipo de Necesidad (consultar nombre)</label>
                                            <select name="TipoPlan" id="TipoPlan" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                <option value="">Individual</option>
                                                <option value="">Colectivo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="SumaAsegurada" class="form-label">Plan / Tipo de producto</label>
                                            <input type="text" class="form-control" id="planProducto"
                                                   name="planProducto" placeholder="Ingrese el plan">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Estado" class="form-label">Tipo de negocio</label>
                                            <select name="TipoNegocio" id="TipoNegocio" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                <option value="">Nuevo</option>
                                                <option value="">Aumento Suma</option>
                                                <option value="">Aumento Tarifa</option>
                                                <option value="">Incorporación</option>
                                            </select>
                                        </div>


                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-4">
                                            <label for="DepartamentoAtiende" class="form-label">Departamento que atenderá</label>
                                            <select name="DepartamentoAtiende" id="DepartamentoAtiende" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                <option value="">OPS AUTOMOTOR</option>
                                                <option value="">OPS DAÑOS</option>
                                                <option value="">OPS VIDA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="DepartamentoAtiende" class="form-label">Método de pago</label>
                                            <select name="FormaPago" id="FormaPago" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($forma_pago as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="DepartamentoAtiende" class="form-label">Forma de Pago</label>
                                            <select name="DepartamentoAtiende" id="DepartamentoAtiende" class="form-control select2" style="width: 100%" required>
                                                <option value="" selected disabled >Seleccione...</option>
                                                <option value="">Anual</option>
                                                <option value="">Semestral</option>
                                                <option value="">Trimestral</option>
                                                <option value="">Mensual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-4">
                                            <label for="FechaVenta" class="form-label">Fecha Venta</label>
                                            <input type="date"  pattern="\d{2}\s\w+\s\d{4}" class="form-control" name="FechaVenta" id="FechaVenta">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="FechaVenta" class="form-label">Inicio Vigencia</label>
                                            <input type="date"  pattern="\d{2}\s\w+\s\d{4}" class="form-control" name="FechaVenta" id="FechaVenta">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="FechaVenta" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="Email" id="Email">
                                        </div>
                                    </div>
                                    <div class="row" style="padding-top: 15px !important;">
                                        <div class="col-md-12">
                                            <label for="FechaVenta" class="form-label">Observaciones o anotaciones</label>
                                                <textarea name="Observacion" rows="3" class="form-control"></textarea>

                                        </div>
                                    </div>

                                    <div class="row" style="padding-top: 15px !important;">
                                        <input type="hidden" id="DataAseguradora" name="Aseguradoras">


                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div>
                                                <h4>Cotizaciones de Aseguradoras</h4>
                                                <hr>
                                            </div>
                                            <div class="x_title">
                                                <div class="float-right" style="text-align: right;">
                                                    <button type="button" onclick="modal_aseguradora()" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> Nueva Cotización
                                                    </button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <div class="table-responsive" id="divAseguradoras">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

            {{--                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">--}}
            {{--                            <div class="form-group">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Tipo Persona </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <select name="TipoPersona" id="TipoPersona" class="form-control">--}}
            {{--                                        <option value="">Seleccione ...</option>--}}
            {{--                                        <option value="1">Natural</option>--}}
            {{--                                        <option value="2">Juridica</option>--}}
            {{--                                    </select>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="form-group" style="display: show;" id='Duis'>--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">DUI </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="text" name="Dui" id="Dui" value="{{ old('Dui') }}" data-inputmask="'mask': ['99999999-9']" onkeydown="mostrar();" class="form-control" required autofocus="true">--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="form-group" style="display: none;" id='DuiRepresentantes'>--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">DUI (Representante) </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="text" name="DuiRepresentante" id="DuiRepresentante" value="{{ old('DuiRepresentante') }}" onkeydown="mostrar();" data-inputmask="'mask': ['99999999-9']" class="form-control" autofocus="true">--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="form-group row" id="Homolo" style="display: show;">--}}

            {{--                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Homologado</label>--}}
            {{--                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">--}}
            {{--                                    <input name="Homologado" id="Homologado" type="checkbox" class="js-switch" />--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="form-group" style="display: show;" id="Nits">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">NIT </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="text" name="Nit" id="Nit" value="{{ old('Nit') }}" class="form-control" required autofocus="true">--}}
            {{--                                </div>--}}

            {{--                            </div>--}}
            {{--                            <div class="form-group" style="display: none;" id='NitEmpresas'>--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">NIT Empresa </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="text" name="NitEmpresa" id="NitEmpresa" value="{{ old('NitEmpresa') }}" data-inputmask="'mask': ['9999-999999-999-9']" class="form-control" required autofocus="true">--}}
            {{--                                </div>--}}

            {{--                            </div>--}}


            {{--                            <div class="form-group">--}}
            {{--                                <label class="control-label col-md-12col-sm-12 col-xs-12" style="text-align: left;">Nombre </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}" class="form-control" required autofocus="true">--}}
            {{--                                </div>--}}

            {{--                            </div>--}}
            {{--                            <div class="form-group">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Forma de Pago </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <select name="FormaPago" id="FormaPago" class="form-control" style="width: 100%" required>--}}
            {{--                                        <option value="">Seleccione...</option>--}}
            {{--                                        @foreach ($forma_pago as $obj)--}}
            {{--                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>--}}
            {{--                                        @endforeach--}}
            {{--                                    </select>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}

            {{--                        </div>--}}
            {{--                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">--}}
            {{--                            <div class="form-group row">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Necesidad de Protección</label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <select name="NecesidadProteccion" id="NecesidadProteccion" class="form-control select2" style="width: 100%;" required>--}}
            {{--                                        <option value="">Seleccione...</option>--}}
            {{--                                        @foreach($necesidad_proteccion as $obj)--}}
            {{--                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>--}}
            {{--                                        @endforeach--}}
            {{--                                    </select>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group row">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Tipo de Plan</label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <select name="TipoPlan" id="TipoPlan" class="form-control" style="width: 100%;" required>--}}


            {{--                                    </select>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="form-group" id="CantidadDependientes" style="display: none;">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Cantidad de Personas </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="number" name="CantidadDependiente" id="CantidadDependiente" value="{{ old('CantidadDependiente') }}" class="form-control" required autofocus="true">--}}
            {{--                                </div>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group" >--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Numero de Cuotas </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="number" name="NumCoutas" id="NumCoutas" value="{{ old('NumCoutas') }}" class="form-control" required autofocus="true">--}}

            {{--                                </div>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group row">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Vendedor</label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>--}}
            {{--                                        <option value="">Seleccione...</option>--}}
            {{--                                        @foreach ($ejecutivos as $obj)--}}
            {{--                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>--}}
            {{--                                        @endforeach--}}
            {{--                                    </select>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="form-group">--}}
            {{--                                <label class="control-label col-md-12col-sm-12 col-xs-12" style="text-align: left;">Inicio de Vigencia </label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <input type="date" name="InicioVigencia" id="InicioVigencia" value="{{ old('InicioVigencia') }}" class="form-control" required autofocus="true">--}}
            {{--                                </div>--}}

            {{--                            </div>--}}
            {{--                            <div class="form-group row">--}}
            {{--                                <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Estado Venta</label>--}}
            {{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
            {{--                                    <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>--}}
            {{--                                        <option value="">Seleccione...</option>--}}
            {{--                                        @foreach ($estados_venta as $obj)--}}
            {{--                                        <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>--}}
            {{--                                        @endforeach--}}
            {{--                                    </select>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}




            {{--                        </div>--}}


                                </div>



                            </div>

                            <div class="form-group" align="center">
                                <button class="btn btn-success" type="submit">Guardar</button>
                                <a href="{{ url('catalogo/negocio/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                            </div>
                        </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ session('tab1') == 2 ? 'active in' : '' }}"
                             id="redes" aria-labelledby="home-tab">

                            <div class="row" style="padding-top: 15px !important;">
                                <input type="hidden" id="DataAseguradora" name="Aseguradoras">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <div class="x_title">
                                        <div class="float-right" style="text-align: right;">
                                            <button type="button" onclick="modal_aseguradora()" class="btn btn-info"><i
                                                    class="fa fa-plus"></i> Nuevo Registro
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="table-responsive" id="divTblInformacinoContacto">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Contacto</th>
                                                    <th scope="col">Descripción de la operación</th>
                                                    <th scope="col">Telefonos de contacto</th>
                                                    <th scope="col">Observaciones</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>Mark</td>
                                                    <td>Otto</td>
                                                    <td>@mdo</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>Jacob</td>
                                                    <td>Thornton</td>
                                                    <td>@fat</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>Larry</td>
                                                    <td>the Bird</td>
                                                    <td>@twitter</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        @include('sweetalert::alert')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </div>
</div>

@include('catalogo.negocio.modal_aseguradora')

<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    function mostrar() {
        if (document.getElementById('TipoPersona').value === '') {
            // Swal.Fire('Tipo de Persona', 'Debe seleccionar el Tipo Persona', 'success');
            //  alert('');
            Swal.fire({
                title: 'Error!',
                text: 'Debe seleccionar el Tipo Persona',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                timer: 1500
            })
        } else {
            if (event.keyCode === 13) {
                var Dui = document.getElementById('Dui').value;
                var Nit = document.getElementById('NitEmpresa').value;
                var tipoPersona = document.getElementById('TipoPersona').value;
                var parametros = {
                    "Dui": Dui,
                    "Nit": Nit,
                    "tipoPersona": tipoPersona
                };

                $.ajax({
                    type: "get",
                    url: "{{URL::to('negocio/getCliente')}}",
                    data: parametros,
                    success: function(data) {
                        console.log(data);
                        $('#Nombre').html(data);
                        var formaPagoData = JSON.parse(data);

                        var _select = '';
                        _select += '<option value="' + formaPagoData.Id + '" selected>' + formaPagoData.Nombre + '</option>';
                        $("#FormaPago").html(_select);
                    }
                });
            }
        }

    }
    $(document).ready(function() {
        $("#NecesidadProteccion").change(function() {
            if (document.getElementById('NecesidadProteccion').value == 10) {
                var select = $("#TipoPlan");

                // Array con las opciones
                var opciones = [{
                        value: '1',
                        text: 'Individual'
                    },
                    {
                        value: '2',
                        text: 'Colectivo'
                    },
                    {
                        value: '3',
                        text: 'Familiar'
                    }
                    // Agregar más opciones si es necesario
                ];
            } else if (document.getElementById('NecesidadProteccion').value == 11) {
                var select = $("#TipoPlan");

                // Array con las opciones
                var opciones = [{
                        value: '1',
                        text: 'Individual'
                    },
                    {
                        value: '2',
                        text: 'Colectivo'
                    },
                    {
                        value: '4',
                        text: 'Usuarios'
                    }
                    // Agregar más opciones si es necesario
                ];
            } else if (document.getElementById('NecesidadProteccion').value == 1 || document.getElementById('NecesidadProteccion').value == 2 ||
                document.getElementById('NecesidadProteccion').value == 5 || document.getElementById('NecesidadProteccion').value == 13) {
                var select = $("#TipoPlan");

                // Array con las opciones
                var opciones = [{
                        value: '1',
                        text: 'Individual'
                    },
                    {
                        value: '2',
                        text: 'Colectivo'
                    }
                    // Agregar más opciones si es necesario
                ];
            } else {
                var select = $("#TipoPlan");

                // Array con las opciones
                var opciones = [{
                        value: '2',
                        text: 'Colectivo'
                    }
                    // Agregar más opciones si es necesario
                ];
            }


            // Generar el HTML con las opciones
            var opcionesHTML = "";
            $.each(opciones, function(index, opcion) {
                opcionesHTML += "<option value='" + opcion.value + "'>" + opcion.text + "</option>";
            });

            // Reemplazar el contenido del select con las opciones generadas
            select.html(opcionesHTML);

        })

        $("#TipoPlan").change(function() {
            if (document.getElementById('NecesidadProteccion').value == 10 && document.getElementById('TipoPlan').value == 3) {
                $("#CantidadDependientes").show();
            } else {
                $("#CantidadDependientes").hide();
            }
        })

        $("#TipoPersona").change(function() {

            if (document.getElementById('TipoPersona').value == 2) {
                $('#DuiRepresentantes').show();
                $('#Duis').hide();
                $('#NitEmpresas').show();
                $('#Nits').hide();
                $('#Homolo').hide();
                document.getElementById('Dui').removeAttribute('required');
                document.getElementById('DuiRepresentantes').setAttribute('required', true);
                document.getElementById('Nit').removeAttribute('required');
                document.getElementById('NitEmpresa').setAttribute('required', true);
            } else {
                $('#DuiRepresentantes').hide();
                $('#Duis').show();
                $('#NitEmpresas').hide();
                $('#Nits').show();
                $('#Homolo').show();
                document.getElementById('DuiRepresentantes').removeAttribute('required');
                document.getElementById('Dui').setAttribute('required', true);
                document.getElementById('NitEmpresa').removeAttribute('required');
                document.getElementById('Nit').setAttribute('required', true);
            }
        })
        $("#Homologado").change(function() {

            if (document.getElementById('Homologado').checked == true) {
                $('#Nit').prop('readonly', true);
                $("#Nit").removeAttr("data-inputmask");
                $("#Nit").attr("data-inputmask", "{'mask': '99999999-9'}");
                document.getElementById('Nit').value = document.getElementById('Dui').value;

                // document.getElementById('Nit').removeAttribute("data-mask");

            } else {
                document.getElementById('Nit').value = '';
                $('#Nit').removeAttr('readonly');
                $("#Nit").removeAttr("data-inputmask");
                $("#Nit").attr("data-inputmask", "{'mask': '9999-999999-999-9'}");
                //  document.getElementById('Nit').removeAttribute("data-mask");
            }
        })
    })

    function modal_aseguradora() {
        if (document.getElementById('NecesidadProteccion').value == '' || document.getElementById('TipoPlan').value == '') {
            ///   alert('Debe seleccionar el Tipo Persona');
            Swal.fire({
                title: 'Error!',
                text: 'Seleccione la necesidad y el plan para continuar ',
                icon: 'info',
                confirmButtonText: 'Aceptar',
                timer: 3500
            })
        } else {
            document.getElementById('ModalNecesidad').value = document.getElementById('NecesidadProteccion').value;
            document.getElementById('ModalTipoPlan').value = document.getElementById('TipoPlan').value;
            //  document.getElementById('ModalDepedientes').value = document.getElementById('CantidadDependiente').value;



            var necesidad = document.getElementById('NecesidadProteccion').value;
            var plan = document.getElementById('TipoPlan').value;
            var dependientes = document.getElementById('CantidadDependiente').value;

            document.getElementById('SumaAsegurada').value = 0;
            document.getElementById('Prima').value = '';
            document.getElementById('FechaNacimiento').value = '';
            document.getElementById('Cantidad').value = '';
            document.getElementById('Marca').value = '';
            document.getElementById('Modelo').value = '';
            document.getElementById('Axo').value = '';
            document.getElementById('Placa').value = '';
            document.getElementById('Direccion').value = '';
            document.getElementById('Giro').value = '';
            document.getElementById('ValorConstruccion').value = '';
            document.getElementById('ValorContenido').value = '';
            document.getElementById('Vida').value = '';
            document.getElementById('Dental').value = '';
            document.getElementById('CantidadPersona').value = '';
            document.getElementById('Contributivo').value = '';
            document.getElementById('MaximoVitalicio').value = '';
            document.getElementById('CantidadTitulares').value = '';
            document.getElementById('Fumador').value = '';
            document.getElementById('InvalidezParcial').value = '';
            document.getElementById('InvalidezTotal').value = '';
            document.getElementById('GastosFunerario').value = '';
            document.getElementById('EnfermedadesGrave').value = '';
            document.getElementById('Termino').value = '';
            document.getElementById('Ahorro').value = '';
            document.getElementById('Plazo').value = '';
            document.getElementById('SesionBeneficio').value = '';
            document.getElementById('Cobertura').value = '';
            document.getElementById('TipoCartera').value = '';
            document.getElementById('FechaNacimientoFamiliar').value = '';
            document.getElementById('GeneroFamiliar').value = '';
            document.getElementById('ParentescoFamiliar').value = '';
            document.getElementById('VidaFamiliar').value = '';
            document.getElementById('DentalFamiliar').value = '';

            $("#FechaNacimientos").hide();
            $("#Generos").hide();
            $("#Cantidads").hide();
            $("#Marcas").hide();
            $("#Modelos").hide();
            $("#Axos").hide();
            $("#Placas").hide();
            $("#Direccions").hide();
            $("#Giros").hide();
            $("#ValorConstruccions").hide();
            $("#ValorContenidos").hide();
            $("#Vidas").hide();
            $("#Dentals").hide();
            $("#CantidadPersonas").hide();
            $("#Contributivos").hide();
            $("#MaximoVitalicios").hide();
            $("#CantidadTitularess").hide();
            $("#Fumadors").hide();
            $("#InvalidezParcials").hide();
            $("#InvalidezTotals").hide();
            $("#GastosFunerarios").hide();
            $("#EnfermedadesGraves").hide();
            $("#Terminos").hide();
            $("#Ahorros").hide();
            $("#Plazos").hide();
            $("#SesionBeneficios").hide();
            $("#Coberturas").hide();
            $("#TipoCarteras").hide();
            $("#FechaNacimientosFamiliar").hide();
            $("#GenerosFamiliar").hide();
            $("#ParentescosFamiliar").hide();
            $("#VidasFamiliar").hide();
            $("#DentalsFamiliar").hide();
            for (let i = 0; i < dependientes; i++) {
                $("#FechaNacimientosFamiliar" + i).hide();
                $("#GenerosFamiliar" + i).hide();
            }
            if (necesidad == 13 && plan == 1) { //accidentes e individual
                $("#FechaNacimientos").show();
                $("#Generos").show();
            } else if (necesidad == 13 && plan == 2) { //accidentes  y colectivo

                $("#Cantidads").show();
            } else if (necesidad == 1 && plan == 1) { //autos e individual
                $("#Marcas").show();
                $("#Modelos").show();
                $("#Axos").show();
                $("#Placas").show();
            } else if (necesidad == 1 && plan == 2) { //autos y colectivo
                $("#Cantidads").show();
            } else if (necesidad == 2 && plan == 1) { //incendio y individual
                $("#Direccions").show();
                $("#Giros").show();
                $("#ValorConstruccions").show();
                $("#ValorContenidos").show();
            } else if (necesidad == 10 && plan == 1) { //gastos medicos e individual
                $("#FechaNacimientos").show();
                $("#Generos").show();
                $("#Vidas").show();
                $("#Dentals").show();
            } else if (necesidad == 10 && plan == 2) { //gastos medicos y colectivo
                $("#CantidadPersonas").show();
                $("#Contributivos").show();
                $("#MaximoVitalicios").show();
                $("#CantidadTitularess").show();
            } else if (necesidad == 10 && plan == 3) { //gastos medicos y familiares
                // for (let i = 0; i < dependientes; i++) {
                //     $("#FechaNacimientosFamiliar" + i).show();
                //     $("#GenerosFamiliar" + i).show();
                // }

                $("#FechaNacimientosFamiliar").show();
                $("#GenerosFamiliar").show();
                $("#ParentescosFamiliar").show();
                $("#VidasFamiliar").show();
                $("#DentalsFamiliar").show();

            } else if (necesidad == 11 && plan == 1) { //vida y individual
                $("#FechaNacimientos").show();
                $("#Generos").show();
                $("#Fumadors").show();
                $("#InvalidezParcials").show();
                $("#InvalidezTotals").show();
                $("#GastosFunerarios").show();
                $("#EnfermedadesGraves").show();
                $("#Terminos").show();
                $("#Ahorros").show();
                $("#Plazos").show();
                $("#SesionBeneficios").show();

            } else if (necesidad == 11 && plan == 2) { //vida y colectivo
                $("#Coberturas").show();
            } else if (necesidad == 11 && plan == 3) { //vida y usuarios
                $("#Coberturas").show();
            } else if (necesidad == 14 && plan == 2) { //vida deuda y colectivo
                $("#Coberturas").show();
                $("#TipoCarteras").show();
            }
            $('#modal_aseguradora').modal('show');
        }

    }
</script>
@include('sweetalert::alert')
@endsection
