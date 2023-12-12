<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoMontos extends Model
{
    use HasFactory;
    protected $table = 'saldos_montos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'LineaCredito',
        'Activo'
    ];

    protected $guarded = [];

    public function linea_credito()
    {
        return $this->belongsTo('App\Models\catalogo\TipoCartera', 'LineaCredito', 'Id');
    }

}
