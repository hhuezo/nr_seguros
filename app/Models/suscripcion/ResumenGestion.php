<?php

namespace App\Models\suscripcion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumenGestion extends Model
{
    use HasFactory;
    protected $table = 'sus_resumen_gestion';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo'
    ];

    protected $guarded = [];
}
