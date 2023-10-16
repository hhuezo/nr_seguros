<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosTecnicos extends Model
{
    use HasFactory;
    protected $table = 'datos_tecnicos';
    protected $primaryKey = 'Id'; // Especificamos la clave primaria
    protected $fillable = ['Nombre', 'NombreJSON', 'Descripcion', 'Producto', 'Activo'];
    public $timestamps = false;

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'Id');
    }
}
