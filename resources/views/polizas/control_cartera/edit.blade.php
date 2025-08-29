@extends ('welcome')
@section('contenido')

    <!-- Toastr CSS -->
    <link href="{{ asset('vendors/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('vendors/toast/toastr.min.js') }}"></script>
    <div class="x_panel">
        <div class="clearfix"></div>

        @if (session('success'))
            <script>
                toastr.success("{{ session('success') }}");
            </script>
        @endif

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
        @endif


        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>Control de flujo de carteras</h2>
                    <ul class="nav navbar-right panel_toolbox">

                    </ul>
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

                <div class="x_content">
                    <br />

                    {{-- <form method="POST" action="{{ url('control_cartera') }}/{{ $poliza->Id }}">
                        @method('PUT')
                        @csrf
                        <div class="form-horizontal">
                            <input class="form-control" type="hidden" name="Tipo" value="{{ $tipo }}" readonly>
                            <input class="form-control" type="hidden" name="Anio" value="{{ $anio }}" readonly>
                            <input class="form-control" type="hidden" name="Mes" value="{{ $mes }}" readonly>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Asegurado</label>
                                    <input class="form-control" name="Asegurado"
                                        value="{{ $poliza->clientes->Nombre ?? '' }}" readonly type="text">
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Tipo póliza</label>
                                    <input class="form-control" name="TipoPoliza" value="Deuda" readonly type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Vigencia desde</label>
                                    <input class="form-control" name="VigenciaDesde" value="{{ $poliza->VigenciaDesde }}"
                                        readonly type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Vigencia hasta</label>
                                    <input class="form-control" name="VigenciaHasta" value="{{ $poliza->VigenciaHasta }}"
                                        readonly type="date">
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Cía. de seguros</label>
                                    <input class="form-control" name="Aseguradora"
                                        value="{{ $poliza->aseguradoras->Abreviatura ?? '' }}" readonly type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Póliza No</label>
                                    <input class="form-control" name="NumeroPoliza" value="{{ $poliza->NumeroPoliza }}"
                                        readonly type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Fecha recepción archivo</label>
                                    <input class="form-control" name="FechaRecepcionArchivo"
                                        value="{{ isset($poliza->control_cartera_por_mes_anio) && $poliza->control_cartera_por_mes_anio->FechaRecepcionArchivo ? $poliza->control_cartera_por_mes_anio->FechaRecepcionArchivo : '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Fecha envío a CIA</label>
                                    <input class="form-control" name="FechaEnvioCia"
                                        value="{{ isset($poliza->control_cartera) && $poliza->control_cartera_por_mes_anio->FechaEnvioCia ? $poliza->control_cartera_por_mes_anio->FechaEnvioCia : '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Trabajo efectuado</label>
                                    <input class="form-control" name="TrabajoEfectuado"
                                        value="{{ $poliza->control_cartera_por_mes_anio->TrabajoEfectuado ?? '' }}" type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Hora tarea</label>
                                    <input class="form-control" name="HoraTarea"
                                        value="{{ isset($poliza->control_cartera) && $poliza->control_cartera_por_mes_anio->HoraTarea ? $poliza->control_cartera_por_mes_anio->HoraTarea : '' }}"
                                        type="time">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Flujo asignado</label>
                                    <input class="form-control" name="FlujoAsignado"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FlujoAsignado ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Usuario</label>
                                    <input class="form-control" name="Usuario"
                                        value="{{ $poliza->control_cartera_por_mes_anio->Usuario ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Usuarios reportados</label>
                                    <input class="form-control" name="UsuariosReportados"
                                        value="{{ $poliza->control_cartera_por_mes_anio->UsuariosReportados ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Tarifa</label>
                                    <input class="form-control" name="Tarifa"
                                        value="{{ $poliza->control_cartera_por_mes_anio->Tarifa ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Prima bruta</label>
                                    <input class="form-control" name="PrimaBruta"
                                        value="{{ $poliza->control_cartera_por_mes_anio->PrimaBruta ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Extra prima</label>
                                    <input class="form-control" name="ExtraPrima"
                                        value="{{ $poliza->control_cartera_por_mes_anio->ExtraPrima ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label" align="right">Prima emitida</label>
                                    <input class="form-control" name="PrimaEmitida"
                                        value="{{ $poliza->control_cartera_por_mes_anio->PrimaEmitida ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                    <a href="{{ url('control_cartera') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>
                        </div>
                    </form> --}}

                    <form method="POST" action="{{ url('control_cartera') }}/{{ $poliza->Id }}">
                        @method('PUT')
                        @csrf
                        <div class="form-horizontal">
                            {{-- Hidden --}}
                            <input class="form-control" type="hidden" name="Tipo" value="{{ $tipo }}" readonly>
                            <input class="form-control" type="hidden" name="Anio" value="{{ $anio }}" readonly>
                            <input class="form-control" type="hidden" name="Mes" value="{{ $mes }}" readonly>

                            {{-- SOLO LECTURA --}}
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Asegurado</label>
                                    <input class="form-control" name="Asegurado"
                                        value="{{ $poliza->clientes->Nombre ?? '' }}" readonly type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Tipo póliza</label>
                                    <input class="form-control" name="TipoPoliza" value="Deuda" readonly type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Vigencia desde</label>
                                    <input class="form-control" name="VigenciaDesde" value="{{ $poliza->VigenciaDesde }}"
                                        readonly type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Vigencia hasta</label>
                                    <input class="form-control" name="VigenciaHasta" value="{{ $poliza->VigenciaHasta }}"
                                        readonly type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Cía. de seguros</label>
                                    <input class="form-control" name="Aseguradora"
                                        value="{{ $poliza->aseguradoras->Abreviatura ?? '' }}" readonly type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Póliza No</label>
                                    <input class="form-control" name="NumeroPoliza" value="{{ $poliza->NumeroPoliza }}"
                                        readonly type="text">
                                </div>
                            </div>

                            {{-- EDITABLES SEGÚN MIGRACIÓN --}}
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha recepción archivo</label>
                                    <input class="form-control" name="FechaRecepcionArchivo"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaRecepcionArchivo ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha envío a CIA</label>
                                    <input class="form-control" name="FechaEnvioCia"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaEnvioCia ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Trabajo efectuado</label>
                                    <input class="form-control" name="TrabajoEfectuado"
                                        value="{{ $poliza->control_cartera_por_mes_anio->TrabajoEfectuado ?? '' }}"
                                        type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Hora tarea</label>
                                    <input class="form-control" name="HoraTarea"
                                        value="{{ $poliza->control_cartera_por_mes_anio->HoraTarea ?? '' }}"
                                        type="time">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Flujo asignado</label>
                                    <input class="form-control" name="FlujoAsignado"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FlujoAsignado ?? '' }}"
                                        type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Usuario</label>
                                    <select name="Usuario" class="form-control">
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Usuarios reportados</label>
                                    <input class="form-control" name="UsuariosReportados"
                                        value="{{ $poliza->control_cartera_por_mes_anio->UsuariosReportados ?? '' }}"
                                        type="number" step="1">
                                </div>
                            </div>

                            {{-- MONTOS Y DECIMALES --}}
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Tarifa</label>
                                    <input class="form-control" name="Tarifa"
                                        value="{{ $poliza->control_cartera_por_mes_anio->Tarifa ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Prima bruta</label>
                                    <input class="form-control" name="PrimaBruta"
                                        value="{{ $poliza->control_cartera_por_mes_anio->PrimaBruta ?? '' }}"
                                        type="number" step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Extra prima</label>
                                    <input class="form-control" name="ExtraPrima"
                                        value="{{ $poliza->control_cartera_por_mes_anio->ExtraPrima ?? '' }}"
                                        type="number" step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Prima emitida</label>
                                    <input class="form-control" name="PrimaEmitida"
                                        value="{{ $poliza->control_cartera_por_mes_anio->PrimaEmitida ?? '' }}"
                                        type="number" step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Porcentaje comisión</label>
                                    <input class="form-control" name="PorcentajeComision"
                                        value="{{ $poliza->control_cartera_por_mes_anio->PorcentajeComision ?? '' }}"
                                        type="number" step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Comisión neta</label>
                                    <input class="form-control" name="ComisionNeta"
                                        value="{{ $poliza->control_cartera_por_mes_anio->ComisionNeta ?? '' }}"
                                        type="number" step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">IVA</label>
                                    <input class="form-control" name="Iva"
                                        value="{{ $poliza->control_cartera_por_mes_anio->Iva ?? '' }}" type="number"
                                        step="any">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Prima líquida</label>
                                    <input class="form-control" name="PrimaLiquida"
                                        value="{{ $poliza->control_cartera_por_mes_anio->PrimaLiquida ?? '' }}"
                                        type="number" step="any">
                                </div>
                            </div>

                            {{-- CAMPOS ADICIONALES --}}
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Anexo declaración</label>
                                    <select class="form-control" name="AnexoDeclaracion">
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Número Cisco</label>
                                    <input class="form-control" name="NumeroCisco"
                                        value="{{ $poliza->control_cartera_por_mes_anio->NumeroCisco ?? '' }}"
                                        type="text">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha vencimiento</label>
                                    <input class="form-control" name="FechaVencimiento"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaVencimiento ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Reproceso Nr</label>
                                    <select class="form-control" name="RepocesoNr">
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha envío corrección</label>
                                    <input class="form-control" name="FechaEnvioCorreccion"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaEnvioCorreccion ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha seguimiento cobro</label>
                                    <input class="form-control" name="FechaSeguimientoCobro"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaSeguimientoCobro ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha reporte CIA</label>
                                    <input class="form-control" name="FechaReporteCia"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaReporteCia ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Fecha aplicación</label>
                                    <input class="form-control" name="FechaAplicacion"
                                        value="{{ $poliza->control_cartera_por_mes_anio->FechaAplicacion ?? '' }}"
                                        type="date">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <label class="control-label">Comentarios</label>
                                    <textarea class="form-control" name="Comentarios" rows="3">{{ $poliza->control_cartera_por_mes_anio->Comentarios ?? '' }}</textarea>
                                </div>
                            </div>

                            {{-- BOTONES --}}
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                    <a href="{{ url('control_cartera') }}"><button type="button"
                                            class="btn btn-primary">Cancelar</button></a>
                                </div>
                            </div>
                        </div>
                    </form>





                </div>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            //mostrar opcion en menu
            displayOption("ul-poliza", "li-control-cartera");

        });
    </script>
@endsection
