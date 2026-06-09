@extends ('welcome')
@section('contenido')
    <div class="x_panel">
        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Ramos </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                @can('ramo create')
                    <a href="{{ route('necesidad_proteccion.create') }}" class="btn btn-info float-right">
                        <i class="fa fa-plus"></i> Nuevo
                    </a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Nombre</th>
                            <th width="15%">Agrupador</th>
                            <th width="10%">Modulo</th>
                            <th width="10%">% Comision ND</th>
                            <th width="10%">Comision Bomberos</th>
                            <th width="10%">% Bomberos</th>
                            <th width="15%">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($necesidad_proteccion as $obj)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $obj->Nombre }}</td>
                                <td>{{ $obj->agrupador_ramo->Nombre ?? '' }}</td>
                                <td>{{ $obj->tipo_poliza->Nombre ?? '' }}</td>
                                <td>{{ $obj->PorcentajeComisionNoDeclarativa !== null ? number_format((float) $obj->PorcentajeComisionNoDeclarativa, 2) . '%' : '' }}</td>
                                <td>{{ (int) ($obj->ComisionBomberos ?? 0) === 1 ? 'Si' : 'No' }}</td>
                                <td>{{ $obj->PorcentajeBomberos !== null ? number_format((float) $obj->PorcentajeBomberos, 2) . '%' : '' }}</td>
                                <td align="center">
                                    @can('ramo edit')
                                        <a href="{{ route('necesidad_proteccion.edit', $obj->Id) }}" class="on-default edit-row">
                                            <button class="btn btn-primary"><i class="fa fa-pencil fa-lg"></i></button>
                                        </a>
                                    @endcan

                                    @can('ramo delete')
                                        <a href="#" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal">
                                            <button class="btn btn-danger"><i class="fa fa-trash fa-lg"></i></button>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                            @include('catalogo.necesidad_proteccion.modal')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var displayStart = {{ $posicion }};
        $(document).ready(function() {
            $('#datatable').DataTable({
                pageLength: 10,
                displayStart: displayStart,
                ordering: false
            });
        });
    </script>
    @include('sweetalert::alert')
@endsection
