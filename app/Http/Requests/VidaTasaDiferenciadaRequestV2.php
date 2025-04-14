<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VidaTasaDiferenciadaRequestV2 extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'TipoCalculoEdit' => 'required',
            //'LineaCreditoEdit' => 'required|exists:linea_credito,id',
            'FechaDesdeEdit' => [
                'required_if:TipoCalculoEdit,1',
                'nullable',
                //'date_format:Y-m-d',
                'before_or_equal:FechaHastaEdit'
            ],
            'FechaHastaEdit' => [
                'required_if:TipoCalculoEdit,1',
                'nullable',
                //'date_format:Y-m-d',
                'after_or_equal:FechaDesdeEdit'
            ],
            'MontoDesdeEdit' => [
                'required_if:TipoCalculoEdit,2',
                'nullable',
                'integer',
                'min:18',
                'lte:MontoHastaEdit'
            ],
            'MontoHastaEdit' => [
                'required_if:TipoCalculoEdit,2',
                'nullable',
                'integer',
                'lte:100',
                'gte:MontoDesdeEdit'
            ],
            'TasaEdit' => [
                'required',
                'numeric',
                'gt:0'
            ]
        ];
    }

    public function messages()
    {
        return [
            //'TipoCalculoEdit.required' => 'El tipo de cálculo es obligatorio',
            //'TipoCalculoEdit.in' => 'Tipo de cálculo no válido',

            //'LineaCreditoEdit.required' => 'La línea de crédito es obligatoria',
            //'LineaCreditoEdit.exists' => 'La línea de crédito seleccionada no es válida',

            'FechaDesdeEdit.required_if' => 'La fecha desde es requerida',
            //'FechaDesdeEdit.date_format' => 'Formato de fecha inválido (AAAA-MM-DD)',
            'FechaDesdeEdit.before_or_equal' => 'La fecha desde no puede ser mayor a la fecha hasta',

            'FechaHastaEdit.required_if' => 'La fecha hasta es requerida',
            //'FechaHastaEdit.date_format' => 'Formato de fecha inválido (AAAA-MM-DD)',
            'FechaHastaEdit.after_or_equal' => 'La fecha hasta no puede ser menor a la fecha desde',

            'MontoDesdeEdit.required_if' => 'La edad desde es requerida',
            'MontoDesdeEdit.integer' => 'La edad debe ser un número entero',
            'MontoDesdeEdit.min' => 'La edad mínima es 18 años',
            'MontoDesdeEdit.lte' => 'La edad desde no puede ser mayor a la edad hasta',

            'MontoHastaEdit.required_if' => 'La edad hasta es requerida',
            'MontoHastaEdit.integer' => 'La edad debe ser un número entero',
            'MontoHastaEdit.lte' => 'La edad máxima no puede superar los 100 años',
            'MontoHastaEdit.gte' => 'La edad hasta no puede ser menor a la edad desde',

            'TasaEdit.required' => 'La tasa es obligatoria',
            'TasaEdit.numeric' => 'La tasa debe ser un valor numérico',
            'TasaEdit.gt' => 'La tasa debe ser mayor a 0'
        ];
    }
}
