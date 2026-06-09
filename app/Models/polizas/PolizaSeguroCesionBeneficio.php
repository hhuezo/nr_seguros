<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCesionBeneficio extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_cesion_beneficios';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroId',
        'CodigoSesion',
        'Beneficiario',
        'FechaVigencia',
        'FechaCancelacion',
        'SumaCedida',
        'Observaciones',
        'Propietario',
        'Activo',
    ];

    public function poliza()
    {
        return $this->belongsTo(PolizaSeguro::class, 'PolizaSeguroId', 'Id');
    }
}
