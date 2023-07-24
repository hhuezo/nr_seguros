<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NecesidadProteccion extends Model
{
    use HasFactory;
    protected $table = 'necesidad_proteccion';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo'
    ];

    protected $guarded = [];
}
