<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgrupadorRamo extends Model
{
    use HasFactory;
    protected $table = 'agrupador_ramo';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Activo',
    ];

    public function necesidades_proteccion()
    {
        return $this->hasMany(NecesidadProteccion::class, 'AgrupadorRamo', 'Id');
    }
}

