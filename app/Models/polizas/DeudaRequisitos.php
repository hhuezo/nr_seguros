<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaRequisitos extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_requisitos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Poliza',
        'Requisito',
        'EdadInicial',
        'EdadFinal',
        'MontoInicial',
        'MontoFinal',
        'EdadInicial2',
        'EdadFinal2',
        'MontoInicial2',
        'MontoFinal2',
        'EdadInicial3',
        'EdadFinal3',
        'MontoInicial3',
        'MontoFinal3'
    ];

    protected $guarded = [];


    public function poliza()
    {
        return $this->belongsTo('App\Models\polizas\Deuda', 'Poliza', 'Id');
    }

}
