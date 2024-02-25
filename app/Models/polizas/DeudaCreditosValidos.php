<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaCreditosValidos extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_creditos_validos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'NumeroReferencia',
        'Poliza',
        'Activo',

    ];
}
