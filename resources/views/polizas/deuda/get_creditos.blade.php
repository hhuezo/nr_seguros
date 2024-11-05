@if ($opcion == 1)
    <table class="table table-striped" id="MyTable3">
        <thead>
            <tr>
                <th>Número crédito</th>
                <th>DUI</th>
                <th>NIT</th>
                <th>Nombre</th>
                <th>Fecha nacimiento</th>
                <th>Fecha otorgamiento</th>
                <th>Edad actual</th>
                <th>Edad desembolso</th>
                <th>Saldo</th>
                <th>Motivo</th>
                <th>Agregar a válidos</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $total = 0;
            @endphp
            @foreach ($poliza_cumulos->where('Excluido', 0) as $registro)
                @php
                    foreach ($requisitos as $req) {
                        if ($registro->Edad < $req->EdadInicial) {
                            $motivo = 'Debe presentar documentacion que justifique sus Saldo'; //mar
                        } elseif ($registro->Edad > $req->EdadFinal) {
                            $motivo = 'La persona se encuentra fuera del rango de asegurabilidad'; //1:32- miguel
                        } elseif ($registro->total_saldo > $req->MontoFinal) {
                            $motivo = 'El monto del usuario se encuentra fuera del rango de la tabla de asegurabilidad'; //7:30 -walter
                        }
                    }

                @endphp
                <tr>
                    <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
                    <td>{{ $registro->Dui }}</td>
                    <td>{{ $registro->Nit }}</td>
                    <td>{{ $registro->PrimerNombre }}
                        {{ $registro->SegundoNombre }}
                        {{ $registro->PrimerApellido }}
                        {{ $registro->SegundoApellido }}
                        {{ $registro->ApellidoCasada }}
                    </td>
                    <td>{{ $registro->FechaNacimiento ?? '' }}</td>
                    <td>{{ $registro->FechaOtorgamiento ?? '' }}</td>
                    <td>{{ $registro->Edad ? $registro->Edad . ' Años' : '' }}</td>
                    <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso . ' Años' : '' }}</td>
                    <td class="text-right">
                        ${{ number_format($registro->total_saldo, 2, '.', ',') }}
                    </td>
                    <td>{{ $motivo }}</td>
                    <td align="center" data-target="#modal_cambio_credito_valido" data-toggle="modal"
                        onclick="get_creditos({{ $registro->Id }})">
                        <button class="btn btn-primary">
                            <i class="fa fa-exchange"></i>
                        </button>
                    </td>
                </tr>
                @php
                    $i++;
                $total += $registro->total_saldo; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" align="right">Total</td>
                <td>${{ number_format($total, 2, '.', ',') }}</td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
@else
    <style>
        .row-warning {
            background-color: #eeb458 !important;
            /* Color naranja sólido */
            color: #292828;
            /* Texto negro */
        }
    </style>
    <table class="table table-striped" id="MyTable4">
        <thead>
            <tr>
                <th>Número crédito</th>
                <th>DUI</th>
                <th>NIT</th>
                <th>Nombre</th>
                <th>Fecha nacimiento</th>
                <th>Edad actual</th>
                <th>Edad otorgamiento</th>
                <th>Fecha otorgamiento</th>
                <th>Requisitos</th>
                <th>Saldo</th>
            </tr>
            <!-- <tr>
            <th colspan="8"></th>
            <th style="width: 20% !important;">
                <select id="requisitosFilter">
                    <option value="">Todos</option>
                </select>
            </th>
            <th></th>
        </tr> -->

        </thead>
        @php $i=1 @endphp
        <tbody>


            @foreach ($poliza_cumulos->where('Perfiles', '<>', null)->sortBy('Rehabilitado')->reverse() as $registro)
                <tr class="{{ $registro->Rehabilitado == 1 ? 'row-warning' : '' }}">
                    <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
                    <td>{{ $registro->Dui }}</td>
                    <td>{{ $registro->Nit }}</td>
                    <td>{{ $registro->PrimerNombre }}
                        {{ $registro->SegundoNombre }}
                        {{ $registro->PrimerApellido }}
                        {{ $registro->SegundoApellido }}
                        {{ $registro->ApellidoCasada }}
                    </td>
                    <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                    </td>
                    <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                    <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                        Años</td>
                    <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                    </td>
                    <td>
                        @php
                            // Split the Perfiles string, remove empty elements, get unique values, and trim each element
                            $perfilesArreglo = array_map('trim', array_filter(array_unique(explode(',', $registro->Perfiles))));
                        @endphp
                    
                        @foreach ($perfilesArreglo as $key => $perfil)
                            {{ $perfil }}{{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </td>
                    <td class="text-right">
                        ${{ number_format($registro->total_saldo, 2, '.', ',') }}</td>

                </tr>
                @php $i++ @endphp
            @endforeach


            @foreach ($poliza_cumulos->where('NoValido', 0)->where('Perfiles', '')->where('Excluido', 0)->sortBy('Rehabilitado')->reverse() as $registro)
                <tr class="table-warning">
                    <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
                    <td>{{ $registro->Dui }}</td>
                    <td>{{ $registro->Nit }}</td>
                    <td>{{ $registro->PrimerNombre }}
                        {{ $registro->SegundoNombre }}
                        {{ $registro->PrimerApellido }}
                        {{ $registro->SegundoApellido }}
                        {{ $registro->ApellidoCasada }}
                    </td>
                    <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}
                    </td>
                    <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
                    <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }}
                        Años</td>
                    <td>{{ $registro->FechaOtorgamiento ? $registro->FechaOtorgamiento : '' }}
                    </td>
                    <td>
                        @php
                            // Split the Perfiles string, remove empty elements, get unique values, and trim each element
                            $perfilesArreglo = array_map('trim', array_filter(array_unique(explode(',', $registro->Perfiles))));
                        @endphp
                    
                        @foreach ($perfilesArreglo as $key => $perfil)
                            {{ $perfil }}{{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </td>
                    <td class="text-right">
                        ${{ number_format($registro->total_saldo, 2, '.', ',') }}</td>

                </tr>
                @php $i++ @endphp
            @endforeach








        </tbody>
    </table>
    <script>
        $(document).ready(function() {

             $('#MyTable4').DataTable({
                ordering: false // Desactiva el ordenamiento
            });
            $('#MyTable3').DataTable();
        });
    </script>

    <script>
        document.getElementById('omitir_declaracion').addEventListener('change', function() {
            // Get the checkbox state
            let omitDeclaracion = this.checked;
            // Get all rows in the table
            let rows = document.querySelectorAll('#MyTable4 tbody tr');

            rows.forEach(row => {
                // Get the "Requisitos" column text content
                let requisitosText = (row.cells[8].innerText.trim() || row.cells[8].textContent.trim());

                // Check if "Declaracion de salud Jurada" is in the requisitosText
                if (requisitosText.includes("Declaracion de salud Jurada")) {
                    // If checked, hide the row if it has "Declaracion de salud Jurada"
                    row.style.display = omitDeclaracion ? 'none' : '';
                }
            });
        });
    </script>
@endif
