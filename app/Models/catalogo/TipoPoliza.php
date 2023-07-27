<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPoliza extends Model
{
    use HasFactory;
    protected $table = 'tipo_poliza';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo'
    ];

    protected $guarded = [];



}
