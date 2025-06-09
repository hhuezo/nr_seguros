<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguro extends Model
{
    use HasFactory;
     protected $table = 'poliza_seguro';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'NumeroPoliza',
        'EstadoPoliza',
        'FormaPago',
        'Oferta',
        'Producto',
        'Cliente',
        'Comision',
        'VigenciaDesde',
        'VigenciaHasta',
        'Activo'

    ];
}
