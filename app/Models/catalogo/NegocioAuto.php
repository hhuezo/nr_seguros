<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioAuto extends Model
{
    use HasFactory;
    protected $table = 'negocio_autos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Marca',
        'Estado',
        'Modelo',
        'Axo',
        'SumaAsegurada',
        'Cantidad',
        'Placa',
        'Activo',
        'Negocio',
        'Aseguradora'
    ];

    protected $guarded = [];

    public function negocios()
    {
        return $this->belongsTo('App\Models\catalogo\Negocio', 'Negocio', 'Id');
    }

    public function aseguradora()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }
}
