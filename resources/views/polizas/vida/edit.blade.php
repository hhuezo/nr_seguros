@extends ('welcome')
@section('contenido')
    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>

    <style>
        .table-simulated {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
        }

        .table-header {
            display: contents;
            background-color: #343a40 !important;
            color: rgb(122, 122, 122);
            font-weight: bold;
        }

        .table-header div {
            padding: 10px;
            border-bottom: 2px solid #dee2e6;
            text-align: center;
        }

        .table-row {
            display: contents;
            border-bottom: 1px solid #dee2e6;
        }

        .table-row div {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            text-align: center;
        }

        .table-row:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>




    <style>
        .subtareas-container {
            /* display: none;
                                                                                                                                                        /* Ocultar subtareas por defecto */
        }

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
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="x_title">
                    <h2>Pólizas / Vida / Póliza de vida <small></small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        @if ($vida->Configuracion == 0)
                            <a href="" data-target="#modal-finalizar" data-toggle="modal"
                                class="btn btn-success">Finalizar <br> Configuración</a>
                        @else
                            <a href="" data-target="#modal-finalizar" data-toggle="modal"
                                class="btn btn-primary">Apertura <br> Configuración</a>
                        @endif
                    </ul>
                    <div class="clearfix"></div>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1"
                        id="modal-finalizar">

                        <form method="POST" action="{{ url('finalizar_configuracion_vida') }}">
                            @method('POST')
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <input type="hidden" name="vida" value="{{ $vida->Id }}">
                                        <h4 class="modal-title">{{ $vida->Configuracion == 0 ? 'Finalizar' : 'Aperturar' }}
                                            Configuración</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Confirme si desea {{ $vida->Configuracion == 0 ? 'finalizar' : 'aperturar' }} la
                                            configuración de la poliza</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="{{ $tab == 1 ? 'active' : '' }}"><a href="#tab_content1"
                                id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Datos de Póliza</a>
                        </li>

                        <li role="presentation" class="{{ $tab == 2 ? 'active' : '' }} "><a href="#tab_content2"
                                id="lineas-tab" role="tab" data-toggle="tab" aria-expanded="true">Tasa diferenciada</a>
                        </li>




                    </ul>

                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 1 ? 'active in' : '' }}" id="tab_content1"
                            aria-labelledby="home-tab">
                            <form action="{{ url('polizas/vida/') }}/{{ $vida->Id }}" method="POST">
                                @method('PUT')
                                @csrf


                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Número de Póliza</label>
                                    <input type="text" class="form-control" name="NumeroPoliza"
                                        value="{{ $vida->NumeroPoliza }}" required>
                                </div>



                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Aseguradora</label>
                                    <select name="Aseguradora" id="Aseguradora" class="form-control select2"
                                        style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($aseguradora as $obj)
                                            <option value="{{ $obj->Id }}"
                                                {{ $vida->Aseguradora == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Aseguradora')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Productos</label>
                                    <select name="Productos" id="Productos" class="form-control select2" style="width: 100%"
                                        required>
                                        <option value="" disabled selected>Seleccione...</option>
                                        @foreach ($productos as $obj)
                                            <option value="{{ $obj->Id }}"
                                                {{ $vida->planes && $vida->planes->productos && $vida->planes->productos->Id == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Productos')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Planes</label>
                                    <select name="Planes" id="Planes" class="form-control select2" style="width: 100%"
                                        required>
                                        <option value="" disabled selected>Seleccione...</option>
                                        @foreach ($planes as $obj)
                                            <option value="{{ $obj->Id }}"
                                                {{ $vida->planes && $vida->planes->Id == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Planes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Asegurado</label>
                                    <select name="Asegurado" id="Asegurado" class="form-control select2"
                                        style="width: 100%" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($cliente as $obj)
                                            <option value="{{ $obj->Id }}"
                                                {{ $vida->cliente->Id == $obj->Id ? 'selected' : '' }}>
                                                {{ $obj->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Nit</label>
                                    <input type="text" class="form-control" name="Nit" id="Nit"
                                        value="{{ trim($vida->cliente->Nit) }}" required>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0% !important">
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label>Ejecutivo</label>
                                        <select name="Ejecutivo" class="form-control select2" style="width: 100%"
                                            required>
                                            <option value="">Seleccione...</option>
                                            @foreach ($ejecutivo as $obj)
                                                <option value="{{ $obj->Id }}"
                                                    {{ $vida->ejecutivo->Id == $obj->Id ? 'selected' : '' }}>
                                                    {{ $obj->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label>Tipo de Cobro</label>
                                        <select name="TipoCobro" class="form-control" required>
                                            @foreach ($tipoCobro as $tipo)
                                                <option value="{{ $tipo->Id }}"
                                                    {{ $vida->tipoCobro->Id == $tipo->Id ? 'selected' : '' }}>
                                                    {{ $tipo->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-12" style="padding: 0% !important" id="div-cobro-creditos">

                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label class="control-label" align="right">Tipo de suma</label>
                                        <select name="TipoTarifa" class="form-control"
                                            onchange="showMultitarifa(this.value)" required>
                                            <option value="1" {{ $vida->TipoTarifa == 1 ? 'selected' : '' }}>Suma
                                                uniforme</option>
                                            <option value="2" {{ $vida->TipoTarifa == 2 ? 'selected' : '' }}>
                                                Multicategoria</option>
                                        </select>
                                    </div>


                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6" id="div-sumaAsegurada"
                                        style="display: {{ $vida->TipoTarifa == 1 ? '' : 'none' }}">
                                        <label class="control-label" align="right">Suma asegurada</label>

                                        <input type="number" class="form-control" name="SumaAsegurada" min="0.001"
                                            value="{{ $vida->SumaAsegurada }}" step="any">

                                    </div>



                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6" id="div-multitarifa"
                                        style="display: {{ $vida->TipoTarifa == 2 ? '' : 'none' }}">
                                        <label class="control-label" align="right">Multicategoria</label>
                                        <input class="form-control" name="Multitarifa" type="text" id="Multitarifa"
                                            min="1" step="any" value="{{ $vida->Multitarifa }}"
                                            oninput="formatMultitarifa(this)">
                                        <label id="multitarifa-error" class="text-danger" style="display: none;">Formato
                                            inválido: use cantidades separadas
                                            por coma.</label>
                                    </div>

                                </div>

                                <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                    <label>Edad Máxima de Inscripción</label>
                                    <input type="number" class="form-control" name="EdadMaximaInscripcion"
                                        value="{{ $vida->EdadMaximaInscripcion }}" required>
                                </div>

                                <div class="item form-group col-sm-12 col-md-3 col-lg-3">
                                    <label>Edad Terminación</label>
                                    <input type="number" class="form-control" name="EdadTerminacion"
                                        value="{{ $vida->EdadTerminacion }}" required>
                                </div>

                                <div class="col-sm-6"><br>

                                    <input name="TarifaExcel" type="checkbox"
                                        {{ $vida->TarifaExcel == 1 ? 'checked' : '' }} class="js-switch">
                                    <label class="control-label" align="right">&nbsp;Cobro con tarifa
                                        excel</label>
                                </div>



                                <div class="col-sm-12" style="padding: 0% !important">
                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label>Vigencia Desde</label>
                                        <input type="date" class="form-control" name="VigenciaDesde"
                                            value="{{ $vida->VigenciaDesde }}" required>
                                    </div>



                                    <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                        <label>Vigencia Hasta</label>
                                        <input type="date" class="form-control" name="VigenciaHasta"
                                            value="{{ $vida->VigenciaHasta }}" required>
                                    </div>
                                </div>


                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Tasa Millar Mensual</label>
                                    <input type="number" class="form-control" name="Tasa"
                                        value="{{ $vida->Tasa }}" required>
                                </div>

                                <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                                    <label>Descuento</label>
                                    <input type="number" class="form-control" name="TasaDescuento"
                                        value="{{ $vida->TasaDescuento ?? 0 }}" required>
                                </div>


                                <div class="form-group col-sm-12 col-md-12">
                                    <label>Concepto</label>
                                    <textarea class="form-control" name="Concepto" rows="3">{{ $vida->Concepto }}</textarea>
                                </div>


                                <div class="clearfix my-3"></div>

                                <!-- Botones -->
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-success"
                                        {{ $vida->Configuracion == 1 ? 'disabled' : '' }}>Guardar y Continuar</button>
                                    <a href="{{ url('polizas/vida') }}" class="btn btn-primary">Cancelar</a>
                                </div>
                            </form>

                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 2 ? 'active in' : '' }}" id="tab_content2"
                            aria-labelledby="lineas-tab">


                            <div class="x_title">

                                <ul class="nav navbar-right panel_toolbox">
                                    <a href="{{ url('polizas/vida/tasa_diferenciada') }}/{{ $vida->Id }}"
                                        class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                </ul>
                                <div class="clearfix"></div>

                            </div>

                            <hr>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <table width="100%" class="table table-striped">

                                        <tbody>

                                            @if ($vida->vida_tipos_cartera->count() > 0)
                                                <table class="table table-bordered">
                                                    <thead class="table-dark">
                                                        <tr class="warning-row">
                                                            <th style="width: 40%;">Tipo cartera</th>
                                                            <th style="width: 20%;">Tipo cálculo</th>
                                                            <th style="width: 20%;">Monto máximo individual</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>



                                                        @foreach ($vida->vida_tipos_cartera as $tipo)
                                                            <tr class="tarea warning-row">
                                                                <td>
                                                                    <span class="expand-icon">▼</span>
                                                                    {{ $tipo->catalogo_tipo_cartera?->Nombre ?? '' }}
                                                                </td>
                                                                <td>{{ $tipo->TipoCalculo == 1 ? 'Fecha' : ($tipo->TipoCalculo == 2 ? 'Monto' : 'No aplica') }}
                                                                </td>
                                                                <td class="text-end">
                                                                    ${{ $tipo->MontoMaximoIndividual }}
                                                                </td>
                                                            </tr>

                                                            <tr class="subtareas-container">
                                                                <td colspan="4" style="background-color: #f8fafc;">

                                                                    @if ($tipo->tasa_diferenciada->count() > 0)
                                                                        <br>
                                                                        <div
                                                                            style="padding-left: 20px !important; padding-right: 20px !important;">
                                                                            <table class="table table-sm table-bordered">
                                                                                <thead class="table-light">
                                                                                    <tr class="primary-row">
                                                                                        <!-- <th>Linea credito</th> -->
                                                                                        @if ($tipo->TipoCalculo == 1)
                                                                                            <th>Fecha inicio</th>
                                                                                            <th>Fecha final</th>
                                                                                        @endif

                                                                                        @if ($tipo->TipoCalculo == 2)
                                                                                            <th>Edad inicio</th>
                                                                                            <th>Edad final</th>
                                                                                        @endif
                                                                                        <th>Tasa</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($tipo->tasa_diferenciada as $tasa_diferenciada)
                                                                                        <tr class="primary-row">
                                                                                            <!-- <td>
                                                                                                                                                                                                    {{ $tasa_diferenciada->linea_credito?->Abreviatura ?? '' }}
                                                                                                                                                                                                    -
                                                                                                                                                                                                    {{ $tasa_diferenciada->linea_credito?->Descripcion ?? '' }}
                                                                                                                                                                                                </td> -->
                                                                                            @if ($tipo->TipoCalculo == 1)
                                                                                                <td>
                                                                                                    {{ $tasa_diferenciada->FechaDesde ? date('d/m/Y', strtotime($tasa_diferenciada->FechaDesde)) : 'Sin fecha' }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $tasa_diferenciada->FechaHasta ? date('d/m/Y', strtotime($tasa_diferenciada->FechaHasta)) : 'Sin fecha' }}
                                                                                                </td>
                                                                                            @endif

                                                                                            @if ($tipo->TipoCalculo == 2)
                                                                                                <td>{{ $tasa_diferenciada->EdadDesde }}
                                                                                                    Años</td>
                                                                                                <td>{{ $tasa_diferenciada->EdadHasta }}
                                                                                                    Años</td>
                                                                                            @endif

                                                                                            <td>{{ $tasa_diferenciada->Tasa }}%
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @endif


                                                                    <br>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="alert alert-warning">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close"><span aria-hidden="true">×</span>
                                                    </button>

                                                    <strong>No hay datos</strong>
                                                </div>
                                            @endif

                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>






    @include('sweetalert::alert')


    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-vida");

        });



        $("#Asegurado").change(async function() {
            const clienteId = $(this).val();
            if (!clienteId) return;

            $('#Nit').val('Cargando...');

            try {
                const response = await fetch(`{{ url('get_cliente') }}?Cliente=${clienteId}`);
                if (!response.ok) throw new Error(`Error: ${response.status}`);

                const data = await response.json();
                $('#Nit').val(data.Nit || '');

                // Manejo de Retención
                const $retencion = $('#Retencion');
                if (data.TipoContribuyente == 1) {
                    $retencion.prop("readonly", true).val(0);
                } else {
                    $retencion.prop("readonly", false);
                }
            } catch (error) {
                console.error('Error al obtener cliente:', error);
                $('#Nit').val('');
                toastr.error('Error al cargar datos del cliente', 'Error');
            }
        });


        $("#Aseguradora").change(async function() {
            const aseguradoraId = $(this).val();
            if (!aseguradoraId) return;

            const $productos = $("#Productos").html('<option value="">Cargando...</option>');

            try {
                const response = await fetch(`{{ url('get_producto') }}/${aseguradoraId}`);
                if (!response.ok) throw new Error(`Error: ${response.status}`);

                const data = await response.json();
                const options = data.map(p => `<option value="${p.Id}">${p.Nombre}</option>`).join('');
                $productos.html(`${options}`);
            } catch (error) {
                console.error('Error al cargar productos:', error);
                $productos.html('<option value="">Error al cargar</option>');
                toastr.error('Error al cargar productos', 'Error');
            }
        });


        $("#Productos").change(async function() {
            const productoId = $(this).val();
            const $planes = $("#Planes");

            if (!productoId) {
                $planes.html('<option value="">Seleccione producto</option>');
                return;
            }

            $planes.html('<option value="">Cargando...</option>');

            try {
                const response = await fetch(`{{ url('get_plan') }}/${productoId}`);
                if (!response.ok) throw new Error(`Error: ${response.status}`);

                const data = await response.json();
                const options = data.map(p => `<option value="${p.Id}">${p.Nombre}</option>`).join('');
                $planes.html(`${options}`);
            } catch (error) {
                console.error('Error al cargar planes:', error);
                $planes.html('<option value="">Error al cargar</option>');
                toastr.error('Error al cargar planes', 'Error');
            }
        });



        function showTipoCobro(id) {
            const divUsuarios = document.getElementById('div-cobro-usuarios');
            const divCreditos = document.getElementById('div-cobro-creditos');

            if (id == 1) {
                divUsuarios.style.display = 'block';
                divCreditos.style.display = 'none';
            } else if (id == 2) {
                divUsuarios.style.display = 'none';
                divCreditos.style.display = 'block';
            }
        }

        function showMultitarifa(id) {
            const divSuma = document.getElementById('div-sumaAsegurada');
            const divMulti = document.getElementById('div-multitarifa');

            if (id == 1) {
                divSuma.style.display = 'block';
                divMulti.style.display = 'none';
            } else if (id == 2) {
                divSuma.style.display = 'none';
                divMulti.style.display = 'block';
            }
        }

        function formatMultitarifa(input) {
            // Permitir solo dígitos, puntos y comas
            input.value = input.value.replace(/[^0-9.,]/g, '');
        }
    </script>


@endsection
