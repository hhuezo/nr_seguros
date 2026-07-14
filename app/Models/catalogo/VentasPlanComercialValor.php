<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasPlanComercialValor extends Model
{
    use HasFactory;

    protected $table = 'ventas_plan_comercial_valor';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PlanComercial',
        'CampoComparativo',
        'ValorTexto',
        'Activo',
    ];

    public function planComercial()
    {
        return $this->belongsTo(VentasPlanComercial::class, 'PlanComercial', 'Id');
    }

    public function campoComparativo()
    {
        return $this->belongsTo(VentasCampoComparativo::class, 'CampoComparativo', 'Id');
    }
}
