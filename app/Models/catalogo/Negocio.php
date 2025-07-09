<?php

namespace App\Models\catalogo;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    use HasFactory;
    protected $table = 'negocio';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'TipoCarteraNr',
        'EstadoVenta',
        'Cliente',
        'NecesidadProteccion',
        'Ejecutivo',
        'TipoNegocio',
        'FechaVenta',
        'NumeroPoliza',
        'InicioVigencia',
        'PeriodoPago',
        'DepartamentoNr',
        'NumCoutas',
        'Observacion',
        'Activo',
        'FechaIngreso',
        'UsuarioIngreso',
    ];

    protected $guarded = [];



    public function usuarioIngreso()
    {
        return $this->belongsTo(User::class, 'UsuarioIngreso');
    }

    public function tipoCarteraNr()
    {
        return $this->belongsTo(TipoCarteraNr::class, 'TipoCarteraNr');
    }

    public function estadoVenta()
    {
        return $this->belongsTo(EstadoVenta::class, 'EstadoVenta');
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class, 'Cliente');
    }

    public function necesidadProteccion()
    {
        return $this->belongsTo(NecesidadProteccion::class, 'NecesidadProteccion');
    }

    public function ejecutivos()
    {
        return $this->belongsTo(Ejecutivo::class, 'Ejecutivo');
    }

    public function tipoNegocio()
    {
        return $this->belongsTo(TipoNegocio::class, 'TipoNegocio');
    }

    public function departamentoNr()
    {
        return $this->belongsTo(DepartamentoNr::class, 'DepartamentoNr');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'Negocio', 'Id');
    }

}
