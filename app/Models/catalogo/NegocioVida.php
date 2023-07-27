<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioVida extends Model
{
    use HasFactory;
    protected $table = 'negocio_vida';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Genero',
        'Negocio',
        'FechaNacimiento',
        'Fumador',
        'InvalidezParcial',
        'InvalidezTotal',
        'GastosFunerarios',
        'EnfermedadesGraves',
        'Termino',
        'Ahorro',
        'Plazo',
        'SesionBeneficios',
        'Estado',
        'Coberturas',

        'Aseguradora',
        'Prima'

    ];

    protected $guarded = [];

    public function negocios()
    {
        return $this->belongsTo('App\Models\catalogo\Negocio', 'Negocio', 'Id');
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
