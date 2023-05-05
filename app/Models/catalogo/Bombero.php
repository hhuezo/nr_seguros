<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bombero extends Model
{
    use HasFactory;
    protected $table = 'bombero';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Valor',
        'Activo'
    ];
}
