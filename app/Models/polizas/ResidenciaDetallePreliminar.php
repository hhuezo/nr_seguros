<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidenciaDetallePreliminar extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'poliza_residencia_detalle_preliminar';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'Id';

    /**
     * Los atributos que son asignables en masa
     */
    protected $fillable = [
        'PolizaResidenciaId',
        'Axo',
        'Mes',
        'MontoCartera',
        'Tasa',
        'PrimaCalculada',
        'ExtraPrima',
        'PrimaDescontada',
        'TasaComision',
        'Comision',
        'Retencion',
        'IvaSobreComision',
        'Iva',
        'APagar',
        'NumeroRecibo',
        'FechaInicio',
        'Usuario',
        'UsuariosReportados',
    ];

    /**
     * Los atributos que deben ser casteados
     */
    protected $casts = [
        'PolizaResidenciaId' => 'integer',
        'Axo' => 'integer',
        'Mes' => 'integer',
        'MontoCartera' => 'decimal:2',
        'Tasa' => 'decimal:4',
        'PrimaCalculada' => 'decimal:2',
        'ExtraPrima' => 'decimal:2',
        'PrimaDescontada' => 'decimal:2',
        'TasaComision' => 'decimal:4',
        'Comision' => 'decimal:2',
        'Retencion' => 'decimal:2',
        'IvaSobreComision' => 'decimal:2',
        'Iva' => 'decimal:2',
        'APagar' => 'decimal:2',
        'UsuariosReportados' => 'integer',
        'FechaInicio' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con PolizaDeuda
     * Descomenta si tienes el modelo PolizaResidencia
     */
    public function polizaResidencia()
    {
        return $this->belongsTo(Residencia::class, 'PolizaResidenciaId', 'Id');
    }
}
