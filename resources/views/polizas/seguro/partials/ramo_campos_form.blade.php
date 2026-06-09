@foreach ($campos_ramo as $campo)
    @php
        $datos_ramo = $datos_ramo ?? [];
        $nombreInputRamo = 'DatosRamo[' . $campo->Id . ']';
        $idInputRamo = 'DatosRamo_' . $campo->Id;
        $valorCampoRamo = old('DatosRamo.' . $campo->Id, $datos_ramo[$campo->Id] ?? '');
        $tipoCampoRamo = $campo->TipoCampo === 'textarea' ? 'textarea' : $campo->TipoCampo;
        $clasesCampoRamo = 'form-control campo-ramo';
        $atributosCampoRamo = '';

        if ($campo->ValidacionCampo === 'dui') {
            $clasesCampoRamo .= ' campo-validacion-dui';
            $atributosCampoRamo .= ' data-inputmask="\'mask\': [\'99999999-9\']" maxlength="10" pattern="^\d{8}-\d$"';
        } elseif ($campo->ValidacionCampo === 'solo_numeros') {
            $clasesCampoRamo .= ' campo-validacion-solo-numeros';
            $atributosCampoRamo .= ' inputmode="numeric"';
        } elseif ($campo->ValidacionCampo === 'solo_numeros_letras') {
            $clasesCampoRamo .= ' campo-validacion-solo-numeros-letras';
        } elseif ($campo->ValidacionCampo === 'solo_texto') {
            $clasesCampoRamo .= ' campo-validacion-solo-texto';
        }
    @endphp
    <div class="col-md-6 col-sm-12 poliza-field">
        <label for="{{ $idInputRamo }}">
            {{ $campo->Etiqueta }}
            @if ((int) $campo->Requerido === 1) * @endif
        </label>
        @if ($tipoCampoRamo === 'textarea')
            <textarea class="{{ $clasesCampoRamo }}" name="{{ $nombreInputRamo }}" id="{{ $idInputRamo }}"
                placeholder="{{ $campo->Placeholder }}" rows="2"
                @if ((int) $campo->Requerido === 1) required @endif>{!! e($valorCampoRamo) !!}</textarea>
        @else
            <input class="{{ $clasesCampoRamo }}"
                type="{{ $tipoCampoRamo === 'email' || $campo->ValidacionCampo === 'correo' ? 'email' : ($tipoCampoRamo === 'number' ? 'number' : ($tipoCampoRamo === 'date' ? 'date' : 'text')) }}"
                name="{{ $nombreInputRamo }}" id="{{ $idInputRamo }}" value="{{ $valorCampoRamo }}"
                placeholder="{{ $campo->Placeholder }}" data-validacion="{{ $campo->ValidacionCampo ?? 'ninguna' }}"
                {!! $atributosCampoRamo !!}
                @if ($campo->TipoCampo === 'number') step="any" @endif
                @if ((int) $campo->Requerido === 1) required @endif>
        @endif
    </div>
@endforeach
