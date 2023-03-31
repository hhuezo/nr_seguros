@extends ('welcome')
@section('contenido')
    <script src="{{ asset('vendors/sweetalert/sweetalert.min.js') }}"></script>
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Seguro de residencias <small></small></h2>
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
                    <div class="x_content">
                        <br />
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Numero</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="NumeroPoliza" value="{{ old('NumeroPoliza') }}"
                                        class="form-control" required autofocus="true">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Nit</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Nit" value="{{ old('Nit') }}" class="form-control"
                                        data-inputmask="'mask': ['9999-999999-999-9']">
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Aseguradora</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select name="Aseguradora" class="form-control select2" style="width: 100%">
                                        @foreach ($aseguradoras as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Asegurado</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" name="Asegurado" value="{{ old('Asegurado') }}"
                                        onblur="this.value = this.value.toUpperCase();" class="form-control">
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

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Vegencia desde</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="date" name="VegenciaDesde" value="{{ old('VegenciaDesde') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Vegencia hasta</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="date" name="VegenciaHasta" value="{{ old('VegenciaHasta') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Monto cartera</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="MontoCartera"
                                        value="{{ old('MontoCartera') }}" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Tasa %</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="Tasa" value="{{ old('Tasa') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Descuento</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="Descuento" value="{{ old('Descuento') }}"
                                        class="form-control" autofocus="true" data-inputmask="'mask': ['9999-9999']">
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">





                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">&nbsp;</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="Iva"> &nbsp;IVA
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite grupo</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="LimiteGrupo"
                                        value="{{ old('LimiteGrupo') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Limite individual</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="LimiteIndividual"
                                        value="{{ old('LimiteIndividual') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Valor prima</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="ValorPrima"
                                        value="{{ old('ValorPrima') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Gastos emision</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="GastosEmision"
                                        value="{{ old('GastosEmision') }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Impuestos bomberos</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="ImpuestosBomberos"
                                        value="{{ old('ImpuestosBomberos') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">IVA</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="Iva" value="{{ old('Iva') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">Menos valor CCF de
                                    comision</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="Comision" value="{{ old('Comision') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12">A pagar</label>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="number" step="0.01" name="APagar" value="{{ old('APagar') }}"
                                        class="form-control">
                                </div>
                            </div>

                        </div>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12"
                                    style="text-align: left">Comentario del cobro</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="Comentario" class="form-control">
                                    {{ old('Comentario') }}
                                   </textarea>
                                </div>

                            </div>
                        </div>



                    </div>

                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Guardar</button>
                        <a href="{{ url('polizas/residencia/') }}"><button class="btn btn-primary"
                                type="button">Cancelar</button></a>
                    </div>

                </form>

            </div>

        </div>
    </div>



@endsection
