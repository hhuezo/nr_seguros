<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaExcluidos extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_excluidos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'Dui',
        'Nombre',
        'NumeroReferencia',
        'Poliza',
        'FechaExclusion',
        'Usuario',
        'EdadMaxima',
        'ResponsabilidadMaxima',

    ];

    public function deuda()
    {
        return $this->belongsTo('App\Models\polizas\Deuda', 'Poliza', 'Id');
    }
}

