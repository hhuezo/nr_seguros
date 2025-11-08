<?php

namespace App\Models\temp;

use App\Models\polizas\Vida;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VidaCarteraTemp extends Model
{
    use HasFactory;
    protected $table = 'poliza_vida_cartera_temp';

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
        'FechaOtorgamientoDate',
        'PolizaVidaTipoCartera',
        'Tasa',
        'Identificador',
    ];

    protected $guarded = [];

    public function poliza_vida(){
        return $this->belongsTo(Vida::class, 'PolizaVida', 'Id');
    }
}
