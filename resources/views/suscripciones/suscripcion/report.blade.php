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
            <th> No. </th>
            <th> FECHA DE INGRESO </th>
            <th> FECHA ENTREGA <br> DOCS COMPLETOS </th>
            <th> DIAS PARA COMPLETAR <br> INFORMACION (CLIENTE) </th>
            <th> GESTOR </th>
            <th> CIA </th>
            <th> CONTRATANTE </th>
            <th> "NUMERO POLIZA DEUDA" </th>
            <th> "NUMERO POLIZA VIDA" </th>
            <th> ASEGURADO </th>
            <th> OCUPACION </th>
            <th> DUI / OTRO DOCUMENTO DE IDENTIDAD </th>
            <th> EDAD </th>
            <th> GENERO </th>
            <th> "SUMA ASEGURADA <br> EVALUADA DEUDA" </th>
            <th> SUMA ASEGURADA EVALUADA <br> VIDA COLECTIVO USUARIOS </th>
            <th> TIPO DE CLIENTE </th>
            <th> TIPO DE CREDITO </th>
            <th> IMC </th>
            <th> TIPO DE IMC </th>
            <th> PADECIMIENTOS </th>
            <th> TIPO DE ORDEN MEDICA </th>
            <th> ESTATUS DEL CASO </th>
            <th> RESUMEN DE GESTION </th>
            <th> FECHA REPORTADO CIA <br>/ RESOLUCION ANTICIPADA </th>
            <th> TRABAJO EFECTUADO DIA HABIL </th>
            <th> "TAREAS EVA (SISA)" </th>
            <th> COMENTARIOS NR SUSCRIPCION </th>
            <th> FECHA CIERRE DE GESTION </th>
            <th> FECHA DE RECEPCION <br>DE RESOLUCION DE CIA </th>
            <th> FECHA DE ENVIO DE <br> RESOLUCION AL CLIENTE </th>
            <th> DIAS PROCESAMIENTO <br> DE RESOLUCION </th>
            <th> REPROCESO DE NR </th>
            <th> FECHA DE ENVIO <br> DE CORRECCION </th>
            <th> TOTAL DIAS CICLO DE PROCESO </th>
            <th> RESOLUCION OFICIAL </th>
            <th> % EXTRAPRIMA </th>

        </tr>
        @php($i=1)
        @foreach($suscripciones as $obj)
        <tr>
            <td>{{$obj->NumeroTarea}}</td>
            <td>{{ $obj->FechaIngreso ? date('d/m/Y', strtotime($obj->FechaIngreso)) : '' }}</td>
            <td></td>
            <td></td>
            <td>{{ $obj->gestor->Nombre ?? ' ' }}</td>
            <td>{{$obj->compania->Nombre ?? '' }}</td>
            <td>{{$obj->contratante->Nombre ?? '' }}</td>
            <td>{{ $obj->polizaDeuda->NumeroPoliza ?? '' }}</td>
            <td>{{ $obj->polizaVida->NumeroPoliza ?? '' }}</td>
            <td>{{$obj->Asegurado}}</td>
            <td>{{$obj->ocupacion->Nombre}}</td>
            <td>{{$obj->Dui}}</td>
            <td>{{$obj->Edad}}</td>
            <td>{{ $obj->Genero == 1 ? 'F' : ($obj->Genero == 2 ? 'M' : '') }}</td>
            <td> {{ $obj->SumaAseguradaDeuda !== null && $obj->SumaAseguradaDeuda > 0
                                        ? number_format($obj->SumaAseguradaDeuda, 2)
                                        : '' }}
            </td>
            <td> {{ $obj->SumaAseguradaVida !== null && $obj->SumaAseguradaVida > 0
                                        ? number_format($obj->SumaAseguradaVida, 2)
                                        : '' }}
            </td>
            <td>{{$obj->tipoCliente->Nombre ?? ''}}</td>
            <td>{{$obj->tipoCredito->Nombre ?? ''}}</td>
            <td>{{number_format($obj->Imc, 2) }}</td>
            <td>{{$obj->tipoImc->Nombre ?? ''}}</td>
            <td>{{$obj->Padecimiento}}</td>
            <td>{{$obj->tipoOrdenMedica->Nombre}}</td>
            <td>{{$obj->estadoCaso->Nombre ?? ''}}</td>
            <td>{{$obj->resumenGestion->Nombre ?? ''}}</td>
            <td>{{ $obj->FechaReportadoCia ? date('d/m/Y', strtotime($obj->FechaReportadoCia)) : '' }}</td>
            <td>{{$obj->TrabajadoEfectuadoDiaHabil}}</td>
            <td>{{$obj->TareasEvaSisa}}</td>
            <td>
                <ul>
                    @foreach($obj->comentarios as $comen)
                    <li>{{$comen->Comentario}}</li>
                    @endforeach
                </ul>
            </td>
            <td>{{ $obj->FechaCierreGestion ? date('d/m/Y', strtotime($obj->FechaCierreGestion)) : '' }}</td>
            <td>{{ $obj->FechaResolucion ? date('d/m/Y', strtotime($obj->FechaResolucion)) : '' }}</td>
            <td>{{ $obj->FechaEnvioResoCliente ? date('d/m/Y', strtotime($obj->FechaEnvioResoCliente)) : '' }}</td>
            <td>{{ $obj->DiasProcesamientoResolucion}}</td>
            <td>{{ $obj->ReprocesoId ?? ''}}</td>
            <td>{{ $obj->FechaEnvioCorreccion ? date('d/m/Y', strtotime($obj->FechaEnvioCorreccion)) : '' }}</td>
            <td>{{ $obj->TotalDiasProceso}}</td>
            <td>{{ $obj->ResolucionFinal}}</td>
            <td>{{ $obj->ValorExtraPrima}}</td>

        </tr>
        @php($i++)
        @endforeach
    </table>
</body>

</html>