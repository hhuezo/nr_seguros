<?php

namespace App\Models\suscripcion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{
    use HasFactory;
        protected $table = 'sus_comentarios';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'SuscripcionId',
        'Usuario',
        'FechaCreacion',
        'Activo',
        'Comentario'
    ];

    // Relación: un comentario pertenece a una suscripción
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'SuscripcionId');
    }


    public function usuario()
    {
        return $this->belongsTo(User::class, 'Usuario');
    }

}
