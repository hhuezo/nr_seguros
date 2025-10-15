<?php

namespace App\Models\polizas;

use App\Models\catalogo\SaldoMontos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesempleoTasaDiferenciada extends Model
{
    use HasFactory;
    protected $table = 'poliza_desempleo_tasa_diferenciada'; // Nombre de la tabla

    protected $primaryKey = 'Id'; // Clave primaria

    public $timestamps = false;

    protected $fillable = [
        'PolizaDesempleoTipoCartera',
        'FechaDesde',
        'FechaHasta',
        'MontoDesde',
        'MontoHasta',
        'Tasa',
        'Usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario');
    }

    public function poliza_desempleo_tipo_cartera()
    {
        return $this->belongsTo(DesempleoTipoCartera::class, 'PolizaDesempleoTipoCartera', 'Id');
    }
}
