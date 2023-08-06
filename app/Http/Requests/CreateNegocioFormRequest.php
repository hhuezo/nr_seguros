<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNegocioFormRequest extends FormRequest
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
            'TipoPersona' => 'required|integer',
            'NombreCliente' => 'required|max:255',
            'NitEmpresa' => 'required_if:TipoPersona,2|max:20',
            'Dui' => 'required_if:TipoPersona,1|max:20',
            'Email' => 'nullable|email|max:255',
            //'Estado' => 'nullable|integer',
            //'Asegurado' => 'required|integer',
            'FechaVenta' => 'nullable|date',
            'NecesidadProteccion' => 'required|integer',
            'InicioVigencia' => 'nullable|date',
            'Observacion' => 'nullable|max:500',
            'Ejecutivo' => 'required|integer',
            //'FechaIngreso' => 'nullable|date',
            //'UsuarioIngreso' => 'nullable|integer',
            'EstadoVenta' => 'required|integer',
            //'NumCoutas' => 'nullable|integer',
            //'Prima' => 'nullable|numeric',
            'TipoPoliza' => 'required|integer',
            'TipoNecesidad' => 'required|integer',
            'NumeroPoliza' => 'required|max:100',
            'PlanTipoProducto' => 'required|max:100',
            'TipoNegocio' => 'required|integer',
            'DepartamentoAtiende' => 'required|integer',
            'MetodoPago' => 'nullable|integer',
            'FormaPago' => 'required|integer',
        ];
    }
}
