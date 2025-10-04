@extends ('welcome')
@section('contenido')
    <style>
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
        }
    </style>

    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <div class="page-title">
        <div class="title_left">
            <h3>Póliza de vida / {{ $vida->NumeroPoliza }}</h3>
        </div>

        <div class="title_right text-right">

            <button class="btn btn-success" type="button" data-target="#modal-add-tipo-cartera" data-toggle="modal"><i
                    class="fa fa-plus"></i></button>

            <a href="{{url('polizas/vida')}}/{{$vida->Id}}/edit" class="btn btn-primary" type="button"><i
                    class="fa fa-arrow-left"></i></a>

        </div>
    </div>

    <div class="x_panel">

        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
        @endif
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

                    @if ($vida->vida_tipos_cartera->count() > 0)
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr class="warning-row">
                                    <th style="width: 40%;">Tipo cartera</th>
                                    <th style="width: 20%;">Tipo cálculo</th>
                                    {{-- @if ($vida->TarifaExcel != 1)
                                        <th style="width: 20%;">Monto máximo individual</th>
                                    @endif --}}

                                    <th style="width: 20%;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>



                                @foreach ($vida->vida_tipos_cartera as $tipo)
                                    <tr class="tarea warning-row">
                                        <td>
                                            <span class="expand-icon">▼</span>
                                            {{ $tipo->catalogo_tipo_cartera?->Nombre ?? '' }}
                                        </td>
                                        <td>
                                            @if ($tipo->TipoCalculo == 1)
                                                {{ 'Fecha' }}
                                            @elseif ($tipo->TipoCalculo == 2)
                                                {{ 'Monto' }}
                                            @else
                                                {{ 'No aplica' }}
                                            @endif
                                        </td>
                                        {{-- @if ($vida->TarifaExcel != 1)
                                            <td class="text-end">
                                                ${{ $tipo->MontoMaximoIndividual }}
                                            </td>
                                        @endif --}}
                                        <td> <button class="btn btn-primary"
                                                data-target="#modal-tipo-cartera-edit-{{ $tipo->Id }}"
                                                data-toggle="modal"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger" data-target="#modal-delete-tipo-cartera"
                                                data-toggle="modal"
                                                onclick="show_modal_delete_tipo_cartera({{ $tipo->Id }})"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    @include('polizas.vida.vida_tasa_diferenciada.tipo_cartera_modal_edit')


                                    <tr class="subtareas-container">
                                        <td colspan="4" style="background-color: #f8fafc;">

                                            @if ($tipo->tasa_diferenciada->count() > 0)
                                                <br>
                                                <div style="padding-left: 20px !important; padding-right: 20px !important;">
                                                    <table class="table table-sm table-bordered">
                                                        <thead class="table-light">
                                                            <tr class="primary-row">
                                                                <!-- <th>Línea crédito</th> -->
                                                                @if ($tipo->TipoCalculo == 1)
                                                                    <th>Fecha inicio</th>
                                                                    <th>Fecha final</th>
                                                                @endif

                                                                @if ($tipo->TipoCalculo == 2)
                                                                    <th>Monto inicio</th>
                                                                    <th>Monto final</th>
                                                                @endif
                                                                <th>Tasa</th>
                                                                <th>Opciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($tipo->tasa_diferenciada as $tasa_diferenciada)
                                                                <tr class="primary-row">
                                                                    @if ($tipo->TipoCalculo == 1)
                                                                        <td>
                                                                            {{ $tasa_diferenciada->FechaDesde ? date('d/m/Y', strtotime($tasa_diferenciada->FechaDesde)) : 'Sin fecha' }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $tasa_diferenciada->FechaHasta ? date('d/m/Y', strtotime($tasa_diferenciada->FechaHasta)) : 'Sin fecha' }}
                                                                        </td>
                                                                    @endif

                                                                    @if ($tipo->TipoCalculo == 2)
                                                                        <td>${{ number_format($tasa_diferenciada->MontoDesde ?? 0, 2) }}
                                                                        </td>
                                                                        <td>${{ number_format($tasa_diferenciada->MontoHasta ?? 0, 2) }}
                                                                        </td>
                                                                    @endif

                                                                    <td>{{ $tasa_diferenciada->Tasa }}%</td>
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
                                                                @include('polizas.vida.vida_tasa_diferenciada.modal_tasa_diferenciada_edit')
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif

                                            <div class="text-center">
                                                @if ($tipo->VidaTipoCartera > 1 && $tipo->TipoCalculo > 0)
                                                    <button class="btn btn-primary" type="button"
                                                        data-target="#modal-tasa-diferenciada" data-toggle="modal"
                                                        onclick="show_modal_tasa_diferenciada({{ $tipo->Id }},{{ $tipo->TipoCalculo }},{{ $tipo->TipoCalculo }})"><i
                                                            class="fa fa-plus"></i></button>
                                                @endif


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
                <form action="{{ url('polizas/vida/agregar_tipo_cartera') }}/{{ $vida->Id }}" method="POST">
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
                                        <option value="1">Fecha </option>
                                        <option value="2">Monto </option>
                                    </select>
                                </div>

                                {{-- <div class="form-group row">
                                    <label class="control-label">Monto maximo individual</label>
                                    <input type="number" step="any" min="0.00" class="form-control"
                                        name="MontoMaximoIndividual" required>

                                </div> --}}



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
                <form action="{{ url('polizas/vida/delete_tipo_cartera') }}" method="POST">
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
                <form action="{{ route('tasa_diferenciada_vida.store') }}" method="POST">
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
                                <input type="hidden" name="PolizaVidaTipoCartera" class="form-control">
                                <input type="hidden" id="tipoCalculoIngreso" name="TipoCalculoIngreso"
                                    class="form-control">

                                <div class="form-group row" id="divFechaDesde" style="display: none">
                                    <label class="control-label">Fecha inicio</label>
                                    <input type="date" name="FechaDesde" class="form-control">
                                </div>

                                <div class="form-group row" id="divFechaHasta" style="display: none">
                                    <label class="control-label">Fecha final</label>
                                    <input type="date" name="FechaHasta" class="form-control">
                                </div>

                                <div class="form-group row" id="divMontoDesde" style="display: none">
                                    <label class="control-label">Monto inicio</label>
                                    <input type="number" step="1" name="MontoDesde" class="form-control">
                                </div>

                                <div class="form-group row" id="divMontoHasta" style="display: none">
                                    <label class="control-label">Monto final</label>
                                    <input type="number" step="1" name="MontoHasta" class="form-control">
                                </div>

                                <div class="form-group row">
                                    <label class="control-label">Tasa</label>
                                    <input type="number" name="Tasa" step="any" value="{{ $vida->Tasa }}"
                                        class="form-control" required>
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


    <div class="modal fade" id="modal-delete-tasa-diferenciada" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('polizas/vida/delete_tasa_diferenciada') }}" method="POST">
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
            $('input[name="MontoDesde"]').val('');
            $('input[name="MontoHasta"]').val('');

            // Ocultar todos los divs de fecha y edad
            $('#divFechaDesde, #divFechaHasta, #divMontoDesde, #divMontoHasta').hide();

            // Mostrar los campos según el tipo
            if (tipo == 1) {
                $('#divFechaDesde, #divFechaHasta').show();
            } else if (tipo == 2) {
                $('#divMontoDesde, #divMontoHasta').show();
            }

            document.querySelector('input[name="PolizaVidaTipoCartera"]').value = id;
            $('#tipoCalculoIngreso').val(tipoCalculo);

        }

        function show_modal_tasa_diferenciada_edit(tipo) {

            // Ocultar todos los divs de fecha y edad
            $('#divFechaDesdeEdit, #divFechaHastaEdit, #divMontoDesdeEdit, #divMontoHastaEdit').hide();

            // Mostrar los campos según el tipo
            if (tipo == 1) {
                $('#divFechaDesdeEdit, #divFechaHastaEdit').show();
            } else if (tipo == 2) {
                $('#divMontoDesdeEdit, #divMontoHastaEdit').show();
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
