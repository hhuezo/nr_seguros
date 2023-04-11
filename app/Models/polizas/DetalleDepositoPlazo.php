<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDepositoPlazo extends Model
{
    use HasFactory;
    protected $table = 'detalle_poliza_deposito_plazo';

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
        'ValorDescuento',
        'IvaSobreComision',
        'Retencion',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'SaldoA',
    ];

    protected $guarded = [];

    public function depositoPlazos(){
        return $this->belongsTo('App\Models\polizas\DepositoPlazo', 'DepositoPlazo', 'Id');
    }
}
