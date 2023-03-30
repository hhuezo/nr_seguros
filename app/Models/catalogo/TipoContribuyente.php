<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContribuyente extends Model
{
    use HasFactory;
    protected $table = 'tipo_contribuyente';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre'
    ];

    protected $guarded = [];

}
