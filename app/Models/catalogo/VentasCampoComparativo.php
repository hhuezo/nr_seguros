<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasCampoComparativo extends Model
{
    use HasFactory;

    protected $table = 'ventas_campo_comparativo';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'NecesidadProteccion',
        'Etiqueta',
        'NombreInterno',
        'Orden',
        'Activo',
    ];

    public function ramo()
    {
        return $this->belongsTo(NecesidadProteccion::class, 'NecesidadProteccion', 'Id');
    }
}
