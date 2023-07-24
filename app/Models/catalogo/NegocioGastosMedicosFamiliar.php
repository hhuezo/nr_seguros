<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioGastosMedicosFamiliar extends Model
{
    use HasFactory;
    protected $table = 'negocio_gastos_medicos_familiar';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'FechaNacimiento',
        'Genero',
        'Parentesco',
        'Negocio',
        'Vida',
        'Dental'
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

    public function parentesco()
    {
        return $this->belongsTo('App\Models\catalogo\Parentesco', 'Parentesco', 'Id');
    }
}
