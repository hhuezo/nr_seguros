<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaTempCarteraHistorial extends Model
{
    use HasFactory;

    protected $table = 'poliza_deuda_temp_cartera_historial';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'PolizaDeudaTipoCartera',
        'LineaCredito',
        'Tasa',
        'TotalCredito',
        'EdadDesembloso',
        'CarnetResidencia',
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
        'PolizaDeuda',
        'FechaInicio',
        'FechaFinal',
        'TipoError',
        'FechaNacimientoDate',
        'Edad',
        'InteresesCovid',
        'MontoNominal',
        'NoValido',
        'Perfiles',
        'FechaOtorgamientoDate',
        'SaldoCumulo',
        'Excluido',
        'OmisionPerfil',
        'Rehabilitado',
        'EdadRequisito',
        'MontoRequisito',
        'MontoMaximoIndividual',
        'TipoDeuda',
        'PorcentajeExtraprima',
        'TipoDocumento',
        'SaldoInteresMora',
        'PagoAutomatico',
        'Errores',
    ];

    protected $casts = [
        'Tasa' => 'decimal:8',
        'TotalCredito' => 'decimal:14',
        'MontoOtorgado' => 'decimal:14',
        'SaldoCapital' => 'decimal:14',
        'Intereses' => 'decimal:14',
        'InteresesMoratorios' => 'decimal:14',
        'InteresesCovid' => 'decimal:14',
        'MontoNominal' => 'decimal:14',
        'MontoRequisito' => 'decimal:14',
        'SaldoCumulo' => 'decimal:14',
        'SaldoInteresMora' => 'decimal:4',
        'FechaInicio' => 'date',
        'FechaFinal' => 'date',
        'FechaNacimientoDate' => 'date',
        'FechaOtorgamientoDate' => 'date',
        'NoValido' => 'boolean',
        'OmisionPerfil' => 'boolean',
        'Rehabilitado' => 'boolean',
        'MontoMaximoIndividual' => 'boolean',
    ];
}
