<?php

namespace App\Models\polizas;

use App\Models\catalogo\Perfil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaRequisitos extends Model
{
    use HasFactory;
    protected $table = 'poliza_deuda_requisitos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Deuda',
        'Perfil',
        'EdadInicial',
        'EdadFinal',
        'MontoInicial',
        'MontoFinal',
        'Activo'

    ];

    protected $guarded = [];


    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'Perfil', 'Id');
    }

}
