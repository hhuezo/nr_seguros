<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoCertificadoCampo extends Model
{
    use HasFactory;

    protected $table = 'producto_certificado_campos';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Producto',
        'Etiqueta',
        'NombreCampo',
        'TipoCampo',
        'ValidacionCampo',
        'Requerido',
        'MostrarEnReporte',
        'Orden',
        'Placeholder',
        'Ayuda',
        'OpcionesJson',
        'Activo',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'Id');
    }
}
