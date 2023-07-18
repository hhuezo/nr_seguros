<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteRetroalimentacion extends Model
{
    use HasFactory;

    protected $table = 'cliente_retroalimentacion';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Cliente',
        'Producto',
        'ValoresAgregados',
        'Competidores',
        'Referidos',
        'QueQuisiera'
    ];

    protected $guarded = [];
}
