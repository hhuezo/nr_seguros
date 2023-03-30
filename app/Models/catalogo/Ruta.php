<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;
    protected $table = 'ruta';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Codigo',
        'Nombre',
        'Activo'
    ];

    protected $guarded = [];
}
