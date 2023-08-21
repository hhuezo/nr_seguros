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
        'FechaVenta',
        'NecesidadProteccion',
        'InicioVigencia',
        'Observacion',
        'Ejecutivo',
        'Activo',
        'FechaIngreso',
        'UsuarioIngreso',
        'EstadoVenta',
        'NumCoutas',
        'Prima',
        'TipoPoliza',
        'TipoNecesidad',
        'NumeroPoliza',
        'PlanTipoProducto',
        'TipoNegocio',
        'DepartamentoAtiende',
        'MetodoPago',
    ];

    protected $guarded = [];



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

    public function contactos()
    {
        return $this->hasMany(NegocioContacto::class, 'negocio');
    }

    public function departamentosNR()
    {
        return $this->belongsTo(DepartamentoNR::class, 'DepartamentoAtiende', 'Id');
    }

}
