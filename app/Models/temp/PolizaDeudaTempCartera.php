<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaTempCartera extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_temp_cartera';

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
        'PolizaDeuda',
        'FechaInicio',
        'FechaFinal'
    ];
}
