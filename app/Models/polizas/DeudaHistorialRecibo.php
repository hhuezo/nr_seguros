<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaHistorialRecibo extends Model
{
    use HasFactory;

    protected $table = 'poliza_deuda_historial_recibo';

    protected $primaryKey = 'Id';

    public $timestamps = true; // Cambiar a true si usas los timestamps

    protected $fillable = [
        'PolizaDeudaDetalle',
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
