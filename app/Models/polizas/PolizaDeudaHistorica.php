<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeudaHistorica extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_historica';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'DatosDeuda',
        'DatosTablaDiferenciada',
        'DatosCreditos',
        'DeudaDetalle',
        'Requisito',
        'Deuda',
        'Fecha',
        'Usuario',
    ];

}
