<?php

namespace App\Models\polizas;

use App\Models\catalogo\PolizaDeclarativaReproceso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeclarativaControl extends Model
{
    use HasFactory;

    protected $table = 'poliza_declarativa_control';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'PolizaDeudaId',
        'PolizaVidaId',
        'PolizaDesempleoId',
        'PolizaResidenciaId',
        'FechaRecepcionArchivo',
        'FechaEnvioACia',
        'TrabajoEfectuadoDiaHabil',
        'HoraTarea',
        'FlujoAsignado',
        'PorcentajeRentabilidad',
        'ValorDescuentoRentabilidad',
        'AnexoDeclaracion',
        'NumeroACSisco',
        'FechaVencimiento',
        'FechaEnvioACCliente',
        'ReprocesoNRId',
        'FechaEnvioCorreccion',
        'FechaSeguimientoCobros',
        'FechaRecepcionPago',
        'FechaReporteACia',
        'FechaAplicacion',
        'Comentarios',
        'Axo',
        'Mes',
    ];

    // === Relaciones con pólizas ===
    public function polizaDeuda()
    {
        return $this->belongsTo(Deuda::class, 'PolizaDeudaId', 'Id');
    }

    public function polizaVida()
    {
        return $this->belongsTo(Vida::class, 'PolizaVidaId', 'Id');
    }

    public function polizaDesempleo()
    {
        return $this->belongsTo(Desempleo::class, 'PolizaDesempleoId', 'Id');
    }

    public function polizaResidencia()
    {
        return $this->belongsTo(Residencia::class, 'PolizaResidenciaId', 'Id');
    }

    // === Relación con el reproceso ===
    public function reproceso()
    {
        return $this->belongsTo(PolizaDeclarativaReproceso::class, 'ReprocesoNRId', 'Id');
    }
}
