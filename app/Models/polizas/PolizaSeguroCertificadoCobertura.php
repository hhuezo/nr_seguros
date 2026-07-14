<?php

namespace App\Models\polizas;

use App\Models\catalogo\Cobertura;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCertificadoCobertura extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_certificado_coberturas';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroCertificadoId',
        'Cobertura',
        'Tarificacion',
        'TarificacionNombre',
        'Nombre',
        'SumaAsegurada',
        'PorcentajeSuma',
        'Tasa',
        'DiasProrrata',
        'PrimaAnual',
        'Prima',
        'Activo',
    ];

    public function certificado()
    {
        return $this->belongsTo(PolizaSeguroCertificado::class, 'PolizaSeguroCertificadoId', 'Id');
    }

    public function cobertura()
    {
        return $this->belongsTo(Cobertura::class, 'Cobertura', 'Id');
    }
}
