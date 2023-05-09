@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

            <div class="x_title">
                <h2>Editar Poliza Vida &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; VICO - Vida Colectivo Seguros<small></small>
                </h2>
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

                <form method="POST" action="{{ route('vida.renovarPoliza', $vida->Id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-horizontal" style="font-size: 12px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Número de Póliza</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroPoliza" type="text" value="{{ $vida->NumeroPoliza }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Código</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Codigo" type="text" value="{{ $vida->Codigo }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Aseguradora</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Aseguradora" type="text" value="{{ $vida->aseguradoras->Nombre }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Asegurado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Asegurado" type="text" value="{{ $vida->clientes->Nombre }}" readonly>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Nit</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Nit" id="Nit" type="text" value="{{ $vida->Nit }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Grupo
                                        Asegurado</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="GrupoAsegurado" row="3" col="4" value="">{{ $vida->GrupoAsegurado }} </textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Clausulas
                                        Especiales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4" value="">{{ $vida->ClausulasEspeciales }} </textarea>
                                    </div>
                                </div>




                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Desde</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaDesde" type="date" value="{{ \Carbon\Carbon::parse($vida->VigenciaDesde)->format('d/m/Y') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo
                                        Cartera</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="TipoCartera" type="text" value="{{ $vida->tipoCarteras->Nombre }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vendedor</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Ejecutivo" type="text" value="{{ $vida->ejecutivos->Nombre }}" readonly>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">&nbsp;
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        @if ($vida->Mensual == 1)
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Mensual" value="1" checked>
                                            <label class="control-label">Tasa ‰ Millar Mensual</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Anual" value="0">
                                            <label class="control-label">Tasa ‰ Millar Anual</label>
                                        </div>
                                        @else
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Mensual" value="1">
                                            <label class="control-label">Tasa ‰ Millar Mensual</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="radio" name="tipoTasa" id="Anual" value="0" checked>
                                            <label class="control-label">Tasa ‰ Millar Anual</label>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Monto
                                        Cartera
                                    </label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="MontoCartera" id="MontoCartera" type="number" step="any" value="{{ $vida->MontoCartera }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tasa
                                        %</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa" id="Tasa" type="number" step="any" value="{{ $vida->Tasa }}" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Sub
                                        Total</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SubTotal" type="number" id="SubTotal" step="any" value="{{ old('PrimaTotal') }}" required>
                                    </div>
                                </div>


                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Vigencia
                                        Hasta</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="VigenciaHasta" type="date" value="{{ \Carbon\Carbon::parse($vida->VigenciaHasta)->format('d/m/Y') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Estatus</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%" required>
                                            @foreach ($estados_poliza as $obj)
                                            @if($obj->Id == 2)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Tipo de
                                        Cobro</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <select name="TipoCobro" class="form-control select2" id="TipoCobro" style="width: 100%" required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($tipoCobro as $obj)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Concepto</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="Concepto" row="3" col="4"> {{ $vida->Concepto }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Beneficios
                                        Adicionales</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <textarea class="form-control" name="BeneficiosAdicionales" row="3" col="4" value="">{{ $vida->BeneficiosAdicionales }} </textarea>
                                    </div>
                                </div>

                                <br>

                            </div>
                            <br><br>
                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>
                            <br>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="Usuarios" style="display: none;">
                            <br><br>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No Usuario 1 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario1" id="NumeroUsuario1" type="number" value="{{ old('NumeroUsuario1') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 1 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada1" id="SumaAsegurada1" type="number" step="0.01" value="{{ old('SumaAsegurada1') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Tasa 1 %*</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa1" id="Tasa1" type="number" step="0.01" value="{{ old('Tasa1') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 1
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima1" id="Prima1" type="number" step="0.01" value="{{ old('Prima1') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No Usuario 2 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario2" id="NumeroUsuario2" type="number" value="{{ old('NumeroUsuario2') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 2 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada2" id="SumaAsegurada2" type="number" step="0.01" value="{{ old('SumaAsegurada2') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Tasa 2 %*</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa2" id="Tasa2" type="number" step="0.01" value="{{ old('Tasa2') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 2
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima2" id="Prima2" type="number" step="0.01" value="{{ old('Prima2') }}" required>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No Usuario 3 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario3" id="NumeroUsuario3" type="number" value="{{ old('NumeroUsuario3') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 3 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada3" id="SumaAsegurada3" type="number" step="0.01" value="{{ old('SumaAsegurada3') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Tasa 3 %*</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa3" id="Tasa3" type="number" step="0.01" value="{{ old('Tasa3') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 3
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima3" id="Prima3" type="number" step="0.01" value="{{ old('Prima3') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No Usuario 4 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario4" id="NumeroUsuario4" type="number" value="{{ old('NumeroUsuario4') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 4 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada4" id="SumaAsegurada4" type="number" step="0.01" value="{{ old('SumaAsegurada4') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Tasa 4 %*</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa4" id="Tasa4" type="number" step="0.01" value="{{ old('Tasa4') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 4
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima4" id="Prima4" type="number" step="0.01" value="{{ old('Prima4') }}" required>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No Usuario 5 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario5" id="NumeroUsuario5" type="number" value="{{ old('NumeroUsuario5') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 5 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada5" id="SumaAsegurada5" type="number" step="0.01" value="{{ old('SumaAsegurada5') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Tasa 5 %*</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa5" id="Tasa5" type="number" step="0.01" value="{{ old('Tasa5') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 5
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima5" id="Prima5" type="number" step="0.01" value="{{ old('Prima5') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">No Usuario 6 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="NumeroUsuario6" id="NumeroUsuario6" type="number" value="{{ old('NumeroUsuario6') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Suma Asegurada 6 *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="SumaAsegurada6" id="SumaAsegurada6" type="number" step="0.01" value="{{ old('SumaAsegurada6') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right" style="margin-top: -3%;">Tasa 6 %*</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Tasa6" id="Tasa6" type="number" step="0.01" value="{{ old('Tasa6') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 col-sm-12 col-xs-12" align="right">Prima 6
                                        *</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <input class="form-control" name="Prima6" id="Prima6" type="number" step="0.01" value="{{ old('Prima6') }}" required>
                                    </div>
                                </div>

                            </div>
                            <br>
                        </div>




                        <br><br>
                        <div class="x_title">
                            <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                            <div class="clearfix"></div>
                        </div>








                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group" align="center">

                                <button type="submit" class="btn btn-success">Renovar</button>
                                <a href="{{ url('poliza/vida') }}"><button type="button" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>




</div>
@include('sweetalert::alert')
<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#Anual").change(function() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            var tasaFinal = (tasa / 1000) / 12;
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
            calculoPrimaRepartida();
        })
        $("#Mensual").change(function() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            var tasaFinal = tasa / 1000;
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
            calculoPrimaRepartida();
        })

        $("#MontoCartera").change(function() {
            calculoSubTotal();
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        
        $("#TipoCobro").change(function() {
            if (document.getElementById('TipoCobro').value == 1) {
                $("#Usuarios").show();
                document.getElementById('MontoCartera').setAttribute("readonly", true);
                document.getElementById('SubTotal').setAttribute("readonly", true);
                tasaRepartido();
            } else {
                $("#Usuarios").hide();
            }


        })

        function calculoSubTotal() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            if (document.getElementById('Anual').checked == true) {
                var tasaFinal = (tasa / 1000) / 12;
            } else {
                var tasaFinal = tasa / 1000;
            }
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
        }
        $('#ExtPrima').change(function() {
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoPrimaTotal() {
            var sub = document.getElementById('SubTotal').value;
            var extra = document.getElementById('ExtPrima').value;
            var prima = Number(sub) + Number(extra);
            document.getElementById('PrimaTotal').value = Number(prima);
        }
        $("#Descuento").change(function() {
            calculoPrimaDescontada();
            calculoCCF();
        })

        function calculoPrimaDescontada() {
            var prima = document.getElementById('PrimaTotal').value;
            var descuento = document.getElementById('Descuento').value;
            if (descuento == 0) {
                var total = Number(prima);
            } else {
                var total = Number(prima * (descuento / 100));
            }
            document.getElementById('PrimaDescontada').value = total + Number(document.getElementById('PrimaTotal').value);

        }

        $("#TasaComision").change(function() {
            calculoCCF();
        })
        $("#Tasa").change(function() {
            if (document.getElementById('TipoCobro').value == 1) {
                $("#Usuarios").show();
                document.getElementById('MontoCartera').setAttribute("readonly", true);
                document.getElementById('SubTotal').setAttribute("readonly", true);
                tasaRepartido();
                calculoPrimaRepartida();
            } else {
                $("#Usuarios").hide();
                calculoSubTotal();
                calculoPrimaTotal();
                calculoPrimaDescontada();
                calculoCCF();
            }

        })
        $("#MontoCartera").change(function() {
            calculoSubTotal();
            calculoPrimaTotal();
            calculoPrimaDescontada();
            calculoCCF();
        })

        function tasaRepartido() {
            var tasa = document.getElementById('Tasa').value;
            document.getElementById('Tasa1').value = tasa;
            document.getElementById('Tasa2').value = tasa;
            document.getElementById('Tasa3').value = tasa;
            document.getElementById('Tasa4').value = tasa;
            document.getElementById('Tasa5').value = tasa;
            document.getElementById('Tasa6').value = tasa;

        }

        function calculoPrimaRepartida() {
            if (document.getElementById('Anual').checked == true) {
                var tasa1 = ((document.getElementById('Tasa1').value) / 1000) / 12;
                var tasa2 = ((document.getElementById('Tasa2').value) / 1000) / 12;
                var tasa3 = ((document.getElementById('Tasa3').value) / 1000) / 12;
                var tasa4 = ((document.getElementById('Tasa4').value) / 1000) / 12;
                var tasa5 = ((document.getElementById('Tasa5').value) / 1000) / 12;
                var tasa6 = ((document.getElementById('Tasa6').value) / 1000) / 12;
            } else if (document.getElementById('Mensual').checked == true) {
                var tasa1 = ((document.getElementById('Tasa1').value) / 1000);
                var tasa2 = ((document.getElementById('Tasa2').value) / 1000);
                var tasa3 = ((document.getElementById('Tasa3').value) / 1000);
                var tasa4 = ((document.getElementById('Tasa4').value) / 1000);
                var tasa5 = ((document.getElementById('Tasa5').value) / 1000);
                var tasa6 = ((document.getElementById('Tasa6').value) / 1000);
            }

            var prima1 = document.getElementById('SumaAsegurada1').value * tasa1;
            var prima2 = document.getElementById('SumaAsegurada2').value * tasa2;
            var prima3 = document.getElementById('SumaAsegurada3').value * tasa3;
            var prima4 = document.getElementById('SumaAsegurada4').value * tasa4;
            var prima5 = document.getElementById('SumaAsegurada5').value * tasa5;
            var prima6 = document.getElementById('SumaAsegurada6').value * tasa6;

            document.getElementById('Prima1').value = prima1;
            document.getElementById('Prima2').value = prima2;
            document.getElementById('Prima3').value = prima3;
            document.getElementById('Prima4').value = prima4;
            document.getElementById('Prima5').value = prima5;
            document.getElementById('Prima6').value = prima6;
            document.getElementById('SubTotal').value = Number(prima1) + Number(prima2) + Number(prima3) + Number(prima4) + Number(prima5) + Number(prima6);

        }


        $("#SumaAsegurada1").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada2").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada3").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada4").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada5").change(function() {
            calculoMontoCartera();
        })
        $("#SumaAsegurada6").change(function() {
            calculoMontoCartera();
        })

        function calculoMontoCartera() {
            var suma1 = document.getElementById('SumaAsegurada1').value;
            var suma2 = document.getElementById('SumaAsegurada2').value;
            var suma3 = document.getElementById('SumaAsegurada3').value;
            var suma4 = document.getElementById('SumaAsegurada4').value;
            var suma5 = document.getElementById('SumaAsegurada5').value;
            var suma6 = document.getElementById('SumaAsegurada6').value;
            document.getElementById('MontoCartera').value = Number(suma1) + Number(suma2) + Number(suma3) + Number(suma4) + Number(suma5) + Number(suma6);
            calculoPrimaRepartida();
        }

        function calculoSubTotal() {
            var monto = document.getElementById('MontoCartera').value;
            var tasa = document.getElementById('Tasa').value;
            if (document.getElementById('Anual').checked == true) {
                var tasaFinal = (tasa / 1000) / 12;
            } else {
                var tasaFinal = tasa / 1000;
            }
            var sub = Number(monto) * Number(tasaFinal);
            document.getElementById('SubTotal').value = sub;
        }


        function calculoCCF() {
            var comision = document.getElementById('TasaComision').value;
            var total = document.getElementById('PrimaDescontada').value;

            var valorDes = total * (comision / 100);
            document.getElementById('ValorDescuento').value = Number(valorDes);
            var IvaSobreComision = Number(valorDes) * 0.13;

            document.getElementById('IvaSobreComision').value = Number(IvaSobreComision);
            if (document.getElementById('Retencion').hasAttribute('readonly')) {
                var Retencion = 0;
            } else {
                var Retencion = valorDes * 0.01;
                document.getElementById('Retencion').value = Retencion;
            }
            var ValorCCF = Number(valorDes) + Number(IvaSobreComision) - Number(Retencion);
            // alert(ValorCCF);
            document.getElementById('ValorCCFE').value = Number(ValorCCF);
            document.getElementById('ValorCCF').value = Number(ValorCCF);
            var PrimaTotal = document.getElementById('PrimaTotal').value;
            var APagar = Number(PrimaTotal) - Number(ValorCCF);
            document.getElementById('APagar').value = APagar;
        }

        $("#habilitar").click(function() {
            //  $("#btn_guardar").click(function() {
            //  document.getElementById('ImpresionRecibo').removeAttribute('readonly');
            document.getElementById('ImpresionRecibo').value = '';
            document.getElementById('EnvioCartera').type = 'date';
            document.getElementById('EnvioPago').type = 'date';
            document.getElementById('PagoAplicado').type = 'date';
            document.getElementById('SaldoA').type = 'date';
            document.getElementById('ValorDescuento').value = 0;
            document.getElementById('IvaSobreComision').value = 0;
            document.getElementById('Retencion').value = 0;
            document.getElementById('ValorCCFE').value = 0;

        })
        $('#SaldoA').change(function() {
            var hoy = new Date().toLocaleDateString();
            //alert(hoy);
            document.getElementById('ImpresionRecibo').value = hoy;
            document.getElementById('ImpresionRecibo').setAttribute("readonly", true);
        })
        $('#EnvioCartera').change(function() {
            var hoy = new Date();
            // alert(hoy);
            if (document.getElementById('ImpresionRecibo').value <= document.getElementById(
                    'EnvioCartera')) {
                alert('debe seleccionar una fecha mayor o igual a la impresion recibo');
            }

        })
        $('#EnvioPago').change(function() {
            var hoy = new Date();
            // alert(hoy);
            if (document.getElementById('EnvioCartera').value <= document.getElementById('EnvioPago')) {
                alert('debe seleccionar una fecha mayor o igual a la envio de cartera');
            }

        })
        $('#PagoAplicado').change(function() {
            var hoy = new Date();
            // alert(hoy);
            if (document.getElementById('EnvioPago').value <= document.getElementById('PagoAplicado')) {
                alert('debe seleccionar una fecha mayor o igual a la envio de pago');
            }

        })


    });

    function modal_edit(id) {
        document.getElementById('ModalSaldoA').value = "";
        document.getElementById('ModalImpresionRecibo').value = "";
        document.getElementById('ModalComentario').value = "";
        document.getElementById('ModalEnvioCartera').value = "";
        document.getElementById('ModalEnvioPago').value = "";
        document.getElementById('ModalPagoAplicado').value = "";
        document.getElementById('ModalId').value = id;



        $.get("{{ url('polizas/vida/get_pago') }}" + '/' + id, function(data) {
            console.log(data);
            document.getElementById('ModalSaldoA').value = data.SaldoA.substring(0, 10);
            document.getElementById('ModalImpresionRecibo').value = data.ImpresionRecibo.substring(0, 10);
            document.getElementById('ModalComentario').value = data.Comentario;
            if (data.EnvioCartera) {
                document.getElementById('ModalEnvioCartera').value = data.EnvioCartera.substring(0, 10);
            } else {
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.EnvioPago) {
                document.getElementById('ModalEnvioPago').value = data.EnvioPago.substring(0, 10);
            } else {
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            }

            if (data.PagoAplicado) {
                document.getElementById('ModalPagoAplicado').value = data.PagoAplicado.substring(0, 10);
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
                $("#ModalPagoAplicado").prop("readonly", true);
            } else {
                $("#ModalEnvioCartera").prop("readonly", true);
                $("#ModalEnvioPago").prop("readonly", true);
            }



        });
        $('#modal_editar_pago').modal('show');

    }
</script>
@endsection