<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AseguradoraCartera extends Model
{
    use HasFactory;
    protected $table = 'aseguradora_has_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'aseguradora_id',
        'cartera_id'
    ];

}
