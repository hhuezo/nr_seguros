<?php

namespace App\Models\polizas;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaTasaDiferenciada extends Model
{
    use HasFactory;

    protected $table = 'poliza_vida_tasa_diferenciada'; // Nombre de la tabla

    protected $primaryKey = 'Id'; // Clave primaria

    public $timestamps = false;

    protected $fillable = [
        'PolizaVidaTipoCartera',
        'FechaDesde',
        'FechaHasta',
        'EdadDesde',
        'EdadHasta',
        'Tasa',
        'Usuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario');
    }

    public function poliza_vida_tipo_cartera(){
        return $this->belongsTo(VidaTipoCartera::class, 'PolizaVidaTipoCartera', 'Id');
    }
}
