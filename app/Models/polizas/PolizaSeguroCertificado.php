<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCertificado extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_certificados';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroId',
        'NumeroCertificado',
        'DatosJson',
        'Observacion',
        'Activo',
    ];

    public function poliza()
    {
        return $this->belongsTo(PolizaSeguro::class, 'PolizaSeguroId', 'Id');
    }

    public function dependientes()
    {
        return $this->hasMany(PolizaSeguroCertificadoDependiente::class, 'PolizaSeguroCertificadoId', 'Id')
            ->where('Activo', 1)
            ->orderBy('NumeroDependiente', 'asc');
    }
}
