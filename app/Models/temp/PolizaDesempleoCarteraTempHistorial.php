<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDesempleoCarteraTempHistorial extends Model
{
    use HasFactory;

    protected $table = 'poliza_desempleo_cartera_temp_historial';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'SaldosMontos',
        'PolizaDesempleo',
        'Nit',
        'Dui',
        'Pasaporte',
        'Nacionalidad',
        'FechaNacimiento',
        'TipoPersona',
        'PrimerApellido',
        'SegundoApellido',
        'ApellidoCasada',
        'PrimerNombre',
        'SegundoNombre',
        'NombreSociedad',
        'Sexo',
        'FechaOtorgamiento',
        'FechaVencimiento',
        'Ocupacion',
        'NumeroReferencia',
        'MontoOtorgado',
        'SaldoCapital',
        'Intereses',
        'MoraCapital',
        'InteresesMoratorios',
        'SaldoTotal',
        'User',
        'Axo',
        'Mes',
        'FechaInicio',
        'FechaFinal',
        'TipoError',
        'FechaNacimientoDate',
        'Edad',
        'EdadDesembloso',
        'InteresesCovid',
        'MontoNominal',
        'NoValido',
        'FechaOtorgamientoDate',
        'Excluido',
        'Rehabilitado',
        'EdadRequisito',
        'PolizaDesempleoDetalle',
        'CarnetResidencia',
        'DesempleoTipoCartera',
        'TotalCredito',
        'Tasa',
    ];
}
