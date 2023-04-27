<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDepositoPlazo extends Model
{
    use HasFactory;
    protected $table = 'poliza_deposito_plazo_detalle';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'DepositoPlazo',
        'Comentario',
        'Tasa',
        'PrimaTotal',
        'Descuento',
        'ExtraPrima',
        'ValorCCF',
        'APagar',
        'Comision',
        'ValorDescuento',
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

    public function depositoPlazos(){
        return $this->belongsTo('App\Models\polizas\DepositoPlazo', 'DepositoPlazo', 'Id');
    }
}
