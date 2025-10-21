<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaResidenciaHistorica extends Model
{
    use HasFactory;
    protected $table = 'poliza_residencia_historica';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'DatosResidencia',
        'ResidenciaDetalle',
        'Residencia',
        'Fecha',
        'Usuario',
        'TipoRenovacion',
        'VigenciaDesde',
        'VigenciaHasta',
        'FechaDesdeRenovacion',
        'FechaHastaRenovacion',
    ];
}
