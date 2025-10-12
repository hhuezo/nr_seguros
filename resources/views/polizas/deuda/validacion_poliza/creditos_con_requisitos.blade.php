<style>
    .row-warning {
        background-color: #eeb458 !important;
        /* Naranja suave */
        color: #292828;
        /* Texto oscuro */
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .btn i {
        pointer-events: none;
    }

    .text-nowrap {
        white-space: nowrap;
    }

    #MyTable4 {
        font-size: 12px;
    }
</style>

<table class="table table-striped table-hover align-middle" id="MyTable4">
    <thead class="table-primary text-center">
        <tr>
            <th>Número crédito</th>
            <th>DUI / Documento</th>
            <th>Nombre</th>
            <th>Fecha nacimiento</th>
            <th>Edad actual</th>
            <th>Edad otorgamiento</th>
            <th>Fecha otorgamiento</th>
            <th>Requisitos</th>
            <th>Tipo cartera</th>
            <th>Cúmulo</th>
            @if ($tipo == 3)
                <th>Último registro</th>
            @endif
            <th>Detalle</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($poliza_cumulos as $registro)
            <tr class="{{ $tipo == 3 ? 'row-warning' : '' }}">
                {{-- Número de crédito --}}
                <td>
                    @if ($tipo == 1)
                        {!! $registro->getNumerosReferencia($registro->PolizaDeudaTipoCartera) !!}
                    @else
                        @php
                            $referencias = array_filter(explode(',', $registro->ConcatenatedNumeroReferencia ?? ''));
                        @endphp
                        @if (count($referencias) > 0)
                            {{ implode(', ', $referencias) }}
                        @endif
                    @endif
                </td>

                {{-- DUI / Documento --}}
                <td>
                    {{ $registro->Dui ?? ($registro->Pasaporte ?? ($registro->CarnetResidencia ?? '')) }}
                </td>

                {{-- Nombre completo --}}
                <td>
                    {{ trim("{$registro->PrimerNombre} {$registro->SegundoNombre} {$registro->PrimerApellido} {$registro->SegundoApellido} {$registro->ApellidoCasada}") }}
                </td>

                {{-- Fecha de nacimiento --}}
                <td class="text-center">
                    {{ $registro->FechaNacimiento }}
                </td>

                {{-- Edad actual --}}
                <td class="text-center text-nowrap">
                    {{ $registro->Edad ? "{$registro->Edad} años" : '' }}
                </td>

                {{-- Edad otorgamiento --}}
                <td class="text-center text-nowrap">
                    {{ $registro->EdadDesembloso ? "{$registro->EdadDesembloso} años" : '' }}
                </td>

                {{-- Fecha otorgamiento --}}
                <td class="text-center">
                    {{ $registro->FechaOtorgamiento }}
                </td>

                {{-- Requisitos --}}
                <td style="width: 25%; white-space: normal; word-wrap: break-word;">
                    @php
                        $perfiles = array_unique(array_filter(explode(',', $registro->Perfiles ?? '')));
                    @endphp
                    {{ implode(', ', $perfiles) }}
                </td>

                {{-- Tipo de cartera --}}
                <td style="width: 5%; white-space: normal; word-wrap: break-word;">
                    {{ $registro->TipoCarteraNombre ?? '-' }}
                </td>

                {{-- Cúmulo --}}
                <td class="text-right text-nowrap">
                    ${{ number_format($registro->saldo_total ?? 0, 2, '.', ',') }}
                </td>

                {{-- Último registro (solo tipo 3) --}}
                @if ($tipo == 3)
                    <td class="text-center">
                        {{ $registro->UltimoRegistro ?? '-' }}
                    </td>
                @endif

                {{-- Botón detalle --}}
                <td class="text-center">
                    <button type="button" id="cumulo-{{ $registro->Dui }}"
                        class="btn btn-sm btn-{{ $tipo == 1 && ($registro->Validado ?? 0) == 0 ? 'success' : 'primary' }}"
                        data-toggle="modal" data-target=".bs-example-modal-lg"
                        onclick="loadDetalleCreditoRequisito('{{ $registro->Dui }}', {{ $deuda->Id }}, {{ $tipo }},{{ $registro->PolizaDeudaTipoCartera }})">
                        <i class="fa fa-eye"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function() {
        // Destruir instancia previa de DataTable
        if ($.fn.DataTable.isDataTable('#MyTable4')) {
            $('#MyTable4').DataTable().destroy();
        }

        // Inicializar DataTable con opciones limpias
        $('#MyTable4').DataTable();
    });
</script>
