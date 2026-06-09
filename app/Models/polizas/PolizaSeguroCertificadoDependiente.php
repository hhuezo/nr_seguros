<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCertificadoDependiente extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_certificado_dependientes';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroCertificadoId',
        'NumeroDependiente',
        'DatosJson',
        'Observacion',
        'Activo',
    ];

    public function certificado()
    {
        return $this->belongsTo(PolizaSeguroCertificado::class, 'PolizaSeguroCertificadoId', 'Id');
    }
}
