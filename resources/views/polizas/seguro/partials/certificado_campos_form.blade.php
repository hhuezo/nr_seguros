@foreach ($certificado_campos as $campo)
    @php
        $campoValores = $campoValores ?? [];
        $valorCampo = $campoValores[$campo->Id] ?? '';
        $validacionCampo = $campo->ValidacionCampo ?? 'ninguna';
        $origenOpciones = $campo->OrigenOpciones ?? 'manual';
        $catalogoOrigen = $campo->CatalogoOrigen ?? null;
        $opciones = [];
        if ($campo->TipoCampo === 'select' && $origenOpciones === 'catalogo' && $catalogoOrigen === 'parentesco_beneficiario') {
            $opciones = collect($catalogosOpcionesCertificado['parentesco_beneficiario'] ?? [])
                ->map(function ($opcion) {
                    return [
                        'valor' => $opcion['Id'] ?? null,
                        'texto' => $opcion['Nombre'] ?? '',
                    ];
                })
                ->filter(fn($opcion) => $opcion['valor'] !== null && $opcion['texto'] !== '')
                ->values()
                ->all();
        } elseif ($campo->OpcionesJson) {
            $tmp = json_decode($campo->OpcionesJson, true);
            $opciones = is_array($tmp)
                ? collect($tmp)->map(fn($opcion) => ['valor' => $opcion, 'texto' => $opcion])->all()
                : [];
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
                    <option value="{{ $opcion['valor'] }}" {{ (string) $valorCampo === (string) $opcion['valor'] ? 'selected' : '' }}>{{ $opcion['texto'] }}</option>
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
