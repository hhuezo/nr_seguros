<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteHabitoConsumo extends Model
{
    use HasFactory;
    protected $table = 'cliente_habito_consumo';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Cliente',
        'ActividadEconomica',
        'IngresoPromedio',
        'GastoMensualSeguro',
        'NivelEducativo'
    ];

    protected $guarded = [];
}
