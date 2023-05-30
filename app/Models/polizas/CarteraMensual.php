<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteraMensual extends Model
{
    use HasFactory;
    protected $table = 'cartera_mensual';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Vida',
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
}
