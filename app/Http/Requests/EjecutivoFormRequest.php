<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EjecutivoFormRequest extends FormRequest
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
            //'Nombre' => 'required|max:255|unique:ejecutivo',
            'Nombre' => 'required|max:255',
            'Codigo' => 'required|max:10',
            'Telefono' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'AreaComercial' => 'required|exists:area_comercial,Id',
        ];
    }

    public function messages()
    {
        return [
            'Telefono.regex' => 'El número de teléfono debe estar en formato "9999-9999".',
        ];
    }
}
