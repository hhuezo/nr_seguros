<?php

namespace App\Models\polizas;

use App\Models\catalogo\SaldoMontos;
use App\Models\catalogo\TipoCartera;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaCredito extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_creditos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Deuda',
        'TipoCartera',
        'SumaAsegurada',
        'Tasa',
        'Total',
        'Saldos',
        'MontoMaximoIndividual'
    ];

    public function tipoCarteras()
    {
        return $this->belongsTo(TipoCartera::class, 'TipoCartera', 'Id');
    }

    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'Deuda', 'Id');
    }

    public function saldos(){
        return $this->belongsTo(SaldoMontos::class, 'Saldos', 'Id');
    }

}
