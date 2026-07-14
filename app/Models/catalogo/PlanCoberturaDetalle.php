<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCoberturaDetalle extends Model
{
    use HasFactory;
    protected $table = 'plan_cobertura_detalle';
    protected $fillable = [
        'Plan',
        'Cobertura',
        'Tarificacion',
        'TarificacionNombre',
        'SumaAsegurada',
        'Tasa',
        'Prima',
        'CoberturaPrincipal',
        'Activo',
    ];
    // Tabla pivote con llave compuesta logica; las escrituras se hacen con DB::table().
    public $incrementing = false;
    public $timestamps = false;

    public function coberturas()
    {
        return $this->belongsTo(Cobertura::class, 'Cobertura', 'Id');
    }

    public function plans()
    {
        return $this->belongsTo(Plan::class, 'Plan', 'Id');
    }
}
