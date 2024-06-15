<?php

namespace App\Models\polizas;

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
        'Saldos'
    ];  

    public function tipoCarteras()
    {
        return $this->belongsTo('App\Models\catalogo\TipoCartera', 'TipoCartera', 'Id');
    }

    public function deuda()
    {
        return $this->belongsTo('App\Models\polizas\Deuda', 'Deuda', 'Id');
    }

    public function saldos(){
        return $this->belongsTo('App\Models\catalogo\SaldoMontos', 'Saldos', 'Id');
    }

}
