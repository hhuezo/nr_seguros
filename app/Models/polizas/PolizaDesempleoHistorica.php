<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDesempleoHistorica extends Model
{
    use HasFactory;
            protected $table = 'poliza_desempleo_historica';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'DatosVida',
        'DatosTablaDiferenciada',
        'DatosCreditos',
        'VidaDetalle',
        'Requisito',
        'TipoRenovacion',
        'Vida',
        'Fecha',
        'Usuario',
    ];

}
