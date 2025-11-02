<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaResidenciaCarteraTempHistorial extends Model
{
    use HasFactory;

    protected $table = 'poliza_residencia_temp_cartera_historial';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Dui',
        'Nit',
        'Pasaporte',
        'CarnetResidencia',
        'Nacionalidad',
        'FechaNacimiento',
        'TipoPersona',
        'NombreCompleto',
        'NombreSociedad',
        'Genero',
        'Direccion',
        'FechaOtorgamiento',
        'FechaVencimiento',
        'NumeroReferencia',
        'SumaAsegurada',
        'Tarifa',
        'PrimaMensual',
        'NumeroCuotas',
        'TipoDeuda',
        'ClaseCartera',
        'User',
        'Axo',
        'Mes',
        'PolizaResidencia',
        'FechaInicio',
        'FechaFinal',
        'Errores',
    ];
}
