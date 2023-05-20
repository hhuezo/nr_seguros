<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Numero',
        'NumeroPoliza',
        'Nit',
        'Codigo',
        'Asegurado',
        'Aseguradora',
        'Ejecutivo',
        'VigenciaDesde',
        'VigenciaHasta',
        'Tasa',
        'MesesDesfase',
        'SaldoCapital',
        'MontoNominal',
        'Beneficios',
        'ClausulasEspeciales',
        'Concepto',
        'TipoCartera',
        'EstadoPoliza',
        'LimiteMedico',
        'MaxAsegurado',
        'Descuento',
        'DescuentoEspecial',
        'ValorPrima',
        'ExtraPrima',
        'ValorCCF',
        'APagar',
        'ValorDescuento',
        'ValorDescuentoEspecial',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'PrimaDescontada',
        'FechaIngreso',
        'Activo',
        'Bomberos',
        'EdadTerminacion',
        'EdadOtorgamiento',
        'EdadMaxTerminacion',
        'EdadIntermediaTerminacion',
        'LimiteMaximoDeclaracion',
        'LimiteIntermedioDeclaracion'
    ];

    protected $guarded = [];

    public function clientes()
    {
        return $this->belongsTo('App\Models\catalogo\Cliente', 'Asegurado', 'Id');
    }

    public function aseguradoras()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }

    public function tipoCarteras()
    {
        return $this->belongsTo('App\Models\catalogo\TipoCartera', 'TipoCartera', 'Id');
    }

    public function ejecutivos()
    {
        return $this->belongsTo('App\Models\catalogo\Ejecutivo', 'Ejecutivo', 'Id');
    }
    public function estadoPolizas()
    {
        return $this->belongsTo('App\Models\catalogo\EstadoPoliza', 'EstadoPoliza', 'Id');
    }
}
