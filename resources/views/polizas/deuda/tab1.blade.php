<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Datos de Poliza
                </button>
            </h2>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                <div class="x_content" style="font-size: 12px;">
                    <div class="col-sm-12 row">
                        <div class="col-sm-4">
                            <input type="hidden" value="{{$deuda->Id}}" name="Deuda">
                            <label class="control-label" align="right">Número de Póliza</label>
                            <input class="form-control" name="NumeroPoliza" id="NumeroPoliza" type="text" value="{{ $deuda->NumeroPoliza }}" required>
                        </div>

                        <div class="col-sm-4">&nbsp;</div>

                        <div class="col-sm-4" style="display: none !important;">
                            <label class="control-label" align="right">Código</label>
                            <input class="form-control" name="Codigo" type="text" value="{{ $deuda->Codigo}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <label class="control-label" align="right">Aseguradora</label>
                        <select name="Aseguradora" id="Aseguradora" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                            @foreach ($aseguradora as $obj)
                            @if($obj->Id == $deuda->Aseguradora)
                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                            @else
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Productos</label>
                        <select name="Productos" id="Productos" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($productos as $obj)
                            @if($obj->Id == $deuda->planes->Producto)
                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                            @else
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Planes</label>
                        <select name="Planes" id="Planes" class="form-control select2" style="width: 100%">
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach ($planes as $obj)
                            @if($obj->Id == $deuda->Plan)
                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                            @else
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <label class="control-label" align="right">Asegurado</label>
                        <select name="Asegurado" id="Asegurado" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                            @foreach ($cliente as $obj)
                            @if($obj->Id == $deuda->Asegurado)
                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                            @else
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Nit</label>
                        <input class="form-control" name="Nit" id="Nit" type="text" value="{{ $deuda->Nit }}" readonly>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Vigencia Desde</label>
                        <input class="form-control" name="VigenciaDesde" id="VigenciaDesde" type="date" value="{{ $deuda->VigenciaDesde }}" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Vigencia Hasta</label>
                        <input class="form-control" name="VigenciaHasta" id="VigenciaHasta" type="date" value="{{ $deuda->VigenciaHasta }}" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Estatus</label>
                        <select name="EstadoPoliza" class="form-control select2" style="width: 100%">
                            @foreach ($estadoPoliza as $obj)
                            @if ($obj->Id == 1)
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Ejecutivo</label>
                        <select name="Ejecutivo" class="form-control select2" style="width: 100%" required>
                            <option value="">Seleccione...</option>
                            @foreach ($ejecutivo as $obj)
                            @if ($obj->Id == $deuda->Ejecutivo)
                            <option value="{{ $obj->Id }}" selected>{{ $obj->Nombre }}</option>
                            @else
                            <option value="{{ $obj->Id }}">{{ $obj->Nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Descuento de Rentabilidad %</label>
                        <input class="form-control" name="Descuento" type="number" step="any" id="Descuento" value="{{ $deuda->Descuento }}" required>
                    </div>
                    <div class="col-sm-4">
                        &nbsp;
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label " align="right">Clausulas Especiales</label>
                        <textarea class="form-control" name="ClausulasEspeciales" row="3" col="4">{{ $deuda->ClausulasEspeciales }} </textarea>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Beneficios Adicionales</label>
                        <textarea class="form-control" name="Beneficios" row="3" col="4">{{ $deuda->Beneficios }} </textarea>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Concepto</label>
                        <textarea class="form-control" name="Concepto" row="3" col="4">{{ $deuda->Concepto}}</textarea>
                    </div>
                    <div class="col-sm-4 ocultar" style="display: none !important;">
                        <br>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="radio" name="tipoTasa" id="Mensual" value="1" {{$deuda->Mensual == 1 ? 'checked': ''}}>
                            <label class="control-label">Tasa ‰ Millar Mensual</label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="radio" name="tipoTasa" id="Anual" value="0" {{$deuda->Mensual == 0 ? 'checked': ''}}>
                            <label class="control-label">Tasa ‰ Millar Anual</label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" align="right">Tasa ‰ </label>
                        <input class="form-control" name="Tasa" type="number" id="Tasa" step="any" value="{{ $deuda->Tasa }}" required>
                    </div>
                    <div class="col-sm-4" align="center">
                        <br>
                        <label class="control-label" align="center">Vida</label>
                        <input id="Vida" type="checkbox" class="js-switch" {{$deuda->Vida <> '' ? 'checked': ''}} />
                    </div>
                    <div class="col-sm-4" align="center">
                        <br>
                        <label class="control-label" align="center">Desempleo</label>
                        <input id="Desempleo" type="checkbox" class="js-switch" {{$deuda->Desempleo <> '' ? 'checked': ''}} />
                    </div>
                    <div class="col-sm-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label" align="right">% Tasa de Comision </label>
                        <input class="form-control" name="TasaComision" id="TasaComision" type="number" step="any" value="{{ $deuda->TasaComision }}">
                    </div>
                    <div class="col-sm-2"><br>
                        <label class="control-label" align="right">¿IVA incluido?</label>
                        <input name="ComisionIva" id="ComisionIva" type="checkbox" class="js-switch" {{$deuda->ComisionIva == 1 ? 'checked': ''}}>
                    </div>
                    <div class="col-sm-4">
                        <div id="poliza_vida" style="display: {{$deuda->Vida <> '' ? 'block': 'none'}};">
                            <label class="control-label">Numero de Poliza Vida</label>
                            <input name="Vida" type="text" class="form-control" value="{{$deuda->Vida}}" />
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div id="poliza_desempleo" style="display:  {{$deuda->Desempleo <> '' ? 'block': 'none'}};">
                            <label class="control-label">Numero de Poliza Desempleo</label>
                            <input name="Desempleo" type="text" class="form-control" value="{{$deuda->Desempleo}}" />
                        </div>

                    </div>

                </div>
                
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Collapsible Group Item #2
                </button>
            </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Collapsible Group Item #3
                </button>
            </h2>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            </div>
        </div>
    </div>
</div>