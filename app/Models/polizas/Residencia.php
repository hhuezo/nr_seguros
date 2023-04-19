<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residencia extends Model
{
    use HasFactory;
    protected $table = 'poliza_residencia';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Numero',
        'NumeroPoliza',
        'Codigo',
        'Aseguradora',
        'Asegurado',
        'EstadoPoliza',
        'VigenciaDesde',
        'VigenciaHasta',
        'LimiteGrupo',
        'LimiteIndividual',
        'MontoCartera',
        'Cliente',
        'Tasa',
        'Prima',
        'Ejecutivo',
        'Descuento',
        'GastosEmision',
        'ImpuestoBomberos',
        'Iva',
        'ValorCCF',
        'APagar',
        'ComentariosDeCobro',
        'DescuentoIva',
        'Nit',
        'Comision',
        'IvaSobreComision',
        'Retencion',
        'Activo',
        'ImpresionRecibo',
        'EnvioCartera',
        'EnvioPago',
        'PagoAplicado',
        'SaldoA',
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


}
