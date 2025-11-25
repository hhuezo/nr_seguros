<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoberturaTarificacion extends Model
{
    use HasFactory;

    protected $table = 'producto_cobertura_tarificacion';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Activo',
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];
}
