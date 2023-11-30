<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'plan';
    protected $primaryKey = 'Id'; // Especificamos la clave primaria
    protected $fillable = ['Nombre', 'Producto', 'Activo'];
    public $timestamps = false;

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'Id');
    }

    public function planesCoberturaDetalles()
    {
        return $this->hasMany(PlanCoberturaDetalle::class, 'Plan', 'Id');
    }

    public function coberturas()
    {
        return $this->belongsToMany(Cobertura::class, 'plan_cobertura_detalle', 'Plan', 'Cobertura')
            ->withPivot(['SumaAsegurada', 'Tasa', 'Prima', 'Activo']);
    }
}
