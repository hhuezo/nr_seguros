<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vida extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Numero',
        'NumeroPoliza',
        'Nit',
        'Codigo',
        'Aseguradora',
        'Asegurado',
        'GrupoAsegurado',
        'VigenciaDesde',
        'VigenciaHasta',
        'BeneficiosAdicionales',
        'ClausulasEspeciales',
        'Concepto',
        'Comentario',
        'Ejecutivo',
        'TipoCartera',
        'EstadoPoliza',
        'TipoCobro',
        'Tasa',
        'PrimaTotal',
        'Descuento',
        'ExtraPrima',
        'ValorCCF',
        'APagar',
        'MontoCartera',
        'TasaComision',
        'Mensual',
        'PrimaDescontada',
        'ValorDescuento',
        'IvaSobreComision',
        'Retencion',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'SaldoA',
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

    public function clientes(){
        return $this->belongsTo('App\Models\catalogo\Cliente', 'Asegurado', 'Id');
    }

    public function aseguradoras(){
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }

    public function tipoCarteras(){
        return $this->belongsTo('App\Models\catalogo\TipoCartera', 'TipoCartera', 'Id');
    }

    public function ejecutivos(){
        return $this->belongsTo('App\Models\catalogo\Ejecutivo', 'Ejecutivo', 'Id');
    }
    public function estadoPolizas(){
        return $this->belongsTo('App\Models\catalogo\EstadoPoliza', 'EstadoPoliza', 'Id');
    }

    public function tipoCobros(){
        return $this->belongsTo('App\Models\catalogo\TipoCobro', 'TipoCobro', 'Id');
    }
}