<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaVidaCarteraTempHistorial extends Model
{
    use HasFactory;

    protected $table = 'poliza_vida_cartera_temp_historial';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaVida',
        'CarnetResidencia',
        'Dui',
        'Pasaporte',
        'Nacionalidad',
        'FechaNacimiento',
        'TipoPersona',
        'Sexo',
        'PrimerApellido',
        'SegundoApellido',
        'ApellidoCasada',
        'PrimerNombre',
        'SegundoNombre',
        'FechaOtorgamiento',
        'FechaVencimiento',
        'NumeroReferencia',
        'SumaAsegurada',
        'User',
        'Axo',
        'Mes',
        'FechaInicio',
        'FechaFinal',
        'FechaNacimientoDate',
        'FechaOtorgamientoDate',
        'Edad',
        'EdadDesembloso',
        'TipoError',
        'Rehabilitado',
        'NoValido',
        'PolizaVidaTipoCartera',
        'Tasa',
        'MontoMaximoIndividual',
        'TipoDeuda',
        'PorcentajeExtraprima',
        'TipoDocumento',
        'SaldoInteresMora',
        'NombreSociedad',
        'SaldoCapital',
        'Intereses',
        'InteresesMoratorios',
        'InteresesCovid',
    ];
}
