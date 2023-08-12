<div class="modal fade" id="modal_aseguradora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-tipo="1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('catalogo/negocio/aseguradora_create') }}" method="POST">
                <div class="modal-header">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="exampleModalLabel">Seleccione.... </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" id="ModalNecesidad" name="ModalNecesidad">
                            <input type="hidden" id="ModalTipoNecesidad" name="ModalTipoNecesidad">
                            <div class="form-group ">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Aseguradora</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="Aseguradora" id="Aseguradora" class="form-control select2 " style="width: 100%;" required>
                                        @foreach($aseguradoras as $obj)
                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Prima </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="Prima" id="Prima" value="{{ old('Prima') }}" class="form-control" required autofocus="true" step='0.01'>
                                </div>

                            </div>
                            <div class="form-group">

                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Suma Asegurada </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="SumaAsegurada" id="SumaAsegurada" value="{{ old('SumaAsegurada') }}" class="form-control" required autofocus="true" step='0.01'>
                                </div>

                            </div>
                            <div class="form-group" id="FechaNacimientos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Fecha Nacimiento </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="date" name="FechaNacimiento" id="FechaNacimiento" value="{{ old('FechaNacimiento') }}" class="form-control" autofocus="true" step='0.01'>
                                </div>

                            </div>
                            <div class="form-group" id="Generos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Genero</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select name="Genero" id="Genero" class="form-control select2 " style="width: 100%;">
                                        @foreach($genero as $obj)
                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group" id="Cantidads" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Cantidad </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="Cantidad" id="Cantidad" value="{{ old('Cantidad') }}" class="form-control" autofocus="true">
                                </div>

                            </div>
                            <div class="form-group" id="Marcas" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Marca </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Marca" id="Marca" value="{{ old('Marca') }}" class="form-control" autofocus="true">
                                </div>

                            </div>
                            <div class="form-group" id="Modelos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Modelo </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Modelo" id="Modelo" value="{{ old('Modelo') }}" class="form-control" autofocus="true">
                                </div>

                            </div>
                            <div class="form-group" id="Axos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Año </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="Axo" id="Axo" value="{{ old('Axo') }}" class="form-control" autofocus="true">
                                </div>

                            </div>
                            <div class="form-group" id="Placas" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Placa </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Placa" id="Placa" value="{{ old('Placa') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Direccions" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Direccion </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Direccion" id="Direccion" value="{{ old('Direccion') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Giros" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Giro </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Giro" id="Giro" value="{{ old('Giro') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="ValorConstruccions" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Valor Construcción </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="ValorConstruccion" id="ValorConstruccion" value="{{ old('ValorConstruccion') }}" class="form-control" step="0.01" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="ValorContenidos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Valor Contenido </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="ValorContenido" id="ValorContenido" value="{{ old('ValorContenidos') }}" class="form-control" step="0.01" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Vidas" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Vida </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="Vida" id="Vida" value="{{ old('Vida') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Dentals" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Dental </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="Dental" id="Dental" value="{{ old('Dental') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="CantidadPersonas" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Cantidad de Personas </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="CantidadPersona" id="CantidadPersona" value="{{ old('CantidadPersona') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Contributivos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Contributivo </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="Contributivo" id="Contributivo" value="{{ old('Contributivo') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="MaximoVitalicios" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Maximo Vitalicio </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="MaximoVitalicio" id="MaximoVitalicio" value="{{ old('MaximoVitalicio') }}" class="form-control" step="0.01" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="CantidadTitularess" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Cantidad Titulares </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="CantidadTitulares" id="CantidadTitulares" value="{{ old('CantidadTitulares') }}" class="form-control" step="0.01" autofocus="true">
                                </div>
                            </div>


                            <!-- familiar -->
                            @php($dep = 0)
                            <input type="hidden" value="<?php echo $dep ?>" id="ModalDepedientes">

                            <div class="form-group" id="FechaNacimientosFamiliar" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Fecha Nacimiento (Familiar) </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="date" name="FechaNacimientoFamiliar" id="FechaNacimientoFamiliar" value="{{ old('FechaNacimientoFamiliar') }}" class="form-control" autofocus="true" step='0.01'>
                                </div>

                            </div>
                            <div class="form-group" id="GenerosFamiliar" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Genero (Familiar)</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select name="GeneroFamiliar" id="GeneroFamiliar" class="form-control select2 " style="width: 100%;">
                                        @foreach($genero as $obj)
                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="form-group" id="ParentescosFamiliar" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Parentesco (Familiar)</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select name="ParentescoFamiliar" id="ParentescoFamiliar" class="form-control select2 " style="width: 100%;">
                                        @foreach($parentesco as $obj)
                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group" id="VidasFamiliar" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Vida </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="VidaFamiliar" id="VidaFamiliar" value="{{ old('VidaFamiliar') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="DentalsFamiliar" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Dental </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="DentalFamiliar" id="DentalFamiliar" value="{{ old('DentalFamiliar') }}" class="form-control" autofocus="true">
                                </div>
                            </div>

                            <!-- familiar -->



                            <div class="form-group" id="Fumadors" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Fumador </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="Fumador" id="Fumador" value="{{ old('Fumador') }}" class="form-control" autofocus="true">
                                </div>
                            </div>

                            <div class="form-group" id="InvalidezParcials" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Invalidez Parcial </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="InvalidezParcial" id="InvalidezParcial" value="{{ old('InvalidezParcial') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="InvalidezTotals" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Invalidez Total </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="InvalidezTotal" id="InvalidezTotal" value="{{ old('InvalidezTotals') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="GastosFunerarios" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Gastos Funerarios </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="GastosFunerario" id="GastosFunerario" value="{{ old('GastosFunerario') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="EnfermedadesGraves" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Enfermedades Graves </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="EnfermedadesGrave" id="EnfermedadesGrave" value="{{ old('EnfermedadesGrave') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Terminos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Termino </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Termino" id="Termino" value="{{ old('Termino') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Ahorros" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Ahorros </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" name="Ahorro" id="Ahorro" value="{{ old('Ahorro') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Plazos" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Plazos </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="Plazo" id="Plazo" value="{{ old('Plazo') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="SesionBeneficios" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Sesion de Beneficios </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" name="SesionBeneficio" id="SesionBeneficio" value="{{ old('SesionBeneficio') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="Coberturas" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Coberturas</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select name="Cobertura" id="Cobertura" class="form-control select2 " style="width: 100%;">
                                        @foreach($cobertura as $obj)
                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group" id="Traslados" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Traslado </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="Traslado" id="Traslado" value="{{ old('GastosTrasladoFunerario') }}" class="form-control" autofocus="true">
                                </div>
                            </div>
                            <div class="form-group" id="TipoCarteras" style="display: none;">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12" style="text-align: left;">Tipo Cartera</label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select name="TipoCartera" id="TipoCartera" class="form-control select2 " style="width: 100%;">
                                        @foreach($tipo_cartera as $obj)
                                        <option value="{{$obj->Id}}">{{$obj->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <br>
                <div class="clearfix"></div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn_modal" class="btn btn-primary">Aceptar</button>
                </div>
            </form>

        </div>
    </div>
</div>



<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $("#btn_modal").click(function() {
        var parametros = {
            "_token": "{{ csrf_token() }}",

            "Prima": document.getElementById('Prima').value,
            "NecesidadProteccion": document.getElementById('ModalNecesidad').value,
            "TipoNecesidad": document.getElementById('ModalTipoNecesidad').value,
            "Aseguradora": document.getElementById('Aseguradora').value,
            "SumaAsegurada": document.getElementById('SumaAsegurada').value,
            "FechaNacimiento": document.getElementById('FechaNacimiento').value,
            "Genero": document.getElementById('Genero').value,
            "Cantidad": document.getElementById('Cantidad').value,
            "Marca": document.getElementById('Marca').value,
            "Modelo": document.getElementById('Modelo').value,
            "Axo": document.getElementById('Axo').value,
            "Placa": document.getElementById('Placa').value,
            "Direccion": document.getElementById('Direccion').value,
            "Giro": document.getElementById('Giro').value,
            "ValorConstruccion": document.getElementById('ValorConstruccion').value,
            "ValorContenido": document.getElementById('ValorContenido').value,
            "Dental": document.getElementById('Dental').value,
            "Vida": document.getElementById('Vida').value,
            "CantidadPersona": document.getElementById('CantidadPersona').value,
            "Contributivo": document.getElementById('Contributivo').value,
            "MaximoVitalicio": document.getElementById('MaximoVitalicio').value,
            "CantidadTitulares": document.getElementById('CantidadTitulares').value,
            "Fumador": document.getElementById('Fumador').value,
            "InvalidezParcial": document.getElementById('InvalidezParcial').value,
            "InvalidezTotal": document.getElementById('InvalidezTotal').value,
            "GastosFunerarios": document.getElementById('GastosFunerarios').value,
            "EnfermedadesGraves": document.getElementById('EnfermedadesGraves').value,
            "Termino": document.getElementById('Termino').value,
            "Ahorro": document.getElementById('Ahorro').value,
            "Plazo": document.getElementById('Plazo').value,
            "SesionBeneficio": document.getElementById('SesionBeneficio').value,
            "Cobertura": document.getElementById('Cobertura').value,
            "Traslado": document.getElementById('Traslado').value,
            "TipoCartera": document.getElementById('TipoCartera').value,
        };
        $.ajax({
            type: "get",
            url: "{{ url('catalogo/negocios/store_aseguradora') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                if (document.getElementById('DataAseguradora').value == "") {
                    document.getElementById('DataAseguradora').value = data;
                } else {
                    document.getElementById('DataAseguradora').value = document.getElementById(
                        'DataAseguradora').value + "," + data;
                }
                $('#modal_aseguradora').modal('hide');

                Swal.fire({
                    title: 'Exito!',
                    text: 'Su asegurada fue agregada a la cotizacion',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    timer: 1500
                })
                get_aseguradoras();

            }
        });
    });


    function get_aseguradoras() {
        var parametros = {
            "ModalAseguradora": document.getElementById('DataAseguradora').value,
        };
        console.log(parametros);

        $.ajax({
            type: "get",
            url: "{{ url('catalogo/negocio/get_aseguradora') }}",
            data: parametros,
            success: function(data) {
                console.log(data);
                $('#divAseguradoras').html(data);
            }
        });
    }
</script>
