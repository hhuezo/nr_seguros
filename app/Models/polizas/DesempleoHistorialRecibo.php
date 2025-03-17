<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesempleoHistorialRecibo extends Model
{
    use HasFactory;

    protected $table = 'poliza_desempleo_historial_recibo';

    protected $primaryKey = 'Id';

    public $timestamps = false; // Cambiar a true si usas los timestamps

    protected $fillable = [
        'PolizaDesempleoDetalle',
        'ImpresionRecibo',
        'NombreCliente',
        'NitCliente',
        'DireccionResidencia',
        'Departamento',
        'Municipio',
        'NumeroRecibo',
        'AvisoCobro',
        'CompaniaAseguradora',
        'ProductoSeguros',
        'NumeroPoliza',
        'VigenciaDesde',
        'VigenciaHasta',
        'FechaInicio',
        'FechaFin',
        'Anexo',
        'Referencia',
        'FacturaNombre',
        'MontoCartera',
        'PrimaCalculada',
        'ExtraPrima',
        'Descuento',
        'PrimaDescontada',
        'TotalAPagar',
        'TasaComision',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'ValorCCF',
        'NumeroCorrelativo',
        'Otros',
        'Cuota',
        'FechaVencimiento',
        'PagoLiquidoPrima',
    ];

}
