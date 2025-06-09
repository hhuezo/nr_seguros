<?php

namespace App\Models\suscripcion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechasFeriadas extends Model
{
    use HasFactory;
        protected $table = 'sus_fechas_feriadas';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'FechaInicio',
        'FechaFinal',
        'Descripcion',
        'Activo'
    ];

    protected $guarded = [];
}
