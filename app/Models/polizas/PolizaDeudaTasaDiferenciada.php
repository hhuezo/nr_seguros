<?php

namespace App\Models\polizas;

use App\Models\catalogo\SaldoMontos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaTasaDiferenciada extends Model
{
    use HasFactory;

    protected $table = 'poliza_deuda_tasa_diferenciada'; // Nombre de la tabla

    protected $primaryKey = 'Id'; // Clave primaria

    public $timestamps = false;

    protected $fillable = [
        'PolizaDuedaTipoCartera',
        'LineaCredito',
        'FechaDesde',
        'FechaHasta',
        'EdadDesde',
        'EdadHasta',
        'Tasa',
        'Usuario'
    ];

    // // Relación con la tabla de créditos (asumiendo que la tabla se llama 'poliza_deuda_credito')
    // public function credito()
    // {
    //     return $this->belongsTo(DeudaCredito::class, 'PolizaDuedaCredito');
    // }

    // Relación con la tabla de usuarios (si aplica)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario');
    }

    public function linea_credito()
    {
        return $this->belongsTo(SaldoMontos::class, 'LineaCredito');
    }

    public function poliza_deuda_tipo_cartera(){
        return $this->belongsTo(PolizaDeudaTipoCartera::class, 'PolizaDuedaTipoCartera', 'Id');
    }
}
