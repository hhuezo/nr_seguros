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
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="NumeroPoliza" type="text" value="{{ old('NumeroPoliza') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Codigo" type="text" value="{{ old('Codigo') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradoras as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($cliente as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-lg-1 col-sm-12 col-xs-12"><i onclick="modal_cliente();" class="fa fa-plus fa-lg" style="padding-top: 60%;"></i></div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Nit" id="Nit" type="text" value="{{ old('Nit') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Desde</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaDesde" type="date" value="{{ old('VigenciaDesde') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                Hasta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="VigenciaHasta" type="date" value="{{ old('VigenciaHasta') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivo as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estatus</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="EstadoPoliza" class="form-control" style="width: 100%">
                                    @foreach ($estados_poliza as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite grupo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="any" name="LimiteGrupo" value="{{ old('LimiteGrupo') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite individual</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="any" name="LimiteIndividual" value="{{ old('LimiteIndividual') }}" class="form-control">
                                </div>
                            </div>
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Monto cartera</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="MontoCartera" value="{{ old('MontoCartera') }}" class="form-control">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Tasa" value="{{ old('Tasa') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Descuento %</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Descuento" Id="Descuento" value="{{ old('Descuento') }}" class="form-control" >
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Valor prima</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="ValorPrima" id="ValorPrima" value="{{ old('ValorPrima') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">IVA</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Iva" Id="Iva" value="{{ old('Iva') }}" class="form-control">
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Menos valor CCF de comision</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="Comision" id="ValorCCF" value="{{ old('Comision') }}" class="form-control">
                            </div>
                            <!-- <a href="" data-target="#modal-calculator" data-toggle="modal" class="col-md-1 control-label" style="text-align: center;"><span class="fa fa-calculator fa-lg"></span></a> -->
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">A pagar</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="APagar" id="APagar" value="{{ old('APagar') }}" class="form-control">
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Descuento con Iva</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="checkbox" name="DescuentoIva" style="width: 10%; height: 5%;">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Gastos emision</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="GastosEmision" id="GastosEmision" value="{{ old('GastosEmision') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Impuestos bomberos</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="any" name="ImpuestoBomberos" id="ImpuestoBomberos" value="{{ old('ImpuestosBomberos') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Estructura CCF de comisión</label>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor
                                Desc</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="ValorDescuento" id="ValorDescuento" type="number" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">mas 13%
                                IVA sobre comisión</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="IvaSobreComision" id="IvaSobreComision" type="number" step="0.00000001">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">menos 1%
                                Retención</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="Retencion" id="Retencion" type="number" step="0.00000001">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Valor CCF
                                Comisión</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input class="form-control" name="ValorCCF" id="ValorCCFE" type="number" step="0.00000001">
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left">Comentario del cobro</label>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="Comentario" class="form-control">
                                {{ old('Comentario') }}
                                </textarea>
                            </div>

                        </div>
                    </div>

                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <br>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 1px solid;">
                        <br>
                        <br>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Impresión
                                    de Recibo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="ImpresionRecibo" type="date" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                    Cartera</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="EnvioCartera" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Envió de
                                    Pago</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="EnvioPago" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Saldo
                                    A</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="SaldoA" type="date" style="background-color: yellow;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Pago
                                    Aplicado</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input class="form-control" name="PagoAplicado" type="date">
                                </div>
                            </div>
                        </div>

                    </div>
                    <br><br>
                    <div class="x_title">
                        <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                        <div class="clearfix"></div>
                    </div>



                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('polizas/residencia/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>

            <div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ url('catalogo/cliente_create') }}" method="POST">
                            <div class="modal-header">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="box-body">

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">NIT</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Nit" id="ModalNit" data-inputmask="'mask': ['9999-999999-999-9']" data-mask type="text" autofocus="true">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dui</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Dui" id="ModalDui" data-inputmask="'mask': ['99999999-9']" data-mask type="text" autofocus="true">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nombre</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Nombre" id="ModalNombre" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                                residencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <textarea class="form-control" name="DireccionResidencia" id="ModalDireccionResidencia"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Dirección
                                                correspondecia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <textarea class="form-control" name="DireccionCorrespondencia" id="ModalDireccionCorrespondencia"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                                residencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="TelefonoResidencia" id="ModalTelefonoResidencia" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                                oficina</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="TelefonoOficina" id="ModalTelefonoOficina" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Teléfono
                                                celular</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="TelefonoCelular" id="ModalTelefonoCelular" data-inputmask="'mask': ['9999-9999']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Correo
                                                electrónico</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Correo" id="ModalCorreo" type="email">
                                            </div>
                                        </div>

                                    </div>








                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ruta</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="Ruta" id="ModalRuta" class="form-control select2" style="width: 100%">
                                                    @foreach ($rutas as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Responsable
                                                pago</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="ResponsablePago" id="ModalResponsablePago" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                                contribuyente</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="TipoContribuyente" id="ModalTipoContribuyente" class="form-control" style="width: 100%">
                                                    @foreach ($tipos_contribuyente as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Ubicación de
                                                cobro</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="UbicacionCobro" id="ModalUbicacionCobro" class="form-control" style="width: 100%">
                                                    @foreach ($ubicaciones_cobro as $obj)
                                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Contacto</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Contacto" id="ModalContacto" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Referencia</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="Referencia" id="ModalReferencia" type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Número
                                                tarjeta</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="NumeroTarjeta" id="ModalNumeroTarjeta" data-inputmask="'mask': ['9999-9999-9999-9999']" data-mask type="text">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Fecha
                                                vencimiento</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <input class="form-control" name="FechaVencimiento" id="ModalFechaVencimiento" data-inputmask="'mask': ['99/99']" data-mask type="text">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Género</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="Genero" id="ModalGenero" class="form-control">
                                                    <option value="1">Masculino</option>
                                                    <option value="2">Femenino</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                                persona</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                <select name="TipoPersona" id="ModalTipoPersona" class="form-control">
                                                    <option value="1">Natural</option>
                                                    <option value="2">Jurídica</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="btn_guardar" class="btn btn-primary">Aceptar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
                    if (data.TipoContribuyente < 2) {
                        document.getElementById('Retencion').setAttribute("readonly", true);
                    }


                }
            });
        });
        $("#ValorPrima").change(function() {
            var ValorPrima = document.getElementById('ValorPrima').value;
            var Iva = Number(ValorPrima) * 0.13;
            document.getElementById('Iva').value = Iva;
        })
        $("#ValorDescuento").change(function() {
            var Descuento = document.getElementById('ValorDescuento').value;
            var IvaSobreComision = Descuento * 0.13;
            
            document.getElementById('IvaSobreComision').value = IvaSobreComision;
            if (document.getElementById('Retencion').hasAttribute('disabled')) {
                var Retencion = 0;
            } else {
                var Retencion = Descuento * 0.01;
                document.getElementById('Retencion').value = Retencion;
            }
            var ValorCCF = Number(Descuento) + Number(IvaSobreComision) - Number(Retencion);
           // alert(ValorCCF);
            document.getElementById('ValorCCFE').value = ValorCCF;
            document.getElementById('ValorCCF').value = ValorCCF;
            var ValorPrima = document.getElementById('ValorPrima').value;
            var Iva = document.getElementById('Iva').value;
            var APagar = (Number(ValorPrima) + Number(Iva)) - Number(ValorCCF);
            document.getElementById('APagar').value = APagar;
        })




    });


    $("#btn_guardar").click(function() {

        var parametros = {
            "_token": "{{ csrf_token() }}",
            "Nit": document.getElementById('ModalNit').value,
            "Dui": document.getElementById('ModalDui').value,
            "Nombre": document.getElementById('ModalNombre').value,
            "DireccionResidencia": document.getElementById('ModalDireccionResidencia').value,
            "DireccionCorrespondencia": document.getElementById('ModalDireccionCorrespondencia').value,
            "TelefonoResidencia": document.getElementById('ModalTelefonoResidencia').value,
            "TelefonoOficina": document.getElementById('ModalTelefonoOficina').value,
            "TelefonoCelular": document.getElementById('ModalTelefonoCelular').value,
            "Correo": document.getElementById('ModalCorreo').value,

            "Ruta": document.getElementById('ModalRuta').value,
            "ResponsablePago": document.getElementById('ModalResponsablePago').value,
            "TipoContribuyente": document.getElementById('ModalTipoContribuyente').value,
            "UbicacionCobro": document.getElementById('ModalUbicacionCobro').value,
            "Contacto": document.getElementById('ModalContacto').value,
            "Referencia": document.getElementById('ModalReferencia').value,
            "NumeroTarjeta": document.getElementById('ModalNumeroTarjeta').value,
            "FechaVencimiento": document.getElementById('ModalFechaVencimiento').value,
            "Genero": document.getElementById('ModalGenero').value,
            "TipoPersona": document.getElementById('ModalTipoPersona').value,
        };
        $.ajax({
            type: "post",
            url: "{{ url('catalogo/cliente_create') }}",
            data: parametros,
            success: function(data) {
                //console.log(data);
                //$('#response').html(data);
                var _select = ''
                for (var i = 0; i < data.length; i++)
                    _select += '<option value="' + data[i].Id + '" selected >' + data[i].Nombre +
                    '</option>';
                $("#Asegurado").html(_select);
                $('#modal_cliente').modal('hide');
            }
        })
    });


    function modal_cliente() {
        $('#modal_cliente').modal('show');
    }
</script>



@endsection