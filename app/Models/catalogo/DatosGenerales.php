<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosGenerales extends Model
{
    use HasFactory;
    protected $table = 'datos_generales';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Id_recibo'
    ];

    protected $guarded = [];

}
