<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidenciaHistorialRecibo extends Model
{
    use HasFactory;
    protected $table = 'poliza_residencia_historial_recibo';

    protected $primaryKey = 'Id';

    public $timestamps = false; // Cambiar a true si usas los timestamps
    protected $fillable = [
        'PolizaResidenciaDetalle',
        'ImpresionRecibo',
        'NombreCliente',
        'NitCliente',
        'DireccionResidencia',
        'Departamento',
        'Municipio',
        'NumeroRecibo',
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
        'SubTotal',
        'Iva',
        'TotalFactura',
        'Descuento',
        'PordentajeDescuento',
        'PrimaDescontada',
        'TotalAPagar',
        'TasaComision',
        'Comision',
        'IvaSobreComision',
        'SubTotalComision',
        'Retencion',
        'ValorCCF',
        'NumeroCorrelativo',
        'Otros',
        'Cuota',
        'FechaVencimiento',
        'PagoLiquidoPrima',
        'CreatedAt',
        'UpdatedAt',
        'Usuario',
        'Activo',
    ];
}
