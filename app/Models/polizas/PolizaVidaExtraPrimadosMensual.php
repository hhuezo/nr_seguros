<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaVidaExtraPrimadosMensual extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_extra_primado_mensual';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'NumeroReferencia',
        'PolizaVida',
        'Nombre',
        'FechaOtorgamiento',
        'MontoOtorgamiento',
        'Tarifa',
        'PorcentajeEP',
        'PagoEP',
        'Mes',
    ];
    
}
