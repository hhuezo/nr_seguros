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
            <form action="{{ url('catalogo/negocio') }}" method="POST" class="forms-sample">
                @csrf
                <div class="x_content">
                    <br />
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Asegurado</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="text" name="Asegurado" value="{{ old('Asegurado') }}" class="form-control" required autofocus="true">
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
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Fecha venta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="date" name="FechaVenta" value="{{ old('FechaVenta') }}" class="form-control">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tipo Poliza (Ramo)</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="TipoPoliza" class="form-control select2" style="width: 100%">
                                    @foreach ($tipos_poliza as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Inicio vigencia</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="date" name="InicioVigencia" value="{{ old('InicioVigencia') }}" class="form-control">
                            </div>
                        </div>



                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Suma asegurada</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="0.01" name="SumaAsegurada" value="{{ old('SumaAsegurada') }}" class="form-control">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Prima</label>
                            <div class="col-lg-4 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" step="0.01" name="Prima" value="{{ old('Prima') }}" class="form-control">
                            </div>
                            <label class="control-label col-md-2 col-sm-12 col-xs-12">Num Cuotas</label>
                            <div class="col-lg-3 col-md-9 col-sm-12 col-xs-12">
                                <input type="number" name="NumCuotas" value="{{ old('NumCuotas') }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Tipo negocio</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="TipoNegocio" class="form-control" style="width: 100%">
                                    @foreach ($tipos_negocio as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Estado venta</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="EstadoVenta" class="form-control" style="width: 100%">
                                    @foreach ($estados_venta as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12">Ejecutivo</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <select name="Ejecutivo" class="form-control select2" style="width: 100%">
                                    @foreach ($ejecutivos as $obj)
                                    <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-12 col-sm-12 col-xs-12" style="text-align: left;">Observación</label>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="Observacion" rows="3" class="form-control">{{ old('Observacion') }}</textarea>
                            </div>
                        </div>
                    </div>




                </div>

                <div class="form-group" align="center">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a href="{{ url('catalogo/negocio/') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                </div>

            </form>

        </div>

    </div>
</div>
@include('sweetalert::alert')
@endsection