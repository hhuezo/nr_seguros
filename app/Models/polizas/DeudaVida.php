<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaVida extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_vida';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'NumeroPoliza',
        'Deuda',
        'NumeroUsuarios',
        'SumaUniforme',
        'CarteraAsegurada',
        'Tasa',
        'ExtraPrima',
        'SaldoAPagar',
        'FechaInicio',
        'FechaFinal',
        'Activo'

    ];

    public function deuda()
    {
        return $this->belongsTo('App\Models\polizas\Deuda', 'Deuda', 'Id');
    }
}
