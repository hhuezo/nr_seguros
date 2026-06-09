<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $primaryKey = 'Id'; // Especificamos la clave primaria
    protected $fillable = ['Nombre', 'Aseguradora', 'NecesidadProteccion', 'Descripcion','PermiteDependientesCertificado','Activo'];
    public $timestamps = false;

    public function aseguradoras()
    {
        return $this->belongsTo(Aseguradora::class, 'Aseguradora', 'Id');
    }

    public function ramos()
    {
        return $this->belongsTo(NecesidadProteccion::class, 'NecesidadProteccion', 'Id');
    }

    public function coberturas()
    {
        return $this->hasMany(Cobertura::class, 'Producto', 'Id');
    }

    public function datosTecnicos()
    {
        return $this->hasMany(DatosTecnicos::class, 'Producto', 'Id');
    }

    public function planes()
    {
        return $this->hasMany(Plan::class, 'Producto', 'Id');
    }

    public function certificadoCampos()
    {
        return $this->hasMany(ProductoCertificadoCampo::class, 'Producto', 'Id')
            ->where('Activo', 1)
            ->orderBy('Orden', 'asc')
            ->orderBy('Id', 'asc');
    }
}
