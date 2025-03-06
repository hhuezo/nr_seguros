<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesempleoCarteraTemp extends Model
{
    use HasFactory;

    protected $table = 'poliza_desempleo_cartera_temp';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
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
        'InteresesCovid',
        'MontoNominal',
        'NoValido',
        'EdadDesembloso',
        'FechaOtorgamientoDate',
        'Excluido',
        'Rehabilitado',
        'EdadRequisito',
    ];
}
