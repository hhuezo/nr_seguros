<?php

namespace App\Models\polizas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaCartera extends Model
{
    use HasFactory;

    protected $table = 'poliza_vida_cartera';

    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'PolizaVida',
        'Nit',
        'Dui',
        'Pasaporte',
        'Nacionalidad',
        'FechaNacimiento',
        'TipoPersona',
        'Sexo',
        'PrimerApellido',
        'SegundoApellido',
        'ApellidoCasada',
        'PrimerNombre',
        'SegundoNombre',
        'FechaOtorgamiento',
        'FechaVencimiento',
        'NumeroReferencia',
        'SumaAsegurada',
        'User',
        'Axo',
        'Mes',
        'FechaInicio',
        'FechaFinal',
        'FechaNacimientoDate',
        'Edad',
        'EdadDesembloso',
        'FechaOtorgamientoDate'
    ];

    protected $guarded = [];

    public function poliza_vida(){
        return $this->belongsTo(Vida::class, 'PolizaVida', 'Id');
    }
}
