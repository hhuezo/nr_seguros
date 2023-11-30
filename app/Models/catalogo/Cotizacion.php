<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizacion';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Negocio',
        'Plan',
        'SumaAsegurada',
        'PrimaNetaAnual',
        'Observaciones',
        'DatosTecnicos',
        'Aceptado',
        'Activo',
    ];

    public function negocios()
    {
        return $this->belongsTo(Negocio::class, 'Negocio');
    }

    public function planes()
    {
        return $this->belongsTo(Plan::class, 'Plan');
    }
}
