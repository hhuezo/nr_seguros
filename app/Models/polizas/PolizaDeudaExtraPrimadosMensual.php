<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaExtraPrimadosMensual extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_extra_primado_mensual';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'NumeroReferencia',
        'PolizaDeuda',
        'Nombre',
        'FechaOtorgamiento',
        'MontoOtorgamiento',
        'Tarifa',
        'PorcentajeEP',
        'PagoEP',
        'Mes',
    ];
}
