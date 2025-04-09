<?php

namespace App\Models\polizas;

use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\TipoCobro;
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
        'Ejecutivo',
        'TipoCartera',
        'EstadoPoliza',
        'TipoCobro',
        'Tasa',
        'Activo',
        'MontoCartera',
        'Mensual',
        'EdadTerminacion',
        'EdadMaxTerminacion',
        'EdadIntermedia',
        'LimiteMaxDeclaracion',
        'LimiteIntermedioDeclaracion',
        'LimiteGrupo',
        'LimiteIndividual',
        'Bomberos',
        'LimiteMenDeclaracion',
        'TasaComision',
    ];

    protected $guarded = [];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Asegurado', 'Id');
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class, 'Aseguradora', 'Id');
    }


    public function planes(){
        return $this->belongsTo(Plan::class, 'Plan', 'Id');
    }

    public function tipoCarteras()
    {
        return $this->belongsTo('App\Models\catalogo\TipoCartera', 'TipoCartera', 'Id');
    }

    public function ejecutivo()
    {
        return $this->belongsTo(Ejecutivo::class, 'Ejecutivo', 'Id');
    }
    public function estadoPoliza()
    {
        return $this->belongsTo(EstadoPoliza::class, 'EstadoPoliza', 'Id');
    }

    public function tipoCobro()
    {
        return $this->belongsTo(TipoCobro::class, 'TipoCobro', 'Id');
    }
}
