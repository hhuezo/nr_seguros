<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleResidencia extends Model
{
    use HasFactory;
    protected $table = 'detalle_poliza_residencia';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Residencia',
        'MontoCartera',
        'Tasa',
        'Prima',
        'Descuento',
        'Iva',
        'ValorCCF',
        'APagar',
        'ComentariosDeCobro',
        'DescuentoIva',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'SaldoA'
    ];

    protected $guarded = [];
}
