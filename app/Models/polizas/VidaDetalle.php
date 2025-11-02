<?php

namespace App\Models\polizas;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaDetalle extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_detalle';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Vida',
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

    public function vidas(){
        return $this->belongsTo(Vida::class, 'PolizaVida', 'Id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'Usuario', 'Id');
    }
}
