@extends ('welcome')
@section('contenido')
    <style>
        /* .subtareas-container {
                                                        display: none;
                                                    } */

        .expand-icon {
            cursor: pointer;
            margin-right: 8px;
        }

        .warning-row {
            border-left: 5px solid #ffc107;
            /* Naranja (warning) */
        }

        .primary-row {
            border-left: 5px solid #007bff;
            /* Azul (primary) */
        }
    </style>

    <div class="page-title">
        <div class="title_left">
            <h3>Póliza de deuda / {{ $deuda->NumeroPoliza }}</h3>
        </div>

        <div class="title_right text-right">

            <button class="btn btn-primary" type="button" data-target="#modal-add-tipo-cartera" data-toggle="modal"><i
                    class="fa fa-plus"></i></button>

        </div>
    </div>

    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_content">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($deuda->deuda_tipos_cartera->count() > 0)
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr class="warning-row">
                                    <th style="width: 40%;">Tipo cartera</th>
                                    <th style="width: 20%;">Tipo cálculo</th>
                                    <th style="width: 20%;">Monto máximo individual</th>
                                    <th style="width: 20%;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($deuda->deuda_tipos_cartera as $tipo)
                                    <tr class="tarea warning-row">
                                        <td>
                                            <span class="expand-icon">▼</span> {{ $tipo->tipo_cartera?->Nombre ?? '' }}
                                        </td>
                                        <td>
                                            @if ($tipo->TipoCalculo == 1)
                                                {{ 'Fecha' }}
                                            @elseif ($tipo->TipoCalculo == 2)
                                                {{ 'Edad' }}
                                            @else
                                                {{ 'No aplica' }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            ${{ $tipo->MontoMaximoIndividual }}
                                        </td>
                                        <td> <button class="btn btn-primary"
                                                data-target="#modal-tipo-cartera-edit-{{ $tipo->Id }}"
                                                data-toggle="modal"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger" data-target="#modal-delete-tipo-cartera"
                                                data-toggle="modal"
                                                onclick="show_modal_delete_tipo_cartera({{ $tipo->Id }})"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    @include('polizas.deuda.deuda_tasa_diferenciada.tipo_cartera_modal_edit')


                                    <tr class="subtareas-container">
                                        <td colspan="4" style="background-color: #f8fafc;">

                                            @if ($tipo->tasa_diferenciada->count() > 0)
                                                <br>
                                                <div style="padding-left: 20px !important; padding-right: 20px !important;">
                                                    <table class="table table-sm table-bordered">
                                                        <thead class="table-light">
                                                            <tr class="primary-row">
                                                                <th>Línea crédito</th>
                                                                @if ($tipo->TipoCalculo == 1)
                                                                    <th>Fecha inicio</th>
                                                                    <th>Fecha final</th>
                                                                @endif

                                                                @if ($tipo->TipoCalculo == 2)
                                                                    <th>Edad inicio</th>
                                                                    <th>Edad final</th>
                                                                @endif
                                                                   @if ($deuda->TarifaExcel != 1)
                                                                    <th>Tasa</th>
                                                                @endif
                                                                <th>Opciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($tipo->tasa_diferenciada as $tasa_diferenciada)
                                                                <tr class="primary-row">
                                                                    <td>
                                                                        {{ $tasa_diferenciada->linea_credito?->Abreviatura ?? '' }}
                                                                        -
                                                                        {{ $tasa_diferenciada->linea_credito?->Descripcion ?? '' }}
                                                                    </td>
                                                                    @if ($tipo->TipoCalculo == 1)
                                                                        <td>
                                                                            {{ $tasa_diferenciada->FechaDesde ? date('d/m/Y', strtotime($tasa_diferenciada->FechaDesde)) : 'Sin fecha' }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $tasa_diferenciada->FechaHasta ? date('d/m/Y', strtotime($tasa_diferenciada->FechaHasta)) : 'Sin fecha' }}
                                                                        </td>
                                                                    @endif

                                                                    @if ($tipo->TipoCalculo == 2)
                                                                        <td>{{ $tasa_diferenciada->EdadDesde }} Años</td>
                                                                        <td>{{ $tasa_diferenciada->EdadHasta }} Años</td>
                                                                    @endif

                                                                    @if ($deuda->TarifaExcel != 1)
                                                                        <td>{{ $tasa_diferenciada->Tasa }}%</td>
                                                                    @endif
                                                                    <td><button class="btn btn-primary"
                                                                            onclick="show_modal_tasa_diferenciada_edit({{ $tipo->TipoCalculo }})"
                                                                            data-target="#modal-tasa-diferenciada_edit-{{ $tasa_diferenciada->Id }}"
                                                                            data-toggle="modal">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger"
                                                                            data-target="#modal-delete-tasa-diferenciada"
                                                                            data-toggle="modal"
                                                                            onclick="show_modal_delete_tasa_diferenciada({{ $tasa_diferenciada->Id }})"><i
                                                                                class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                                @include('polizas.deuda.deuda_tasa_diferenciada.modal_tasa_diferenciada_edit')
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif

                                            <div class="text-center">
                                                <button class="btn btn-primary" type="button"
                                                    data-target="#modal-tasa-diferenciada" data-toggle="modal"
                                                    onclick="show_modal_tasa_diferenciada({{ $tipo->Id }},{{ $tipo->TipoCalculo }},{{ $tipo->TipoCalculo }})"><i
                                                        class="fa fa-plus"></i></button>
                                            </div>
                                            <br>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>

                            <strong>No hay datos</strong>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modal-add-tipo-cartera" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/deuda/agregar_tipo_cartera') }}/{{ $deuda->Id }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar tipo cartera</h5>
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
                                    <select class="form-control" name="TipoCartera">
                                        @foreach ($tiposCartera as $tipo)
                                            <option value="{{ $tipo->Id }}">{{ $tipo->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label">Tipo cálculo</label>
                                    <select name="TipoCalculo" class="form-control" required>
                                        <option value="0">No aplica</option>
                                        <option value="1">Fecha
                                        </option>
                                        <option value="2">Edad
                                        </option>
                                    </select>
                                </div>


                                <div class="form-group row">
                                    <label class="control-label">Monto maximo individual</label>
                                    <input type="number" step="any" min="0.00" class="form-control"
                                        name="MontoMaximoIndividual" required>

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


    <div class="modal fade" id="modal-delete-tipo-cartera" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/deuda/delete_tipo_cartera') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Eliminar tipo cartera</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <input type="hidden" name="TipoCarteraId" id="TipoCarteraId">
                            <h5>¿Desea eliminar el registro?</h5>
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



    <div class="modal fade" id="modal-tasa-diferenciada" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('tasa_diferenciada.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar tasa diferenciada</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="PolizaDuedaTipoCartera" class="form-control">
                                <input type="hidden" id="tipoCalculoIngreso" name="TipoCalculoIngreso"
                                    class="form-control">
                                <div class="form-group row">
                                    <label class="control-label">Línea crédito</label>
                                    <select class="form-control" name="LineaCredito">
                                        @foreach ($lineas_credito as $linea)
                                            <option value="{{ $linea->Id }}">
                                                {{ $linea->Abreviatura }} - {{ $linea->Descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group row" id="divFechaDesde" style="display: none">
                                    <label class="control-label">Fecha inicio</label>
                                    <input type="date" name="FechaDesde" class="form-control">
                                </div>

                                <div class="form-group row" id="divFechaHasta" style="display: none">
                                    <label class="control-label">Fecha final</label>
                                    <input type="date" name="FechaHasta" class="form-control">
                                </div>

                                <div class="form-group row" id="divEdadDesde" style="display: none">
                                    <label class="control-label">Edad inicio</label>
                                    <input type="number" step="1" name="EdadDesde" class="form-control">
                                </div>

                                <div class="form-group row" id="divEdadHasta" style="display: none">
                                    <label class="control-label">Edad final</label>
                                    <input type="number" step="1" name="EdadHasta" class="form-control">
                                </div>
                                @if ($deuda->TarifaExcel != 1)
                                    <div class="form-group row">
                                        <label class="control-label">Tasa</label>
                                        <input type="number" name="Tasa" step="any" value="{{ $deuda->Tasa }}"
                                            class="form-control" required>
                                    </div>
                                @endif



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


    <div class="modal fade" id="modal-delete-tasa-diferenciada" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/deuda/delete_tasa_diferenciada') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <h5 class="modal-title" id="exampleModalLabel">Eliminar tasa diferenciada</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <input type="hidden" name="TasaDiferenciadaId" id="TasaDiferenciadaId">
                            <h5>¿Desea eliminar el registro?</h5>
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
        $(document).ready(function() {
            $(".expand-icon").click(function() {
                let row = $(this).closest("tr");
                let subtable = row.next(".subtareas-container");

                // Alternar icono
                $(this).text($(this).text() === "►" ? "▼" : "►");

                // Mostrar u ocultar tabla de subtareas
                subtable.toggle();
            });
        });

        function show_modal_tasa_diferenciada(id, tipo, tipoCalculo) {

            // limpiando los inputs
            $('input[name="FechaDesde"]').val('');
            $('input[name="FechaHasta"]').val('');
            $('input[name="EdadDesde"]').val('');
            $('input[name="EdadHasta"]').val('');

            // Ocultar todos los divs de fecha y edad
            $('#divFechaDesde, #divFechaHasta, #divEdadDesde, #divEdadHasta').hide();

            // Mostrar los campos según el tipo
            if (tipo == 1) {
                $('#divFechaDesde, #divFechaHasta').show();
            } else if (tipo == 2) {
                $('#divEdadDesde, #divEdadHasta').show();
            }

            document.querySelector('input[name="PolizaDuedaTipoCartera"]').value = id;
            $('#tipoCalculoIngreso').val(tipoCalculo);

        }

        function show_modal_tasa_diferenciada_edit(tipo) {

            // Ocultar todos los divs de fecha y edad
            $('#divFechaDesdeEdit, #divFechaHastaEdit, #divEdadDesdeEdit, #divEdadHastaEdit').hide();

            // Mostrar los campos según el tipo
            if (tipo == 1) {
                $('#divFechaDesdeEdit, #divFechaHastaEdit').show();
            } else if (tipo == 2) {
                $('#divEdadDesdeEdit, #divEdadHastaEdit').show();
            }
        }

        function show_modal_delete_tasa_diferenciada(id) {
            document.getElementById('TasaDiferenciadaId').value = id;
        }

        function show_modal_delete_tipo_cartera(id) {
            document.getElementById('TipoCarteraId').value = id;
        }
    </script>
@endsection
