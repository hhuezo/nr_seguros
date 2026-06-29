<?php

namespace App\Models\polizas;

use App\Models\catalogo\EstadoPoliza;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroRenovacion extends Model
{
    use HasFactory;

    protected $table = 'poliza_seguro_renovacion';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'PolizaSeguroId',
        'TipoRenovacion',
        'EstadoPoliza',
        'NumeroVigencia',
        'VigenciaDesde',
        'VigenciaHasta',
        'TarifaPlan',
        'SumaAsegurada',
        'PrimaNetaAnual',
        'DatosPolizaJson',
        'CambiosJson',
        'Usuario',
        'FechaRegistro',
        'Activo',
    ];

    public function poliza()
    {
        return $this->belongsTo(PolizaSeguro::class, 'PolizaSeguroId', 'Id');
    }

    public function estadoPolizaRelacion()
    {
        return $this->belongsTo(EstadoPoliza::class, 'EstadoPoliza', 'Id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario', 'id');
    }
}
