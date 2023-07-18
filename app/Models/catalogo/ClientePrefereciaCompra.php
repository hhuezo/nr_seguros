<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientePrefereciaCompra extends Model
{
    use HasFactory;
    protected $table = 'cliente_preferencia_compra';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo'
    ];

    protected $guarded = [];
    
}
