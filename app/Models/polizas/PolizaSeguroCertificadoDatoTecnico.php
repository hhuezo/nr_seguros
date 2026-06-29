<?php

namespace App\Models\polizas;

use App\Models\catalogo\DatosTecnicos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCertificadoDatoTecnico extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_certificado_datos_tecnicos';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroCertificadoId',
        'DatoTecnicoId',
        'Nombre',
        'Descripcion',
        'Valor',
        'Activo',
    ];

    public function certificado()
    {
        return $this->belongsTo(PolizaSeguroCertificado::class, 'PolizaSeguroCertificadoId', 'Id');
    }

    public function datoTecnico()
    {
        return $this->belongsTo(DatosTecnicos::class, 'DatoTecnicoId', 'Id');
    }
}
