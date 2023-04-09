<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositoPlazo extends Model
{
    use HasFactory;
    protected $table = 'poliza_deposito_plazo';

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
        'ValorDescuento',
        'IvaSobreComision',
        'Retencion',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'SaldoA',
        'NumeroUsuario1',
        'SumaAseguradora1',
        'Prima1',
        'NumeroUsuario2',
        'SumaAseguradora2',
        'Prima2',
        'NumeroUsuario3',
        'SumaAseguradora3',
        'Prima3',
        'NumeroUsuario4',
        'SumaAseguradora4',
        'Prima4',
        'NumeroUsuario5',
        'SumaAseguradora5',
        'Prima5',
        'NumeroUsuario6',
        'SumaAseguradora6',
        'Prima6',
        'Activo'
    ];

    protected $guarded = [];

    public function clientes(){
        return $this->belongsTo('App\Models\catalogo\Cliente', 'Asegurado', 'Id');
    }
}
