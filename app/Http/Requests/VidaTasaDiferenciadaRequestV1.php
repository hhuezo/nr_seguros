<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VidaTasaDiferenciadaRequestV1 extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'TipoCalculoIngreso' => 'required',
            //'LineaCro' => 'required|exists:linea_cro,id',
            'FechaDesde' => [
                'required_if:TipoCalculoIngreso,1',
                'nullable',
                //'date_format:Y-m-d',
                'before_or_equal:FechaHasta'
            ],
            'FechaHasta' => [
                'required_if:TipoCalculoIngreso,1',
                'nullable',
                //'date_format:Y-m-d',
                'after_or_equal:FechaDesde'
            ],
            'MontoDesde' => [
                'required_if:TipoCalculoIngreso,2',
                'nullable',
                'integer',
                'min:18',
                'lte:MontoHasta'
            ],
            'MontoHasta' => [
                'required_if:TipoCalculoIngreso,2',
                'nullable',
                'integer',
                'lte:100',
                'gte:MontoDesde'
            ],
            'Tasa' => [
                'required',
                'numeric',
                'gt:0'
            ]
        ];
    }

    public function messages()
    {
        return [
            //'TipoCalculoIngreso.required' => 'El tipo de cálculo es obligatorio',
            //'TipoCalculoIngreso.in' => 'Tipo de cálculo no válido',

            //'LineaCro.required' => 'La línea de crédito es obligatoria',
            //'LineaCro.exists' => 'La línea de crédito seleccionada no es válida',

            'FechaDesde.required_if' => 'La fecha desde es requerida',
            //'FechaDesde.date_format' => 'Formato de fecha inválido (AAAA-MM-DD)',
            'FechaDesde.before_or_equal' => 'La fecha desde no puede ser mayor a la fecha hasta',

            'FechaHasta.required_if' => 'La fecha hasta es requerida',
            //'FechaHasta.date_format' => 'Formato de fecha inválido (AAAA-MM-DD)',
            'FechaHasta.after_or_equal' => 'La fecha hasta no puede ser menor a la fecha desde',

            'MontoDesde.required_if' => 'La edad desde es requerida',
            'MontoDesde.integer' => 'La edad debe ser un número entero',
            'MontoDesde.min' => 'La edad mínima es 18 años',
            'MontoDesde.lte' => 'La edad desde no puede ser mayor a la edad hasta',

            'MontoHasta.required_if' => 'La edad hasta es requerida',
            'MontoHasta.integer' => 'La edad debe ser un número entero',
            'MontoHasta.lte' => 'La edad máxima no puede superar los 100 años',
            'MontoHasta.gte' => 'La edad hasta no puede ser menor a la edad desde',

            'Tasa.required' => 'La tasa es obligatoria',
            'Tasa.numeric' => 'La tasa debe ser un valor numérico',
            'Tasa.gt' => 'La tasa debe ser mayor a 0'
        ];
    }
}
