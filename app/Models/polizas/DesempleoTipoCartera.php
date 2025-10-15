<?php

namespace App\Models\polizas;

use App\Models\catalogo\SaldoMontos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesempleoTipoCartera extends Model
{
    use HasFactory;
     protected $table = 'poliza_desempleo_tipo_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'PolizaDesempleo',
        'SaldosMontos',
        'TipoCalculo',
    ];

    public function poliza_desempleo(){
        return $this->belongsTo(Desempleo::class, 'PolizaDesempleo', 'Id');
    }

    public function saldos_montos(){
        return $this->belongsTo(SaldoMontos::class, 'SaldosMontos', 'Id');
    }

    public function tasa_diferenciada()
    {
        return $this->hasMany(DesempleoTasaDiferenciada::class, 'PolizaDesempleoTipoCartera', 'Id');
    }

}
