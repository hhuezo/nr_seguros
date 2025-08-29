<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaControlCartera extends Model
{
    use HasFactory;

    protected $table = 'poliza_control_carteras';

    protected $primaryKey = 'id';

    public $timestamps = false;


    // Aquí las columnas para asignación masiva
    protected $fillable = [
        'Axo',
        'Mes',
        'DeudaId',
        'DesempleoId',
        'ResidenciaId',
        'VidaId',
        'FechaRecepcionArchivo',
        'FechaEnvioCia',
        'TrabajoEfectuado',
        'HoraTarea',
        'FlujoAsignado',
        'Usuario',
        'UsuariosReportados',
        'Tarifa',
        'PrimaBruta',
        'ExtraPrima',
        'PrimaEmitida',
    ];

    // Relación ejemplo: deuda (poliza_deuda)
    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'DeudaId', 'Id');
    }

    public function desempleo()
    {
        return $this->belongsTo(Desempleo::class, 'DesempleoId', 'Id');
    }

    public function residencia()
    {
        return $this->belongsTo(Residencia::class, 'ResidenciaId', 'Id');
    }

    public function vida()
    {
        return $this->belongsTo(Vida::class, 'VidaId', 'Id');
    }
}
