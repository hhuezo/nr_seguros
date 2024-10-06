<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluidos</title>
</head>

<body>
    <table>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Dui</th>
            <th>Número de Referencia</th>
            <th>Fecha</th>
            <th>Credito</th>
        </tr>
        
        @foreach($excluidos as $obj)
        @php
        $i=1;
        $sub_total = $obj->total_saldo + $obj->total_interes + $obj->total_covid + $obj->total_moratorios + $obj->total_monto_nominal;
        @endphp
        @if($sub_total > $deuda->ResponsabilidadMaxima)
        <tr>

            <td>{{$i}}</td>
            <td>{{$obj->PrimerNombre}}
                 {{$obj->SegundoNombre}}
                 {{$obj->PrimerApellido}}
                 {{$obj->SegundoApellido}}
            </td>
            <td>{{strlen($obj->Dui) == 9 ? substr($obj->Dui, 0, 8) . '-' . substr($obj->Dui, 8, 1) : 
                substr($obj->Dui, 0, 4) . '-' . substr($obj->Dui, 4, 6) . '-' . substr($obj->Dui, 10, 3) . '-' . substr($obj->Dui, 13, 1)}}</td>
            <td>{{$obj->NumeroReferencia}}</td>
            <td>{{\Carbon\Carbon::now()->format('d/m/Y')}}</td>
            <td>${{number_format($sub_total,2,'.',',')}}</td>

        </tr>
        @endif
        @php($i++)
        @endforeach
    </table>
</body>

</html>