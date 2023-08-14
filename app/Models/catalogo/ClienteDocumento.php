<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteDocumento extends Model
{
    use HasFactory;
    protected $table = 'cliente_documentos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'Cliente',
        'Nombre',
        'Activo'

    ];

    protected $guarded = [];
}
