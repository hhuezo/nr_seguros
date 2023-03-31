<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    use HasFactory;
    protected $table = 'negocio';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Asegurado',
        'Aseguradora',
        'FechaVenta',
        'Ramo',
        'InicioVigencia',
        'Observacion',
        'TipoNegocio',
        'EstadoVenta',
        'Ejecutivo',
        'Activo'
    ];

    protected $guarded = [];

    public function aseguradora()
    {
        return $this->belongsTo('App\Models\catalogo\Aseguradora', 'Aseguradora', 'Id');
    }

}
