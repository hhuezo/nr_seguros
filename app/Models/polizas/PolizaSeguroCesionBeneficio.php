<?php

namespace App\Models\polizas;

use App\Models\catalogo\Cesionario;
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
        'PolizaSeguroCertificadoId',
        'CodigoSesion',
        'CesionarioId',
        'FechaVigencia',
        'FechaCancelacion',
        'SumaCedida',
        'Observaciones',
        'Activo',
    ];

    public function poliza()
    {
        return $this->belongsTo(PolizaSeguro::class, 'PolizaSeguroId', 'Id');
    }

    public function certificado()
    {
        return $this->belongsTo(PolizaSeguroCertificado::class, 'PolizaSeguroCertificadoId', 'Id');
    }

    public function cesionario()
    {
        return $this->belongsTo(Cesionario::class, 'CesionarioId', 'Id');
    }
}
