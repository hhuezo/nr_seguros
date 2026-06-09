@foreach ($certificado_campos as $campo)
    @php
        $campoValores = $campoValores ?? [];
        $valorCampo = $campoValores[$campo->Id] ?? '';
        $validacionCampo = $campo->ValidacionCampo ?? 'ninguna';
        $opciones = [];
        if ($campo->OpcionesJson) {
            $tmp = json_decode($campo->OpcionesJson, true);
            $opciones = is_array($tmp) ? $tmp : [];
        }
        $required = (int) $campo->Requerido === 1 ? 'required' : '';
        $tipo = in_array($campo->TipoCampo, ['text', 'number', 'date', 'email']) ? $campo->TipoCampo : 'text';
        $claseValidacion = '';
        $pattern = '';
        $maxlength = '';

        if ($validacionCampo === 'dui') {
            $tipo = 'text';
            $claseValidacion = 'campo-validacion-dui';
            $pattern = '^\d{8}-\d$';
            $maxlength = '10';
        } elseif ($validacionCampo === 'solo_numeros') {
            $tipo = 'text';
            $claseValidacion = 'campo-validacion-solo-numeros';
        } elseif ($validacionCampo === 'solo_numeros_letras') {
            $tipo = 'text';
            $claseValidacion = 'campo-validacion-solo-numeros-letras';
        } elseif ($validacionCampo === 'solo_texto') {
            $tipo = 'text';
            $claseValidacion = 'campo-validacion-solo-texto';
        } elseif ($validacionCampo === 'correo') {
            $tipo = 'email';
        }
    @endphp
    <div class="col-md-6 col-sm-12" style="margin-bottom: 12px;">
        <label>{{ $campo->Etiqueta }} @if ((int) $campo->Requerido === 1) * @endif</label>
        @if ($campo->TipoCampo === 'textarea')
            <textarea name="campos[{{ $campo->Id }}]" class="form-control" rows="2"
                placeholder="{{ $campo->Placeholder }}" data-validacion-campo="{{ $validacionCampo }}" {{ $required }}>{{ $valorCampo }}</textarea>
        @elseif ($campo->TipoCampo === 'select')
            <select name="campos[{{ $campo->Id }}]" class="form-control" data-validacion-campo="{{ $validacionCampo }}" {{ $required }}>
                <option value="">Seleccione...</option>
                @foreach ($opciones as $opcion)
                    <option value="{{ $opcion }}" {{ (string) $valorCampo === (string) $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                @endforeach
            </select>
        @else
            <input type="{{ $tipo }}" name="campos[{{ $campo->Id }}]" class="form-control {{ $claseValidacion }}"
                placeholder="{{ $campo->Placeholder }}" value="{{ $valorCampo }}"
                data-validacion-campo="{{ $validacionCampo }}"
                @if ($validacionCampo === 'dui') data-mask data-inputmask="'mask': '99999999-9'" @endif
                @if ($pattern) pattern="{{ $pattern }}" @endif
                @if ($maxlength) maxlength="{{ $maxlength }}" @endif
                {{ $required }}>
        @endif
        @if ($campo->Ayuda)
            <small class="text-muted">{{ $campo->Ayuda }}</small>
        @endif
    </div>
@endforeach
