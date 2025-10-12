<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaValidados extends Model
{
    use HasFactory;

    protected $table = 'poliza_deuda_validados';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [

        'Dui',
        'Nombre',
        'NumeroReferencia',
        'Poliza',
        'TipoCartera',
        'Mes',
        'Usuario',
    ];

    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'Poliza', 'Id');
    }
}
