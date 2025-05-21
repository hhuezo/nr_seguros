@extends ('welcome')
@section('contenido')
    <div class="x_panel">

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

                            <th>FECHA DE INGRESO</th>
                            <th>No.</th>
                            <th>GESTOR</th>
                            <th>CIA</th>
                            <th>CONTRATANTE </th>
                            <th>NUMERO <br>POLIZA DEUDA</th>
                            <th>NUMERO <br> POLIZA VIDA</th>
                            <th>ASEGURADO </th>
                            <th>Resumen de Gestión</th>
                            <th>OPCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($suscripciones as $obj)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($obj->FechaIngreso)) }}</td>
                                <td>{{ $i }}</td>
                                <td>{{ $obj->gestor->name ?? '' }}</td>
                                <td>{{ $obj->compania->Nombre ?? '' }}</td>
                                <td>{{ $obj->contratante->Nombre ?? '' }}</td>
                                <td>{{ $obj->polizaDeuda->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->polizaVida->NumeroPoliza ?? '' }}</td>
                                <td>{{ $obj->Asegurado }}</td>
                                <td class="bg-{{$obj->resumenGestion->Color ?? ''}}">{{ $obj->resumenGestion->Nombre ?? '' }}</td>

                                <td align="center">
                                    @can('edit users')
                                        <a href="{{ url('suscripciones') }}/{{ $obj->Id }}/edit" class="btn btn-primary"
                                            class="on-default edit-row">
                                            <i class="fa fa-pencil fa-lg"></i></a>
                                    @endcan
                                    @can('delete users')
                                        &nbsp;&nbsp;<a href="#" class="btn btn-danger" data-target="#modal-delete-{{ $obj->Id }}"
                                            data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
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
