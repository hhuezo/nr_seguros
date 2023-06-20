<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaResidenciaTempCartera extends Model
{
    use HasFactory;
    protected $table = 'poliza_residencia_temp_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Dui',
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
        'NumeroReferencia',
        'SumaAsegurada',
        'Tarifa',
        'PrimaMensual',
        'NumeroCuotas',
        'TipoDeuda',
        'ClaseCartera',
    ];
}
