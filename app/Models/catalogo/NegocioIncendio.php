<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioIncendio extends Model
{
    use HasFactory;
    protected $table = 'negocio_incendio';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Direccion',
        'Giro',
        'ValorConstruccion',
        'ValorContenido',
        'Activo',
        'Negocio',
        'Estado',
        'Aseguradora',
        'Prima'
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
