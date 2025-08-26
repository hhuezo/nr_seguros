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
        'Mensual',
        'Comision',
        'ValorDescuento',
        'Tasa',
        'Ejecutivo',
        'Descuento',
        'Nit',
        'Comision',
        'Activo',
        'TasaDescuento'

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

    public function planes()
    {
        return $this->belongsTo('App\Models\catalogo\Plan', 'Plan', 'Id');
    }


    public function control_cartera()
    {
        return $this->belongsTo(PolizaControlCartera::class, 'ResidenciaId', 'Id');
    }
}
