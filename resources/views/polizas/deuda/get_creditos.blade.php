@if ($opcion == 1)
<table class="table table-striped" id="MyTable3">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>Tipo cartera</th>
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

        {{-- @if ($edades->EdadInicial < $registro->Edad)
                    @php
                        $motivo = $registro->Edad.'- '.$req->EdadInicial.  'El monto del usuario se encuentra fuera del rango de la tabla de asegurabilidad';
                    @endphp
                @else

                    @php
                        foreach ($requisitos as $req) {
                            if ($registro->Edad < $req->EdadInicial) {
                                $motivo = $registro->Edad.'- '.$req->EdadInicial. 'Debe presentar documentacion que justifique sus Saldo'; //mar
                            } elseif ($registro->Edad > $req->EdadFinal) {
                                $motivo = $registro->Edad.'- '.$req->EdadInicial.  'La persona se encuentra fuera del rango de asegurabilidad'; //1:32- miguel
                            } elseif ($registro->total_saldo > $req->MontoFinal) {
                                $motivo = $registro->Edad.'- '.$req->EdadInicial.  'El monto del usuario se encuentra fuera del rango de la tabla de asegurabilidad'; //7:30 -walter
                            }

                        }
                    @endphp

                @endif --}}

        <tr>
            <td>{{ $registro->ConcatenatedNumeroReferencia }}</td>
            <td>{{ $registro->TipoCarteraNombre }}
                ({{ $registro->Abreviatura }})
            </td>
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
                ${{ number_format($registro->saldo_total, 2, '.', ',') }}
            </td>
            <td>{{ $registro->Motivo }}</td>
            <td align="center" data-target="#modal_cambio_credito_valido" data-toggle="modal" onclick="get_creditos({{ $registro->Id }})">
                <button class="btn btn-primary">
                    <i class="fa fa-exchange"></i>
                </button>
            </td>
        </tr>
        @php

        $i++;
        $total += $registro->saldo_total; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" align="right">Total</td>
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

    .row-error {
        background-color: #E63946 !important;
        /* Color naranja sólido */
        color: #F1FAEE;
        /* Texto negro */
    }

</style>
<table class="table table-striped" id="MyTable4">
    <thead>
        <tr>
            <th>Número crédito</th>
            <th>DUI/NIT</th>
            {{-- <th>NIT</th> --}}
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Edad actual</th>
            <th>Edad otorgamiento</th>
            <th>Fecha otorgamiento</th>
            <th>Requisitos</th>
            <th>Cúmulo</th>
            <th>Detalle</th>
        </tr>


    </thead>
    @php $i=1 @endphp
    <tbody>

        @if($poliza_cumulos)
        @foreach ($poliza_cumulos->sortBy('Rehabilitado')->reverse() as $registro)

        <tr class="{{ $tipo == 3 ? 'row-warning' : '' }}">
            {{-- <td>{{ $registro->ConcatenatedNumeroReferencia }} </td> --}}
            <td>
                @php
                $referencias = !empty($registro->ConcatenatedNumeroReferencia)
                ? explode(',', $registro->ConcatenatedNumeroReferencia)
                : [];
                @endphp

                @if (count($referencias) > 1)
                @foreach ($referencias as $index => $referencia)
                @if ($index == count($referencias) - 1 && $tipo == 1)
                <span style="color: red;">{{ $referencia }}</span>
                @else
                {{ $referencia }},
                @endif
                @endforeach
                @else
                {{ implode(', ', $referencias) }}
                @endif
            </td>
            <td>
                {{
                                $registro->Dui && $registro->Nit && $registro->Dui !== $registro->Nit
                                ? $registro->Dui . ' - ' . $registro->Nit
                                : ($registro->Dui ?? $registro->Nit)
                            }}
            </td>


            <td>{{ $registro->PrimerNombre }}
                {{ $registro->SegundoNombre }}
                {{ $registro->PrimerApellido }}
                {{ $registro->SegundoApellido }}
                {{ $registro->ApellidoCasada }}
            </td>
            <td>{{ $registro->FechaNacimiento ? $registro->FechaNacimiento : '' }}</td>
            <td>{{ $registro->Edad ? $registro->Edad : '' }} Años</td>
            <td>{{ $registro->EdadDesembloso ? $registro->EdadDesembloso : '' }} Años</td>
            <td>{{ $registro->FechaOtorgamiento ? date('d/m/Y', strtotime($registro->FechaOtorgamiento)) : '' }}
            </td>

            <td>
                @php
                $perfilesArreglo = explode(',', $registro->Perfiles);
                $uniquePerfiles = array_unique($perfilesArreglo);
                @endphp

                @foreach ($uniquePerfiles as $key => $perfil)
                {{ $perfil }}{{ $loop->last ? '' : ', ' }}
                @endforeach
            </td>
            <td class="text-right">
                ${{ number_format($registro->saldo_total, 2, '.', ',') }}
                {{-- <i
                                class="{{ $registro->MontoMaximoIndividual <= $registro->saldo_total ? 'btn btn-danger fa fa-warning' : '' }}"></i> --}}



            </td>
            {{-- <td>{{ $registro->Existe }}</td> --}}
            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="get_creditos_detalle('{{ $registro->Dui }}',{{$deuda->Id}})"><i class="fa fa-eye"></i></button></td>

        </tr>
        @php $i++ @endphp

        @endforeach
        @endif




    </tbody>
</table>


<script>
    $(document).ready(function() {
        $('#MyTable4').DataTable().destroy();
        $('#MyTable4').DataTable({
            //ordering: false, // Desactiva el ordenamiento
            paging: false // Desactiva la paginación
        });

        $('#MyTable3').DataTable();
    });

</script>

@endif
