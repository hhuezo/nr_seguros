@php
    $campo = $campo ?? null;
    $ramoActual = $ramoActual ?? null;
@endphp

<div class="row">
    <div class="col-md-6 form-group">
        <label class="control-label">Ramo <span class="text-danger">*</span></label>
        @if ($ramoActual)
            <input type="hidden" name="NecesidadProteccion" value="{{ $ramoActual->Id }}">
            <input type="text" class="form-control" value="{{ $ramoActual->Nombre }}" readonly>
        @else
            <select name="NecesidadProteccion" class="form-control select2" style="width: 100%" required>
                <option value="" selected disabled>Seleccione...</option>
                @foreach ($ramos as $ramo)
                    <option value="{{ $ramo->Id }}" {{ old('NecesidadProteccion', $campo->NecesidadProteccion ?? '') == $ramo->Id ? 'selected' : '' }}>
                        {{ $ramo->Nombre }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>
    <div class="col-md-6 form-group">
        <label class="control-label">Orden</label>
        <input type="number" min="1" step="1" name="Orden" class="form-control" value="{{ old('Orden', $campo->Orden ?? 1) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 form-group">
        <label class="control-label">Concepto <span class="text-danger">*</span></label>
        <input type="text" name="Etiqueta" class="form-control campo-mayuscula" value="{{ old('Etiqueta', $campo->Etiqueta ?? '') }}" required>
    </div>
    <div class="col-md-6 form-group">
        <label class="control-label">Nombre interno</label>
        <input type="text" name="NombreInterno" class="form-control" value="{{ old('NombreInterno', $campo->NombreInterno ?? '') }}" placeholder="Autogenerado si queda vacio">
    </div>
</div>
