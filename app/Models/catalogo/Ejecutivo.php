<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejecutivo extends Model
{
    use HasFactory;
    protected $table = 'ejecutivo';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'Nombre',
        'Activo',
        'Telefono',
        'Codigo',
        'AreaComercial',
        'Correo'

    ];

    protected $guarded = [];

    public function areaComercial(){
        return $this->belongsTo('App\Models\catalogo\AreaComercial', 'AreaComercial', 'Id');
    }
}
