<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desempleo extends Model
{
    use HasFactory;
    protected $table = 'poliza_desempleo';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Numero',
        'NumeroPoliza',
        'Nit',
        'Codigo',
        'Asegurado',
        'Aseguradora',
        'Ejecutivo',
        'VigenciaDesde',
        'VigenciaHasta',
        'Tasa',
        'Beneficios',
        'ClausulasEspeciales',
        'Concepto',
        'EstadoPoliza',
        'Descuento',
        'TasaComision',
        'FechaIngreso',
        'Activo',
        'EdadTerminacion',
        'EdadMaxTerminacion',
        'EdadIntermedia',
        'Mensual',
        'Deuda',
        'Plan',
        'Configuracion',
        'Usuario',
        'ComisionIva',
    ];
}
