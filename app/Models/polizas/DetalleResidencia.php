<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleResidencia extends Model
{
    use HasFactory;
    protected $table = 'poliza_residencia_detalle';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Residencia',
        'Comentario',
        'Tasa',
        'PrimaTotal',
        'Descuento',
        'ExtraPrima',
        'ValorCCF',
        'APagar',
        'ImpuestoBomberos',
        'ValorDescuento',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'SaldoA',
        'MontoCartera',
        'TasaComision',
        'PrimaDescontada'
    ];

    protected $guarded = [];
}
