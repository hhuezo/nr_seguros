@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="x_title">
                    <h2>Nuevo Producto <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('catalogo/producto') }}" class="btn btn-info fa fa-undo" style="color: white"> Atras</a>
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

                <form method="POST" action="{{ route('producto.store') }}">
                    @csrf
                    <div class="x_content">
                        <br />
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Nombre del Producto</label>
                                <input type="text" name="Nombre" value="{{ old('Nombre') }}" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Aseguradora</label>
                                <select name="Aseguradora" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradoras as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('Aseguradora') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label class="form-label">Ramo</label>
                                <select name="NecesidadProteccion" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($ramos as $obj)
                                        <option value="{{ $obj->Id }}" {{ old('NecesidadProteccion') == $obj->Id ? 'selected' : '' }}>{{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">% Comisión NR (No Declarativas)</label>
                                <div class="input-group">
                                    <input class="form-control text-right" name="PorcentajeComisionNoDeclarativa" type="number" min="0" max="100"
                                        step="0.0001" value="{{ old('PorcentajeComisionNoDeclarativa') }}" placeholder="0.0000">
                                    <span class="input-group-addon">%</span>
                                </div>
                                <small class="text-muted">Tasa de comisión propia del producto.</small>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control" name="Descripcion">{{ old('Descripcion') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        @can('producto create')
                            <button class="btn btn-success" type="submit">Guardar</button>
                        @endcan
                        <a href="{{ url('catalogo/producto') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-catalogo", "li-producto");
        });
    </script>
@endsection
