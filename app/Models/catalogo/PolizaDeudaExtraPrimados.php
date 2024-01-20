<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaExtraPrimados extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_extra_primado';

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
