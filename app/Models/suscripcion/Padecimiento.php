<?php

namespace App\Models\suscripcion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\suscripcion\Suscripcion;

class Padecimiento extends Model
{
    use HasFactory;
    protected $table = 'sus_padecimientos';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Activo'
    ];


    public function suscripciones()
    {
        return $this->belongsToMany(
            Suscripcion::class,
            'suscripcion_padecimientos',
            'PadecimientoId',
            'SuscripcionId'
        );
    }
}
