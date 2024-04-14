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
        'Beneficios',
        'ClausulasEspeciales',
        'Concepto',
        'EstadoPoliza',
        'Descuento',
        'Comision',
        'FechaIngreso',
        'Activo',
        'Bomberos',
        'Videuda',
        'LimiteMaximo'
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

    public function ejecutivos()
    {
        return $this->belongsTo('App\Models\catalogo\Ejecutivo', 'Ejecutivo', 'Id');
    }
    public function estadoPolizas()
    {
        return $this->belongsTo('App\Models\catalogo\EstadoPoliza', 'EstadoPoliza', 'Id');
    }

    public function planes(){
        return $this->belongsTo('App\Models\catalogo\Plan', 'Plan', 'Id');
    }

    public function requisitos()
    {
        return $this->hasMany(DeudaRequisitos::class,'Deuda', 'Id');
    }

    public function extra_primados()
    {
        return $this->hasMany(PolizaDeudaExtraPrimados::class, 'PolizaDeuda', 'Id');
    }
}
