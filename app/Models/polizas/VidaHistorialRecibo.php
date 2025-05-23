<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaHistorialRecibo extends Model
{
    use HasFactory;


    protected $table = 'poliza_vida_historial_recibo';

    protected $primaryKey = 'Id';

    public $timestamps = false; // Cambiar a true si usas los timestamps

    protected $fillable = [
        'PolizaVidaDetalle',
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
