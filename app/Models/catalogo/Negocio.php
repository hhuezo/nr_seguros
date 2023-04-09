<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    use HasFactory;
    protected $table = 'negocio';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Asegurado',
        'Aseguradora',
        'FechaVenta',
        'TipoPoliza',
        'InicioVigencia',
        'Observacion',
        'TipoNegocio',
        'EstadoVenta',
        'Ejecutivo',
        'Activo',
        'SumaAsegurada',
        'Prima',
        'FechaIngreso',
        'UsuarioIngreso',
        'NumCuotas'
    ];

    protected $guarded = [];

    public function aseguradora()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }

    public function tipo_poliza()
    {
        return $this->belongsTo('App\Models\catalogo\TipoPoliza', 'TipoPoliza', 'Id');
    }

    public function ejecutivos()
    {
        return $this->belongsTo('App\Models\catalogo\Ejecutivo', 'Ejecutivo', 'Id');
    }

    public function clientes()
    {
        return $this->belongsTo('App\Models\catalogo\Cliente', 'Asegurado', 'Id');
    }
}
