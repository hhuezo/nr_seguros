@extends ('welcome')
@section('contenido')
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<div class="x_panel">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <div class="x_title">
                <h2>Pólizas / Deuda / Póliza de deuda / Renovar póliza<small></small>
                </h2>

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
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-horizontal form-label-left">
            <form action="{{ url('polizas/deuda/renovar') }}" method="POST">
                @csrf
                <input type="hidden" id="Id" name="Id" value="{{ $deuda->Id }}">
                <div class="x_content" style="font-size: 12px;">
                    <div class="col-sm-12 row">
                        <div class="col-sm-4">
                            <input type="hidden" value="{{ $deuda->Id }}" name="Deuda">
                            <label class="control-label" align="right">Número de Póliza *</label>
                            <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text"
                                value="{{ $deuda->NumeroPoliza }}" readonly>
                        </div>

                        <div class="col-sm-4">&nbsp;</div>

                        <div class="col-sm-4" style="display: none !important;">
                            <label class="control-label" align="right">Código *</label>
                            <input class="form-control" name="Codigo" type="text" value="{{ $deuda->Codigo }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <label class="control-label" align="right">Aseguradora *</label>
                        <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%"
                            disabled>
                            <option value="">Seleccione...</option>
                            @foreach ($aseguradora as $obj)
                            @if ($obj->Id == $deuda->Aseguradora)
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
                                {{ $deuda->planes && $obj->Id == $deuda->planes->Producto ? 'selected' : '' }}>
                                {{ $obj->Nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Planes *</label>
                        <select name="Planes" id="Planes" class="form-control select2" style="width: 100%" disabled>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($planes as $obj)
                            @if ($obj->Id == $deuda->Plan)
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
                            @if ($obj->Id == $deuda->Asegurado)
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
                            value="{{ $deuda->Nit }}" readonly>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Vigencia Desde</label>
                        <input class="form-control" type="date" value="{{ $deuda->VigenciaDesde }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Vigencia Hasta</label>
                        <input class="form-control" type="date" value="{{ $deuda->VigenciaHasta }}" readonly>
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
                            @if ($obj->Id == $deuda->Ejecutivo)
                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}
                            </option>
                            @else
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Descuento de Rentabilidad *</label>
                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento"
                            value="{{ $deuda->Descuento }}" required>
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label" align="right">Edad Máxima Terminación *</label>
                        <input type="number" name="EdadMaximaTerminacion" class="form-control" required
                            value="{{ $deuda->EdadMaximaTerminacion }}">
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Responsabilidad Máxima *</label>
                        <div class=" form-group has-feedback">
                            <input type="text" name="ResponsabilidadMaxima" id="ResponsabilidadMaxima"
                                style="padding-left: 15%;"
                                value="{{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }}"
                                oninput="this.value = this.value.replace(/[^0-9.,]/g, '')" class="form-control"
                                required onblur="formatResponsabilidadMax(this)">
                            {{-- <input type="text" step="any" style="padding-left: 15%; display: block;"
                                    id="ResponsabilidadMaximaTexto"
                                    value="{{ number_format($deuda->ResponsabilidadMaxima, 2, '.', ',') }}"
                            class="form-control" required onfocus="ResponsabilidadMaxTexto(this.value)"> --}}
                            <span class="fa fa-dollar form-control-feedback left" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label" align="right">Tasa Millar Mensual *</label>
                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any"
                            value="{{ $deuda->Tasa }}" required>
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label" align="right">% de Comisión *</label>
                        <input class="form-control" name="TasaComision" id="TasaComision" type="number"
                            step="any" value="{{ $deuda->TasaComision }}">
                    </div>


                    <div class="col-sm-4"><br>
                        <label class="control-label" align="right">¿IVA incluído?</label><br>
                        <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch"
                            {{ $deuda->ComisionIva == 1 ? 'checked' : '' }}>
                    </div>



                    <div class="col-sm-4">
                        <label class="control-label " align="right">Clausulas Especiales </label>
                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4">{{ $deuda->ClausulasEspeciales }} </textarea>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Beneficios Adicionales</label>
                        <textarea class="form-control" name="Beneficios" row="3" col="4">{{ $deuda->Beneficios }} </textarea>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Concepto</label>
                        <textarea class="form-control" name="Concepto" row="3" col="4">{{ $deuda->Concepto }}</textarea>
                    </div>
                    <div class="col-sm-4 ocultar" style="display: none !important;">
                        <br>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="radio" name="tipoTasa" id="Mensual" value="1"
                                {{ $deuda->Mensual == 1 ? 'checked' : '' }}>
                            <label class="control-label">Tasa Millar Mensual *</label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="radio" name="tipoTasa" id="Anual" value="0"
                                {{ $deuda->Mensual == 0 ? 'checked' : '' }}>
                            <label class="control-label">Tasa ‰ Millar Anual *</label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>

                    <div class="col-sm-4" align="center" style="display:show">
                        <br>
                        <label class="control-label" align="center">Vida</label>
                        <input id="Vida" name="checkedVida" type="checkbox" class="js-switch"
                            {{ $deuda->PolizaVida != '' ? 'checked' : '' }} />
                    </div>
                    <div class="col-sm-4" align="center" style="display:show">
                        <br>
                        <label class="control-label" align="center">Desempleo</label>
                        <input id="Desempleo" name="checkedDesempleo" type="checkbox" class="js-switch"
                            {{ $deuda->PolizaDesempleo != '' ? 'checked' : '' }} />
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>

                    <div class="col-sm-4" style="display:  {{ $deuda->PolizaVida != '' ? 'block' : 'none' }};" id="poliza_vida">
                        <label class="control-label">Numero de Poliza Vida *</label>
                        <input name="Vida" type="text" class="form-control" value="{{ $deuda->vidas->NumeroPoliza }}" readonly />
                    </div>
                    <div class="col-sm-4">
                        <div id="poliza_desempleo"  style="display:  {{ $deuda->PolizaDesempleo != '' ? 'block' : 'none' }};">
                            <label class="control-label">Número de Póliza Desempleo *</label>
                            <input name="Desempleo" type="text" class="form-control"  value="{{ $deuda->desempleos->NumeroPoliza }}" readonly />
                        </div>

                    </div>
                    <br>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>


                    <div class="col-sm-12">
                    </div>
                    <div style="background-color: #e9f1f4 !important; height: 80px; border-radius: 10px;" class="col-sm-12">

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
                </div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <br>
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <a href="{{ url('polizas/deuda') }}"><button type="button"
                                class="btn btn-primary">Cancelar</button></a>
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
                            @if($historico_poliza->count() == 0)
                            <i class="fa fa-trash fa-lg"
                                data-target="#modal-delete-inicial-{{ $registroInicial->Id }}"
                                data-toggle="modal"></i>
                            @endif
                        </td>
                    </tr>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"
                        tabindex="-1" id="modal-delete-inicial-{{ $registroInicial->Id }}">

                        <form method="POST"
                            action="{{ url('eliminar_renovacion', $obj->Id) }}">
                            @method('post')
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Eliminar Registros holi</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Confirme si desea Eliminar el Registro</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit"
                                            class="btn btn-primary">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    @foreach ($historico_poliza as $obj)
                    <tr @if($obj->TipoRenovacion == 1) style="background-color: #e8f5ee;" @endif>
                        <td>{{ $obj->TipoRenovacion == 1 ? 'Anual' : 'Parcial' }}</td>
                        <td>{{ $obj->FechaDesdeRenovacion ? date('d/m/Y', strtotime($obj->FechaDesdeRenovacion)) : '' }}</td>
                        <td>{{ $obj->FechaHastaRenovacion ? date('d/m/Y', strtotime($obj->FechaHastaRenovacion)) : '' }}</td>
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
                            action="{{ url('eliminar_renovacion', $obj->Id) }}">
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
                                        <button type="submit"
                                            class="btn btn-primary">Confirmar</button>
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




<script>
    $(document).ready(function() {
        //mostrar opcion en menu
        displayOption("ul-poliza", "li-poliza-deuda");

        $("#Vida").change(function() {
            if (document.getElementById('Vida').checked == true) {
                $('#poliza_vida').show();
            } else {
                $('#poliza_vida').hide();
            }
        })

        $("#Desempleo").change(function() {
            if (document.getElementById('Desempleo').checked == true) {
                $('#poliza_desempleo').show();
            } else {
                $('#poliza_desempleo').hide();
            }
        })
    })
    document.addEventListener("DOMContentLoaded", function() {
        toggleFechaHasta();
    });

    function formatResponsabilidadMax(input) {
        let value = parseFloat(input.value.replace(/,/g, '')); // Elimina comas y convierte a número
        if (!isNaN(value)) {
            input.value = value.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } else {
            input.value = ''; // Si no es un número válido, lo deja vacío
        }
    }

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