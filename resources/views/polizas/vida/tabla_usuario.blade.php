@if ($usuario_vidas)
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Numero Usuarios</th>
                <th>Suma Asegurada</th>
                <th>MontoCartera</th>
                <th>Tasa</th>
                <th>Sub Total Asegurado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @php($totalUsuario = 0)
            @php($montocartera = 0)
            @php($subtotal = 0)
            @foreach ($usuario_vidas as $obj)
                <tr>
                    <td>{{ $obj->NumeroUsuario }}</td>
                    <td>{{ $obj->SumaAsegurada }}</td>
                    <td>{{ $obj->SubTotalAsegurado }}</td>
                    <td>{{ $obj->Tasa }}</td>
                    <td>{{ $obj->TotalAsegurado }}</td>
                    <td>
                        <a onclick="edit_usuario({{$obj->Id}},{{$obj->Tasa}},{{$obj->Poliza}},{{$obj->NumeroUsuario}},
                        {{$obj->SumaAsegurada}},{{$obj->SubTotalAsegurado}},{{$obj->TotalAsegurado}})" data-toggle="modal"
                            class="on-default edit-row">
                            <i class="fa fa-pencil fa-lg"></i></a>
                        &nbsp;&nbsp;<a  onclick="delete_usuario({{$obj->Id}})" data-toggle="modal"><i
                                class="fa fa-trash fa-lg"></i></a>
                    </td>

                    @php($totalUsuario += $obj->NumeroUsuario)
                    @php($montocartera += $obj->SubTotalAsegurado)
                    @php($subtotal += $obj->TotalAsegurado)
                </tr>
               

              
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Totales{{ $totalUsuario }}</td>
                <td></td>
                <td>${{ $montocartera }}</td>
                <td></td>
                <td>${{ $subtotal }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>


    <script>
        document.getElementById('MontoCartera').value = <?php echo $montocartera; ?>;
        document.getElementById('SubTotal').value = <?php echo $subtotal; ?>;
    </script>

@endif


