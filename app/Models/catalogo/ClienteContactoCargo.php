<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteContactoCargo extends Model
{
    use HasFactory;
    

    protected $table = 'cliente_contacto_cargo';
    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Valor',
        'Activo'
    ];
  
}
