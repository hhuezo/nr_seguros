<?php

namespace App\Models\suscripcion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuscripcionTemp extends Model
{
    use HasFactory;

    protected $table = 'suscripcion_temp';
    protected $primaryKey = 'Id';

    public $timestamps = false;


    protected $fillable = [
        'FechaIngreso',
        'FechaEntregaDocsCompletos',
        'DiasParaCompletarInfoCliente',
        'Gestor',
        'Cia',
        'Contratante',
        'NumeroPolizaDeuda',
        'NumeroPolizaVida',
        'Asegurado',
        'Ocupacion',
        'DocumentoIdentidad',
        'Edad',
        'Genero',
        'SumaAseguradaEvaluadaDeuda',
        'SumaAseguradaEvaluadaVida',
        'TipoCliente',
        'TipoCredito',
        'Imc',
        'TipoImc',
        'Padecimientos',
        'TipoOrdenMedica',
        'EstatusDelCaso',
        'ResumenDeGestion',
        'FechaReportadoCia',
        'TrabajoEfectuadoDiaHabil',
        'TareasEvaSisa',
        'ComentariosNrSuscripcion',
        'FechaCierreGestion',
        'FechaRecepcionResolucionCia',
        'FechaEnvioResolucionCliente',
        'DiasProcesamientoResolucion',
        'ResolucionOficial',
        'PorcentajeExtraprima',
        'Usuario'
    ];

    protected $guarded = [];
}
