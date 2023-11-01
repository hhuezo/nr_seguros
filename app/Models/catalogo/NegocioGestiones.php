<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioGestiones extends Model
{
    use HasFactory;
    protected $table = 'negocio_gestiones';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = ['Negocio', 'DescripcionActividad', 'Usuario', 'FechaHora','Activo'];

    public function negocios()
    {
        return $this->belongsTo(Negocio::class, 'Negocio');
    }

    public function usuarios()
    {
        return $this->belongsTo(\App\Models\User::class, 'Usuario');
    }
}
