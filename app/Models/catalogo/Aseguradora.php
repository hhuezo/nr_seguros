<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aseguradora extends Model
{
    use HasFactory;
    protected $table = 'aseguradora';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo',
        'Codigo',
        'Telefono',
        'Contacto',
        'Direccion',
        'PaginaWeb',
        'Fax',
        'Nit',
        'RegistroFiscal',
        'Abreviatura',
        'Correo'
    ];

    protected $guarded = [];
}
