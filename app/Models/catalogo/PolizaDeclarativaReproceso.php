<?php

namespace App\Models\catalogo;

use App\Models\polizas\PolizaDeclarativaControl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolizaDeclarativaReproceso extends Model
{
    use HasFactory;

    protected $table = 'poliza_declarativa_reproceso';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Activo',
    ];

    // Relación con controles declarativos
    public function controles()
    {
        return $this->hasMany(PolizaDeclarativaControl::class, 'ReprocesoNR', 'Id');
    }
}
