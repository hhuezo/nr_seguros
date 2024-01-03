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
                                <a href="{{url('/polizas/deuda')}}/{{$deuda->Id}}/edit">
                                <button class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i>
                                </button>
                                </a>
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
                                            <td>{{ $registro->PrimerNombre }} {{ $registro->SegundoNombre }}
                                                {{ $registro->PrimerApellido }} {{ $registro->SegundoApellido }}
                                                {{ $registro->ApellidoCasada }}</td>
                                            <td class="{{ in_array(1, $registro->Errores) ? 'alert alert-danger alert-dismissible' : '' }}"
                                                role="alert">
                                                @if (in_array(1, $registro->Errores))
                                                    <strong>{{ $registro->FechaNacimiento }}</strong>
                                                @else
                                                    {{ $registro->FechaNacimiento }}
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
@endsection
