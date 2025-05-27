@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        <style>
            #datatable {
                font-size: 12px;
                /* Ajusta el tamaño según lo necesites */
            }
        </style>


        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Suscripciones </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url('suscripciones/create/') }}"><button class="btn btn-info float-right"> <i
                            class="fa fa-plus"></i> Nuevo</button></a>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Fecha ingreso</th>
                            <th>Gestor</th>
                            <th>CIA</th>
                            <th>Contratante </th>
                            <th>Número <br>póliza deuda</th>
                            <th>Número <br>póliza vida</th>
                            <th>Asegurado </th>
                            <th>DUI</th>
                            <th>Edad</th>
                            <th>Genero</th>
                            <th>Suma<br>Asegurada<br>Deuda</th>
                            <th>Suma<br>Asegurada<br>Vida</th>
                            <th>Tipo cliente</th>
                            <th>IMC</th>
                            <th>Padecimientos</th>
                            <th>Tipo de orden medica</th>
                            <th>Estatus del caso</th>
                            <th>Resumen de gestion</th>
                            <th>Fecha reportado cia</th>
                            <th>Tareas eva (sisa)</th>
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($suscripciones as $obj)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d/m/Y', strtotime($obj->FechaIngreso)) }}</td>
                                <td>{{ $obj->gestor->Nombre ?? '' }}</td>
                                <td>{{ $obj->compania->Nombre ?? '' }}</td>
                                <td>{{ $obj->contratante->Nombre ?? '' }}</td>
                                <td>{{ $obj->polizaDeuda->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->polizaVida->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->Asegurado }}</td>
                                <td>{{ $obj->Dui }}</td>
                                <td>{{ $obj->Edad }}</td>
                                <td>
                                    {{ $obj->Genero == 1 ? 'F' : ($obj->Genero == 2 ? 'M' : '') }}
                                </td>
                                <td>{{ $obj->SumaAseguradaDeuda !== null && $obj->SumaAseguradaDeuda > 0 ? $obj->SumaAseguradaDeuda : '' }}
                                </td>
                                <td>{{ $obj->SumaAseguradaVida !== null && $obj->SumaAseguradaVida > 0 ? $obj->SumaAseguradaVida : '' }}
                                </td>
                                <td>{{ $obj->tipoCliente->Nombre ?? '' }}</td>
                                <td>{{ number_format($obj->Imc, 2) }}</td>
                                <td>{{ $obj->Padecimiento }}</td>
                                <td>{{ $obj->tipoOrdenMedica->Nombre ?? '' }}</td>
                                <td>{{ $obj->estadoCaso->Nombre }}</td>
                                <td class="bg-{{ $obj->resumenGestion->Color ?? '' }}">
                                    {{ $obj->resumenGestion->Nombre ?? '' }}</td>
                                <td>
                                    {{ $obj->FechaReportadoCia ? date('d/m/Y', strtotime($obj->FechaReportadoCia)) : '' }}
                                </td>
                                <td>{{ $obj->TareasEvaSisa }}</td>

                                <td align="center">
                                    @can('edit users')
                                        <a href="{{ url('suscripciones') }}/{{ $obj->Id }}/edit" class="btn btn-primary"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                    @endcan
                                    @can('delete users')
                                        <a href="#" class="btn btn-danger"
                                            data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i
                                                class="fa fa-trash fa-lg"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @include('suscripciones.suscripcion.modal')
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
