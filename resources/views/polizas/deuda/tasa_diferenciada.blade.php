@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Nueva aseguradora <small></small></h2>

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
                <form action="{{ url('catalogo/aseguradoras') }}" method="POST" class="forms-sample">
                    @csrf
                    <div class="x_content">
                        <br />
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label ">Línea de Crédito</label>
                                <select name="TipoCartera" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipoCartera as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $deuda_credito->TipoCartera == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Saldos y Montos</label>
                                <select name="Saldos" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($saldos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $deuda_credito->Saldos == $obj->Id ? 'selected' : '' }}>
                                            {{ $obj->Abreviatura }} -
                                            {{ $obj->Descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px!important;">
                            <div class="col-sm-6">
                                <label class="control-label ">Tasa General</label>
                                <input class="form-control" type="number" step="any" name="Tasa"
                                    value="{{ $deuda_credito->deuda->Tasa }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label ">Monto Máximo</label>
                                <input class="form-control" type="number" step="any" name="MontoMaximoIndividual"
                                    value="{{ $deuda_credito->MontoMaximoIndividual }}">
                            </div>
                        </div>

                        <br>

                    </div>
                    <div class="form-group" align="center">
                        <button class="btn btn-success" type="submit">Guardar</button>
                        <a href="{{ url('catalogo/aseguradoras/') }}"><button class="btn btn-primary"
                                type="button">Cancelar</button></a>
                    </div>
                </form>


            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Tasa diferenciada</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="" class="btn btn-info" data-target="#modal_creditos" data-toggle="modal">Nuevo</a>
                    </ul>

                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Tipo cálculo</th>
                                <th>Fecha inicio</th>
                                <th>Fecha final</th>
                                <th>Edad inicio</th>
                                <th>Edad final</th>
                                <th>Tasa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deuda_credito->tasasDiferenciadas as $registro)
                                <tr>
                                    <td>
                                        {{ $registro->TipoCalculo == 1 ? 'Fecha' : ($registro->TipoCalculo == 2 ? 'Edad' : '') }}
                                    </td>

                                    <td>
                                        {{ !empty($registro->FechaDesde) ? date('d/m/Y', strtotime($registro->FechaDesde)) : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($registro->FechaHasta) ? date('d/m/Y', strtotime($registro->FechaHasta)) : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($registro->EdadDesde) ? $registro->EdadDesde . ' AÑOS' : '' }}
                                    </td>
                                    <td>
                                        {{ !empty($registro->EdadHasta) ? $registro->EdadHasta . ' AÑOS' : '' }}
                                    </td>

                                    <td>{{ $registro->Tasa }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            data-target="#modal-creditos-edit-{{ $registro->Id }}"
                                            data-toggle="modal">Editar</button>
                                        <button class="btn btn-danger btn-sm" data-target="#modal-creditos-delete-{{ $registro->Id }}"
                                            data-toggle="modal">Eliminar</button>
                                    </td>
                                </tr>
                                @include('polizas.deuda.tasa_diferenciada_modal_edit')
                                @include('polizas.deuda.tasa_diferenciada_modal_delete')
                            @endforeach

                        </tbody>
                    </table>
                </div>


            </div>
        </div>

    </div>


    <div class="modal fade" id="modal_creditos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-tipo="1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/deuda/tasa_diferenciada') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Nueva tasa diferenciada</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Tipo de Cartera</label>
                                    <input type="hidden" name="Id" value="{{ $deuda_credito->Id }}"
                                        class="form-control" readonly>
                                    <input type="text" name="TipoCartera"
                                        value="{{ optional($deuda_credito->tipoCarteras)->Nombre ?? '' }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label">Saldos y Montos</label>
                                    <input type="text" name="Saldos"
                                        value="{{ optional($deuda_credito->saldos)->Abreviatura ?? '' }} - {{ trim(optional($deuda_credito->saldos)->Descripcion ?? '') }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label">Tipo cálculo</label>
                                    <select name="TipoCalculo" id="TipoCalculo" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <option value="1" {{ old('TipoCalculo') == '1' ? 'selected' : '' }}>Fecha
                                        </option>
                                        <option value="2" {{ old('TipoCalculo') == '2' ? 'selected' : '' }}>Edad
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group row" id="divFechaDesde" style="display: none">
                                    <label class="control-label">Fecha inicio</label>
                                    <input type="date" name="FechaDesde" class="form-control"
                                        value="{{ old('FechaDesde') }}">
                                </div>

                                <div class="form-group row" id="divFechaHasta" style="display: none">
                                    <label class="control-label">Fecha final</label>
                                    <input type="date" name="FechaHasta" class="form-control"
                                        value="{{ old('FechaHasta') }}">
                                </div>

                                <div class="form-group row" id="divEdadDesde" style="display: none">
                                    <label class="control-label">Edad inicio</label>
                                    <input type="number" step="1" name="EdadDesde" class="form-control"
                                        value="{{ old('EdadDesde') }}">
                                </div>

                                <div class="form-group row" id="divEdadHasta" style="display: none">
                                    <label class="control-label">Edad final</label>
                                    <input type="number" step="1" name="EdadHasta" class="form-control"
                                        value="{{ old('EdadHasta') }}">
                                </div>

                                <div class="form-group row" id="divTasa" style="display: none">
                                    <label class="control-label">Tasa</label>
                                    <input type="number" step="0.01" name="Tasa" class="form-control"
                                        value="{{ old('Tasa') }}">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer" align="center">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectTipoCalculo = document.getElementById("TipoCalculo");

            const divFechaDesde = document.getElementById("divFechaDesde");
            const divFechaHasta = document.getElementById("divFechaHasta");
            const divEdadDesde = document.getElementById("divEdadDesde");
            const divEdadHasta = document.getElementById("divEdadHasta");
            const divTasa = document.getElementById("divTasa");

            function limpiarInputs(div) {
                const inputs = div.querySelectorAll("input");
                inputs.forEach(input => input.value = "");
            }

            function actualizarVisibilidad() {
                const valor = selectTipoCalculo.value;

                // Ocultar y limpiar todos los campos por defecto
                [divFechaDesde, divFechaHasta, divEdadDesde, divEdadHasta, divTasa].forEach(div => {
                    div.style.display = "none";
                    limpiarInputs(div);
                });

                if (valor === "1") {
                    // Mostrar fechas y tasa si se elige "Fecha"
                    divFechaDesde.style.display = "block";
                    divFechaHasta.style.display = "block";
                    divTasa.style.display = "block";
                } else if (valor === "2") {
                    // Mostrar edades y tasa si se elige "Edad"
                    divEdadDesde.style.display = "block";
                    divEdadHasta.style.display = "block";
                    divTasa.style.display = "block";
                }
            }

            // Ejecutar al cargar y al cambiar la selección
            selectTipoCalculo.addEventListener("change", actualizarVisibilidad);
            actualizarVisibilidad();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("[id^='TipoCalculo']").forEach(select => {
                const id = select.id.replace("TipoCalculo", ""); // Extrae el ID dinámico
                const divFechaDesde = document.getElementById(`divFechaDesde${id}`);
                const divFechaHasta = document.getElementById(`divFechaHasta${id}`);
                const divEdadDesde = document.getElementById(`divEdadDesde${id}`);
                const divEdadHasta = document.getElementById(`divEdadHasta${id}`);
                const divTasa = document.getElementById(`divTasa${id}`);

                function actualizarVisibilidad() {
                    const valor = select.value;

                    // Ocultar todos los campos por defecto sin limpiar los valores
                    [divFechaDesde, divFechaHasta, divEdadDesde, divEdadHasta, divTasa].forEach(div => {
                        div.style.display = "none";
                    });

                    if (valor === "1") {
                        divFechaDesde.style.display = "block";
                        divFechaHasta.style.display = "block";
                        divTasa.style.display = "block";
                    } else if (valor === "2") {
                        divEdadDesde.style.display = "block";
                        divEdadHasta.style.display = "block";
                        divTasa.style.display = "block";
                    }
                }

                // Ejecutar al cambiar la selección
                select.addEventListener("change", actualizarVisibilidad);
                // Ejecutar al cargar (para preselección)
                actualizarVisibilidad();
            });
        });
    </script>


@endsection
