<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaDetalle extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_detalle';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Deuda',
        'Tasa',
        'SaldoCapital',
        'MontoNominal',
        'ComentarioCobro',
        'LimiteMedico',
        'MaxAsegurado',
        'NoRecibo',
        'Descuento',
        'DescuentoEspecial',
        'ValorPrima',
        'ExtraPrima',
        'ValorCCF',
        'APagar',
        'ValorDescuento',
        'ValorDescuentoEspecial',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'PrimaDescontada',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado'
    ];


    public function deuda()
    {
        return $this->belongsTo('App\Models\polizas\Deuda', 'Deuda', 'Id');
    }
}
