@if ($requisitos)
<table class="table table-striped jambo_table bulk_action">
    <thead>
        <tr class="headings">
            <th class="column-title">Requisitos</th>
            <th class="column-title">&nbsp; </th>
            <th class="column-title">&nbsp;</th>
            <th class="column-title">&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($requisitos as $requisito)
        <tr class="even pointer">
            <td class=" ">{{$requisito->Requisito}}</td>
            <td align="center"><strong>Hasta los {{$requisito->EdadFinal}} Años  <strong> <br>
                Desde ${{$requisito->MontoInicial}} <br> Hasta  ${{$requisito->MontoFinal}}
            </td>
            <td align="center">
                @if ($requisito->EdadFinal2 == "")
                   
                @else
                <strong>De {{$requisito->EdadInicial2}} Hasta {{$requisito->EdadFinal2}} Años  <strong> <br>
                    Desde ${{$requisito->MontoInicial2}} <br> Hasta  ${{$requisito->MontoFinal2}}
                </td>
                @endif
            </td>
            <td align="center">
                @if ($requisito->EdadFinal3 == "")
                   
                @else
                <strong>De {{$requisito->EdadInicial3}} Hasta {{$requisito->EdadFinal3}} Años  <strong> <br>
                    Desde ${{$requisito->MontoInicial3}} <br> Hasta  ${{$requisito->MontoFinal3}}
                </td>
                @endif
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@endif

