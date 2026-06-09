@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="x_title">
                    <h2>Nuevo Ramo <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('catalogo/necesidad_proteccion') }}" class="btn btn-info fa fa-undo" style="color: white"> Atras</a>
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

                <form method="POST" action="{{ route('necesidad_proteccion.store') }}">
                    @csrf
                    <div class="x_content">
                        <br />
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Nombre</label>
                                <input class="form-control" name="Nombre" type="text" value="{{ old('Nombre') }}" required
                                    oninput="let s=this.selectionStart,e=this.selectionEnd;this.value=this.value.toUpperCase();this.setSelectionRange(s,e)">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Agrupador de Ramo</label>
                                <select name="AgrupadorRamo" class="form-control select2" style="width: 100%">
                                    <option value="">Seleccione</option>
                                    @foreach ($agrupadores_ramo as $agrupador)
                                        <option value="{{ $agrupador->Id }}" {{ old('AgrupadorRamo') == $agrupador->Id ? 'selected' : '' }}>
                                            {{ $agrupador->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label class="control-label">Modulo</label>
                                <select name="TipoPoliza" class="form-control select2" style="width: 100%">
                                    <option value="">Seleccione</option>
                                    @foreach ($tipos_poliza as $tipo)
                                        <option value="{{ $tipo->Id }}" {{ old('TipoPoliza') == $tipo->Id ? 'selected' : '' }}>
                                            {{ $tipo->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">% Comisión NR no declarativas</label>
                                <input class="form-control" name="PorcentajeComisionNoDeclarativa" type="number" min="0" max="100"
                                    step="0.0001" value="{{ old('PorcentajeComisionNoDeclarativa') }}" placeholder="Ej. 12.50">
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label class="control-label">Comisión de bomberos</label>
                                <div>
                                    <input type="hidden" name="ComisionBomberos" value="0">
                                    <label class="switch">
                                        <input type="checkbox" name="ComisionBomberos" value="1" class="js-bomberos-switch"
                                            {{ (int) old('ComisionBomberos', 0) === 1 ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                    <span style="margin-left:8px;">Si / No</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">% Bomberos</label>
                                <input class="form-control js-bomberos-input" name="PorcentajeBomberos" type="number" min="0" max="100"
                                    step="0.0001" value="{{ old('PorcentajeBomberos') }}" placeholder="Ej. 5.00">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" align="center">
                        @can('ramo create')
                            <button class="btn btn-success" type="submit">Guardar y continuar</button>
                        @endcan
                        <a href="{{ url('catalogo/necesidad_proteccion') }}"><button class="btn btn-primary" type="button">Cancelar</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            displayOption("ul-catalogo", "li-necesidad_proteccion");

            function toggleComisionInput() {
                var isBomberos = $('.js-bomberos-switch').is(':checked');
                var $input = $('.js-bomberos-input');

                if (isBomberos) {
                    $input.prop('disabled', false);
                } else {
                    $input.val('');
                    $input.prop('disabled', true);
                }
            }

            toggleComisionInput();
            $(document).on('change', '.js-bomberos-switch', toggleComisionInput);
        });
    </script>
@endsection
