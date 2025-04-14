<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaCatalogoTipoCartera extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_catalogo_tipo_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Vida',
        'Nombre',
        'Activo' 
    ];
}
