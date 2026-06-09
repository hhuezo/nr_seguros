<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgrupadorRamoFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'Nombre' => 'required|string|max:150',
        ];
    }
}

