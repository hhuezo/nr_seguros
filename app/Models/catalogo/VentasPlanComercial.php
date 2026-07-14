<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasPlanComercial extends Model
{
    use HasFactory;

    protected $table = 'ventas_plan_comercial';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Aseguradora',
        'NecesidadProteccion',
        'Producto',
        'Plan',
        'NombreComercial',
        'Activo',
    ];

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class, 'Aseguradora', 'Id');
    }

    public function ramo()
    {
        return $this->belongsTo(NecesidadProteccion::class, 'NecesidadProteccion', 'Id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Producto', 'Id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'Plan', 'Id');
    }

    public function valores()
    {
        return $this->hasMany(VentasPlanComercialValor::class, 'PlanComercial', 'Id')
            ->where('Activo', 1);
    }
}
