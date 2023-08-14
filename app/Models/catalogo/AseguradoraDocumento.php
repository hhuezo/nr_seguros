<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AseguradoraDocumento extends Model
{
    use HasFactory;
    protected $table = 'aseguradora_documentos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'Cliente',
        'Nombre',
        'Activo'

    ];

    protected $guarded = [];
}
