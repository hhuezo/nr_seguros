<?php

namespace App\Models\polizas;

use App\Models\catalogo\Cobertura;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroCobertura extends Model
{
    use HasFactory;
    protected $table = 'poliza_seguro_cobertura';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'PolizaSeguro',
        'Cobertura',
        'Valor',
        'Activo'
    ];

    public function cobertura()
    {
        return $this->belongsTo(Cobertura::class, 'Cobertura', 'Id');
    }
}
