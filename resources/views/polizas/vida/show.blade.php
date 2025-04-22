@extends ('welcome')
@section('contenido')

@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div id="loading-overlay" style="display: none">
        <img src="{{ asset('img/ajax-loader.gif') }}" alt="Loading..." />
    </div>


    <div class="x_title">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h4>Polizas de Vida </h4>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12" align="right">
            <a href="{{ url('polizas/desempleo/') }}"><button class="btn btn-info float-right"> <i
                        class="fa fa-arrow-left"></i></button></a>
        </div>
        <div class="clearfix"></div>
    </div>

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
        <li class="nav-item {{ isset($tab) && $tab == 1 ? 'active in' : '' }}">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Datos de <br>Póliza</a>
        </li>
        <li class="nav-item {{ isset($tab) && $tab == 2 ? 'active in' : '' }}">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                aria-controls="profile" aria-selected="false">Generar <br> cartera</a>
        </li>
        <li class="nav-item {{ isset($tab) && $tab == 3 ? 'active in' : '' }}">
            <a class="nav-link" id="extra-prima-tab" data-toggle="tab" href="#extra-prima" role="tab" aria-controls="extra-prima"
                aria-selected="false">Extra Prima <br> {{ $poliza_vida->NumeroPoliza }}</a>
        </li>
        <li class="nav-item {{ isset($tab) && $tab == 4 ? 'active in' : '' }}">
            <a class="nav-link" id="hoja-tab" data-toggle="tab" href="#hoja" role="tab" aria-controls="hoja"
                aria-selected="false">Hoja de Cartera <br> {{ $poliza_vida->NumeroPoliza }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos"
                aria-selected="false">Estado <br> de Pago</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                aria-controls="contact" aria-selected="false">Ver <br> Aviso</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade {{ isset($tab) && $tab == 1 ? 'active in' : '' }}" id="home" role="tabpanel"
            aria-labelledby="home-tab">

            <br><br>
            <!-- Número de Póliza -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Número de Póliza</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->NumeroPoliza }}" readonly>
            </div>

            <!-- Aseguradora -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Aseguradora</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->aseguradora->Nombre }}" readonly>
            </div>

            <!--Productos -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Productos</label>
                <input type="text" class="form-control"
                    value="{{ $poliza_vida->planes && $poliza_vida->planes->productos ? $poliza_vida->planes->productos->Nombre : '' }}"
                    readonly>
            </div>

            <!-- Plan -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Planes</label>
                <input type="text" class="form-control"
                    value="{{ $poliza_vida->planes ? $poliza_vida->planes->Nombre : '' }}" readonly>
            </div>

            <!-- Asegurado -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Asegurado</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->cliente->Nombre }}" readonly>
            </div>


            <!-- Nit -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Nit</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->cliente->Nit }}" readonly>
            </div>

            <!-- Ejecutivo -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Ejecutivo</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->ejecutivo->Nombre }}" readonly>
            </div>

            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                <label class="control-label" align="right">Tipo cobro</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->tipoCobro->Nombre }}" readonly>
            </div>

            <!-- Vigencia Desde -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Vigencia Desde</label>
                <input type="date" class="form-control" value="{{ $poliza_vida->VigenciaDesde }}" readonly>
            </div>

            <!-- Vigencia Hasta -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Vigencia Hasta</label>
                <input type="date" class="form-control" value="{{ $poliza_vida->VigenciaHasta }}" readonly>
            </div>

            <!-- Edad Máxima de Inscripción -->
            <div class="form-group col-sm-12 col-md-6 col-lg-6">
                <label>Edad Máxima de Inscripción</label>
                <input type="text" class="form-control" value="{{ $poliza_vida->EdadMaximaInscripcion }}"
                    readonly>
            </div>



            <!-- Edad Terminación -->
            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                <label class="control-label" align="right">Edad Terminación</label>
                <input class="form-control" type="number" value="{{ $poliza_vida->EdadTerminacion }}" readonly>
            </div>


            <!-- Tasa Millar Mensual -->
            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                <label class="control-label" align="right">Tasa Millar Mensual</label>
                <input class="form-control" type="number" value="{{ $poliza_vida->Tasa }}" readonly>
            </div>

            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                <label class="control-label" align="right">Suma asegurada</label>
                <input class="form-control" type="number" value="{{ $poliza_vida->SumaAsegurada }}" readonly>
            </div>

            <!-- Tasa Millar Mensual -->
            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                <label class="control-label" align="right">Descuento</label>
                <input class="form-control" type="number" value="{{ $poliza_vida->Descuento }}" readonly>
            </div>

            <!-- Concepto -->
            <div class="item form-group col-sm-12 col-md-6 col-lg-6">
                <label class="control-label" align="right">Concepto</label>
                <textarea class="form-control" rows="3" cols="4" readonly>{{ $poliza_vida->Concepto }}</textarea>
            </div>


        </div>
        <div class="tab-pane fade {{ isset($tab) && $tab == 2 ? 'active in' : '' }}" id="profile" role="tabpanel"
            aria-labelledby="profile-tab">

            <ul class="nav navbar-right panel_toolbox">
                <a href="{{url('polizas/vida/subir_cartera')}}/{{$poliza_vida->Id}}">
                    <div class="btn btn-info float-right">
                        Subir Archivo Excel</div>
                </a>
            </ul>


            @include('polizas.vida.tab2')

        </div>
        <div class="tab-pane fade {{ isset($tab) && $tab == 3 ? 'active in' : '' }}" id="extra-prima" role="tabpanel" aria-labelledby="extra-prima-tab">
            <br>
            @include('polizas.vida.tab5')
        </div>
        <div class="tab-pane fade {{ isset($tab) && $tab == 4 ? 'active in' : '' }}" id="hoja" role="tabpanel" aria-labelledby="hoja-tab">

            @include('polizas.vida.tab3')
        </div>
        <div class="tab-pane fade {{ isset($tab) && $tab == 5 ? 'active in' : '' }}" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">

            @include('polizas.vida.tab4')
        </div>
        <div class="tab-pane fade " id="contact" role="tabpanel" aria-labelledby="contact-tab">


        </div>
    </div>

</div>


<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-vida");


        // Interceptar solo el formulario con id form-create-pago
        $('#form-create-pago').on('submit', function(e) {
            // Mostrar el indicador de carga dentro del modal
            $('#loading-indicator').show();

            // Deshabilitar el botón de submit para evitar envíos duplicados
            $('#btnSubirCartera').prop('disabled', true).html('Procesando...');

            // Opcional: Evitar que el modal se cierre mientras se procesa
            $('#modal_pago').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('#clientes-extra').DataTable();
        $('#clientes').DataTable();

    });


</script>

@endsection
