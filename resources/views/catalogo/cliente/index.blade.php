@extends ('welcome')
@section('contenido')
<div class="x_panel" style="background-image:url('dentco-html/images/LOGO_app.png'); background-repeat: no-repeat; background-size: 30% ; background-position-x:right ; background-position-y:bottom ;">

    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h3>Listado de clientes </h3>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('catalogo/cliente/create/') }}"><button class="btn btn-info float-right"> <i class="fa fa-plus"></i> Nuevo</button></a>
        </div>
        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <table id="example" class="table table-striped table-bordered" >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre o <br> Razón Social</th>
                        <th>DUI/NIT</th>
                        <th>Dirección de <br>Correspondencia</th>
                        <th>Teléfono Principal</th>
                        <th>Correo Electrónico <br>Principal</th>
                        <th>Vinculado al Grupo o Referencia</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $obj)
                    <tr>
                        <td>{{ $obj->Id }}</td>
                        <td>{{ $obj->Nombre }}</td>
                        @if($obj->TipoPersona == 1)
                        <td>{{$obj->Dui}}</td>
                        @else
                        <td>{{$obj->Nit}}</td>
                        @endif
                        <td>{{$obj->DireccionCorrespondencia}}</td>
                        <td>{{ $obj->TelefonoCelular }}</td>
                        <td>{{$obj->CorreoPrincipal}}</td>
                        <td>{{$obj->Referencia}}</td>
                       
                        <td align="center">

                            @can('edit users')
                            <a href="{{ url('catalogo/cliente') }}/{{ $obj->Id }}/edit" class="on-default edit-row">
                                <i class="fa fa-pencil fa-lg"></i></a>
                            @endcan
                            @can('delete users')
                            @if ($obj->Activo == 1)
                            &nbsp;&nbsp;<a href="" data-target="#modal-delete-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-trash fa-lg"></i></a>
                            @else
                            &nbsp;&nbsp;<a href="" data-target="#modal-active-{{ $obj->Id }}" data-toggle="modal"><i class="fa fa-check-square fa-lg"></i></a>
                            @endif

                            @endcan
                        </td>
                    </tr>
                    @include('catalogo.cliente.modal')
                    @include('catalogo.cliente.active')
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@include('sweetalert::alert')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js"></script>
<script>
$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');    
 
    var table = $('#example').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
 
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        },
    });
});

</script>


@endsection