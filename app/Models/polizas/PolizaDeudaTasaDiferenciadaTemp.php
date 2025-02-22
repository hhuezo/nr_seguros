<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaTasaDiferenciadaTemp extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_tasa_diferenciada_temp';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaDuedaCredito',
        'PolizaDueda',
        'FechaDesde',
        'FechaHasta',
        'EdadDesde',
        'EdadHasta',
        'Tasa',
        'EsTasaDiferenciada',
        'TipoCalculo',
        'Usuario'
    ];
}
