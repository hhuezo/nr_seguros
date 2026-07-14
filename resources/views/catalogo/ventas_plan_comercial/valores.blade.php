@extends ('welcome')
@section('contenido')
    @can('ventas-plan-comercial edit')
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

        <style>
            .tabla-especificaciones {
                font-size: 12px;
            }

            .tabla-especificaciones th,
            .tabla-especificaciones td {
                vertical-align: top;
            }

            .tabla-especificaciones textarea {
                min-height: 54px;
                resize: vertical;
                font-size: 12px;
            }
        </style>

        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <h3>Especificaciones del plan comercial</h3>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12" align="right">
                    <a href="{{ url('catalogo/ventas_plan_comercial') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="alert alert-info">
                Complete el texto que aplicara en cada concepto de la plantilla del ramo.
            </div>

            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Ramo</label>
                    <input type="text" class="form-control" value="{{ $planComercial->ramo->Nombre ?? '' }}" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label>Aseguradora</label>
                    <input type="text" class="form-control" value="{{ $planComercial->aseguradora->Nombre ?? '' }}" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label>Producto</label>
                    <input type="text" class="form-control" value="{{ $planComercial->producto->Nombre ?? '' }}" readonly>
                </div>
                <div class="col-md-3 form-group">
                    <label>Plan comercial</label>
                    <input type="text" class="form-control" value="{{ $planComercial->NombreComercial }}" readonly>
                </div>
            </div>

            @if ($campos->count() == 0)
                <div class="alert alert-warning">
                    Este ramo no tiene conceptos configurados. Primero agregue conceptos en la plantilla comparativa del ramo.
                </div>
            @else
                <form method="POST" action="{{ url('catalogo/ventas_plan_comercial/' . $planComercial->Id . '/valores') }}">
                    @csrf

                    <div style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-striped table-bordered tabla-especificaciones">
                            <thead>
                                <tr>
                                    <th width="8%">Orden</th>
                                    <th width="32%">Concepto</th>
                                    <th width="60%">Especificacion del plan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($campos as $campo)
                                    <tr>
                                        <td class="text-center">{{ $campo->Orden }}</td>
                                        <td><strong>{{ $campo->Etiqueta }}</strong></td>
                                        <td>
                                            <textarea name="Valores[{{ $campo->Id }}]" class="form-control campo-mayuscula">{{ $valores[$campo->Id]->ValorTexto ?? '' }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right" style="margin-top: 15px;">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-floppy-o"></i> Guardar especificaciones
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <script>
            function normalizarTextoVentas(input) {
                if (!input || input.readOnly) {
                    return;
                }

                var start = input.selectionStart;
                var end = input.selectionEnd;
                input.value = input.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
                input.setSelectionRange(start, end);
            }

            $(document).on('input', '.campo-mayuscula', function () {
                normalizarTextoVentas(this);
            });
        </script>
    @endcan
@endsection
