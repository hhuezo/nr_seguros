<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioGastosMedicos extends Model
{
    use HasFactory;
    protected $table = 'negocio_gastos_medicos';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Activo',
        'Estado',
        'FechaNacimiento',
        'Genero',
        'Negocio',
        'Vida',
        'Dental',
        'MaximoVitalicio',
        'CantidadTitulares',
        'CantidadPersonas',
        'Contributivo',
        'CantidadDependientes',
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
