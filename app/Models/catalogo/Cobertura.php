<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobertura extends Model
{
    use HasFactory;
    protected $table = 'producto_cobertura';
    protected $primaryKey = 'Id'; // Especificamos la clave primaria
    protected $fillable = ['Nombre', 'TarificacionId', 'Descuento', 'Iva', 'ProductoId', 'Activo'];
    public $timestamps = false;

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'ProductoId', 'Id');
    }

    public function planesCoberturaDetalles()
    {
        return $this->hasMany(PlanCoberturaDetalle::class, 'Cobertura', 'Id');
    }

    public function planes()
    {
        return $this->belongsToMany(Plan::class, 'plan_cobertura_detalle', 'Cobertura', 'Plan')
            ->withPivot(['SumaAsegurada', 'Tasa', 'Prima', 'Activo']);
    }

    public function tarificacion()
    {
        return $this->belongsTo(CoberturaTarificacion::class, 'TarificacionId', 'Id');
    }
}
