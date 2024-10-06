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
                            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                                <form method="POST" action="{{ url('polizas/deuda/delete_temp') }}">
                                    @csrf
                                    <input type="hidden" name="LineaCredito" value="{{ $credito }}">
                                    <input type="hidden" name="DeudaId" value="{{ $deuda->Id }}">
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i>
                                    </button>

                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>DUI</th>
                                        <th>NIT</th>
                                        <th>Nombre</th>
                                        <th>Fecha nacimiento</th>
                                        <th>Fecha otorgamiento</th>
                                        <th>Fecha vencimiento</th>
                                        <th>No De Referencia Del Crédito </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_error as $registro)
                                        <tr>
                                            <td class="{{ in_array(2, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(2, $registro->Errores))
                                                    <strong> {{ $registro->Dui }}</strong>
                                                @else
                                                    {{ $registro->Dui }}
                                                @endif
                                            </td>

                                            <td>{{ $registro->Nit }}</td>

                                            <td class="{{ in_array(4, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(4, $registro->Errores))
                                                {{ $registro->PrimerNombre ? $registro->PrimerNombre : '(Falta el primer nombre)' }}
                                                {{ $registro->SegundoNombre }}
                                                {{ $registro->PrimerApellido ? $registro->PrimerApellido : '(Falta el primer apellido)' }}
                                                {{ $registro->SegundoApellido }}
                                                {{ $registro->ApellidoCasada }}
                                                @else
                                                {{ $registro->PrimerNombre }} {{ $registro->SegundoNombre }}
                                                {{ $registro->PrimerApellido }} {{ $registro->SegundoApellido }}
                                                {{ $registro->ApellidoCasada }}
                                                @endif
                                            </td>
                                            <td class="{{ in_array(1, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(1, $registro->Errores))
                                                    <strong>{{ $registro->FechaNacimiento }}</strong>
                                                @else
                                                    {{ $registro->FechaNacimiento }}
                                                @endif
                                            </td>
                                            <td class="{{ in_array(5, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(5, $registro->Errores))
                                                    <strong>{{ $registro->FechaOtorgamiento }}</strong>
                                                @else
                                                    {{ $registro->FechaOtorgamiento }}
                                                @endif
                                            </td>
                                            <td class="{{ in_array(6, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(6, $registro->Errores))
                                                    <strong>{{ $registro->FechaVencimiento }}</strong>
                                                @else
                                                    {{ $registro->FechaVencimiento }}
                                                @endif
                                            </td>
                                            <td class="{{ in_array(7, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(7, $registro->Errores))
                                                    <strong>{{ $registro->NumeroReferencia }}</strong>
                                                @else
                                                    {{ $registro->NumeroReferencia }}
                                                @endif
                                            </td>
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
    <script type="text/javascript">
        $(document).ready(function() {
            //mostrar opcion en menu
            displayOption("ul-poliza", "li-poliza-deuda");
        });
    </script>
@endsection
