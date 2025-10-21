@extends ('welcome')
@section('contenido')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="x_panel">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">

                <div class="x_title">
                    <h2>RESI - Poliza de Residencia Renovar o Cancelar Poliza <small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <a href="{{ url('polizas/residencia') }}" class="btn btn-info fa fa-undo " style="color: white">
                            Atrás</a>
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


                <form method="POST" action="{{ url('polizas/residencia/renovar') }}">

                    @csrf
                    <div class="x_content" style="font-size: 12px;">
                        <br />
                        <input type="hidden" id="Id" name="Id" value="{{ $residencia->Id }}">
                        <div class="x_content" style="font-size: 12px;">
                            <div class="col-sm-12 row">
                                <div class="col-sm-4">

                                    <label class="control-label" align="right">Número de Póliza *</label>
                                    <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                        value="{{ $residencia->NumeroPoliza }}" readonly>
                                </div>

                                <div class="col-sm-4">&nbsp;</div>


                            </div>
                            <div class="col-sm-8">
                                <label class="control-label" align="right">Aseguradora *</label>
                                <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%"
                                    disabled>
                                    <option value="">Seleccione...</option>
                                    @foreach ($aseguradora as $obj)
                                        @if ($obj->Id == $residencia->Aseguradora)
                                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                            </option>
                                        @else
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Productos *</label>
                                <select name="Productos" id="Productos" class="form-control select2" style="width: 100%"
                                    disabled>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($productos as $obj)
                                        <option value="{{ $obj->Id }}"
                                            {{ $residencia->planes && $obj->Id == $residencia->planes->Producto ? 'selected' : '' }}>
                                            {{ $obj->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Planes *</label>
                                <select name="Planes" id="Planes" class="form-control select2" style="width: 100%"
                                    disabled>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($planes as $obj)
                                        @if ($obj->Id == $residencia->Plan)
                                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                            </option>
                                        @else
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label class="control-label" align="right">Asegurado *</label>
                                <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%"
                                    disabled>
                                    <option value="">Seleccione...</option>
                                    @foreach ($cliente as $obj)
                                        @if ($obj->Id == $residencia->Asegurado)
                                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                            </option>
                                        @else
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" align="right">DUI / NIT *</label>
                                <input class="form-control" name="Nit" id="Nit" type="text"
                                    value="{{ $residencia->Nit }}" readonly>
                            </div>
                            <div class="col-sm-12">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" align="right">Vigencia Desde</label>
                                <input class="form-control" type="date" value="{{ $residencia->VigenciaDesde }}"
                                    readonly>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" align="right">Vigencia Hasta</label>
                                <input class="form-control" type="date" value="{{ $residencia->VigenciaHasta }}"
                                    readonly>
                            </div>

                            <div class="col-sm-4" style="display: none">
                                <label class="control-label" align="right">Estado *</label>
                                <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
                                    @foreach ($estadoPoliza as $obj)
                                        @if ($obj->Id == 2)
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" align="right">Ejecutivo *</label>
                                <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($ejecutivo as $obj)
                                        @if ($obj->Id == $residencia->Ejecutivo)
                                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                                            </option>
                                        @else
                                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label class="control-label">Descuento de Rentabilidad % *</label>
                                <div class="form-group has-feedback">
                                    <input type="text" step="any" name="TasaDescuento" id="TasaDescuento"
                                        class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                        value="{{ $residencia->TasaDescuento }}" required>
                                    <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                                </div>
                            </div>


                            <div class="col-sm-4">

                                <label class="control-label">Límite de Grupo *</label>
                                <div class="form-group has-feedback">
                                    <input type="text" name="LimiteGrupo" id="LimiteGrupo"
                                        class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                        value="{{ $residencia->LimiteGrupo }}" oninput="validarNumero(this)" required>
                                    <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label class="control-label">Límite Individual *</label>
                                <div class="form-group has-feedback">
                                    <input type="text" name="LimiteIndividual" id="LimiteIndividual"
                                        class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                        value="{{ $residencia->LimiteIndividual }}" oninput="validarNumero(this)"
                                        required>
                                    <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <label class="control-label">Tasa % *</label>
                                <div class="form-group has-feedback">
                                    <input type="text" step="any" name="Tasa" id="Tasa"
                                        class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                        value="{{ $residencia->Tasa }}" required>
                                    <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label class="control-label">Porcentaje de comisión *</label>
                                <div class="form-group has-feedback">
                                    <input type="text" step="any" name="TasaComision" id="TasaComision"
                                        class="form-control cantidad-texto" style="padding-left: 15%; display: block;"
                                        value="{{ $residencia->Comision }}" required>
                                    <span class="fa fa-percent form-control-feedback left" aria-hidden="true"></span>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="tipoTasaMensual" value="1"
                                            {{ $residencia->Mensual == '1' ? 'checked' : '' }} required>
                                        <label class="control-label" for="tipoTasaMensual">
                                            <i class="fa fa-calendar"></i> Tasa Millar Mensual *
                                        </label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="radio" name="tipoTasa" id="tipoTasaAnual" value="0"
                                            {{ $residencia->Mensual == '0' ? 'checked' : '' }} required>
                                        <label class="control-label" for="tipoTasaAnual">
                                            <i class="fa fa-calendar-o"></i> Tasa ‰ Millar Anual *
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                                    {{ $residencia->DescuentoIva ? 'checked' : '' }}>
                                <label class="control-label" align="right">¿IVA incluído?</label>
                            </div>


                            <div style="background-color: #e9f1f4 !important; height: 80px; border-radius: 10px;marging-top: 25px"
                                class="col-sm-12">

                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Tipo renovación</label>
                                    <select name="TipoRenovacion" id="TipoRenovacion" class="form-control"
                                        onchange="toggleFechaHasta()">
                                        <option value="1">Anual</option>
                                        <option value="2">Parcial</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Fecha inicio renovación</label>
                                    <input class="form-control" type="hidden" id="FechaDesdeRenovacionAnual"
                                        value="{{ $fechaDesdeRenovacionAnual }}" readonly>
                                    <input class="form-control" type="hidden" id="FechaDesdeRenovacionTemporal"
                                        value="{{ $fechaDesdeRenovacion }}" readonly>
                                    <input class="form-control" type="date" name="FechaDesdeRenovacion"
                                        id="FechaDesdeRenovacion" readonly>
                                </div>


                                <div class="col-sm-4">
                                    <label class="control-label" align="right">Fecha final renovación</label>
                                    <input class="form-control" type="date" name="FechaHastaRenovacion"
                                        id="FechaHastaRenovacion" readonly>
                                </div>
                            </div>




                            <div class="x_title">
                                <h2> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<small></small></h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group" align="center">

                                <button class="btn btn-success" type="submit">Aceptar</button>
                                <a href="{{ url('polizas/residencia') }}"><button class="btn btn-primary"
                                        type="button">Cancelar</button></a>
                            </div>
                        </div>



                </form>


                @if ($historico_poliza->count() > 0)
                    <div class="x_title">
                        <h2>Renovaciones</h2>
                        <div class="clearfix"></div>
                    </div>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo Renovacion</th>
                                <th>Vigencia Desde</th>
                                <th>Vigencia Hasta</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Registro inicial</td>
                                <td>{{ $registroInicial->VigenciaDesde ? date('d/m/Y', strtotime($registroInicial->VigenciaDesde)) : '' }}
                                </td>
                                <td>{{ $registroInicial->VigenciaHasta ? date('d/m/Y', strtotime($registroInicial->VigenciaHasta)) : '' }}
                                </td>
                                <td>
                                    @if ($historico_poliza->count() == 0)
                                        <i class="fa fa-trash fa-lg"
                                            data-target="#modal-delete-inicial-{{ $registroInicial->Id }}"
                                            data-toggle="modal"></i>
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"
                                tabindex="-1" id="modal-delete-inicial-{{ $registroInicial->Id }}">

                                <form method="POST" action="{{ url('polizas/residencia/eliminar_renovacion', $obj->Id) }}">
                                    @method('post')
                                    @csrf
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title">Eliminar Registros</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Confirme si desea Eliminar el Registro</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            @foreach ($historico_poliza as $obj)
                                <tr @if ($obj->TipoRenovacion == 1) style="background-color: #e8f5ee;" @endif>
                                    <td>{{ $obj->TipoRenovacion == 1 ? 'Anual' : 'Parcial' }}</td>
                                    <td>{{ $obj->FechaDesdeRenovacion ? date('d/m/Y', strtotime($obj->FechaDesdeRenovacion)) : '' }}
                                    </td>
                                    <td>{{ $obj->FechaHastaRenovacion ? date('d/m/Y', strtotime($obj->FechaHastaRenovacion)) : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($loop->last)
                                            <i class="fa fa-trash fa-lg"
                                                data-target="#modal-delete-documento-{{ $obj->Id }}"
                                                data-toggle="modal"></i>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"
                                    tabindex="-1" id="modal-delete-documento-{{ $obj->Id }}">

                                    <form method="POST"
                                        action="{{ url('polizas/residencia/eliminar_renovacion', $obj->Id) }}">
                                        @method('post')
                                        @csrf
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title">Eliminar Registros </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirme si desea Eliminar el Registro</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>


        </div>

    </div>
    </div>

    </div>
    @include('sweetalert::alert')
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            toggleFechaHasta();
        })

        function toggleFechaHasta() {
            var tipoRenovacion = document.getElementById("TipoRenovacion").value;
            var fechaInicioAnual = document.getElementById("FechaDesdeRenovacionAnual").value;
            var fechaInicioTemporal = document.getElementById("FechaDesdeRenovacionTemporal").value;
            var campoFechaFin = document.getElementById("FechaHastaRenovacion");

            if (tipoRenovacion == "2") {
                // Habilitar el campo de fecha de fin para renovación temporal
                campoFechaFin.removeAttribute("readonly");

                // Establecer la fecha de inicio a la fecha de inicio temporal
                document.getElementById("FechaDesdeRenovacion").value = fechaInicioTemporal;
                campoFechaFin.value = fechaInicioTemporal;
            } else {
                // Hacer el campo de fecha de fin de solo lectura para renovación anual
                campoFechaFin.setAttribute("readonly", true);

                if (fechaInicioAnual) {
                    // Calcular la fecha de fin un año después de la fecha de inicio
                    var fechaInicio = new Date(fechaInicioAnual);
                    fechaInicio.setFullYear(fechaInicio.getFullYear() + 1); // Sumar un año
                    fechaInicio.setDate(fechaInicio.getDate() + 1); // Sumar 1 día para año exacto

                    var mes = (fechaInicio.getMonth() + 1).toString().padStart(2, '0'); // Formato MM
                    var dia = fechaInicio.getDate().toString().padStart(2, '0'); // Formato DD
                    campoFechaFin.value = fechaInicio.getFullYear() + "-" + mes + "-" + dia; // Formato YYYY-MM-DD

                    // Establecer la fecha de inicio para renovación anual
                    document.getElementById("FechaDesdeRenovacion").value = fechaInicioAnual;
                } else {
                    campoFechaFin.value = ""; // Limpiar la fecha de fin si no hay fecha de inicio
                }
            }
        }


        function validarNumero(input) {
            let valor = input.value;

            // ✅ Permitir solo números, puntos y comas
            valor = valor.replace(/[^0-9.,]/g, '');

            // ✅ Evitar más de un separador decimal
            const partes = valor.split(/[.,]/);
            if (partes.length > 2) {
                toastr.error('Solo se permite un separador decimal (punto o coma).', 'Error');
                valor = partes[0] + '.' + partes[1]; // conservar solo el primer separador
            }

            input.value = valor;
        }


        document.getElementById("FechaHastaRenovacion").addEventListener("blur", function() {
            var fechaDesde = document.getElementById("FechaDesdeRenovacion").value;
            var fechaHasta = document.getElementById("FechaHastaRenovacion").value;

            if (fechaDesde && fechaHasta) {
                var inicio = new Date(fechaDesde);
                var fin = new Date(fechaHasta);

                var maxFecha = new Date(fechaDesde);
                maxFecha.setFullYear(maxFecha.getFullYear() + 1); // Sumar 1 año
                maxFecha.setDate(maxFecha.getDate() - 1); // Restar 1 día

                // Validar que FechaHasta no sea mayor que la FechaDesde
                if (fin <= inicio) {
                    alert("La fecha de fin no puede ser igual o anterior a la fecha de inicio.");
                    document.getElementById("FechaHastaRenovacion").value = ""; // Limpiar la fecha incorrecta
                    return; // Salir de la función si la validación falla
                }

                // Validar que FechaHasta no supere un año desde FechaDesde
                if (fin > maxFecha) {
                    alert("La fecha de fin no puede superar un año desde la fecha de inicio.");
                    document.getElementById("FechaHastaRenovacion").value = ""; // Limpiar la fecha incorrecta
                }
            }
        });
    </script>

@endsection
