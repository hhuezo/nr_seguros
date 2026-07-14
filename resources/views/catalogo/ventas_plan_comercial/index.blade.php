@extends ('welcome')
@section('contenido')
    @can('ventas-plan-comercial read')
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

        <style>
            #tabla-planes-comerciales,
            #tabla-planes-comerciales_wrapper {
                font-size: 12px;
            }

            #tabla-planes-comerciales td,
            #tabla-planes-comerciales th {
                vertical-align: middle;
            }

            .acciones-plan-comercial {
                white-space: nowrap;
                min-width: 126px;
            }

            .acciones-plan-comercial .btn {
                width: 34px;
                height: 30px;
                padding: 5px 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        </style>

        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <h3>Planes comerciales de ventas</h3>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12" align="right">
                    @can('ventas-plan-comercial create')
                        <button class="btn btn-info" data-target="#modal-create" data-toggle="modal">
                            <i class="fa fa-plus"></i> Nuevo
                        </button>
                    @endcan
                </div>
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

            <div class="alert alert-info">
                Estos planes comerciales toman la plantilla del ramo y permiten llenar las especificaciones que luego se usaran en comparativos.
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table id="tabla-planes-comerciales" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="18%">Ramo</th>
                                <th width="16%">Aseguradora</th>
                                <th width="18%">Producto</th>
                                <th width="16%">Plan tecnico</th>
                                <th width="17%">Nombre comercial</th>
                                <th width="8%">Especificaciones</th>
                                <th width="10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($planesComerciales as $obj)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $obj->ramo->Nombre ?? 'N/A' }}</td>
                                    <td>{{ $obj->aseguradora->Nombre ?? 'N/A' }}</td>
                                    <td>{{ $obj->producto->Nombre ?? 'N/A' }}</td>
                                    <td>{{ $obj->plan->Nombre ?? 'N/A' }}</td>
                                    <td><strong>{{ $obj->NombreComercial }}</strong></td>
                                    <td class="text-center">
                                        <span class="label label-info">
                                            {{ $obj->valores->filter(function ($valor) { return trim((string) $valor->ValorTexto) !== ''; })->count() }} llenas
                                        </span>
                                    </td>
                                    <td align="center" class="acciones-plan-comercial">
                                        <div class="btn-group" role="group">
                                            @can('ventas-plan-comercial edit')
                                                <a href="{{ url('catalogo/ventas_plan_comercial/' . $obj->Id . '/valores') }}" class="btn btn-success btn-sm" title="Llenar especificaciones">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                                <button class="btn btn-primary btn-sm" data-target="#modal-edit-{{ $obj->Id }}" data-toggle="modal" title="Editar plan comercial">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            @endcan
                                            @can('ventas-plan-comercial delete')
                                                <button class="btn btn-danger btn-sm" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal" title="Desactivar plan comercial">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modal-edit-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('ventas_plan_comercial.update', $obj->Id) }}">
                                                @method('PUT')
                                                @csrf
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Editar plan comercial</h4>
                                                </div>
                                                <div class="modal-body">
                                                    @include('catalogo.ventas_plan_comercial.partials.form', ['planComercial' => $obj])
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-delete-{{ $obj->Id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('ventas_plan_comercial.destroy', $obj->Id) }}">
                                                @method('delete')
                                                @csrf
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Eliminar registro</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirme si desea desactivar el plan comercial <strong>{{ $obj->NombreComercial }}</strong>.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ url('catalogo/ventas_plan_comercial') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Nuevo plan comercial</h4>
                        </div>
                        <div class="modal-body">
                            @include('catalogo.ventas_plan_comercial.partials.form', ['planComercial' => null])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var productosComerciales = @json($productosComerciales);
            var planesTecnicos = @json($planesTecnicos);

            function normalizarTextoVentas(input) {
                if (!input || input.readOnly) {
                    return;
                }

                var start = input.selectionStart;
                var end = input.selectionEnd;
                input.value = input.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
                input.setSelectionRange(start, end);
            }

            function filtrarProductos($scope) {
                var aseguradora = $scope.find('.select-aseguradora').val();
                var ramo = $scope.find('.select-ramo').val();
                var $producto = $scope.find('.select-producto');
                var seleccionado = $producto.data('selected') || $producto.val();

                $producto.empty().append('<option value="" selected disabled>Seleccione...</option>');

                productosComerciales.forEach(function (producto) {
                    if (String(producto.Aseguradora) === String(aseguradora) && String(producto.NecesidadProteccion) === String(ramo)) {
                        var selected = String(seleccionado) === String(producto.Id) ? 'selected' : '';
                        $producto.append('<option value="' + producto.Id + '" ' + selected + '>' + producto.Nombre + '</option>');
                    }
                });

                $producto.trigger('change.select2');
                filtrarPlanes($scope);
            }

            function filtrarPlanes($scope) {
                var producto = $scope.find('.select-producto').val();
                var $plan = $scope.find('.select-plan');
                var seleccionado = $plan.data('selected') || $plan.val();

                $plan.empty().append('<option value="" selected disabled>Seleccione...</option>');

                planesTecnicos.forEach(function (plan) {
                    if (String(plan.Producto) === String(producto)) {
                        var selected = String(seleccionado) === String(plan.Id) ? 'selected' : '';
                        $plan.append('<option value="' + plan.Id + '" ' + selected + '>' + plan.Nombre + '</option>');
                    }
                });

                $plan.trigger('change.select2');
            }

            function inicializarSelect2Modal($modal) {
                $modal.find('select.select2').each(function () {
                    var $select = $(this);

                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('destroy');
                    }

                    $select.select2({
                        width: '100%',
                        dropdownParent: $modal
                    });
                });
            }

            $(document).on('input', '.campo-mayuscula', function () {
                normalizarTextoVentas(this);
            });

            $(document).on('change', '.select-aseguradora, .select-ramo', function () {
                var $scope = $(this).closest('.form-plan-comercial');
                $scope.find('.select-producto').removeData('selected');
                $scope.find('.select-plan').removeData('selected');
                filtrarProductos($scope);
            });

            $(document).on('change', '.select-producto', function () {
                var $scope = $(this).closest('.form-plan-comercial');
                $scope.find('.select-plan').removeData('selected');
                filtrarPlanes($scope);
            });

            $(document).on('shown.bs.modal', '.modal', function () {
                inicializarSelect2Modal($(this));
            });

            $(function () {
                $('.form-plan-comercial').each(function () {
                    filtrarProductos($(this));
                });

                if (!$.fn.DataTable.isDataTable('#tabla-planes-comerciales')) {
                    $('#tabla-planes-comerciales').DataTable({
                        scrollY: 450,
                        scrollCollapse: true,
                        pageLength: 25,
                        order: [[1, 'asc'], [5, 'asc']],
                    });
                }
            });
        </script>
    @endcan
@endsection
