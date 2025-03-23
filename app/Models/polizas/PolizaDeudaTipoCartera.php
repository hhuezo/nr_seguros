<?php

namespace App\Models\polizas;

use App\Models\catalogo\TipoCartera;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaTipoCartera extends Model
{
    use HasFactory;

    protected $table = 'poliza_deuda_tipo_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'PolizaDeuda',
        'TipoCartera',
        'TipoCalculo',
        'MontoMaximoIndividual'
    ];

    public function poliza_deuda(){
        return $this->belongsTo(Deuda::class, 'PolizaDeuda', 'Id');
    }

    public function tipo_cartera(){
        return $this->belongsTo(TipoCartera::class, 'TipoCartera', 'Id');
    }

    public function tasa_diferenciada()
    {
        return $this->hasMany(PolizaDeudaTasaDiferenciada::class, 'PolizaDuedaTipoCartera', 'Id');
    }
}
