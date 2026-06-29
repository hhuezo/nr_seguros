<table>
    <tr>
        <td colspan="{{ $totalColumnas }}"><strong>POLIZA: {{ $poliza->NumeroPoliza }}</strong></td>
    </tr>
    <tr>
        <td colspan="{{ $totalColumnas }}"><strong>ASEGURADO: {{ optional($poliza->clientes)->Nombre ?? '' }}</strong></td>
    </tr>
    <tr>
        <td colspan="{{ $totalColumnas }}"></td>
    </tr>
    <tr>
        <th>Certificado</th>
        @foreach ($camposDinamicos as $campo)
            <th>{{ $campo->Etiqueta }}</th>
        @endforeach
    </tr>
    @foreach ($filas as $fila)
        <tr>
            <td>{{ $fila['certificado'] }}</td>
            @foreach ($camposDinamicos as $campo)
                <td>{{ $fila['valores'][$campo->Id] ?? '' }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
