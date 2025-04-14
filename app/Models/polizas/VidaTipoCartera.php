<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaTipoCartera extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_tipo_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'PolizaVida',
        'VidaTipoCartera',
        'TipoCalculo',
        'MontoMaximoIndividual'
    ];

    public function poliza_vida(){
        return $this->belongsTo(Vida::class, 'PolizaVida', 'Id');
    }

    public function catalogo_tipo_cartera(){
        return $this->belongsTo(VidaCatalogoTipoCartera::class, 'VidaTipoCartera', 'Id');
    }

    public function tasa_diferenciada()
    {
        return $this->hasMany(VidaTasaDiferenciada::class, 'PolizaVidaTipoCartera', 'Id');
    }

}
