@extends ('welcome')
@section('contenido')
    <div class="x_panel">

        @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])


        @if (isset($nuevos))
            @if (count($nuevos) > 0)
                <div class="x_title">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h3>Nuevos créditos</h3>

                        <div class="clearfix"></div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Numero de Referencia</th>
                                    <th>DUI</th>
                                    <th>Nombre Completo</th>
                                    <th>Suma Asegurada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nuevos as $obj)
                                    <tr>
                                        <td>{{ $obj->NumeroReferencia }}</td>
                                        <td>{{ $obj->Dui }}</td>
                                        <td>{{ $obj->NombreCompleto }}</td>
                                        <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            @endif
        @endif



        @if (isset($eliminados))
            @if (count($eliminados) > 0)
                <div class="x_title">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h3>Créditos Eliminados</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table id="datatable-fixed-header" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Numero de Referencia</th>
                                    <th>DUI</th>
                                    <th>Nombre Completo</th>
                                    <th>Suma Asegurada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eliminados as $obj)
                                    <tr>
                                        <td>{{ $obj->NumeroReferencia }}</td>
                                        <td>{{ $obj->Dui }}</td>
                                        <td>{{ $obj->NombreCompleto }}</td>
                                        <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            @endif
        @endif


        @if (isset($asegurados_limite_individual))
            <div class="x_title">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h3>Personas que sobre pasan el limite individual </h3>


                </div>
                <ul class="nav navbar-right panel_toolbox">

                    <a href="{{url('polizas/residencia/' . $idPolizaResidencia . '/edit')}}" class="btn btn-info fa fa-undo " style="color: white"> Atrás</a>
                </ul>
                <div class="clearfix"></div>

            </div>


            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Numero de Referencia</th>
                                <th>DUI</th>
                                <th>Nombre Completo</th>
                                <th>Suma Asegurada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asegurados_limite_individual as $obj)
                                <tr>
                                    <td>{{ $obj->NumeroReferencia }}</td>
                                    <td>{{ $obj->Dui }}</td>
                                    <td>{{ $obj->NombreCompleto }}</td>
                                    <td>${{ number_format($obj->SumaAsegurada, 2, '.', ',') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        @endif
    </div>



    @include('sweetalert::alert')
@endsection
