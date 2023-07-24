<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioVideDeudaCobertura extends Model
{
    use HasFactory;
    protected $table = 'negocio_vida_deuda_cobertura';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Abreviatura',
        'Activo'
    ];

    protected $guarded = [];

}
