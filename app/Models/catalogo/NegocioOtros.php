<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioOtros extends Model
{
    use HasFactory;
    protected $table = 'negocio_otros';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Estado',
        'SumaAsegurada',
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
