<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteEstado extends Model
{
    use HasFactory;
    protected $table = 'cliente_estado';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'Nombre',
        'Activo'

    ];

    protected $guarded = [];
}
