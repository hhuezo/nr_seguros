@extends ('welcome')
@section('contenido')
    <div role="main">
        <div class="">


            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h2>Error<small>Cartera con registros erroneos</small> </h2>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12" style="text-align: right;">
                                <form action="{{ url('exportar/registros_erroneos') }}/{{ $poliza_vida->Id }}" method="POST"
                                    style="display: inline-block; margin-right: 10px;">
                                    @csrf
                                    <button class="btn btn-success">Descargar Excel</button>
                                </form>
                                <form method="POST" action="{{ url('polizas/vida/delete_temp') }}/{{$poliza_vida->Id}}"
                                    style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-striped" id="example">
                                <thead>
                                    <tr>
                                        <th>Error</th>
                                        <th>DUI</th>
                                        <th>NIT</th>
                                        <th>Nombre</th>
                                        <th>Fecha nacimiento</th>
                                        <th>Fecha otorgamiento</th>
                                        {{-- <th>Fecha vencimiento</th> --}}
                                        <th>No De Referencia Del Crédito </th>
                                        <th>Pasaporte</th>
                                        <th>Nacionalidad</th>
                                        <th>Género</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_error as $registro)
                                        <tr>
                                            <td>
                                                @if (in_array(1, $registro->Errores))
                                                    <span style="color: red;">Formato de fecha de nacimiento no
                                                        válido</span>
                                                @endif

                                                @if (in_array(2, $registro->Errores))
                                                    <span style="color: red;">Formato de fecha de otorgamiento no
                                                        válido</span>
                                                @endif

                                                @if (in_array(3, $registro->Errores))
                                                    <span style="color: red;">El dato de la nacionalidad esta vacia</span>
                                                @endif


                                                @if (in_array(4, $registro->Errores))
                                                    <span style="color: red;">Formato de DUI no válido</span>
                                                @endif

                                                @if (in_array(5, $registro->Errores))
                                                    <span style="color: red;">Pasaporte no válido</span>
                                                @endif



                                                @if (in_array(6, $registro->Errores))
                                                    @if (!$registro->PrimerNombre)
                                                        <span style="color: red;">&nbsp;Falta el primer nombre</span>
                                                    @endif
                                                    @if (!$registro->PrimerApellido)
                                                        <span style="color: red;">&nbsp;Falta el primer apellido</span>
                                                    @endif
                                                @endif



                                                @if (in_array(7, $registro->Errores))
                                                    <span style="color: red;">Número de referecia no válido</span>
                                                @endif





                                                @if (in_array(8, $registro->Errores))
                                                    <span style="color: red;">El género no es válido</span>
                                                @endif

                                            </td>
                                            <td>
                                                @if (in_array(4, $registro->Errores))
                                                    <strong><span style="color: red;">{{ $registro->Dui }}</span></strong>
                                                @else
                                                    {{ $registro->Dui }}
                                                @endif
                                            </td>

                                            <td>{{ $registro->Nit }}</td>

                                            <td>
                                                @if (in_array(6, $registro->Errores))
                                                    <strong><span style="color: red;">
                                                            {{ $registro->PrimerNombre }}
                                                            {{ $registro->SegundoNombre }}
                                                            {{ $registro->PrimerApellido }}
                                                            {{ $registro->SegundoApellido }}
                                                            {{ $registro->ApellidoCasada }}
                                                        </span></strong>
                                                @else
                                                    {{ $registro->PrimerNombre }} {{ $registro->SegundoNombre }}
                                                    {{ $registro->PrimerApellido }} {{ $registro->SegundoApellido }}
                                                    {{ $registro->ApellidoCasada }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (in_array(1, $registro->Errores))
                                                    <strong><span
                                                            style="color: red;">{{ $registro->FechaNacimiento }}</span></strong>
                                                @else
                                                    {{ $registro->FechaNacimiento }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (in_array(2, $registro->Errores))
                                                    <strong><span
                                                            style="color: red;">{{ $registro->FechaOtorgamiento }}</span></strong>
                                                @else
                                                    {{ $registro->FechaOtorgamiento }}
                                                @endif
                                            </td>
                                            {{-- <td class="{{ in_array(6, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(6, $registro->Errores))
                                                    <strong><span
                                                            style="color: red;">{{ $registro->FechaVencimiento }}</span></strong>
                                                @else
                                                    {{ $registro->FechaVencimiento }}
                                                @endif
                                            </td> --}}
                                            <td>
                                                @if (in_array(7, $registro->Errores))
                                                    <strong><span
                                                            style="color: red;">{{ $registro->NumeroReferencia }}</span></strong>
                                                @else
                                                    {{ $registro->NumeroReferencia }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (in_array(8, $registro->Errores))
                                                    <strong><span
                                                            style="color: red;">{{ $registro->Pasaporte }}</span></strong>
                                                @else
                                                    {{ $registro->Pasaporte }}
                                                @endif
                                            </td>
                                            <td> {{ $registro->Nacionalidad }}</td>
                                            <td> {{ $registro->Sexo }}</td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>



                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");


            $('#example').DataTable();
        });
    </script>
@endsection
