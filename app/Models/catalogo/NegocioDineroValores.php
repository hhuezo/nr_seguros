<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegocioDineroValores extends Model
{
    use HasFactory;
    protected $table = 'negocio_dinero_valores';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Estado',
        'SumaAsegurada',
        'Negocio',
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
