<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NecesidadProteccionCampo extends Model
{
    use HasFactory;

    protected $table = 'necesidad_proteccion_campos';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'NecesidadProteccion',
        'Etiqueta',
        'NombreCampo',
        'TipoCampo',
        'ValidacionCampo',
        'Requerido',
        'Placeholder',
        'Activo',
    ];

    public function necesidadProteccion()
    {
        return $this->belongsTo(NecesidadProteccion::class, 'NecesidadProteccion', 'Id');
    }
}
