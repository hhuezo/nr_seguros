<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioVideDeuda extends Model
{
    use HasFactory;
    protected $table = 'negocio_vida_deuda';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Genero',
        'Negocio',
        'Estado',
        'TipoCartera',
        'Traslado',
        'Coberturas',

        'Aseguradora',
        'Prima'

    ];

    protected $guarded = [];

    public function negocios()
    {
        return $this->belongsTo('App\Models\catalogo\Negocio', 'Negocio', 'Id');
    }

    public function generos()
    {
        return $this->belongsTo('App\Models\catalogo\Genero', 'Genero', 'Id');
    }

    public function coberturas()
    {
        return $this->belongsTo('App\Models\catalogo\NegocioVidaDeudaCobertura', 'Coberturas', 'Id');
    }
    public function aseguradora()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }
}
