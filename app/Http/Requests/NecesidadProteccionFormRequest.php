<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NecesidadProteccionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'Nombre' => 'required|string|max:100',
            'AgrupadorRamo' => 'nullable|exists:agrupador_ramo,Id',
            'PorcentajeComisionNoDeclarativa' => 'nullable|numeric|min:0|max:100',
            'ComisionBomberos' => 'nullable|in:0,1',
            'PorcentajeBomberos' => 'nullable|numeric|min:0|max:100|required_if:ComisionBomberos,1',
        ];
    }
}
