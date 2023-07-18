<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteContactoFrecuente extends Model
{
    use HasFactory;

    protected $table = 'cliente_contacto_frecuente';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Cliente',
        'Nombre',
        'Cargo',
        'Telefono',
        'Email',
        'LugarTrabajo'
    ];

    protected $guarded = [];
}
