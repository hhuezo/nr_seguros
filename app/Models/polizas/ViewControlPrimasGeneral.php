<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewControlPrimasGeneral extends Model
{
    use HasFactory;
    protected $table = 'view_control_primas_general';

    protected $fillable = [
        'IdPoliza',
        'IdClienteAsegurado',
        'Asegurado',
        'IdAseguradora',
        'TipoPoliza',
        'PolizaNo',
        'Seguradora',
        'VigenciaDesdePoliza',
        'VigenciaHastaPoliza',
        'IdPolizaDetalle',
        'FechaInicioDetalle',
        'FechaFinalDetalle',
        'Descuento',
        'Apagar',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'RepeticionRegistro',
    ];
}
