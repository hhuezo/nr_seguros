@extends ('welcome')
@section('contenido')
    <div class="x_panel">
        <br>
        <div class="x_title">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <h3>Errores en la Importación de Suscripciones </h3>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12" align="right">
                <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Volver</a>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">

            @if ($customFailures->count() > 0)
                <div class="alert alert-danger">
                    Se encontraron errores en el archivo. Revisa la tabla a continuación:
                </div>

                <table class="table table-bordered table-striped" id="datatable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fila</th>
                            <th>Campo</th>
                            <th>Error</th>
                            <th>Valor Original</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($failures as $index => $failure)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $failure->row() }}</td>
                                <td>{{ $failure->attribute() }}</td>
                                <td>
                                    @foreach ($failure->errors() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </td>
                                <td>{{ $failure->values()[$failure->attribute()] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                        @foreach ($customFailures ?? [] as $index => $failure)
                            <tr>
                                <td>{{ $loop->iteration + $failures->count() }}</td>
                                <td>{{ $failure['row'] }}</td>
                                <td>{{ $failure['attribute'] }}</td>
                                <td>
                                    @foreach ($failure['errors'] as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </td>
                                <td>{{ $failure['value'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    No hay errores para mostrar.
                </div>
            @endif
        </div>
    </div>
@endsection
