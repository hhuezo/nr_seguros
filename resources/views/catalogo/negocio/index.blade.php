@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Listado de negocios </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('negocio create')
                <a href="{{ url('catalogo/negocio/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-2" >
                        <label for="estadoNegocio" class="form-label">Estado Negocio</label>
                        <select name="estadoNegocio" id="estadoNegocio" class="form-control" style="margin-bottom: 12px!important;"
                            onchange="actualizarSumas();">
                        </select>
                    </div>
                    <div class="col-md-2" >
                        <label for="ejecutivo" class="form-label">Vendedor</label>
                        <select name="ejecutivo" id="ejecutivo" class="form-control" style="margin-bottom: 12px!important;"
                            onchange="actualizarSumas();">
                        </select>
                    </div>
                <table id="datatableNegocio" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>Id Cotización</th>
                            <th>Cliente</th>
                            <th>Correo</th>
                            <th>Producto</th>
                            <th>Plan</th>
                            <th>Estado Negocio</th>
                            <th>Suma Asegurada</th>
                            <th>Prima Neta Anual</th>
                            <th>Vendedor</th>
                            <th>Fecha Cotización</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($negocios as $obj)
                            <tr>
                                <td>{{$obj->Id}}</td>
                                    <td>{{ $obj->clientes->Nombre ?? 'N/A' }}</td>
                                <td>{{ $obj->clientes->CorreoPrincipal ?? 'N/A' }}</td>
                                @php
                                  $cotizacionAceptada= $obj->cotizaciones->first(function ($item) {
                                        return $item->Aceptado == 1;
                                    });
                                @endphp
                               <td>{{$cotizacionAceptada->planes->productos->Nombre ?? 'N/A'}}</td>
                               <td>{{$cotizacionAceptada->planes->Nombre ?? 'N/A'}}</td>
                               <td>{{ $obj->estadoVenta->Nombre }}</td>
                               <td>${{number_format($cotizacionAceptada->SumaAsegurada ?? 0, 2)}}</td>
                               <td>${{number_format($cotizacionAceptada->PrimaNetaAnual ?? 0, 2)}}</td>
                               <td>{{ $obj->ejecutivos->Nombre }}</td>
                                @if ($obj->FechaVenta)
                                    <td>{{ date('d/m/Y', strtotime($obj->FechaVenta)) }}</td>
                                @else
                                    <td></td>
                                @endif
                                <td align="center">

                                    @can('negocio edit')
                                        <a href="{{ url('catalogo/negocio') }}/{{ $obj->Id }}/edit"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                    @endcan
                                    @can('negocio delete')
                                        &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @include('catalogo.negocio.modal')
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:right">Tota Por Pagina:</th>
                            <th></th>
                            <th></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-4">
                        <label id="totalPrimaAceptada">Total Prima Aceptada</label>
                    </div>
                    <div class="col-md-4">
                        <label id="totalPrimaPendiente">Total Prima Pendiente</label>
                    </div>
                    <div class="col-md-4">
                        <label id="totalPrimaAnulada">Total Prima Anulada</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label id="totalSumaAseguradaAceptada">Total Suma Asegurada Aceptada</label>
                    </div>
                    <div class="col-md-4">
                        <label id="totalSumaAseguradaPendiente">Total Suma Asegurada Pendiente</label>
                    </div>
                    <div class="col-md-4">
                        <label id="totalSumaAseguradaAnulada">Total Suma Asegurada Anulada</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
    <!-- Agrega las hojas de estilo y los scripts de DataTables y DataTables Select -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css">

    <script>
        $(document).ready(function() {
            let opcionesFormato = {
                style: 'decimal',
                useGrouping: true,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                };

            let formatter = new Intl.NumberFormat('es-SV', opcionesFormato);

            let miTabla = $('#datatableNegocio').DataTable({
                /*select: {
                    style: 'single' // Puedes cambiar 'single' por 'multi' si deseas selección múltiple
                }*/
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(6, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(6).footer()).html(
                        '$' + formatter.format(pageTotal) /*+ ' ( $' +  formatter.format(total) + ' Total de cotizaciones)'*/
                    );

                    total = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(7, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(7).footer()).html(
                        '$' + formatter.format(pageTotal) /*+ ' ( $' + ormatter.format(total) + ' Total de cotizaciones)'*/
                    );
                }
            });

            // Agregar filtro a una columna específica (en este caso, la quinta columna)
            miTabla.columns(5).every(function() {
                let column = this;
                let select = $("#estadoNegocio").on('change', function() {
                        let val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                    select.append('<option value="">Todos</option>');
                column.data().unique().sort().each(function(d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });

         /*   miTabla.columns(5).every(function() {
                let column = this;
                let select = $('<select><option value="">Todos</option></select>')
                .appendTo($(column.footer()).empty())
                    .on('change', function() {
                        let val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                column.data().unique().sort().each(function(d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            }); */


            miTabla.columns(8).every(function() {
                let column = this;
                let select = $("#ejecutivo").on('change', function() {
                        let val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                    select.append('<option value="">Todos</option>');
                column.data().unique().sort().each(function(d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });

          /*  miTabla.columns(8).every(function() {
                let column = this;
                let select = $('<select><option value="">Todos</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function() {
                        let val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                column.data().unique().sort().each(function(d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });*/


                function calcularSumaPorEstado(estado) {
                    let suma = [0,0];

                    let intVal = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };

                    miTabla.rows().data().each(function(value, index) {
                        let estadoFila = miTabla.cell(index, 5, { search: 'applied' }).data(); // Columna 5 para los estados
                        let sumaAsegurada = miTabla.cell(index, 6, { search: 'applied' }).data(); // Columna 6 para los números
                        let PrimaNetaAnual = miTabla.cell(index, 7, { search: 'applied' }).data(); // Columna 7 para los números
                        if (estadoFila === estado) {
                            suma[0] += intVal(sumaAsegurada);
                            suma[1] += intVal(PrimaNetaAnual);
                        }
                    });

                    return suma;
                }

                function actualizarSumas() {
                    let sumaAceptado = calcularSumaPorEstado('ACEPTADA');
                    let sumaAnulado = calcularSumaPorEstado('ANULADA');
                    let sumaPendiente = calcularSumaPorEstado('PENDIENTE');

                /*console.log('Suma Aceptado $' + sumaAceptado[0].toLocaleString());
                console.log('Suma Anulado $' + sumaAnulado[0].toLocaleString());
                console.log('Suma Pendiente $' + sumaPendiente[0].toLocaleString());
                console.log('Prima Aceptado $' + sumaAceptado[1].toLocaleString());
                console.log('Prima Anulado $' + sumaAnulado[1].toLocaleString());
                console.log('Prima Pendiente $' + sumaPendiente[1].toLocaleString());*/
                $("#totalPrimaAceptada").text("Total Prima Aceptada: $"+  formatter.format(sumaAceptado[0]));
                $("#totalPrimaPendiente").text("Total Prima Pendiente: $" +  formatter.format(sumaPendiente[0]));
                $("#totalPrimaAnulada").text("Total Prima Anulada: $" + formatter.format(sumaAnulado[0]));
                $("#totalSumaAseguradaAceptada").text("Total Suma Asegurada Aceptada: $" +  formatter.format(sumaAceptado[1]));
                $("#totalSumaAseguradaPendiente").text("Total Suma Asegurada Pendiente: $"+   formatter.format(sumaPendiente[1]));
                $("#totalSumaAseguradaAnulada").text("Total Suma Asegurada Anulada: $" +  formatter.format(sumaAnulado[1]));

                }

                // Actualizar las sumas al cargar la página y en cada cambio de filtro
                actualizarSumas();

                miTabla.on('draw.dt', function() {
                    actualizarSumas();
                });

        });
    </script>

@endsection
