<?php

namespace App\Models\polizas;

use App\Models\catalogo\Aseguradora;
use App\Models\catalogo\Cliente;
use App\Models\catalogo\Ejecutivo;
use App\Models\catalogo\EstadoPoliza;
use App\Models\catalogo\Plan;
use App\Models\catalogo\SaldoMontos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desempleo extends Model
{
    use HasFactory;
    protected $table = 'poliza_desempleo';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'NumeroPoliza',
        'Asegurado',
        'Aseguradora',
        'Ejecutivo',
        'VigenciaDesde',
        'VigenciaHasta',
        'Tasa',
        'EdadMaximaInscripcion',
        'EdadMaxima',
        'TipoCalculo',
        'EstadoPoliza',
        'Activo',
        'Usuario',
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Asegurado', 'Id');
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class, 'Aseguradora', 'Id');
    }

    public function ejecutivo()
    {
        return $this->belongsTo(Ejecutivo::class, 'Ejecutivo', 'Id');
    }
    public function estadoPoliza()
    {
        return $this->belongsTo(EstadoPoliza::class, 'EstadoPoliza', 'Id');
    }


    public function planes()
    {
        return $this->belongsTo(Plan::class, 'Plan', 'Id');
    }

    public function control_cartera()
    {
        return $this->belongsTo(PolizaControlCartera::class, 'DesempleoId', 'Id');
    }

     public function desempleo_tipos_cartera()
    {
        return $this->hasMany(DesempleoTipoCartera::class, 'PolizaDesempleo', 'Id');
    }
}
