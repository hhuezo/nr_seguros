@php
    $planComercial = $planComercial ?? null;
@endphp

<div class="form-plan-comercial">
    <div class="row">
        <div class="col-md-6 form-group">
            <label class="control-label">Aseguradora <span class="text-danger">*</span></label>
            <select name="Aseguradora" class="form-control select2 select-aseguradora" style="width: 100%" required>
                <option value="" selected disabled>Seleccione...</option>
                @foreach ($aseguradoras as $aseguradora)
                    <option value="{{ $aseguradora->Id }}" {{ old('Aseguradora', $planComercial->Aseguradora ?? '') == $aseguradora->Id ? 'selected' : '' }}>
                        {{ $aseguradora->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label class="control-label">Ramo <span class="text-danger">*</span></label>
            <select name="NecesidadProteccion" class="form-control select2 select-ramo" style="width: 100%" required>
                <option value="" selected disabled>Seleccione...</option>
                @foreach ($ramos as $ramo)
                    <option value="{{ $ramo->Id }}" {{ old('NecesidadProteccion', $planComercial->NecesidadProteccion ?? '') == $ramo->Id ? 'selected' : '' }}>
                        {{ $ramo->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label class="control-label">Producto <span class="text-danger">*</span></label>
            <select name="Producto" class="form-control select2 select-producto" style="width: 100%" data-selected="{{ old('Producto', $planComercial->Producto ?? '') }}" required>
                <option value="" selected disabled>Seleccione...</option>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label class="control-label">Plan tecnico <span class="text-danger">*</span></label>
            <select name="Plan" class="form-control select2 select-plan" style="width: 100%" data-selected="{{ old('Plan', $planComercial->Plan ?? '') }}" required>
                <option value="" selected disabled>Seleccione...</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group">
            <label class="control-label">Nombre comercial <span class="text-danger">*</span></label>
            <input type="text" name="NombreComercial" class="form-control campo-mayuscula" value="{{ old('NombreComercial', $planComercial->NombreComercial ?? '') }}" required>
        </div>
    </div>
</div>
