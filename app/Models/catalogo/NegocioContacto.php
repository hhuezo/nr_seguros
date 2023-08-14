<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioContacto extends Model
{
    use HasFactory;
    protected $table = 'negocio_contactos';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'negocio',
        'Contacto',
        'DescripcionOperacion',
        'TelefonoContacto',
        'ObservacionContacto',
        'Activo',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class, 'negocio');
    }
}
