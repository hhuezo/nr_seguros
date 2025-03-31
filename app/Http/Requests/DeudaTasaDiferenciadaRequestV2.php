<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeudaTasaDiferenciadaRequestV2 extends FormRequest
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
            'EdadDesdeEdit' => [
                'required_if:TipoCalculoEdit,2',
                'nullable',
                'integer',
                'min:18',
                'lte:EdadHastaEdit'
            ],
            'EdadHastaEdit' => [
                'required_if:TipoCalculoEdit,2',
                'nullable',
                'integer',
                'lte:100',
                'gte:EdadDesdeEdit'
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

            'EdadDesdeEdit.required_if' => 'La edad desde es requerida',
            'EdadDesdeEdit.integer' => 'La edad debe ser un número entero',
            'EdadDesdeEdit.min' => 'La edad mínima es 18 años',
            'EdadDesdeEdit.lte' => 'La edad desde no puede ser mayor a la edad hasta',

            'EdadHastaEdit.required_if' => 'La edad hasta es requerida',
            'EdadHastaEdit.integer' => 'La edad debe ser un número entero',
            'EdadHastaEdit.lte' => 'La edad máxima no puede superar los 100 años',
            'EdadHastaEdit.gte' => 'La edad hasta no puede ser menor a la edad desde',

            'TasaEdit.required' => 'La tasa es obligatoria',
            'TasaEdit.numeric' => 'La tasa debe ser un valor numérico',
            'TasaEdit.gt' => 'La tasa debe ser mayor a 0'
        ];
    }
}
