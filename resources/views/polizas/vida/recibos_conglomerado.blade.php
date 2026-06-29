<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avisos de Cobro</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .recibo-page {
            margin-top: -10%;
            page-break-after: always;
        }

        .recibo-page:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body>
    <footer>
        <table style="width: 100%; text-align: center;">
            <tr>
                <td>
                    {!! $configuracion->Pie !!}
                </td>
            </tr>
        </table>
    </footer>

    @foreach ($recibos as $registro)
        @php
            $detalle = $registro['detalle'];
            $recibo_historial = $registro['recibo_historial'];
        @endphp
        <div class="recibo-page">
            @include('polizas.vida.partials.recibo_contenido')
        </div>
    @endforeach
</body>

</html>
