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
    
    ];

    protected $guarded = [];


    public function poliza()
    {
        return $this->belongsTo('App\Models\polizas\Deuda', 'Poliza', 'Id');
    }

}
