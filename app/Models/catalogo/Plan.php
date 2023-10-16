<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'plan';
    protected $primaryKey = 'Id'; // Especificamos la clave primaria
    protected $fillable = ['Nombre', 'producto', 'Activo'];
    public $timestamps = false;

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'producto', 'Id');
    }

    public function planesCoberturaDetalles()
    {
        return $this->hasMany(planesCoberturaDetalle::class, 'Plan', 'Id');
    }
}
