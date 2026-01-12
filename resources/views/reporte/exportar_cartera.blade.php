<table>
    <tr>
        <th> NIT </th>
        <th> DUI </th>
        <th> PASAPORTE </th>
        <th> SALVADOREÑO </th>
        <th> FECHA NACIMIENTO </th>
        <th> TIPO DE PERSONA </th>
        <th> PRIMER APELLIDO </th>
        <th> SEGUNDO APELLIDO </th>
        <th> APELLIDO CASADA </th>
        <th> PRIMER NOMBRE </th>
        <th> SEGUNDO NOMBRE </th>
        <th> NOMBRE SOCIEDAD </th>
        <th> TASA</th>
        <th> SEXO </th>
        <th> FECHA DE OTORGAMIENTO </th>
        <th> FECHA DE VENCIMIENTO </th>
        <th> OCUPACION </th>
        <th>EDAD</th>
        <th>EDAD DESEMBLOSO</th>
        <th> No DE REFERENCIA DEL CRÉDITO </th>
        <th> MONTO OTORGADO DEL CREDITO </th>
        <th> SALDO VIGENTE DE CAPITAL </th>
        <th> INTERESES </th>
        <th> INTERESES MORATORIOS </th>
        <th> INTERESES COVID</th>
        <th> MONTO NOMINAL</th>
        <th> SALDO TOTAL </th>

    </tr>
    @foreach($cartera as $obj)
    <tr>

        <td>{{$obj->Nit}}</td>
        <td>{{$obj->Dui}}</td>
        <td>{{$obj->Pasaporte}}</td>
        <td>{{$obj->Nacionalidad}}</td>
        <td>{{$obj->FechaNacimiento}}</td>
        <td>{{$obj->TipoPersona == 'N' ? 'Natural': 'Apoderado'}}</td>
        <td>{{$obj->PrimerApellido}}</td>
        <td>{{$obj->SegundoApellido}}</td>
        <td>{{$obj->ApellidoCasada}}</td>
        <td>{{$obj->PrimerNombre}}</td>
        <td>{{$obj->SegundoNombre}}</td>
        <td>{{$obj->NombreSociedad}}</td>
        <td>{{$obj->Tasa}}</td>
        <td>{{$obj->Sexo == 'M' ? 'Masculino' : 'Femenino'}}</td>
        <td>{{$obj->FechaOtorgamiento}}</td>
        <td>{{$obj->FechaVencimiento}}</td>
        <td>{{$obj->Ocupacion}}</td>
        <td>{{$obj->Edad}}</td>
        <td>{{$obj->EdadDesembloso}}</td>
        <td>{{$obj->NumeroReferencia}}</td>
        <td>{{$obj->MontoOtorgado}}</td>
        <td>{{$obj->SaldoCapital}}</td>
        <td>{{$obj->Intereses}}</td>
        <td>{{$obj->InteresesMoratorios}}</td>
        <td>{{$obj->InteresesCovid}}</td>
        <td>{{$obj->MontoNominal}}</td>
        <td>{{$obj->SaldoTotal}}</td>


    </tr>
    @endforeach
</table>
