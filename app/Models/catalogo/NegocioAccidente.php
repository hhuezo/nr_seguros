<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioAccidente extends Model
{
    use HasFactory;
    protected $table = 'negocio_accidentes';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Genero',
        'Aseguradora',
        'Negocio',
        'Estado',
        'FechaNacimiento',
        'Cantidad',
        'SumaAsegurada',
        'Prima'

    ];

    protected $guarded = [];

    public function negocios(){
        return $this->belongsTo('App\Models\catalogo\Negocio','Negocio','Id');
    }

    public function aseguradora()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }
}
