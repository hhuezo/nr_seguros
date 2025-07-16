<?php

namespace App\Models\polizas;

use App\Models\catalogo\DatosTecnicos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaSeguroDatosTecnicos extends Model
{
    use HasFactory;
            protected $table = 'poliza_seguro_datos_tecnicos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


   protected $fillable = [
        'PolizaSeguroId',
        'Nombre',
        'Descripcion',
        'Activo'
    ];
}
