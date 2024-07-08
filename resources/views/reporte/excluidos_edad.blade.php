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
            <th>Fecha de Exclusión</th>
            <th>Edad</th>
        </tr>
        @php($i=1)
        @foreach($excluidos as $obj)
        <tr>

            <td>{{$i}}</td>
            <td>{{$obj->Nombre}}</td>
            <td>{{strlen($obj->Dui) == 9 ? substr($obj->Dui, 0, 8) . '-' . substr($obj->Dui, 8, 1) : 
                substr($obj->Dui, 0, 4) . '-' . substr($obj->Dui, 4, 6) . '-' . substr($obj->Dui, 10, 3) . '-' . substr($obj->Dui, 13, 1)}}</td>
            <td>{{$obj->NumeroReferencia}}</td>
            <td>{{ \Carbon\Carbon::parse($obj->FechaExclusion)->format('d/m/Y') }}</td>
            <td>{{$obj->Edad}} años</td>

        </tr>
        @php($i++)
        @endforeach
    </table>
</body>

</html>