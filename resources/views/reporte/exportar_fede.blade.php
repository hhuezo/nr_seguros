<table>
    <tr>
        <th> DUI </th>
        <th> PRIMER APELLIDO </th>
        <th> SEGUNDO APELLIDO </th>
        <th> NOMBRES </th>
        <th> FECHA NACIMIENTO </th>
        <th> SEXO </th>
        <th> No DE REFERENCIA DEL CRÉDITO </th>
        <th> FECHA DE OTORGAMIENTO </th>
        <th> SALDO VIGENTE DE CAPITAL </th>
        <th> MONTO OTORGADO DEL CREDITO </th>
        <th> INTERESES </th>
        <th> MORA CAPITAL</th>
        <th> INTERESES MORATORIOS </th>
        <th> INTERESES COVID</th>
        <th> MONTO NOMINAL</th>
        <th> SALDO TOTAL </th>
        <th> EXTRAPRIMA</th>

    </tr>
    @foreach($cartera as $obj)
    <tr>
        
        <td>{{$obj->Dui}}</td>
        <td>{{$obj->PrimerApellido}}</td>
        <td>{{$obj->SegundoApellido}}</td>
        <td>{{$obj->PrimerNombre}}</td>
        <td>{{$obj->FechaNacimiento}}</td>
        <td>{{$obj->Sexo == 'M' ? 'Masculino' : 'Femenino'}}</td>
        <td>{{$obj->NumeroReferencia}}</td>
        <td>{{$obj->FechaOtorgamiento}}</td>
        <td>{{$obj->MontoOtorgado}}</td>
        <td>{{$obj->SaldoCapital}}</td>
        <td>{{$obj->Intereses}}</td>
        <td>{{$obj->MoraCapital}}</td>
        <td>{{$obj->InteresesMoratorios}}</td>
        <td>{{$obj->InteresesCovid}}</td>
        <td>{{$obj->MontoNominal}}</td>
        <td>{{$obj->SaldoTotal}}</td>
        <td>0</td>

       
    </tr>
    @endforeach
</table>