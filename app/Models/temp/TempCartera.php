<?php

namespace App\Models\temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCartera extends Model
{
    use HasFactory;
    protected $table = 'temp_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nit',
        'Dui',
        'Pasaporte',
        'FechaNacimiento',
        'Nacionalidad',
        'TipoPersona',
        'PrimerApellido',
        'SegundoApellido',
        'CasadaApellido',
        'PrimerNombre',
        'SegundoNombre',
        'SociedadNombre',
        'Sexo',
        'FechaOtorgamiento',
        'FechaVencimiento',
        'Ocupacion',
        'NoRefereciaCredito',
        'MontoOtorgado',
        'SaldoVigenteCapital',
        'Interes',
        'InteresMoratorio',
        'SaldoTotal',
        'TarifaMensual',
        'PrimaMensual',
        'TipoDeuda',
        'PorcentajeExtraprima',
        'Usuario',
        'FechaNacimientoDate',
        'FechaOtorgamientoDate',
        'FechaVencimientoDate'
    ];

    protected $guarded = [];
}
