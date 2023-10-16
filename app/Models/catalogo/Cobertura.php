<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobertura extends Model
{
    use HasFactory;
    protected $table = 'cobertura';
    protected $primaryKey = 'Id'; // Especificamos la clave primaria
    protected $fillable = ['Nombre', 'Tarificacion', 'Descuento', 'Iva', 'Producto', 'Activo'];
    public $timestamps = false;

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'Id');
    }

    public function planesCoberturaDetalles()
    {
        return $this->hasMany(planesCoberturaDetalle::class, 'Cobertura', 'Id');
    }
}
